<?php
/**
 * Created by PhpStorm.
 * User: degaray
 * Date: 6/27/14
 * Time: 1:18 PM
 */

namespace Transpais\Service;

use Transpais\Type\RequestRuns;

class ClientTest extends \PHPUnit_Framework_TestCase
{
    protected $fakeSoapClient;

    public function test_if_instance_is_ok()
    {
        $client = new Client($this->fakeSoapClient);
        $this->assertInstanceOf('Transpais\Service\Client', $client);
    }


    public function test_if_normalize_response_to_run_returns_a_valid_run_object()
    {
        $dummyresponse = new \stdClass();
        $dummyresponse->out->Corrida[] = (object) array(
            'asientosDisp' => 36,
            'claseServicioId' => null,
            'corrida2Id' => null,
            'corridaId' => 38236,
            'cveDestino' => '',
            'descripcionClaseServicio' => 'SALON',
            'descripcionEmpresa' => 'TRANSPAIS UNICO',
            'empresaId' => 1,
            'fechaCorrida' => '2014-07-12T00:00:00-05:00',
            'fechorLlegada' => '2014-07-13T07:25:00-05:00',
            'fechorSalida' => '2014-07-13T03:20:00-05:00',
            'nombreMarca' => 'TRANSPAIS',
            'numKms' => 325,
            'numPiso' => '',
            'precioBase' => '315',
            'tiempoRecorrido' => '4:05',
            'tipoServicio' => 'PASO',
        );

        $this->fakeSoapClient->setResponse($dummyresponse);

        $requestRuns = new RequestRuns();

        $requestRuns->setOriginId(1886);
        $requestRuns->setDestinationId(2219);
        $tomorrow = new \DateTime('tomorrow');
        $requestRuns->setDateOfRun($tomorrow);

        $client = new Client($this->fakeSoapClient);
        $runs = $client->getRunsInADay($requestRuns);


    }

    protected function setUp()
    {
        $this->fakeSoapClient = new \MockSoapClient('fake://wdsl');
    }

//    protected $_origen_id = 1886;
//    protected $_destination_id = 2219;
//    protected $_sale_type_id = 4825;
//    protected $_pos_id = 12;
//    protected $_client_id = -1;
//    protected $_user_id = 619;
//    protected $_tomorrow;
//    protected $_in_two_days;
//
//    public function __construct(){
//
//        $this->_tomorrow = date('c', strtotime(date('c') . ' + 1 day'));
//        $this->_in_two_days = date('c', strtotime(date('c') . ' + 2 day'));
//    }
//    public function testConnectionToWs(){
//        $client = new Client('http://128.10.100.30:8080/VentaWebService/services/VentaService?wsdl');
//
//    }
//    public function testFunctionalTest(){
//
//        $client = new Client('http://128.10.100.30:8080/VentaWebService/services/VentaService?wsdl');
//
//        // set params needed to get all the runs in a Day for a given route
//        $client->setOriginId(1886);
//        $client->setDestinationId(2219);
//        $tomorrow = new \DateTime('tomorrow');
//        $client->setDateOfRun(date('c', strtotime( $tomorrow->format('Y-m-d H:i:s') . ' + 1 day')));
//
//        $runs = $client->getRunsInADay();
//
//        $this->assertObjectHasAttribute('Corrida', $runs);
//        $this->assertArrayHasKey(0, $runs->Corrida);
//
//        $aRun = $runs->Corrida{0}->corridaId;
//        $client->setRunId($aRun);
//        $client->setSaleTypeId(12);
//        $client->setPosId(4825);
//
//        $seats = $client->getSeatMap();
//
//        $this->assertObjectHasAttribute('detallesDiagrama', $seats, 'Run\'s object is not contructed as expected');
//        $this->assertObjectHasAttribute('DetalleDiagrama', $seats->detallesDiagrama, 'Run\'s object is not contructed as expected');
//        $this->assertObjectHasAttribute('disponibilidad', $seats, 'Run\'s object is not contructed as expected');
//        $this->assertGreaterThan(0, count($seats->detallesDiagrama->DetalleDiagrama), 'It does not return any row');
//        $this->assertArrayHasKey(0, $seats->detallesDiagrama->DetalleDiagrama, 'It does not return run\'s details');
//        $this->assertGreaterThan(2, $seats->disponibilidad->Disponibilidad->cantidad, 'There are no available seats in this run, or to continue with this test');
//
//        // get 3 seats available
//        $client->setUserId(619);
//        $client->setClientId(-1);
//        $client->setTransactionNum(-1);
//        $tickets_blocked = array();
//        foreach ($seats->detallesDiagrama->DetalleDiagrama as $seat) {
//            if (is_int($seat->asiento*1)) {
//                $seat_to_block = array(
//                    'category_id' => 1,
//                    'seat_number' => $seat->asiento,
//                    'passenger_name' => 'Jonh Secada',
//                    'price' => floatval($runs->Corrida{0}->precioBase)
//                );
//
//                $tickets_blocked[] = $client->blockSeat($seat_to_block);
//            }
//            if (count($tickets_blocked) >=3) {
//                break;
//            }
//        }
//
//        if (count($tickets_blocked) <3){
//            throw new Exception('There are no enough available seats to further perform this test.');
//        }
//
//        // Test unblocking a seat
//
//        $result = $client->unblockSeat($tickets_blocked[2]->Boleto{0}->boletoId);
//
//        $this->assertEquals('Eliminado', $result->status{0}, 'The ticket was not unblocked');
//
//        /*var_dump($result);
//        echo '-----------------------------------'."\n";
//
//
//        $tickets_to_confirm['Boleto'][] = (array) $ticket_blocked->Boleto;
//        $tickets_to_confirm['Boleto'][] = (array) $ticket_blocked2->Boleto;
//
//        $card = array(
//            'autorizacion' => '1234',
//        );
//        $client->setCard($card);
//        $payment = $client->confirmPayment($tickets_to_confirm, false);
//        var_dump($payment);*/
//    }

} 