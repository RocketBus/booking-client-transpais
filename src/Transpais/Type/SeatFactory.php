<?php
/**
 * Created by PhpStorm.
 * User: degaray
 * Date: 7/14/14
 * Time: 12:35 PM
 */

namespace Transpais\Type;

use Transpais\Type\Errors\TypeException;

class SeatFactory
{
    public static function create($seatParams)
    {
        $seat = new Seat();

        if (!is_string($seatParams->asiento)) {
            throw new TypeException('Asiento should be a string');
        } else {
            $seat->setSeatNumber($seatParams->asiento);
        }

        $seat->setColumn($seatParams->coluna);
        $seat->setRow($seatParams->fila);

        if (!is_string($seatParams->status)) {
            throw new TypeException('Status should be a string');
        } else {
            $seat->setStatus($seatParams->status);
        }

        return $seat;
    }

}
