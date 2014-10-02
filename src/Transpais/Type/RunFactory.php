<?php
/**
 * Created by PhpStorm.
 * User: degaray
 * Date: 7/14/14
 * Time: 11:46 AM
 */

namespace Transpais\Type;

use Transpais\Type\Errors\TypeException;

class RunFactory
{
    public static function create($corrida)
    {
        if (!is_int(intval($corrida->asientosDisp))) {
            throw new TypeException('Asientos disponibles should be a numeric value');
        }

        if ((intval($corrida->corridaId)) == 0) {
            throw new TypeException('Corrida id should be a numeric value');
        }

        if (!is_string($corrida->descripcionClaseServicio)) {
            throw new TypeException('Descripcion Servicio should be a string');
        }

        if (!is_string($corrida->descripcionEmpresa)) {
            throw new TypeException('Descripcion Empresa should be a string');
        }

        if (intval($corrida->empresaId) == 0) {
            throw new TypeException('Empresa id should be a numeric value');
        }

        $formattedFechaCorrida = \DateTime::createFromFormat('Y-m-d\TH:i:sO', $corrida->fechaCorrida);
        if ($formattedFechaCorrida == false) {
            throw new TypeException('Fecha Hora Corrida should be a string with date format');
        }

        $formattedFechaLlegada = \DateTime::createFromFormat('Y-m-d\TH:i:sO', $corrida->fechorLlegada);
        if ($formattedFechaLlegada == false) {
            throw new TypeException('Fecha Hora LLegada should be a string with date format');
        }

        $formattedFechorSalida = \DateTime::createFromFormat('Y-m-d\TH:i:sO', $corrida->fechorSalida);
        if ($formattedFechorSalida == false) {
            throw new TypeException('Fecha Hora Salida should be a string with date format');
        }

        if (intval($corrida->precioBase) == 0) {
            throw new Errors\TypeException('Precio Base should be a numeric value');
        }

        $run = new Run();
        $run->setIdleSeatsNum($corrida->asientosDisp);
        $run->setServiceClassId($corrida->claseServicioId);
        $run->setRun2Id($corrida->corrida2Id);
        $run->setRunId($corrida->corridaId);
        $run->setDestinationCode($corrida->cveDestino);
        $run->setServiceClassDescription($corrida->descripcionClaseServicio);
        $run->setCompanyDescription($corrida->descripcionEmpresa);
        $run->setCompanyId($corrida->empresaId);

        $run->setDateOfRun($formattedFechaCorrida);
        $run->setDateOfArrival($formattedFechaLlegada);
        $run->setDateOfDeparture($formattedFechorSalida);
        $run->setBrandName($corrida->nombreMarca);
        $run->setKms($corrida->numKms);
        $run->setFloorNumber($corrida->numPiso);
        $run->setBasePrice($corrida->precioBase);

        $run->setRunDuration($corrida->tiempoRecorrido);
        $run->setServiceType($corrida->tipoServicio);

        return $run;
    }
}
