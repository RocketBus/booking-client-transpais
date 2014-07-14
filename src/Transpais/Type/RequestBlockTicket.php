<?php
/**
 * Created by PhpStorm.
 * User: degaray
 * Date: 7/7/14
 * Time: 10:51 AM
 */

namespace Transpais\Type;

class RequestBlockTicket
{
    protected $client_id; //clienteId
    protected $user_id; //usuarioId
    protected $RequestSeatMap;
    protected $transaction_num; //numOperacion
    protected $ticket;

    /**
     * @param mixed $RequestSeatMap
     */
    public function setRequestSeatMap(RequestSeatMap $RequestSeatMap)
    {
        $this->RequestSeatMap = $RequestSeatMap;
    }

    /**
     * @return mixed
     */
    public function getRequestSeatMap()
    {
        return $this->RequestSeatMap;
    }

    /**
     * @param mixed $ticket
     */
    public function setTicket($ticket)
    {
        $this->ticket = $ticket;
    }

    /**
     * @return mixed
     */
    public function getTicket()
    {
        return $this->ticket;
    }

    /**
     * @param mixed $client_id
     */
    public function setClientId($client_id)
    {
        $this->client_id = $client_id;
    }

    /**
     * @return mixed
     */
    public function getClientId()
    {
        return $this->client_id;
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

    /**
     * @param mixed $user_id
     */
    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
    }

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->user_id;
    } //boletos



    public function appendTicket(Ticket $ticket)
    {
        if (!$ticket instanceof Ticket ) {
            throw new \TypeException("An instance of Ticket is required");
        }

        $this->ticket = $ticket;
    }
} 