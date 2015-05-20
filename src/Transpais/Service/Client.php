<?php
namespace Transpais\Service;

use SebastianBergmann\Exporter\Exception;
use Transpais\Exception\TimeoutException;
use Transpais\Type\Errors\SoapException;
use Transpais\Type\RequestBlockTicket;
use Transpais\Type\RequestConfirmPayment;
use Transpais\Type\RequestRuns;
use Transpais\Type\RequestSeatMap;
use Transpais\Type\ResponseRuns;
use Transpais\Type\ResponseSeatMapFactory;
use Transpais\Type\StopsResponseFactory;
use Transpais\Type\TicketToBlockFactory;
use Transpais\Type\RunFactory;
use Transpais\Type\Errors\RequestException;

/**
 * Class Client
 * @package Transpais\Service
 */
class Client
{
    protected $soap_client;
    protected $usuario;
    protected $password;
    private $logger;

    const MAX_SOCKET_TIME = 15;
    const SECOND_FLOOR = 2;


    /**
     * Initializing the SoapClient is needed on each call to this class
     */
    public function __construct($soapClient, $config)
    {
        $this->setSoapClient($soapClient);
        $this->usuario = $config['usuario'];
        $this->password = $config['password'];

        $this->maxSocketTimeout = (isset($config['max_socket_timeout'])) ?
            $config['max_socket_timeout'] :
            self::MAX_SOCKET_TIME;
    }

    public function getRunsInADay(RequestRuns $requestRuns)
    {
        $service_type = 'consultarCorridas';

        $formattedDateOfRun = $requestRuns->getDateOfRun()->format('c');
        $service_params = array(
            'in0' => $requestRuns->getOriginId(), // origin Place ID (origenId)
            'in1' => $requestRuns->getDestinationId(), // destination Place ID (destinoId)
            'in2' => $formattedDateOfRun,
            'in3' => $this->usuario,
            'in4' => $this->password
        );

        $soap_param = array(
            'ventaService' => $service_params
        );

        $configMaxSocketTimeout = ini_get('default_socket_timeout');
        try {
            ini_set('default_socket_timeout', $this->maxSocketTimeout);
            $soap_response = $this->callSoapServiceByType($service_type, $soap_param);
            ini_set('default_socket_timeout', $configMaxSocketTimeout);

        } catch (\Exception $e) {
            ini_set('default_socket_timeout', $configMaxSocketTimeout);
            throw new TimeoutException('There was no response from the Booking Engine');

        }

        if (isset($this->logger)) {
            $this->logger->addNotice(print_r($soap_response, true));
        }

        if (!isset($soap_response->out->Corrida)) {
            return new ResponseRuns();
        }

        $corrida = $soap_response->out->Corrida;
        $response = $this->normalizeResponseToRun($corrida);

        return $response;
    }

    public function getAllOrigins($companyId)
    {
        $service_type = 'buscarOrigenInternet';

        $service_params = array(
            'in0' => $companyId,
            'in1' => $this->usuario,
            'in2' => $this->password
        );

        $soap_param =  array(
            'ventaService' => $service_params
        );

        $soap_response = $this->callSoapServiceByType($service_type, $soap_param);

        $origins = StopsResponseFactory::create($soap_response);

        return $origins;

    }

    public function getAllDestinationsOfAnOrigin($companyId, $originId)
    {

        $service_type = 'buscarDestinoInternet';

        $service_params = array(
            'in0' => $companyId,
            'in1' => $originId,
            'in2' => $this->usuario,
            'in3' => $this->password
        );

        $soap_param =  array(
            'ventaService' => $service_params
        );

        $soap_response = $this->callSoapServiceByType($service_type, $soap_param);

        $destinations = StopsResponseFactory::create($soap_response);

        return $destinations;
    }

    public function getSeatMap(RequestSeatMap $requestSeatMap)
    {
        $service_type = 'consultarAutobus';

        $formattedDateOfRun = $requestSeatMap->getDateOfRun()->format('c');
        $service_params = array(
            'in0' => $requestSeatMap->getRunId(), // run ID (corridaId)
            'in1' => $formattedDateOfRun,
            'in2' => $requestSeatMap->getOriginId(),
            'in3' => $requestSeatMap->getDestinationId(), // destination Place ID (destinoId)
            'in4' => $requestSeatMap->getPosId(),
            'in5' => $requestSeatMap->getSaleTypeId(),
            'in6' => $this->usuario,
            'in7' => $this->password
        );

        $soap_param = array(
            'ventaService' => $service_params
        );

        $soap_response = $this->callSoapServiceByType($service_type, $soap_param);

        $response = ResponseSeatMapFactory::create($soap_response);

        return $response;
    }

    /**
     * When a seat is selected we have to create a ticket and block it.
     * @param RequestBlockTicket $RequestBlockTicket
     * @return mixed
     * @throws RequestException
     */
    public function blockTicket(RequestBlockTicket $RequestBlockTicket)
    {
        $service_type = 'bloquearAsientos';

        $tickets_to_block['Boleto'] = TicketToBlockFactory::create($RequestBlockTicket->getTicket());

        $service_params = array(
            'in0' => $RequestBlockTicket->getClientId(),
            'in1' => $RequestBlockTicket->getUserId(),
            'in2' => $RequestBlockTicket->getPosId(),
            'in3' => $RequestBlockTicket->getTransactionNum(),
            'in4' => $tickets_to_block,
            'in5' => $this->usuario,
            'in6' => $this->password
        );

        $soap_param = array(
            'ventaService' => $service_params
        );

        $soap_response = $this->callSoapServiceByType($service_type, $soap_param);

        if (isset($this->logger)) {
            $this->logger->addNotice(print_r($service_params, true));
            $this->logger->addNotice(print_r($soap_response, true));
        }

        if (is_null($soap_response->out->Boleto->boletoId)) {
            $error_msg = 'The seat you are tying to block is already taken, please select a '.
                'different one or unblock this seat first.';
            throw new RequestException($error_msg);
        }

        $Boleto = $soap_response->out->Boleto;
        $ticket = $RequestBlockTicket->getTicket();
        $ticket->setTicketId($Boleto->boletoId);
        $ticket->setPrice($Boleto->precio);
        $ticket->setTransactionNum($Boleto->numOperacion);

        return $ticket;
    }

    public function unblockTicket($ticket_id, $user_id)
    {
        $service_type = 'desbloquearAsientos';

        $service_params = array(
            'in0' => $ticket_id, // Ticket ID (boletoId)
            'in1' => $user_id, // User ID (usuarioId)
            'in2' => $this->usuario,
            'in3' => $this->password

        );

        $soap_param = array(
            'ventaService' => $service_params
        );

        $soap_response = $this->callSoapServiceByType($service_type, $soap_param);

        if (isset($this->logger)) {
            $this->logger->addNotice(print_r($soap_response, true));
        }

        $status = $soap_response->out->status;
        if ($status !== 'Eliminado') {
            throw new SoapException('The ticket was either not deleted or was not previously blocked');
        }

        return true;
    }

    /**
     * Called in the checkout with you press "Confirmar" button.
     * @param RequestConfirmPayment $requestConfirmPayment
     * @return array
     * @throws RequestException
     */
    public function confirmPayment(RequestConfirmPayment $requestConfirmPayment)
    {
        $service_type = 'confirmarVentaTarjeta';

        $tickets_to_confirm = $requestConfirmPayment->getTicketsToConfirm();
        $formattedTicketsToConfirm = $this->prepareTicketsToConfirm($tickets_to_confirm);

        $service_params = array(
            'in0' => $requestConfirmPayment->getClientId(),
            'in1' => $requestConfirmPayment->getUserId(),
            'in2' => $requestConfirmPayment->getCompanyId(),
            'in3' => $requestConfirmPayment->getCard(),
            'in4' => $formattedTicketsToConfirm,
            'in5' => $requestConfirmPayment->getIsReturnTicket(),
            'in6' => $this->usuario,
            'in7' => $this->password,
        );

        $soap_param = array(
            'confirmarVentaTarjeta' => $service_params
        );

        $soap_response = $this->callSoapServiceByType($service_type, $soap_param);

        if (isset($this->logger)) {
            $this->logger->addNotice(print_r($service_params, true));
            $this->logger->addNotice(print_r($soap_response, true));
        }

        $responseArray = $this->normalizePaymentConfirmationToArray($soap_response->out->Boleto);

        if ($this->verifyTicketsWereConfirmed($responseArray) == false) {
            throw new RequestException('Payment of ticket cannot be confirmed with bus line');
        }

        $ticketsToConfirm = $requestConfirmPayment->getTicketsToConfirm();
        $confirmedTickets = $this->assignTicketNumberToTicketsInArray($ticketsToConfirm, $responseArray);

        return $confirmedTickets;
    }

    protected function prepareTicketsToConfirm(array $tickets)
    {
        foreach ($tickets as $ticket) {
            $tickets_to_block[] = TicketToBlockFactory::create($ticket);
        }

        return $tickets_to_block;
    }

    /**
     * @param $ticketsToConfirm
     * @param $responseTickets
     * @return array
     */
    protected function assignTicketNumberToTicketsInArray($ticketsToConfirm, $responseTickets)
    {
        foreach ($ticketsToConfirm as $ticketToConfirm) {
            $confirmedTicket = $this->findTicketBySeatNumber($ticketToConfirm->getSeatNumber(), $responseTickets);

            $ticketToConfirm->setTicketId($confirmedTicket->boletoId);
            $ticketToConfirm->setTransactionNum($confirmedTicket->numOperacion);
            $ticketToConfirm->setFolioNumber($confirmedTicket->numFolioSistema);
            $ticketToConfirm->setIKey($confirmedTicket->iKey);

            $confirmedTickets[] = $ticketToConfirm;
        }

        return $confirmedTickets;
    }

    protected function findTicketBySeatNumber($haystack, $tickets)
    {
        foreach ($tickets as $ticket) {
            if ($ticket->numAsiento == $haystack) {
                (object) $response = $ticket;
                break;
            }
        }

        return $response;
    }

    protected function verifyTicketsWereConfirmed($tickets)
    {
        foreach ($tickets as $ticket) {
            if ($ticket->numOperacion === null || $ticket->numOperacion == -1) {
                return false;

            }
        }

        return true;
    }

    protected function callSoapServiceByType($type, $params)
    {
        $options = array('trace' => 1, 'exception' => 1);
        $response = $this->soap_client->__soapCall($type, $params, array('trace' => $options));

        return $response;
    }

    public function setSoapClient($soapClient)
    {
        $this->soap_client = $soapClient;
    }

    protected function normalizePaymentConfirmationToArray($object)
    {
        if (!is_array($object)) {
            $array[] = $object;
        } else {
            $array = $object;
        }

        return $array;
    }

    protected function normalizeResponseToRun($response)
    {
        $responseRuns = new ResponseRuns();

        if (is_array($response)) {
            foreach ($response as $run) {
                if ($run->numPiso == self::SECOND_FLOOR) {
                    continue;
                }
                $runObj = RunFactory::create($run);
                $responseRuns->append($runObj);
            }
        } else {
            $runObj = RunFactory::create($response);
            $responseRuns->append($runObj);
        }

        return $responseRuns;
    }

    public function setLog($logger)
    {
        $this->logger = $logger;
    }
}
