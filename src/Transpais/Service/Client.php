<?php
/**
 * Created by PhpStorm.
 * User: degaray
 * Date: 7/3/14
 * Time: 10:23 AM
 */

namespace Transpais\Service;

use Transpais\Type\RequestBlockTicket;
use Transpais\Type\RequestRuns;
use Transpais\Type\RequestSeatMap;
use Transpais\Type\ResponseSeatMap;
use Transpais\Type\ResponseRuns;
use Transpais\Type\Run;
use Transpais\Type\Seat;
use Transpais\Type\Ticket;

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

        $service_params = array(
            'in0' => $requestRuns->getOriginId(), // origin Place ID (origenId)
            'in1' => $requestRuns->getDestinationId(), // destination Place ID (destinoId)
            'in2' => $requestRuns->getDateOfRun()->format('c'),
        );

        $soap_param = array(
            'ventaService' => $service_params
        );
        $soap_response = $this->callSoapServiceByType($service_type, $soap_param);
        
        $response = $this->normalizeResponseToRun($soap_response->out->Corrida);

        return $response;
    }

    public function getSeatMap(RequestSeatMap $requestSeatMap)
    {
        $service_type = 'consultarAutobus';

        $requestRuns = $requestSeatMap->getRequestRuns();
        $formattedDateOfRun = $requestRuns->getDateOfRun()->format('c');

        $service_params = array(
            'in0' => $requestSeatMap->getRunId(), // run ID (corridaId)
            'in1' => $formattedDateOfRun,
            'in2' => $requestRuns->getOriginId(),
            'in3' => $requestRuns->getDestinationId(), // destination Place ID (destinoId)
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

    public function blockSeat(RequestBlockTicket $RequestBlockTicket)
    {
        $service_type = 'bloquearAsientos';
        $tickets_to_block['Boleto'] = $this->createTicketToBlock($RequestBlockTicket->getTicket());
        $seatMap = $RequestBlockTicket->getRequestSeatMap();

        $service_params = array(
            'in0' => $RequestBlockTicket->getClientId(),
            'in1' => $RequestBlockTicket->getUserId(),
            'in2' => $seatMap->getPosId(),
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
            throw new \RequestException($error_msg);
        }

        $Boleto = $soap_response->out->Boleto;
        $Ticket = $RequestBlockTicket->getTicket();
        $Ticket->setTicketId($Boleto->boletoId);
        $Ticket->setPrice($Boleto->precio);

        return $Ticket;
    }

    protected function callSoapServiceByType($type, $params)
    {

        $response = $this->_soap_client->__soapCall($type, $params, array('trace' => 1));

        return $response;
    }

    protected function normalizeSingleObject($out)
    {

        foreach ($out as $index => $object) {

            if (!is_array($object)) {
                $class = new \stdClass();
                $class->{$index}[] = $object;
                return $class;
            } else {
                return $out;
            }
        }
        return $out;
    }

    public function setSoapClient($soapClient)
    {
        $this->_soap_client = $soapClient;
    }

    protected function normalizeResponseToRun($response)
    {
        $responseRuns = new ResponseRuns();

        foreach ($response as $run) {
            $runObj = new Run($run);
            $responseRuns->append($runObj);
        }

        return $responseRuns;
    }

    protected function normalizeResponseSeatMap($response)
    {
        $responseSeatMap = new ResponseSeatMap();

        foreach ($response->detallesDiagrama->DetalleDiagrama as $seat) {
            $seatObj = new Seat($seat);
            $responseSeatMap->append($seatObj);
        }

        return $responseSeatMap;
    }

    public function createTicketToBlock(Ticket $Ticket)
    {
        $ticket_to_block = array(
            'categoriaId' => $Ticket->getCategoryId(),
            'corridaId' => $Ticket->getRun()->getCorridaId(),
            'destinoId' => $Ticket->getRequestRuns()->getDestinationId(),
            'fechaCorrida' => $Ticket->getRequestRuns()->getDateOfRun()->format('c'),
            'nombrePasajero' => $Ticket->getPassengerName(),
            'numAsiento' => $Ticket->getSeat()->getAsiento(),
            'origenId' => $Ticket->getRequestRuns()->getOriginId(),
            'precio' => $Ticket->getRun()->getPrecioBase(),
        );

        return $ticket_to_block;
    }
}