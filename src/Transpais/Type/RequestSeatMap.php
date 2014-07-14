<?php
/**
 * Created by PhpStorm.
 * User: degaray
 * Date: 7/3/14
 * Time: 3:32 PM
 */

namespace Transpais\Type;

use Transpais\Type\Errors\RequestException;
use Transpais\Type\RequestRuns;

class RequestSeatMap
{
    protected $RequestRuns;
    protected $run_id;
    protected $sale_type_id;
    protected $pos_id;

    public function setRequestRuns(RequestRuns $requestRuns)
    {
        $this->RequestRuns = $requestRuns;
    }

    public function setRunId($id)
    {
        if (!is_int($id)) {
            throw new RequestException('Run Id must be numeric');
        }
        $this->run_id = $id;
    }

    public function setSaleTypeId($id)
    {
        if (!is_int($id)) {
            throw new RequestException('Sale Type must be numeric');
        }
        $this->sale_type_id = $id;
    }

    public function setPosId($id)
    {
        if (!is_int($id)) {
            throw new RequestException('Point of Sale must be numeric');
        }
        $this->pos_id = $id;
    }

    public function getRequestRuns()
    {
        return $this->RequestRuns;
    }

    public function getRunId()
    {
        return $this->run_id;
    }

    public function getSaleTypeId()
    {
        return $this->sale_type_id;
    }

    public function getPosId()
    {
        return $this->pos_id;
    }
} 