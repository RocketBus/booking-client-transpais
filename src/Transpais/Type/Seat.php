<?php
/**
 * Created by PhpStorm.
 * User: degaray
 * Date: 7/3/14
 * Time: 11:38 AM
 */

namespace Transpais\Type;


class Seat
{
    protected $asiento;
    protected $coluna;
    protected $fila;
    protected $status;

    public function __construct($seat)
    {
        // set minimum configuration
        $this->setAsiento($seat->asiento);
        $this->setColuna($seat->coluna);
        $this->setFila($seat->fila);
        $this->setStatus($seat->status);
    }

    // setters
    public function setAsiento($asiento)
    {
        $this->asiento = $asiento;
    }
    public function setColuna($coluna)
    {
        $this->coluna = $coluna;
    }
    public function setFila($fila)
    {
        $this->fila = $fila;
    }
    public function setStatus($status)
    {
        $this->status = $status;
    }

    // getters
    public function getAsiento()
    {
        return $this->asiento;
    }
    public function getColuna()
    {
        return $this->coluna;
    }
    public function getFila()
    {
        return $this->fila;
    }
    public function getStatus()
    {
        return $this->status;
    }
}
