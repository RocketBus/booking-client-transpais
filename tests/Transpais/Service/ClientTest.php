<?php
/**
 * Created by PhpStorm.
 * User: degaray
 * Date: 6/27/14
 * Time: 1:18 PM
 */

namespace Transpais\Service;

use Transpais\Type\RequestRuns;
use Transpais\Type\Errors;
use Transpais\Type\RequestSeatMap;
use Transpais\Type\RequestBlockTicket;
use Transpais\Type\RequestSeatMapFactory;
use Transpais\Type\Ticket;
use Transpais\Type\RequestConfirmPaymentFactory;



class ClientTest extends \PHPUnit_Framework_TestCase
{
    protected $fakeSoapClient;
    protected $client;

    public function test_if_instance_is_ok()
    {
        $this->assertInstanceOf('Transpais\Service\Client', $this->client);
    }

    protected function setUp()
    {
        $this->fakeSoapClient = new \MockSoapClient('fake://wdsl');
        $this->client = new Client($this->fakeSoapClient);
    }

    public function test_if_normalize_response_to_run_returns_a_valid_runs_object()
    {
        $dummyresponse = self::createDummyConsultaCorridasResponseObject();
        $this->fakeSoapClient->setResponse($dummyresponse);

        $requestRuns = new RequestRuns();

        $requestRuns->setOriginId(1886);
        $requestRuns->setDestinationId(2219);
        $tomorrow = new \DateTime('tomorrow');
        $requestRuns->setDateOfRun($tomorrow);

        $runs = $this->client->getRunsInADay($requestRuns);

        $this->assertGreaterThan(0, count($runs));
        $this->assertInstanceOf('Transpais\Type\Run', $runs[0]);
        $this->assertInstanceOf('\DateTime', $runs[0]->getDateOfRun());
        $this->assertInstanceOf('\DateTime', $runs[0]->getDateOfArrival());
        $this->assertInstanceOf('\DateTime', $runs[0]->getDateOfDeparture());
    }

    public function test_if_normalize_response_to_run_returns_a_valid_runs_object_one_run_response()
    {
        $dummyresponse1 = self::createDummyConsultaCorridasResponseObject();

        $dummyresponse = (object) array(
            'out' => (object) array(
                'Corrida' => $dummyresponse1->out->Corrida[0]
            )
        );
        $this->fakeSoapClient->setResponse($dummyresponse);

        $requestRuns = new RequestRuns();

        $requestRuns->setOriginId(1886);
        $requestRuns->setDestinationId(2219);
        $tomorrow = new \DateTime('tomorrow');
        $requestRuns->setDateOfRun($tomorrow);

        $runs = $this->client->getRunsInADay($requestRuns);

        $this->assertGreaterThan(0, count($runs));
        $this->assertInstanceOf('Transpais\Type\Run', $runs[0]);
        $this->assertInstanceOf('\DateTime', $runs[0]->getDateOfRun());
        $this->assertInstanceOf('\DateTime', $runs[0]->getDateOfArrival());
        $this->assertInstanceOf('\DateTime', $runs[0]->getDateOfDeparture());
    }

    public function test_if_response_seat_map_factory_returns_a_valid_response_seat_map_object()
    {
        $dummySoapResponse = self::createDummyResponseSeatMap();

        $this->fakeSoapClient->setResponse($dummySoapResponse);

        $requestSeatMap = self::createDummyRequestSeatMap();

        $responseSeatMap = $this->client->getSeatMap($requestSeatMap);

        $this->assertInstanceOf('Transpais\Type\ResponseSeatMap', $responseSeatMap);
        $seatTypes = $responseSeatMap->getSeatTypes();
        $this->assertNotEmpty($seatTypes[0]);
        $seats = $responseSeatMap->getSeats();
        $this->assertInstanceOf('Transpais\Type\Seat', $seats[0]);
    }

    public function test_if_ticket_id_is_assigned_to_the_ticket_object_in_blockticket()
    {
        $dummySoapResponse = self::createDummyBlockTicketResponseObject('10000002910666');

        $this->fakeSoapClient->setResponse($dummySoapResponse);

        $requestBlockTicket = self::createDummyBlockTicketRequestObjectWithoutTicket();
        $ticket_to_block = self::createDummyTicketObject(null);
        $requestBlockTicket->appendTicket($ticket_to_block);
        $ticket_blocked = $this->client->blockTicket($requestBlockTicket);

        $this->assertInstanceOf('Transpais\Type\Ticket', $ticket_blocked);
        $this->assertTrue(!is_null($ticket_blocked->getTicketId()));
    }

    /**
     * @expectedException \Transpais\Type\Errors\RequestException
     */
    public function test_exception_thrown_in_block_ticket_if_ticketid_is_null()
    {
        $dummySoapResponse = self::createDummyBlockTicketResponseObject();// this is the catch :)

        $this->fakeSoapClient->setResponse($dummySoapResponse);

        $requestBlockTicket = self::createDummyBlockTicketRequestObjectWithoutTicket();
        $ticket_to_block = self::createDummyTicketObject(null);
        $requestBlockTicket->appendTicket($ticket_to_block);
        $this->client->blockTicket($requestBlockTicket);

    }

    public function test_unblockticket_returns_true()
    {
        $responseObj = new \stdClass();
        $responseObj->out = new \stdClass();
        $responseObj->out->status = 'Eliminado';

        $this->fakeSoapClient->setResponse($responseObj);

        $userId = 619;
        $response = $this->client->unblockTicket('10000002910666', $userId);

        $this->assertTrue($response == true);
    }

    /**
     * @expectedException \Transpais\Type\Errors\SoapException
     */
    public function test_unblockticket_returns_exception()
    {
        $responseObj = new \stdClass();
        $responseObj->out = new \stdClass();
        $responseObj->out->status = null;

        $this->fakeSoapClient->setResponse($responseObj);

        $userId = 619;
        $this->client->unblockTicket('10000002910666', $userId);
    }

    public function test_payment_confirmed()
    {
        $requestConfirmPayment = self::createDummyRequestConfirmPayment();

        $confirmTicket = self::createDummyBlockTicketResponseObject('10000002910666');
        $confirmTicket->out->Boleto->numOperacion = 'Array';

        $this->fakeSoapClient->setResponse($confirmTicket);

        $this->client->confirmPayment($requestConfirmPayment);
    }

    public function test_payment_confirmed_two_tickets()
    {
        $requestConfirmPayment = self::createDummyRequestConfirmPayment();

        $confirmTicket1 = self::createDummyBlockTicketResponseObject('10000002910666');
        $confirmTicket1->out->Boleto->numOperacion = 'Array';
        $confirmTicket2 = self::createDummyBlockTicketResponseObject('10000002910667');
        $confirmTicket2->out->Boleto->asientoId = 'P006';
        $confirmTicket2->out->Boleto->numOperacion = 'Array';
        $confirmTicket2->out->Boleto->numFolioSistema = '456788';
        $confirmTickets = (object) array(
            'out' => (object) array(
                    'Boleto' => array(
                        $confirmTicket1->out->Boleto,
                        $confirmTicket2->out->Boleto
                    )
                )
        );

        $this->fakeSoapClient->setResponse($confirmTickets);

        $this->client->confirmPayment($requestConfirmPayment);
    }

    /**
     * @expectedException \Transpais\Type\Errors\RequestException
     */
    public function test_payment_not_confirmed()
    {
        $requestConfirmPayment = self::createDummyRequestConfirmPayment();

        $confirmTicket = self::createDummyBlockTicketResponseObject('10000002910666');
        $confirmTicket->out->Boleto->numOperacion = -1;
        $confirmTicket->out->Boleto->boletoId = null;

        $this->fakeSoapClient->setResponse($confirmTicket);

        $this->client->confirmPayment($requestConfirmPayment);
    }

    static public function createDummyResponseSeatMap()
    {
        $dummySoapResponse = new \stdClass();
        $dummySoapResponse->out->detallesDiagrama->DetalleDiagrama[0] = (object) array(
                'asiento' => 'P009',
                'coluna' => null,
                'fila' => null,
                'status' => 'DP'
        );
        $dummySoapResponse->out->disponibilidad->Disponibilidad[0] = (object) array(
            'cantidad' => 36,
            'categoriaId' => 1,
            'descCategoria' => 'ADULTO',
            'precio' => '315.0',
            'serviciosCorrida' => new \stdClass()
        );
        return $dummySoapResponse;
    }

    static public function createDummyResponseSeatMapOneSeat()
    {
        $dummySoapResponse = (object) array(
            'out' => (object) array(
                    'detallesDiagrama' => (object) array(
                            'DetalleDiagrama' => (object) array(
                                    'asiento' => 'P009',
                                    'coluna' => null,
                                    'fila' => null,
                                    'status' => 'DP'
                                )
                        )
                )
        );

        return $dummySoapResponse;
    }

    static public function createDummyRequestSeatMap()
    {
        $tomorrow = new \DateTime('tomorrow');
        $params = array(
            'origin_id' => 1886,
            'destination_id' => 2219,
            'date_of_run' => $tomorrow,
            'pos_id' => 4825,
            'run_id' => 37842,
            'sale_type_id' => 12
        );
        $requestSeatMap = RequestSeatMapFactory::create($params);

        return $requestSeatMap;
    }

    static public function createDummyRequestConfirmPayment()
    {
        $card = array(
            'autorizacion' => '1234',
        );
        $ticket1 = self::createDummyTicketObject('10000002910666');
        $ticket1->setSeatNumber('P007');
        $ticket_to_confirm = array($ticket1);
        $confirmPaymentParams = array(
            'client_id' => -1,
            'user_id' => 619,
            'company_id' => -1,
            'card' => $card,
            'tickets_to_confirm' => $ticket_to_confirm,
            'is_return' => false
        );

        return RequestConfirmPaymentFactory::create($confirmPaymentParams);
    }

    static public function createDummyConsultaCorridasResponseObject()
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

        return $dummyresponse;
    }
    static public function createDummyBlockTicketResponseObject($ticket_id = null)
    {
        $dummySoapResponse = (object) array(
            'out' => (object) array(
                'Boleto' => (object) array(
                    'boletoId' => ($ticket_id === null)? null : $ticket_id,
                    'categoriaId' => 1,
                    'corridaId' => 37842,
                    'destinoId' => 2219,
                    'fechaCorrida' => '2014-07-15T17:00:00-05:00',
                    'nombrePasajero' => 'Jonh Secada',
                    'numAsiento' => 'P007',
                    'numOperacion' => '-1',
                    'origenId' => 1886,
                    'precio' => '315'
                )
            )
        );

        return $dummySoapResponse;
    }

    static public function createDummyBlockTicketRequestObjectWithoutTicket()
    {
        $requestBlockTicket = new RequestBlockTicket();
        $requestBlockTicket->setUserId(619);
        $requestBlockTicket->setClientId(-1);
        $requestBlockTicket->setPosId(4825);
        $requestBlockTicket->setTransactionNum(-1);

        return $requestBlockTicket;
    }

    static public function createDummyTicketObject($id)
    {
        $ticket_to_block = new Ticket();
        if ($id) {
            $ticket_to_block->setTicketId($id);
        }
        $ticket_to_block->setCategoryId(1);// full price
        $ticket_to_block->setRunId(37842);//Dynamic number, this is a mock number
        $ticket_to_block->setOriginId(1886);
        $ticket_to_block->setDestinationId(2219);
        $tomorrow = new \DateTime('tomorrow');
        $ticket_to_block->setDateOfRun($tomorrow);
        $ticket_to_block->setPassengerName('Jonh Secada');
        $ticket_to_block->setPrice(212);

        return $ticket_to_block;
    }


} 