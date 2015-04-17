<?php

namespace KTU\ForestBundle\Document;

use ONGR\ElasticsearchBundle\Document\DocumentInterface;
use ONGR\ElasticsearchBundle\Document\DocumentTrait;
use ONGR\ElasticsearchBundle\Annotation as ES;

/**
 * Stores forestry data.
 *
 * @ES\Document(type="lot")
 */
class Lot implements DocumentInterface
{
    use DocumentTrait;

    /**
     * @var string
     *
     * @ES\Property(name="department", type="string")
     */
    private $department; //uredija

    /**
     * @var  string
     * @ES\Property(name="forestry", type="string")
     */
    private $forestry; //girininkija

    /**
     * @var  string
     *
     * @ES\Property(name="municipality", type="string")
     */
    private $municipality; //savivaldybe

    /**
     * @var  float
     *
     * @ES\Property(name="territory", type="float")
     */
    private $territory;

    /**
     * @var  float
     *
     * @ES\Property(name="lushness", type="float")
     */
    private $lushness;

    /**
     * @var  string
     *
     * @ES\Property(name="from", type="string")
     */
    private $from;

    /**
     * @var  string
     *
     * @ES\Property(name="to", type="string")
     */
    private $to;

    /**
     * @var  string
     *
     * @ES\Property(name="layer", type="string")
     */
    private $layer;

    /**
     * @var  string
     *
     * @ES\Property(name="species", type="string")
     */
    private $species;

    /**
     * @var  float
     *
     * @ES\Property(name="ratio", type="float")
     */
    private $ratio;

    /**
     * @var  integer
     *
     * @ES\Property(name="age", type="integer")
     */
    private $age;

    /**
     * @var  float
     *
     * @ES\Property(name="height", type="float")
     */
    private $height;

    /**
     * @var  float
     *
     * @ES\Property(name="diameter", type="float")
     */
    private $diameter;

    /**
     * @return string
     */
    public function getDepartment()
    {
        return $this->department;
    }

    /**
     * @param string $department
     */
    public function setDepartment($department)
    {
        $this->department = $department;
    }

    /**
     * @return string
     */
    public function getForestry()
    {
        return $this->forestry;
    }

    /**
     * @param string $forestry
     */
    public function setForestry($forestry)
    {
        $this->forestry = $forestry;
    }

    /**
     * @return string
     */
    public function getMunicipality()
    {
        return $this->municipality;
    }

    /**
     * @param string $municipality
     */
    public function setMunicipality($municipality)
    {
        $this->municipality = $municipality;
    }

    /**
     * @return float
     */
    public function getTerritory()
    {
        return $this->territory;
    }

    /**
     * @param float $territory
     */
    public function setTerritory($territory)
    {
        $this->territory = $territory;
    }

    /**
     * @return float
     */
    public function getLushness()
    {
        return $this->lushness;
    }

    /**
     * @param float $lushness
     */
    public function setLushness($lushness)
    {
        $this->lushness = $lushness;
    }

    /**
     * @return string
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @param string $from
     */
    public function setFrom($from)
    {
        $this->from = $from;
    }

    /**
     * @return string
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * @param string $to
     */
    public function setTo($to)
    {
        $this->to = $to;
    }

    /**
     * @return string
     */
    public function getLayer()
    {
        return $this->layer;
    }

    /**
     * @param string $layer
     */
    public function setLayer($layer)
    {
        $this->layer = $layer;
    }

    /**
     * @return string
     */
    public function getSpecies()
    {
        return $this->species;
    }

    /**
     * @param string $species
     */
    public function setSpecies($species)
    {
        $this->species = $species;
    }

    /**
     * @return float
     */
    public function getRatio()
    {
        return $this->ratio;
    }

    /**
     * @param float $ratio
     */
    public function setRatio($ratio)
    {
        $this->ratio = $ratio;
    }

    /**
     * @return int
     */
    public function getAge()
    {
        return $this->age;
    }

    /**
     * @param int $age
     */
    public function setAge($age)
    {
        $this->age = $age;
    }

    /**
     * @return float
     */
    public function getDiameter()
    {
        return $this->diameter;
    }

    /**
     * @param float $diameter
     */
    public function setDiameter($diameter)
    {
        $this->diameter = $diameter;
    }

    /**
     * @return float
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @param float $height
     */
    public function setHeight($height)
    {
        $this->height = $height;
    }
}
