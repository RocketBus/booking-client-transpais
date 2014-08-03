<?php
/**
 * Created by PhpStorm.
 * User: degaray
 * Date: 7/16/14
 * Time: 11:09 AM
 */

namespace Transpais\Type;

class RequestConfirmPayment
{
    protected $client_id;
    protected $user_id;
    protected $company_id;
    protected $card;
    protected $tickets_to_confirm;
    protected $is_return_ticket;

    /**
     * @param mixed $card
     */
    public function setCard($card)
    {
        $this->card = $card;
    }

    /**
     * @return mixed
     */
    public function getCard()
    {
        return $this->card;
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
     * @param mixed $company_id
     */
    public function setCompanyId($company_id)
    {
        $this->company_id = $company_id;
    }

    /**
     * @return mixed
     */
    public function getCompanyId()
    {
        return $this->company_id;
    }

    /**
     * @param mixed $is_return_ticket
     */
    public function setIsReturnTicket($is_return_ticket)
    {
        $this->is_return_ticket = $is_return_ticket;
    }

    /**
     * @return mixed
     */
    public function getIsReturnTicket()
    {
        return $this->is_return_ticket;
    }

    /**
     * @param mixed $tickets_to_confirm
     */
    public function setTicketsToConfirm($tickets_to_confirm)
    {
        $this->tickets_to_confirm = $tickets_to_confirm;
    }

    /**
     * @return mixed
     */
    public function getTicketsToConfirm()
    {
        return $this->tickets_to_confirm;
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
    }

}
