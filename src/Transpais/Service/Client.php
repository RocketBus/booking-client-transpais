<?php
/**
 * Created by PhpStorm.
 * User: degaray
 * Date: 7/3/14
 * Time: 10:23 AM
 */

namespace Transpais\Service;

use Transpais\Type\Errors\SoapException;
use Transpais\Type\RequestBlockTicket;
use Transpais\Type\RequestConfirmPayment;
use Transpais\Type\RequestRuns;
use Transpais\Type\RequestSeatMap;
use Transpais\Type\ResponseSeatMap;
use Transpais\Type\ResponseRuns;
use Transpais\Type\TicketToBlockFactory;
use Transpais\Type\RunFactory;
use Transpais\Type\Errors\RequestException;
use Transpais\Type\SeatFactory;

class Client
{
    protected $soap_client;

    /**
     * Initializing the SoapClient is needed on each call to this class
     */
    public function __construct( $soapClient)
    {
        $this->setSoapClient($soapClient);
    }

    public function getRunsInADay(RequestRuns $requestRuns)
    {
        $service_type = 'consultarCorridas';

        $formattedDateOfRun = $requestRuns->getDateOfRun()->format('c');
        $service_params = array(
            'in0' => $requestRuns->getOriginId(), // origin Place ID (origenId)
            'in1' => $requestRuns->getDestinationId(), // destination Place ID (destinoId)
            'in2' => $formattedDateOfRun,
        );

        $soap_param = array(
            'ventaService' => $service_params
        );
        $soap_response = $this->callSoapServiceByType($service_type, $soap_param);

        $corrida = $soap_response->out->Corrida;
        $response = $this->normalizeResponseToRun($corrida);

        return $response;
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
        );

        $soap_param = array(
            'ventaService' => $service_params
        );

        $soap_response = $this->callSoapServiceByType($service_type, $soap_param);

        $response = $this->normalizeResponseSeatMap($soap_response->out);

        return $response;
    }

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
        );

        $soap_param = array(
            'ventaService' => $service_params
        );

        $soap_response = $this->callSoapServiceByType($service_type, $soap_param);

        if (is_null($soap_response->out->Boleto->boletoId)) {
            $error_msg = 'The seat you are tying to block is already taken, please select a '.
                'different one or unblock this seat first.';
            throw new RequestException($error_msg);
        }

        $Boleto = $soap_response->out->Boleto;
        $ticket = $RequestBlockTicket->getTicket();
        $ticket->setTicketId($Boleto->boletoId);
        $ticket->setPrice($Boleto->precio);

        return $ticket;
    }

    public function unblockTicket($ticket_id, $user_id)
    {

        $service_type = 'desbloquearAsientos';

        $service_params = array(
            'in0' => $ticket_id, // Ticket ID (boletoId)
            'in1' => $user_id, // User ID (usuarioId)
        );

        $soap_param = array(
            'ventaService' => $service_params
        );

        $soap_response = $this->callSoapServiceByType($service_type, $soap_param);

        $status = $soap_response->out->status;
        if ($status !== 'Eliminado') {
            throw new SoapException('The ticket was either not deleted or was not previously blocked');
        }

        return true;
    }

    public function confirmPayment(RequestConfirmPayment $requestConfirmPayment)
    {
        $service_type = 'bloquearAsientos';

        $service_params = array(
            'in0' => $requestConfirmPayment->getClientId(), // client ID (corridaId)
            'in1' => $requestConfirmPayment->getUserId(), // user ID (usuarioId)
            'in2' => $requestConfirmPayment->getCompanyId(), // company Id (empresaVoucherId - empresaId from corrida) objeto corrida
            'in3' => $requestConfirmPayment->getCard(), // card array (tarjeta)
            'in4' => $requestConfirmPayment->getTicketsToConfirm(), // tickets array (boletos)
            'in5' => $requestConfirmPayment->getIsReturnTicket(), // is a return ticket BOOL (esRedondo)
        );

        $soap_param = array(
            'confirmarVentaTarjeta' => $service_params
        );

        $soap_response = $this->callSoapServiceByType($service_type, $soap_param);

        $response = $this->normalizeSingleObject($soap_response->out->Boleto);

        return $response;
    }

    protected function callSoapServiceByType($type, $params)
    {

        $response = $this->_soap_client->__soapCall($type, $params, array('trace' => 1));

        return $response;
    }

    public function setSoapClient($soapClient)
    {
        $this->_soap_client = $soapClient;
    }

    protected function normalizeResponseToRun($response)
    {
        $responseRuns = new ResponseRuns();

        if (is_array($response)) {
            foreach ($response as $run) {
                $runObj = RunFactory::create($run);
                $responseRuns->append($runObj);
            }
        } else {
            $runObj = RunFactory::create($response);
            $responseRuns->append($runObj);
        }

        return $responseRuns;
    }

    protected function normalizeResponseSeatMap($response)
    {
        $responseSeatMap = new ResponseSeatMap();

        $detalleDiagrama = $response->detallesDiagrama->DetalleDiagrama;
        if (is_array($detalleDiagrama)) {
            foreach ($detalleDiagrama as $seat) {
                $seatObj = SeatFactory::create($seat);
                $responseSeatMap->append($seatObj);
            }
        } else {
            $seatObj = SeatFactory::create($response);
            $responseSeatMap->append($seatObj);
        }

        return $responseSeatMap;
    }
}