<?php
namespace Transpais\Type;

use Transpais\Type\Errors\RequestException;
use Transpais\Type\RequestRuns;

/**
 * Class RequestSeatMap
 * @package Transpais\Type
 */
class RequestSeatMap
{
    protected $origin_id;
    protected $destination_id;
    protected $date_of_run;
    protected $run_id;
    protected $sale_type_id;
    protected $pos_id;

    /**
     * @param mixed $date_of_run
     */
    public function setDateOfRun($date_of_run)
    {
        $this->date_of_run = $date_of_run;
    }

    /**
     * @return mixed
     */
    public function getDateOfRun()
    {
        return $this->date_of_run;
    }

    /**
     * @param mixed $destination_id
     */
    public function setDestinationId($destination_id)
    {
        $this->destination_id = $destination_id;
    }

    /**
     * @return mixed
     */
    public function getDestinationId()
    {
        return $this->destination_id;
    }

    /**
     * @param mixed $origin_id
     */
    public function setOriginId($origin_id)
    {
        $this->origin_id = $origin_id;
    }

    /**
     * @return mixed
     */
    public function getOriginId()
    {
        return $this->origin_id;
    }

    /**
     * @param mixed $sale_type_id
     */
    public function setSaleTypeId($sale_type_id)
    {
        $this->sale_type_id = $sale_type_id;
    }

    /**
     * @return mixed
     */
    public function getSaleTypeId()
    {
        return $this->sale_type_id;
    }

    /**
     * @param mixed $pos_id
     */
    public function setPosId($pos_id)
    {
        $this->pos_id = $pos_id;
    }

    /**
     * @return mixed
     */
    public function getPosId()
    {
        return $this->pos_id;
    }

    /**
     * @param mixed $run_id
     */
    public function setRunId($run_id)
    {
        $this->run_id = $run_id;
    }

    /**
     * @return mixed
     */
    public function getRunId()
    {
        return $this->run_id;
    }
}
