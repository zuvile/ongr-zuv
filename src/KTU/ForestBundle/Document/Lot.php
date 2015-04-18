<?php

namespace KTU\ForestBundle\Document;

use Doctrine\Common\Collections\ArrayCollection;
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
     * @var  Layers
     *
     * @ES\Property(name="layers", type="object", multiple=true, objectName="KTUForestBundle:Layer")
     */
    private $layers = [];

    /**
     * Lot constructor.
     */
    public function __construct()
    {
        $this->layers = [];
    }


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
     * @return array
     */
    public function getLayers()
    {
        return $this->layers;
    }

    /**
     * @param array $layers
     */
    public function setLayers($layers)
    {
        $this->layers = $layers;
    }

    public function dump()
    {
        $data = null;
        if (!empty($this->layers))
        {
            $data['layers'] = $this->layers->jsonSerialize();
        }

        return $data;
    }

    public function addLayer(Layer $layer)
    {
        $this->layers[] = clone $layer;
    }
}