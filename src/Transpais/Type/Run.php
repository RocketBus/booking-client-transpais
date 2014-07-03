<?php
/**
 * Created by PhpStorm.
 * User: degaray
 * Date: 7/3/14
 * Time: 11:38 AM
 */

namespace Transpais\Type;


class Run
{
    protected $asientosDisp;
    protected $claseServicioId;
    protected $corrida2Id;
    protected $corridaId;
    protected $cveDestino;
    protected $descripcionClaseServicio;
    protected $descripcionEmpresa;
    protected $empresaId;
    protected $fechaCorrida;
    protected $fechorLlegada;
    protected $fechorSalida;
    protected $nombreMarca;
    protected $numKms;
    protected $numPiso;
    protected $precioBase;
    protected $tiempoRecorrido;
    protected $tipoServicio;

    public function __construct($corrida)
    {
        $this->setAsientosDisp($corrida->asientosDisp);
        $this->setClaseServicioId($corrida->claseServicioId);
        $this->setCorrida2Id($corrida->corrida2Id);
        $this->setCorridaId($corrida->corridaId);
        $this->setCveDestino($corrida->cveDestino);
        $this->setDescripcionClaseServicio($corrida->descripcionClaseServicio);
        $this->setDescripcionEmpresa($corrida->descripcionEmpresa);
        $this->setEmpresaId($corrida->empresaId);
        $this->setFechaCorrida($corrida->fechaCorrida);
        $this->setFechorLlegada($corrida->fechorLlegada);
        $this->setFechorSalida($corrida->fechorSalida);
        $this->setNombreMarca($corrida->nombreMarca);
        $this->setNumKms($corrida->numKms);
        $this->setNumPiso($corrida->numPiso);
        $this->setPrecioBase($corrida->precioBase);
        $this->setTiempoRecorrido($corrida->tiempoRecorrido);
        $this->setTipoServicio($corrida->tipoServicio);
    }

    // setters
    public function setAsientosDisp($asientosDisp)
    {
        $this->asientosDisp = $asientosDisp;
    }
    public function setClaseServicioId($claseServicioId)
    {
        $this->claseServicioId = $claseServicioId;
    }
    public function setCorrida2Id($corrida2Id)
    {
        $this->corrida2Id = $corrida2Id;
    }
    public function setCorridaId($corridaId)
    {
        $this->corridaId = $corridaId;
    }
    public function setCveDestino($cveDestino)
    {
        $this->cveDestino = $cveDestino;
    }
    public function setDescripcionClaseServicio($descripcionClaseServicio)
    {
        $this->descripcionClaseServicio = $descripcionClaseServicio;
    }
    public function setDescripcionEmpresa($descripcionEmpresa)
    {
        $this->descripcionEmpresa = $descripcionEmpresa;
    }
    public function setEmpresaId($empresaId)
    {
        $this->empresaId = $empresaId;
    }
    public function setFechaCorrida($fechaCorrida)
    {
        $this->fechaCorrida = $fechaCorrida;
    }
    public function setFechorLlegada($fechorLlegada)
    {
        $this->fechorLlegada = $fechorLlegada;
    }
    public function setFechorSalida($fechorSalida)
    {
        $this->fechorSalida = $fechorSalida;
    }
    public function setNombreMarca($nombreMarca)
    {
        $this->nombreMarca = $nombreMarca;
    }
    public function setNumKms($numKms)
    {
        $this->numKms = $numKms;
    }
    public function setNumPiso($numPiso)
    {
        $this->numPiso = $numPiso;
    }
    public function setPrecioBase($precioBase)
    {
        $this->precioBase = $precioBase;
    }
    public function setTiempoRecorrido($tiempoRecorrido)
    {
        $this->tiempoRecorrido = $tiempoRecorrido;
    }
    public function setTipoServicio($tipoServicio)
    {
        $this->tipoServicio = $tipoServicio;
    }

    // getters
    public function getAsientosDisp()
    {
        return $this->asientosDisp;
    }
    public function getClaseServicioId()
    {
        return $this->claseServicioId;
    }
    public function getCorrida2Id()
    {
        return $this->corrida2Id;
    }
    public function getCorridaId()
    {
        return $this->corridaId;
    }
    public function getCveDestino()
    {
        return $this->cveDestino;
    }
    public function getDescripcionClaseServicio()
    {
        return $this->descripcionClaseServicio;
    }
    public function getDescripcionEmpresa()
    {
        return $this->descripcionEmpresa;
    }
    public function getEmpresaId()
    {
        return $this->empresaId;
    }
    public function getFechaCorrida()
    {
        return $this->fechaCorrida;
    }
    public function getFechorLlegada()
    {
        return $this->fechorLlegada;
    }
    public function getFechorSalida()
    {
        return $this->fechorSalida;
    }
    public function getNombreMarca()
    {
        return $this->nombreMarca;
    }
    public function getNumKms()
    {
        return $this->numKms;
    }
    public function getNumPiso()
    {
        return $this->numPiso;
    }
    public function getPrecioBase()
    {
        return $this->precioBase;
    }
    public function getTiempoRecorrido()
    {
        return $this->tiempoRecorrido;
    }
    public function getTipoServicio()
    {
        return $this->tipoServicio;
    }
}
