<?php
/**
 * Created by PhpStorm.
 * User: degaray
 * Date: 7/3/14
 * Time: 10:23 AM
 */

namespace Transpais\Service;

use Transpais\Type\RequestRuns;
use Transpais\Type\ResponseRuns;
use Transpais\Type\Run;

class Client
{
    protected $wdsl_url;
    protected $request_runs;

    /**
     * Initializing the SoapClient is needed on each call to this class
     */
    public function __construct($wdsl_url = null)
    {
        if (!is_null($wdsl_url)) {
            $this->setWdslUrl($wdsl_url);
        }

        if (!isset($this->wdsl_url)) {
            throw new Exception('You need to set the WDSL URL');
        }

        $this->setSoapClient($this->wdsl_url);
    }

    public function getRunsInADay(RequestRuns $requestRuns)
    {
        $service_type = 'consultarCorridas';

        $service_params = array(
            'in0' => $requestRuns->getOriginId(), // origin Place ID (origenId)
            'in1' => $requestRuns->getDestinationId(), // destination Place ID (destinoId)
            'in2' => $requestRuns->getDateOfRun()->format('c'),
        );

        $soap_param = array(
            'ventaService' => $service_params
        );
        $soap_response = $this->callSoapServiceByType($service_type, $soap_param);

        $response = $this->normalizeResponseToRun($soap_response->out->Corrida);

        return $response;
    }

    protected function callSoapServiceByType($type, $params)
    {

        $response = $this->_soap_client->__soapCall($type, $params, array('trace' => 1));

        return $response;
    }

    protected function normalizeSingleObject($out)
    {

        foreach ($out as $index => $object) {

            if (!is_array($object)) {
                $class = new \stdClass();
                $class->{$index}[] = $object;
                return $class;
            } else {
                return $out;
            }
        }
        return $out;
    }

    public function setWdslUrl($url)
    {
        $this->wdsl_url = $url;
    }

    public function setSoapClient($url)
    {
        $this->_soap_client = new \SoapClient($url);
    }

    protected function normalizeResponseToRun($response)
    {
        $responseRuns = new ResponseRuns();

        foreach ($response as $run) {
            $runObj = new Run($run);
            $responseRuns->append($runObj);
        }

        return $responseRuns;
    }

}