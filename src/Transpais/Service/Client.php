<?php

namespace Transpais\Service;

use SebastianBergmann\Exporter\Exception;

/**
 * Class Client
 * @package Transpais\Service
 */

class Client
{
    protected $wdsl_url;
    protected $soap_client;
    protected $company_id ;
    protected $pos_id;
    protected $sale_type_id;
    protected $client_id;
    protected $ticket_types = array('sold', 'unsold', 'blocked');
    protected $tickets;
    protected $origin_id;
    protected $destination_id;
    protected $dateOf;
    protected $run_id;
    protected $date_of_run;
    protected $tickets_to_block;
    protected $user_id;
    protected $transaction_num;
    protected $seat_map;
    protected $blocked_seats;
    protected $tickets_to_confirm;
    protected $runsOfDay;
    protected $runBasePrice;

    /**
     * Initializing the SoapClient is needed on each call to this class
     */
    public function __construct($wdsl_url = null)
    {
        if (!is_null($wdsl_url)) {
            $this->setWdslUrl($wdsl_url);
        }

        if (!isset($this->wdsl_url)) {
            throw new Exception('You need to set the WDSL URL');
        }

        $this->setSoapClient($this->wdsl_url);
    }

    public function setWdslUrl($url)
    {
        $this->wdsl_url = $url;
    }

    public function setSoapClient($url)
    {
        $this->_soap_client = new \SoapClient($url);
    }

    public function setCompanyId($id)
    {
        $this->company_id = $id;
    }

    public function setPosId($id)
    {
        $this->pos_id = $id;
    }

    public function setSaleTypeId($id)
    {
        $this->sale_type_id = $id;
    }

    public function setClientId($id)
    {
        $this->client_id = $id;
    }

    public function setOriginId($id)
    {
        $this->origin_id = $id;
    }
    public function setDestinationId($id)
    {
        $this->destination_id = $id;
    }

    public function setDateOfRun($date)
    {
        $this->date_of_run = $date;
    }

    public function setRunId($id)
    {
        $this->run_id = $id;
    }

    public function setTransactionNum($num)
    {
        $this->transaction_num = $num;
    }

    public function getRunsInADay()
    {
        if (!isset($this->origin_id) || !is_int($this->origin_id)) {
            throw new \Exception('Origin ID must be set and a numeric value.');
        }

        if (!isset($this->destination_id) || !is_int($this->destination_id)) {
            throw new \Exception('Destination ID must be set and a numeric value.');
        }

        if (!isset($this->date_of_run) || !static::testIso8601($this->date_of_run)) {
            throw new \Exception('Date of run should be set and in ISO8601 format Ej. "2004-02-12T15:19:21+00:00".');
        }

        $service_type = 'consultarCorridas';

        $service_params = array(
            'in0' => $this->origin_id, // origin Place ID (origenId)
            'in1' => $this->destination_id, // destination Place ID (destinoId)
            'in2' => $this->date_of_run,
        );

        $soap_param = array(
            'ventaService' => $service_params
        );
        $soap_response = $this->callSoapServiceByType($service_type, $soap_param);

        $response = $this->normalizeSingleObject($soap_response->out);

        $this->setRunsOfDay($response);

        return $response;
    }

    public static function testIso8601($date_string)
    {
        if ($date_string == date('c', strtotime($date_string))) {
            return true;
        }

        return false;
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

    public function setRunsOfDay($runs)
    {
        $this->runsOfDay = $runs;
    }

    public function setRunBasePrice($price)
    {
        $this->run_base_price = $price;
    }

    public function getSeatMap()
    {
        if (!isset($this->run_id) || !is_int($this->run_id)) {
            throw new \Exception('Run Id must be set and a numeric value');
        }
        if (!isset($this->date_of_run) || !static::testIso8601($this->date_of_run)) {
            throw new \Exception('Date of run should be set and in ISO8601 format Ej. "2004-02-12T15:19:21+00:00".');
        }
        if (!is_int($this->origin_id) || !is_int($this->origin_id)) {
            throw new \Exception('Origin ID must be set and a numeric value.');
        }
        if (!is_int($this->destination_id) || !is_int($this->destination_id)) {
            throw new \Exception('Destination ID must be set and a numeric value.');
        }
        if (!is_int($this->sale_type_id) || !is_int($this->sale_type_id)) {
            throw new \Exception('Sale Type ID must be set and a numeric value.');
        }
        if (!is_int($this->pos_id) || !is_int($this->pos_id)) {
            throw new \Exception('POS ID must be set and a numeric value.');
        }

        $service_type = 'consultarAutobus';

        $service_params = array(
            'in0' => $this->run_id, // run ID (corridaId)
            'in1' => $this->date_of_run,
            'in2' => $this->origin_id,
            'in3' => $this->destination_id, // destination Place ID (destinoId)
            'in4' => $this->pos_id,
            'in5' => $this->sale_type_id,
        );

        $soap_param = array(
            'ventaService' => $service_params
        );

        $soap_response = $this->callSoapServiceByType($service_type, $soap_param);

        $response = $this->normalizeSeatObject($soap_response->out);

        $this->setSeatMap($response);

        return $response;
    }

    public function setSeatMap($seat_map)
    {
        $this->seat_map = $seat_map;
    }

    protected function normalizeSeatObject($out)
    {
        if (!is_array($out->detallesDiagrama->DetalleDiagrama)) {
            $detail = $out->detallesDiagrama->DetalleDiagrama;
            $out->detallesDiagrama->DetalleDiagrama = null;
            $out->detallesDiagrama->DetalleDiagrama = $detail;
        }
        if (!is_array($out->disponibilidad->Disponibilidad)) {
            $disponibilidad = $out->disponibilidad->Disponibilidad;
            $out->disponibilidad->Disponibilidad = null;
            $out->disponibilidad->Disponibilidad = $disponibilidad;
        }
        return $out;
    }

    public function setUserId($id)
    {
        $this->user_id = $id;
    }

    public function blockSeat(array $seat)
    {
        if (!isset($this->client_id) || !is_int($this->client_id)) {
            throw new \Exception('Client Id must be set and a numeric value');
        }
        if (!isset($this->user_id) || !is_int($this->user_id)) {
            throw new \Exception('User Id must be set and a numeric value');
        }
        if (!isset($this->pos_id) || !is_int($this->pos_id)) {
            throw new \Exception('POS Id must be set and a numeric value');
        }
        if (isset($this->transaction_num) && !is_int($this->transaction_num)) {
            throw new \Exception('Transaction Number must be a numeric value');
        }
        if (!isset($this->run_id) || !is_int($this->run_id)) {
            throw new \Exception('Run Id must be a numeric value');
        }
        $service_type = 'bloquearAsientos';

        $seat_to_block = $this->createSeatToBlock($seat);

        $service_params = array(
            'in0' => $this->client_id,
            'in1' => $this->user_id,
            'in2' => $this->pos_id,
            'in3' => $this->transaction_num,
            'in4' => $seat_to_block,
        );

        $soap_param = array(
            'ventaService' => $service_params
        );

        $soap_response = $this->callSoapServiceByType($service_type, $soap_param);

        if (is_null($soap_response->out->Boleto->boletoId)) {
            $error_msg = 'The seat you are tying to block is already taken, please select a '.
                'different one or unblock this seat first.';
            throw new \Exception($error_msg);
        }
        $response = $this->normalizeSingleObject($soap_response->out);

        $this->addBlockedSeat($response);

        return $response;
    }

    public function createSeatToBlock(array $seat_param)
    {

        if (isset($this->run_id) && !is_int($this->run_id)) {
            throw new \Exception('Run Id must be a numeric value');
        }
        if (isset($this->origin_id) && !is_int($this->origin_id)) {
            throw new \Exception('Origin Id must be a numeric value');
        }
        if (isset($this->destination_id) && !is_int($this->destination_id)) {
            throw new \Exception('Destination Id must be a numeric value');
        }
        if (!isset($this->date_of_run) || !static::testIso8601($this->date_of_run)) {
            throw new \Exception('Date of run should be set and in ISO8601 format Ej. "2004-02-12T15:19:21+00:00".');
        }

        $seat['Boleto'][] = array(

            //'boletoId' => null,
            'categoriaId' => $seat_param['category_id'],
            'corridaId' => $this->run_id,
            'destinoId' => $this->destination_id,
            'fechaCorrida' => $this->date_of_run,
            'nombrePasajero' => $seat_param['passenger_name'],
            'numAsiento' => $seat_param['seat_number'],
            //$numFolioSistema = ,
            //$numOperacion = ,
            'origenId' => $this->origin_id,
            'precio' => $seat_param['price'],
            //$precioServicio1 = ,
            //$precioServicio2 = ,
            //$precioServicio3 = ,
            //$precioServicio4 = ,
            //$servicio1Id = ,
            //$servicio2Id = ,
            //$servicio3Id = ,
            //$servicio4Id = ,

        );

        return $seat;
    }

    public function addBlockedSeat($seat)
    {
        $this->blocked_seats[] = $seat;
    }

    public function setBlockedSeats($seats)
    {
        $this->blocked_seats = $seats;
    }

    public function setCard(array $card)
    {
        $this->card = $card;
    }

    public function confirmPayment(array $tickets_to_confirm, $is_return_ticket)
    {
        if (!isset($this->client_id) || !is_int($this->client_id)) {
            throw new \Exception('Client Id must be set and a numeric value');
        }
        if (!isset($this->user_id) || !is_int($this->user_id)) {
            throw new \Exception('User Id must be set and a numeric value');
        }
        if (!isset($this->company_id) || !is_int($this->company_id)) {
            throw new \Exception('Company Id must be set and a numeric value');
        }
        if (!isset($this->card) || !is_array($this->card)) {
            throw new \Exception('Card must be set and an array');
        }
        if (!is_bool($is_return_ticket)) {
            throw new \Exception('You have to specify whether the ticket is a return one');
        }

        $service_type = 'bloquearAsientos';

        $service_params = array(
            'in0' => $this->client_id, // client ID (corridaId)
            'in1' => $this->user_id, // user ID (usuarioId)
            'in2' => $this->company_id, // company Id (empresaVoucherId - empresaId from corrida) objeto corrida
            'in3' => $this->card, // card array (tarjeta)
            'in4' => $tickets_to_confirm, // tickets array (boletos)
            'in5' => $is_return_ticket, // is a return ticket BOOL (esRedondo)
        );

        $soap_param = array(
            'confirmarVentaTarjeta' => $service_params
        );

        $soap_response = $this->callSoapServiceByType($service_type, $soap_param);

        $response = $this->normalizeSingleObject($soap_response->out);

        return $response;
    }

    public function unblockSeat($ticket_id)
    {

        if (!is_int($ticket_id)) {
            throw new \Exception('Ticket ID must be a numeric value.');
        }
        if (!isset($this->user_id) || !is_int($this->user_id)) {
            throw new \Exception('User ID must be a numeric value.');
        }

        $service_type = 'desbloquearAsientos';

        $service_params = array(
            'in0' => $ticket_id, // Ticket ID (boletoId)
            'in1' => $this->user_id, // User ID (usuarioId)
        );

        $soap_param = array(
            'ventaService' => $service_params
        );

        $soap_response = $this->callSoapServiceByType($service_type, $soap_param);

        $response = $this->normalizeSingleObject($soap_response->out);

        $this->unsetBlockedSeat($ticket_id);

        return $response;
    }

    /*
     * It converts a single object into an array of 1 index.
     * It is used to normalize the object when receiving as a
     * response only one place to an object similar to another
     * when receiving two places.
     *
     */

    public function unsetBlockedSeat($seat_id)
    {

        foreach ($this->blocked_seats as $index => $seat) {
            if ($seat->Boleto{0}->boletoId == $seat_id) {
                unset($this->blocked_seats[$index]);
            }
        }

        return $seat_id;
    }

    /**
     * @param array $ticket
     * @param $type
     * @throws \Exception
     */
    public function addTicket(array $ticket, $type)
    {

        if (!in_array($type, $this->ticket_types)) {
            throw new \Exception('Ticket type is not supported');
        }

        $this->tickets[$type][] = $ticket;

    }

    protected function placeArrayToEnglish($array)
    {
        if (!is_array($array)) {
            throw new \Exception('An array is expected');
        }

        foreach ($array as $row) {
            $object = new \stdClass();

            $object->key = $row->cve;
            $object->description = $row->descripcion;
            $object->id = $row->id;

            $outputArr[] = $object;
        }

        return $outputArr;
    }
}
