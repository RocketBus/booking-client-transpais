<?php
/**
 * Created by PhpStorm.
 * User: degaray
 * Date: 7/16/14
 * Time: 11:22 AM
 */

namespace Transpais\Type;


use Transpais\Type\Errors\TypeException;

class RequestConfirmPaymentFactory
{
    static public function create($params)
    {
        $requestConfirmPayment = new RequestConfirmPayment();

        if (!is_int($params['client_id'])) {
            throw new TypeException('Cliente Id must be a numeric value');
        }
        $requestConfirmPayment->setClientId($params['client_id']);

        if (!is_int($params['user_id'])) {
            throw new TypeException('Usuario Id must be a numeric value');
        }
        $requestConfirmPayment->setUserId($params['user_id']);

        if (!is_int($params['company_id'])) {
            throw new TypeException('Empresa Id must be a numeric value');
        }
        $requestConfirmPayment->setCompanyId($params['company_id']);

        if (!is_array($params['card'])) {
            throw new TypeException('Tarjeta must be an array');
        }
        $requestConfirmPayment->setCard($params['card']);

        if (!is_array($params['tickets_to_confirm'])) {
            throw new TypeException('Tickets must be an array of tickets');
        }

        foreach ($params['tickets_to_confirm'] as $ticket) {
            if (!($ticket instanceof Ticket)) {
                throw new TypeException('Tickets must be an array of tickets');
            } else {
                $tickets_to_confirm[] = TicketToBlockFactory::create($ticket);
            }
        }
        $requestConfirmPayment->setTicketsToConfirm($tickets_to_confirm);

        if (!is_bool($params['is_return'])) {
            throw new TypeException('Es Regreso must be a boolean');
        }
        $requestConfirmPayment->setIsReturnTicket($params['is_return']);

        return $requestConfirmPayment;
    }
}
