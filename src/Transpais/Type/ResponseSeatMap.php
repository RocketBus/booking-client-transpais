<?php
/**
 * Created by PhpStorm.
 * User: degaray
 * Date: 7/4/14
 * Time: 5:37 PM
 */

namespace Transpais\Type;

use Transpais\Type\Errors\TypeException;

class ResponseSeatMap
{
    protected $seats;
    protected $seat_types;

    /**
     * @param mixed $seat_types
     */
    public function setSeatTypes($seat_types)
    {
        $this->seat_types = $seat_types;
    }

    /**
     * @return mixed
     */
    public function getSeatTypes()
    {
        return $this->seat_types;
    }

    /**
     * @param mixed $seats
     */
    public function setSeats($seats)
    {
        $this->seats = $seats;
    }

    /**
     * @return mixed
     */
    public function getSeats()
    {
        return $this->seats;
    }

    public function appendSeat($seat)
    {
        $this->seats[] = $seat;
    }

    public function appendSeatType($seat_type)
    {
        $this->seat_types[] = $seat_type;
    }

}