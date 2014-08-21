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
    static public function create($corrida)
    {
        $run = new Run();

        if (!is_int((int)$corrida->asientosDisp)) {
            throw new TypeException('Asientos disponibles should be a numeric value');
        } else {
            $run->setIdleSeatsNum($corrida->asientosDisp);
        }

        $run->setServiceClassId($corrida->claseServicioId);
        $run->setRun2Id($corrida->corrida2Id);

        if (!is_int((int)$corrida->corridaId)) {
            throw new TypeException('Corrida id should be a numeric value');
        } else {
            $run->setRunId($corrida->corridaId);
        }

        $run->setDestinationCode($corrida->cveDestino);

        if (!is_string($corrida->descripcionClaseServicio)) {
            throw new TypeException('Descripcion Servicio should be a string');
        } else {
            $run->setServiceClassDescription($corrida->descripcionClaseServicio);
        }

        if (!is_string($corrida->descripcionEmpresa)) {
            throw new TypeException('Descripcion Empresa should be a string');
        } else {
            $run->setCompanyDescription($corrida->descripcionEmpresa);
        }

        if (!is_int((int) $corrida->empresaId)) {
            throw new TypeException('Empresa id should be a numeric value');
        } else {
            $run->setCompanyId($corrida->empresaId);
        }

        $formattedFechaCorrida = \DateTime::createFromFormat('Y-m-d\TH:i:sO', $corrida->fechaCorrida);
        if ($formattedFechaCorrida == FALSE) {
            throw new TypeException('Fecha Hora Corrida should be a string with date format');
        } else {
            $run->setDateOfRun($formattedFechaCorrida);
        }

        $formattedFechaLlegada = \DateTime::createFromFormat('Y-m-d\TH:i:sO', $corrida->fechorLlegada);
        if ($formattedFechaLlegada == FALSE) {
            throw new TypeException('Fecha Hora LLegada should be a string with date format');
        } else {
            $run->setDateOfArrival($formattedFechaLlegada);
        }

        $formattedFechorSalida = \DateTime::createFromFormat('Y-m-d\TH:i:sO', $corrida->fechorSalida);
        if ($formattedFechorSalida == FALSE) {
            throw new TypeException('Fecha Hora Salida should be a string with date format');
        } else {
            $run->setDateOfDeparture($formattedFechorSalida);
        }

        $run->setBrandName($corrida->nombreMarca);
        $run->setKms($corrida->numKms);
        $run->setFloorNumber($corrida->numPiso);

        if (!is_int(intval($corrida->precioBase))) {
            throw new Errors\TypeException('Precio Base should be a numeric value');
        } else {
            $run->setBasePrice($corrida->precioBase);
        }

        $run->setRunDuration($corrida->tiempoRecorrido);
        $run->setServiceType($corrida->tipoServicio);

        return $run;
    }
} 