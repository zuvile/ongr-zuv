<?php

namespace KTU\ForestBundle\Service;

use KTU\ForestBundle\Document\Lot;
use KTU\ForestBundle\Model\Layer;
use Symfony\Component\Console\Output\OutputInterface;

class ImportService
{
    /** @var */
    private $manager;

    /**
     * @param mixed $manager
     */
    public function setManager($manager)
    {
        $this->manager = $manager;
    }

    /** @var  string */
    private $file;

    /** @var  OutputInterface */
    private $output;

    /**
     * @param string $file
     */
    public function setFile($file)
    {
        $this->file = $file;
    }

    /**
     * @param OutputInterface $output
     */
    public function setOutput($output)
    {
        $this->output = $output;
    }

    public function execute()
    {
        $xml = simplexml_load_file($this->file);

        foreach ($xml->row as $row) {
            $lot = $this->loadLot($row);
            $this->writeToDB($lot);
        }
    }

    private function loadLot($row)
    {
        $lot = new Lot();
        $lot->setId((string)$row->id);
        $lot->setMunicipality((string)$row->savivaldybe);
        $lot->setLushness((float)$row->skalsumas);
        $lot->setDepartment((string)$row->uredija);
        $lot->setForestry((string)$row->girininkija);
        $lot->setTerritory((float)$row->plotas);
        $lot->setFrom((string)$row->nuo);

        $layer = $this->formLayer($row);

        $lot->getLayers()->addLayer($layer);

        return $lot;

    }

    private function writeToDB($lot)
    {
        $this->manager->persist($lot);
        $this->manager->commit($lot);
    }

    private function formLayer($row)
    {
        $layer = new Layer();
        $layer->setLayer((string)$row->ardas);
        $layer->setRatio((float)$row->sudeties_koeficientas);
        $layer->setSpecies((string)$row->medzio_rusis);
        $layer->setAge((int)$row->amzius);
        $layer->setHeight((float)$row->aukstis);
        $layer->setDiameter((float)$row->diametras);

        return $layer;
    }
}
