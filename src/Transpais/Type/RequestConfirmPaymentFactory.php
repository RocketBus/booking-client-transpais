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
    public static function create($params)
    {
        if (!is_int($params['client_id'])) {
            throw new TypeException('Cliente Id must be a numeric value');
        }

        if (!is_int($params['user_id'])) {
            throw new TypeException('Usuario Id must be a numeric value');
        }

        if (!is_int($params['company_id'])) {
            throw new TypeException('Empresa Id must be a numeric value');
        }

        if (!is_array($params['card'])) {
            throw new TypeException('Tarjeta must be an array');
        }


        if (!is_array($params['tickets_to_confirm'])) {
            throw new TypeException('Tickets must be an array of tickets');
        }

        foreach ($params['tickets_to_confirm'] as $ticket) {
            if (!($ticket instanceof Ticket)) {
                throw new TypeException('Tickets must be an array of tickets');
            }
        }

        if (!is_bool($params['is_return'])) {
            throw new TypeException('Es Regreso must be a boolean');
        }

        $requestConfirmPayment = new RequestConfirmPayment();
        $requestConfirmPayment->setClientId($params['client_id']);
        $requestConfirmPayment->setUserId($params['user_id']);
        $requestConfirmPayment->setCompanyId($params['company_id']);
        $requestConfirmPayment->setCard($params['card']);
        $requestConfirmPayment->setTicketsToConfirm($params['tickets_to_confirm']);

        $requestConfirmPayment->setIsReturnTicket($params['is_return']);

        return $requestConfirmPayment;
    }
}
