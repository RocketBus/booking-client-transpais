<?php
/**
 * Created by PhpStorm.
 * User: degaray
 * Date: 7/4/14
 * Time: 5:37 PM
 */

namespace Transpais\Type;

use Transpais\Type\Errors\TypeException;

class ResponseSeatMap extends \ArrayObject
{
    public function append($seat)
    {
        if (!$seat instanceof Seat ) {
            throw new TypeException("An instance of Seat is required");
        }

        parent::append($seat);
    }
}