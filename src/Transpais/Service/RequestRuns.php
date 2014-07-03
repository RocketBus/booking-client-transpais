<?php
/**
 * Created by PhpStorm.
 * User: degaray
 * Date: 7/3/14
 * Time: 10:04 AM
 */

namespace Transpais\Service;


class RequestRuns
{

    protected $origin_id;
    protected $destination_id;
    protected $date_of_run;

    public function setOriginId($id)
    {
        if (!is_int($id)) {
            throw new \Exception('Origin ID must be set and a numeric value.');
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
            throw new \Exception('Destination ID must be set and a numeric value.');
        }
        $this->destination_id = $id;
    }

    public function getDestinationId()
    {
        return $this->destination_id;
    }

    public function setDateOfRun($date)
    {
        if (!static::testIso8601($date)) {
            throw new \Exception('Date of run should be set and in ISO8601 format Ej. "2004-02-12T15:19:21+00:00".');
        }
        $this->date_of_run = $date;
    }

    public function getDateOfRun()
    {
        return $this->date_of_run;
    }

    protected static function testIso8601($date_string)
    {
        if ($date_string == date('c', strtotime($date_string))) {
            return true;
        }

        return false;
    }

    protected function callSoapServiceByType($type, $params)
    {

        $response = $this->_soap_client->__soapCall($type, $params, array('trace' => 1));

        return $response;
    }
}
