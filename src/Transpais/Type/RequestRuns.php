<?php

namespace Transpais\Type;

use Transpais\Type\Errors\RequestException;

/**
 * Class RequestRuns
 * @package Transpais\Type
 */
class RequestRuns
{

    protected $origin_id;
    protected $destination_id;
    protected $date_of_run;

    public function setOriginId($id)
    {
        if (!is_int($id)) {
            throw new RequestException('Origin ID must be set and a numeric value.');
        }
        $this->origin_id = $id;
    }

    public function getOriginId()
    {
        return $this->origin_id;
    }

    public function setDestinationId($id)
    {
        if (!is_int($id)) {
            throw new RequestException('Destination ID must be set and a numeric value.');
        }
        $this->destination_id = $id;
    }

    public function getDestinationId()
    {
        return $this->destination_id;
    }

    public function setDateOfRun(\DateTime $date)
    {
        $this->date_of_run = $date;
    }

    public function getDateOfRun()
    {
        return $this->date_of_run;
    }
}
