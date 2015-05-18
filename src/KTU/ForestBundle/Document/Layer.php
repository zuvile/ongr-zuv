<?php

namespace KTU\ForestBundle\Document;
use ONGR\ElasticsearchBundle\Annotation as ES;
use ONGR\ElasticsearchBundle\Document\DocumentInterface;
use ONGR\ElasticsearchBundle\Document\DocumentTrait;

/**
 * Product location data.
 *
 * @ES\Object()
 */
class Layer
{
    /**
     * @var  string
     *
     * @ES\Property(name="layer", type="string")
     */
    private $layer;

    /**
     * @var  float
     *
     * @ES\Property(name="ratio", type="float")
     */
    private $ratio;

    /**
     * @var  string
     *
     * @ES\Property(name="species", type="string", index="not_analyzed")
     */
    private $species;

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
     * @return array|mixed
     */
    public function jsonSerialize()
    {
        return [
            'layer' => $this->layer,
            'ratio' => $this->ratio,
            'species' => $this->species,
            'age' => $this->age,
            'height' => $this->height,
            'diameter' => $this->diameter
        ];
    }

    public function equalsTo(Layer $layer)
    {

    }
}
