<?php
/**
 * Created by PhpStorm.
 * User: degaray
 * Date: 7/17/14
 * Time: 12:01 PM
 */

namespace Transpais\Type;

use Transpais\Type\Errors\TypeException;

class TicketFactory
{
    public static function create($params)
    {
        if (!isset($params['ticket_id']) || is_null($params['ticket_id']) || !is_int($params['ticket_id'])) {
            throw new TypeException('Boleto Id must be a numeric value');
        }

        if (!is_int($params['category_id'])) {
            throw new TypeException('Categoria Id must be a numeric value');
        }

        if (!is_int($params['run_id'])) {
            throw new TypeException('Corrida Id must be a numeric value');
        }

        if (!is_int($params['origin_id'])) {
            throw new TypeException('Origen Id must be a numeric value');
        }

        if (!is_int($params['destination_id'])) {
            throw new TypeException('Destination Id must be a numeric value');
        }

        if (! $params['date_of_run'] instanceof \DateTime) {
            throw new TypeException('Date of run Id must be a Date Time');
        }

        if (!is_string($params['passenger_name'])) {
            throw new TypeException('Passenger Name must be a string');
        }

        $ticket_to_block = new Ticket();
        $ticket_to_block->setTicketId($params['ticket_id']);
        $ticket_to_block->setCategoryId($params['category_id']);
        $ticket_to_block->setRunId($params['run_id']);
        $ticket_to_block->setOriginId($params['origin_id']);
        $ticket_to_block->setDestinationId($params['destination_id']);
        $ticket_to_block->setDateOfRun($params['date_of_run']);
        $ticket_to_block->setPassengerName($params['passenger_name']);

        if (!isset($params['seat_number'])) {
            $ticket_to_block->setSeatNumber(intval($params['seat_number']));
        }

        if (isset($params['base_price'])) {
            $ticket_to_block->setBasePrice(floatval($params['base_price']));
        }

        return $ticket_to_block;
    }
}
