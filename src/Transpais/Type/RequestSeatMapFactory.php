<?php
/**
 * Created by PhpStorm.
 * User: degaray
 * Date: 7/18/14
 * Time: 4:54 PM
 */

namespace Transpais\Type;


use SebastianBergmann\Exporter\Exception;
use Transpais\Type\Errors\TypeException;

class RequestSeatMapFactory
{
    public static function create($params)
    {
        if (! $params['date_of_run'] instanceof \DateTime) {
            throw new TypeException('Fecha de Corrida must be a Date Time');
        }

        $requestSeatMap = new RequestSeatMap();
        $requestSeatMap->setOriginId($params['origin_id']);
        $requestSeatMap->setDestinationId($params['destination_id']);
        $requestSeatMap->setDateOfRun($params['date_of_run']);
        $requestSeatMap->setPosId($params['pos_id']);
        $requestSeatMap->setRunId($params['run_id']);//Dynamic number, this is a mock number
        $requestSeatMap->setSaleTypeId($params['sale_type_id']);

        return $requestSeatMap;
    }
}
