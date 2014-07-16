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
    protected $idleSeatsNum;
    protected $serviceClassId;
    protected $run2Id;
    protected $runId;
    protected $destinationCode;
    protected $serviceClassDescription;
    protected $companyDescription;
    protected $companyId;
    protected $dateOfRun;
    protected $dateOfArrival;
    protected $dateOfDeparture;
    protected $brandName;
    protected $kms;
    protected $floorNumber;
    protected $basePrice;
    protected $runDuration;
    protected $serviceType;

    /**
     * @param mixed $basePrice
     */
    public function setBasePrice($basePrice)
    {
        $this->basePrice = $basePrice;
    }

    /**
     * @return mixed
     */
    public function getBasePrice()
    {
        return $this->basePrice;
    }

    /**
     * @param mixed $brandName
     */
    public function setBrandName($brandName)
    {
        $this->brandName = $brandName;
    }

    /**
     * @return mixed
     */
    public function getBrandName()
    {
        return $this->brandName;
    }

    /**
     * @param mixed $serviceClassDescription
     */
    public function setServiceClassDescription($serviceClassDescription)
    {
        $this->serviceClassDescription = $serviceClassDescription;
    }

    /**
     * @return mixed
     */
    public function getServiceClassDescription()
    {
        return $this->serviceClassDescription;
    }

    /**
     * @param mixed $companyDescription
     */
    public function setCompanyDescription($companyDescription)
    {
        $this->companyDescription = $companyDescription;
    }

    /**
     * @return mixed
     */
    public function getCompanyDescription()
    {
        return $this->companyDescription;
    }

    /**
     * @param mixed $companyId
     */
    public function setCompanyId($companyId)
    {
        $this->companyId = $companyId;
    }

    /**
     * @return mixed
     */
    public function getCompanyId()
    {
        return $this->companyId;
    }

    /**
     * @param mixed $dateOfArrival
     */
    public function setDateOfArrival($dateOfArrival)
    {
        $this->dateOfArrival = $dateOfArrival;
    }

    /**
     * @return mixed
     */
    public function getDateOfArrival()
    {
        return $this->dateOfArrival;
    }

    /**
     * @param mixed $dateOfDeparture
     */
    public function setDateOfDeparture($dateOfDeparture)
    {
        $this->dateOfDeparture = $dateOfDeparture;
    }

    /**
     * @return mixed
     */
    public function getDateOfDeparture()
    {
        return $this->dateOfDeparture;
    }

    /**
     * @param mixed $dateOfRun
     */
    public function setDateOfRun($dateOfRun)
    {
        $this->dateOfRun = $dateOfRun;
    }

    /**
     * @return mixed
     */
    public function getDateOfRun()
    {
        return $this->dateOfRun;
    }

    /**
     * @param mixed $destinationCode
     */
    public function setDestinationCode($destinationCode)
    {
        $this->destinationCode = $destinationCode;
    }

    /**
     * @return mixed
     */
    public function getDestinationCode()
    {
        return $this->destinationCode;
    }

    /**
     * @param mixed $floorNumber
     */
    public function setFloorNumber($floorNumber)
    {
        $this->floorNumber = $floorNumber;
    }

    /**
     * @return mixed
     */
    public function getFloorNumber()
    {
        return $this->floorNumber;
    }

    /**
     * @param mixed $idleSeatsNum
     */
    public function setIdleSeatsNum($idleSeatsNum)
    {
        $this->idleSeatsNum = $idleSeatsNum;
    }

    /**
     * @return mixed
     */
    public function getIdleSeatsNum()
    {
        return $this->idleSeatsNum;
    }

    /**
     * @param mixed $kms
     */
    public function setKms($kms)
    {
        $this->kms = $kms;
    }

    /**
     * @return mixed
     */
    public function getKms()
    {
        return $this->kms;
    }

    /**
     * @param mixed $run2Id
     */
    public function setRun2Id($run2Id)
    {
        $this->run2Id = $run2Id;
    }

    /**
     * @return mixed
     */
    public function getRun2Id()
    {
        return $this->run2Id;
    }

    /**
     * @param mixed $runDuration
     */
    public function setRunDuration($runDuration)
    {
        $this->runDuration = $runDuration;
    }

    /**
     * @return mixed
     */
    public function getRunDuration()
    {
        return $this->runDuration;
    }

    /**
     * @param mixed $runId
     */
    public function setRunId($runId)
    {
        $this->runId = $runId;
    }

    /**
     * @return mixed
     */
    public function getRunId()
    {
        return $this->runId;
    }

    /**
     * @param mixed $serviceClassId
     */
    public function setServiceClassId($serviceClassId)
    {
        $this->serviceClassId = $serviceClassId;
    }

    /**
     * @return mixed
     */
    public function getServiceClassId()
    {
        return $this->serviceClassId;
    }

    /**
     * @param mixed $serviceType
     */
    public function setServiceType($serviceType)
    {
        $this->serviceType = $serviceType;
    }

    /**
     * @return mixed
     */
    public function getServiceType()
    {
        return $this->serviceType;
    }


}
