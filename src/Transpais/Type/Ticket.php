<?php
/**
 * Created by PhpStorm.
 * User: degaray
 * Date: 7/7/14
 * Time: 12:21 PM
 */

namespace Transpais\Type;


class Ticket
{
    protected $ticket_id;
    protected $category_id; //catergory_id
    protected $RequestRuns;
    protected $passenger_name; //nombrePasajero
    protected $Seat;
    protected $Run;
    protected $folio_number; //numFolioSistema
    protected $transaction_num; //numOperacion
    protected $price;
    protected $price_service1; //precioServicio1
    protected $price_service2; //precioServicio2
    protected $price_service3; //precioServicio3
    protected $price_service4; //precioServicio4
    protected $service1_id; //servicio1Id
    protected $service2_id; //servicio2Id
    protected $service3_id; //servicio3Id
    protected $service4_id;

    /**
     * @param mixed $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * @return mixed
     */
    public function getPrice()
    {
        return $this->price;
    } //servicio4Id

    /**
     * @param mixed $ticket_id
     */
    public function setTicketId($ticket_id)
    {
        $this->ticket_id = $ticket_id;
    }

    /**
     * @return mixed
     */
    public function getTicketId()
    {
        return $this->ticket_id;
    }

    /**
     * @param mixed $RequestRuns
     */
    public function setRequestRuns(RequestRuns $RequestRuns)
    {
        $this->RequestRuns = $RequestRuns;
    }

    /**
     * @return mixed
     */
    public function getRequestRuns()
    {
        return $this->RequestRuns;
    }

    /**
     * @param mixed $Seat
     */
    public function setSeat(Seat $Seat)
    {
        $this->Seat = $Seat;
    }

    /**
     * @return mixed
     */
    public function getSeat()
    {
        return $this->Seat;
    }

    /**
     * @param mixed $Run
     */
    public function setRun(Run $Run)
    {
        $this->Run = $Run;
    }

    /**
     * @return mixed
     */
    public function getRun()
    {
        return $this->Run;
    }

    /**
     * @param mixed $category_id
     */
    public function setCategoryId($category_id)
    {
        $this->category_id = $category_id;
    }

    /**
     * @return mixed
     */
    public function getCategoryId()
    {
        return $this->category_id;
    }

    /**
     * @param mixed $folio_number
     */
    public function setFolioNumber($folio_number)
    {
        $this->folio_number = $folio_number;
    }

    /**
     * @return mixed
     */
    public function getFolioNumber()
    {
        return $this->folio_number;
    }

    /**
     * @param mixed $passenger_name
     */
    public function setPassengerName($passenger_name)
    {
        $this->passenger_name = $passenger_name;
    }

    /**
     * @return mixed
     */
    public function getPassengerName()
    {
        return $this->passenger_name;
    }

    /**
     * @param mixed $price_service1
     */
    public function setPriceService1($price_service1)
    {
        $this->price_service1 = $price_service1;
    }

    /**
     * @return mixed
     */
    public function getPriceService1()
    {
        return $this->price_service1;
    }

    /**
     * @param mixed $price_service2
     */
    public function setPriceService2($price_service2)
    {
        $this->price_service2 = $price_service2;
    }

    /**
     * @return mixed
     */
    public function getPriceService2()
    {
        return $this->price_service2;
    }

    /**
     * @param mixed $price_service3
     */
    public function setPriceService3($price_service3)
    {
        $this->price_service3 = $price_service3;
    }

    /**
     * @return mixed
     */
    public function getPriceService3()
    {
        return $this->price_service3;
    }

    /**
     * @param mixed $price_service4
     */
    public function setPriceService4($price_service4)
    {
        $this->price_service4 = $price_service4;
    }

    /**
     * @return mixed
     */
    public function getPriceService4()
    {
        return $this->price_service4;
    }

    /**
     * @param mixed $service1_id
     */
    public function setService1Id($service1_id)
    {
        $this->service1_id = $service1_id;
    }

    /**
     * @return mixed
     */
    public function getService1Id()
    {
        return $this->service1_id;
    }

    /**
     * @param mixed $service2_id
     */
    public function setService2Id($service2_id)
    {
        $this->service2_id = $service2_id;
    }

    /**
     * @return mixed
     */
    public function getService2Id()
    {
        return $this->service2_id;
    }

    /**
     * @param mixed $service3_id
     */
    public function setService3Id($service3_id)
    {
        $this->service3_id = $service3_id;
    }

    /**
     * @return mixed
     */
    public function getService3Id()
    {
        return $this->service3_id;
    }

    /**
     * @param mixed $service4_id
     */
    public function setService4Id($service4_id)
    {
        $this->service4_id = $service4_id;
    }

    /**
     * @return mixed
     */
    public function getService4Id()
    {
        return $this->service4_id;
    }

    /**
     * @param mixed $transaction_num
     */
    public function setTransactionNum($transaction_num)
    {
        $this->transaction_num = $transaction_num;
    }

    /**
     * @return mixed
     */
    public function getTransactionNum()
    {
        return $this->transaction_num;
    }
}
