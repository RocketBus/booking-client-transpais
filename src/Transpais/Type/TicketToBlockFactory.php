<?php
/**
 * Created by PhpStorm.
 * User: degaray
 * Date: 7/14/14
 * Time: 1:49 PM
 */

namespace Transpais\Type;

class TicketToBlockFactory
{
    static public function create(Ticket $ticket) {

        $formattedDateOfRun = $ticket->getDateOfRun()->format('c');
        $ticket_to_block = array(
            'categoriaId' => $ticket->getCategoryId(),
            'corridaId' => $ticket->getRunId(),
            'destinoId' => $ticket->getDestinationId(),
            'fechaCorrida' => $formattedDateOfRun,
            'nombrePasajero' => $ticket->getPassengerName(),
            'numAsiento' => $ticket->getSeatNumber(),
            'origenId' => $ticket->getOriginId(),
            'precio' => $ticket->getBasePrice(),
        );

        return $ticket_to_block;

    }
} 