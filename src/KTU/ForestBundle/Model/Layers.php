<?php

namespace KTU\ForestBundle\Model;


class Layers implements \JsonSerializable
{
    private $layers = [];

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $layers = [];
        foreach ($this->layers as $layer) {
            /** @var Layer $layer */
            $layers[] = $layer->jsonSerialize();
        }
        return $layers;
    }

    /**
     * @param Layer $layer
     */
    public function addLayer(Layer $layer)
    {
        $this->layers[] = $layer;
    }
}