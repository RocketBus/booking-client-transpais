<?php
namespace Transpais\Type;

use SebastianBergmann\Exporter\Exception;
use Transpais\Type\Errors\TypeException;

/**
 * Class RequestSeatMapFactory
 * @package Transpais\Type
 */
class RequestSeatMapFactory
{
    public static function create($params)
    {
        $requestSeatMap = new RequestSeatMap();
        $requestSeatMap->setOriginId($params['origin_id']);
        $requestSeatMap->setDestinationId($params['destination_id']);

        if ($params['date_of_run'] instanceof \DateTime) {
            $requestSeatMap->setDateOfRun($params['date_of_run']);
        } else {
            throw new TypeException('Fecha de Corrida must be a Date Time');
        }

        $requestSeatMap->setPosId($params['pos_id']);
        $requestSeatMap->setRunId($params['run_id']);//Dynamic number, this is a mock number
        $requestSeatMap->setSaleTypeId($params['sale_type_id']);

        return $requestSeatMap;
    }
}
