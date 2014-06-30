<?php

namespace Transpais\Service;


class Client {
    CONST WDSL_URL = 'http://128.10.100.30:8080/VentaWebService/services/VentaService?wsdl';

    protected $_soap_client;

    protected $_company_id = -1;

    protected $_pos_id = 4825; //Point of Sales id

    protected $_sale_type_id = 12; // Sale Type id (Internet)

    protected $_client_id = -1;

    protected $_ticket_types = array('sold', 'unsold', 'blocked');

    protected $_tickets;

    protected $_selected_run;

    public function __construct(){

        $this->setSoapClient(self::WDSL_URL);

    }

    public function setSoapClient($url){

        $this->_soap_client = new \SoapClient($url);
    }

    public function getRunsInADay($origin_id, $destination_id, $date_of_run){

        if(!is_int($origin_id)){ throw new \Exception('Origin ID must be a numeric value.'); }
        if(!is_int($destination_id)){ throw new \Exception('Destination ID must be a numeric value.'); }
        //if(!is_int($origin_id)){ throw new \Exception('Origin ID must be a numeric value.'); }

        $service_type = 'consultarCorridas';

        $service_params = array(
            'in0' => $origin_id, // origin Place ID (origenId)
            'in1' => $destination_id, // destination Place ID (destinoId)
            'in2' => $date_of_run, // date of run ID (destinoId)
        );

        $soap_param = array (
            'ventaService' => $service_params
        );
        $soap_response = $this->_callSoapServiceByType($service_type, $soap_param);

        $response = $this->_normalizeSingleObject($soap_response->out);

        return $response;
    }

    public function getSelectedRun(){

        return $this->_selected_run;

    }

    public function setSelectedRun($run){

        $this->_selected_run = $run;

    }

    public function getSeatMap($run_id, $run_date, $origin_id, $destination_id, $sale_type_id = null, $pos_id = null){

        //if(!is_int($origin_id)){ throw new \Exception('Origin ID must be a numeric value.'); }
        //if(!is_int($origin_id)){ throw new \Exception('Origin ID must be a numeric value.'); }
        if(!is_int($origin_id)){ throw new \Exception('Origin ID must be a numeric value.'); }
        if(!is_int($destination_id)){ throw new \Exception('Destination ID must be a numeric value.'); }

        $pos_id = (is_null($pos_id))?$this->_pos_id:$pos_id;
        $sale_type_id = (is_null($sale_type_id))?$this->_sale_type_id:$sale_type_id;

        $service_type = 'consultarAutobus';

        $service_params = array(
            'in0' => $run_id, // run ID (corridaId)
            'in1' => $run_date, // destination Place ID (fechaCorrida)
            'in2' => $origin_id, // destination Place ID (origenId)
            'in3' => $destination_id, // destination Place ID (destinoId)
            'in4' => $sale_type_id, // destination Place ID (puntoVentId)
            'in5' => $pos_id, // destination Place ID (puntoVentId)
        );

        $soap_param = array (
            'ventaService' => $service_params
        );

        $soap_response = $this->_callSoapServiceByType($service_type, $soap_param);

        $response = $this->_normalizeSeatObject($soap_response->out);

        return $response;
    }


    /*
     * @client_id int
     * @user_id int
     * @pos_id int
     * @transaction_num int
     * @tickets array
     */
    public function blockSeat($client_id, $user_id, $pos_id=null, $transaction_num = null, array $tickets = null){
        if(is_null($tickets)){
            $tickets = $this->_tickets;
        }

        //if(!is_int($origin_id)){ throw new \Exception('Origin ID must be a numeric value.'); }
        //if(!is_int($origin_id)){ throw new \Exception('Origin ID must be a numeric value.'); }
        if(!is_int($client_id)){ throw new \Exception('Client ID must be a numeric value.'); }
        if(!is_int($user_id)){ throw new \Exception('User ID must be a numeric value.'); }
        if(!is_int($pos_id)){ throw new \Exception('POS ID must be a numeric value.'); }
        if(!is_int($transaction_num)){ throw new \Exception('Transaction Number must be a numeric value.'); }

        $pos_id = (is_null($pos_id))?$this->_pos_id:$pos_id;

        $service_type = 'bloquearAsientos';

        $service_params = array(
            'in0' => $client_id, // run ID (corridaId)
            'in1' => $user_id, // destination Place ID (fechaCorrida)
            'in2' => $pos_id, // origin Place ID (origenId)
            'in3' => $transaction_num, // origin Place ID (origenId)
            'in4' => $tickets, // origin Place ID (origenId)
        );

        $soap_param = array (
            'ventaService' => $service_params
        );

        $soap_response = $this->_callSoapServiceByType($service_type, $soap_param);

        $response = $this->_normalizeSingleObject($soap_response->out);

        return $response;

    }

    public function confirmPayment($client_id, $user_id, $company_id, array $card, array $tickets, $is_return_ticket){
        /* TODO: validate data */

        $service_type = 'bloquearAsientos';

        $service_params = array(
            'in0' => $client_id, // run ID (corridaId)
            'in1' => $user_id, // user ID (usuarioId)
            'in2' => $company_id, // company Id (empresaVoucherId - empresaId from corrida) objeto corrida
            'in3' => $card, // card array (tarjeta)
            'in4' => $tickets, // tickets array (boletos)
            'in5' => $is_return_ticket, // is a return ticket BOOL (esRedondo)
        );

        $soap_param = array (
            'confirmarVentaTarjeta' => $service_params
        );

        $soap_response = $this->_callSoapServiceByType($service_type, $soap_param);

        $response = $this->_normalizeSingleObject($soap_response->out);

        return $response;

    }

    public function unblockSeat($ticket_id, $user_id){

        if(!is_int($ticket_id)){ throw new \Exception('Ticket ID must be a numeric value.'); }
        if(!is_int($user_id)){ throw new \Exception('User ID must be a numeric value.'); }

        $service_type = 'desbloquearAsientos';

        $service_params = array(
            'in0' => $ticket_id, // Ticket ID (boletoId)
            'in1' => $user_id, // User ID (usuarioId)
        );

        $soap_param = array (
            'ventaService' => $service_params
        );
        $soap_response = $this->_callSoapServiceByType($service_type, $soap_param);

        $response = $this->_normalizeSingleObject($soap_response->out);

        return $response;
    }

    /*
     * @ticket array(
     *      boletoId int
     *      categoriaId int
     *      corridaId int
     *      destinoId int
     *      fechaCorrida datetime ISO8601 ej. 2014-06-17T00:01:01-05:00
     *      nombrePasajero string
     *      numAsiento int
     *      numFolioSistema
     *      numOperacion int
     *      origenId int
     *      precio float(7,2)
     *      precioServicio1 float(7,2)
     *      precioServicio2 float(7,2)
     *      precioServicio3 float(7,2)
     *      precioServicio4 float(7,2)
     *      servicio1Id int
     *      servicio2Id int
     *      servicio3Id int
     *      servicio4Id int
     * )
     * @type string:
     */
    public function addTicket(array $ticket, $type){

        if(!in_array($type, $this->_ticket_types)){ throw new \Exception('Ticket type is not supported');}

        $this->_tickets[$type][] = $ticket;

    }

    /*
     * @card array(
     *      aid
     *      arqc
     *      autorizacion
     *      banco
     *      complemento
     *      correo
     *      direccion
     *      empresa
     *      fechaOperacion
     *      importe
     *      nombre
     *      numOperacion
     *      numTarjeta
     *      referencia
     *      status
     *      sucursal
     *      tipoOperacion
     *      tipoTarjeta
     *      usuarioBancario
     *      vigencia
     * )
     */
    public function setCard($card){
        $this->_card = $card;
    }

    public function getCard(){
        return $this->_card;
    }

    public function setTickets(array $tickets, $type){
        if(!in_array($type, $this->_ticket_types)){ throw new \Exception('Ticket type is not supported');}

        $this->_tickets[$type] = $tickets;
    }

    public function getTickets(array $tickets, $type){
        if(!in_array($type, $this->_ticket_types)){ throw new \Exception('Ticket type is not supported');}

        return $this->_tickets[$type];
    }

    protected  function _callSoapServiceByType($type, $params){

        $response = $this->_soap_client->__soapCall($type, $params, array('trace' => 1));

        return $response;
    }

    /*
     * It converts a single object into an array of 1 index.
     * It is used to normalize the object when receiving as a
     * response only one place to an object similar to another
     * when receiving two places.
     *
     */
    protected  function _normalizeSingleObject( $out){
        foreach($out as $object){
            if(!is_array($object)){
                $parada = $object;
                $object = null;
                $object{0} = $parada;
            }
        }

        return $out;
    }

    protected function _normalizeSeatObject($out){
        if(!is_array($out->detallesDiagrama->DetalleDiagrama)){
            $detail = $out->detallesDiagrama->DetalleDiagrama;
            $out->detallesDiagrama->DetalleDiagrama = null;
            $out->detallesDiagrama->DetalleDiagrama = $detail;
        }
        if(!is_array($out->disponibilidad->Disponibilidad)){
            $disponibilidad = $out->disponibilidad->Disponibilidad;
            $out->disponibilidad->Disponibilidad = null;
            $out->disponibilidad->Disponibilidad = $disponibilidad;
        }
        return $out;
    }

    protected function _placeArrayToEnglish($array){
        if(!is_array($array)){
            throw new \Exception('An array is expected');
        }

        foreach($array as $row){
            $object = new \stdClass();

            $object->key = $row->cve;
            $object->description = $row->descripcion;
            $object->id = $row->id;

            $outputArr[] = $object;
        }

        return $outputArr;
    }


}
