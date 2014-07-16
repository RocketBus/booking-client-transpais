<?php
/**
 * Created by PhpStorm.
 * User: degaray
 * Date: 6/27/14
 * Time: 1:18 PM
 */

namespace Transpais\Service;

use Transpais\Type\RequestRuns;
use Transpais\Type\RequestSeatMap;
use Transpais\Type\RequestBlockTicket;
use Transpais\Type\Ticket;

class ClientTest extends \PHPUnit_Framework_TestCase
{
    protected $fakeSoapClient;
    protected $client;

    public function test_if_instance_is_ok()
    {
        $this->assertInstanceOf('Transpais\Service\Client', $this->client);
    }


    public function test_if_normalize_response_to_run_returns_a_valid_runs_object()
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

        $runs = $this->client->getRunsInADay($requestRuns);

        $this->assertGreaterThan(0, count($runs));
        $this->assertInstanceOf('Transpais\Type\Run', $runs[0]);
        $this->assertInstanceOf('\DateTime', $runs[0]->getDateOfRun());
        $this->assertInstanceOf('\DateTime', $runs[0]->getDateOfArrival());
        $this->assertInstanceOf('\DateTime', $runs[0]->getDateOfDeparture());

    }

    public function test_if_normalize_response_seat_map_returns_a_valid_response_seat_map_object()
    {
        $dummySoapResponse = new \stdClass();
        $dummySoapResponse->out->detallesDiagrama->DetalleDiagrama[0] = (object) array(
            'asiento' => 'P009',
            'coluna' => null,
            'fila' => null,
            'status' => 'DP'
        );

        $this->fakeSoapClient->setResponse($dummySoapResponse);

        $requestSeatMap = new RequestSeatMap();
        $requestSeatMap->setOriginId(1886);
        $requestSeatMap->setDestinationId(2219);
        $tomorrow = new \DateTime('tomorrow');
        $requestSeatMap->setDateOfRun($tomorrow);
        $requestSeatMap->setPosId(4825);
        $requestSeatMap->setRunId(37842);//Dynamic number, this is a mock number
        $requestSeatMap->setSaleTypeId(12);

        $responseSeatMap = $this->client->getSeatMap($requestSeatMap);

        $this->assertInstanceOf('Transpais\Type\ResponseSeatMap', $responseSeatMap);
        $this->assertInstanceOf('Transpais\Type\Seat', $responseSeatMap[0]);
    }

    public function test_if_ticket_id_is_assigned_to_the_ticket_object_in_block_ticket()
    {
        $dummySoapResponse = (object) array(
            'out' => (object) array(
                'Boleto' => (object) array(
                    'boletoId' => '10000002910666',
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

        $this->fakeSoapClient->setResponse($dummySoapResponse);

        $requestBlockTicket = new RequestBlockTicket();
        $requestBlockTicket->setUserId(619);
        $requestBlockTicket->setClientId(-1);
        $requestBlockTicket->setPosId(4825);
        $requestBlockTicket->setTransactionNum(-1);

        $ticket_to_block = new Ticket();
        $ticket_to_block->setCategoryId(1);// full price
        $ticket_to_block->setRunId(37842);//Dynamic number, this is a mock number
        $ticket_to_block->setOriginId(1886);
        $ticket_to_block->setDestinationId(2219);
        $tomorrow = new \DateTime('tomorrow');
        $ticket_to_block->setDateOfRun($tomorrow);
        $ticket_to_block->setPassengerName('Jonh Secada');
        $ticket_to_block->setPrice(212);

        $requestBlockTicket->appendTicket($ticket_to_block);

        $ticket_blocked = $this->client->blockTicket($requestBlockTicket);

        $this->assertInstanceOf('Transpais\Type\Ticket', $ticket_blocked);
        $this->assertTrue(!is_null($ticket_blocked->getTicketId()));
    }

    protected function setUp()
    {
        $this->fakeSoapClient = new \MockSoapClient('fake://wdsl');
        $this->client = new Client($this->fakeSoapClient);
    }


} 