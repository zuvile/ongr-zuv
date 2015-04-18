<?php

namespace KTU\ForestBundle\Service;

use Elasticsearch\Common\Exceptions\Missing404Exception;
use KTU\ForestBundle\Document\Layer;
use KTU\ForestBundle\Document\Lot;
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
            if (!$this->lotExists($row)) {
                $lot = $this->loadLot($row);
                $this->writeToDB($lot);
            } else {
                $this->lotUpdate($row);
            }
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

    private function lotExists($row)
    {
        $exists = false;
        $manager = $this->manager;
        $repository = $manager->getRepository('KTUForestBundle:Lot');
        try {
            $repository->find($row->id);
            $exists = true;
        } catch (Missing404Exception $e) {

        }

        return $exists;
    }

    private function lotUpdate($row) {
        $repository = $this->manager->getRepository('KTUForestBundle:Lot');
        $document = $repository->find($row->id);

        $document->department = "Ziviles";

        $this->manager->persist($document);
        $this->manager->commit();
    }
}
