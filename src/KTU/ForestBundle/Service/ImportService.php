<?php

namespace KTU\ForestBundle\Service;

use Elasticsearch\Common\Exceptions\Missing404Exception;
use KTU\ForestBundle\Document\Layer;
use KTU\ForestBundle\Document\Lot;
use ONGR\ElasticsearchBundle\ORM\Manager;
use Symfony\Component\Console\Output\OutputInterface;

class ImportService
{
    /** @var Manager */
    private $manager;

    /** @var string */
    private $file;

    /** @var OutputInterface */
    private $output;

    public function __construct(Manager $manager)
    {
        $this->manager = $manager;
    }
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
                $this->manager->persist($lot);
            } else {
                $this->lotUpdate($row);
            }
        }
        $this->manager->commit();
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

        $lot->addLayer($layer);

        return $lot;
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
        try {
            $this->manager->getRepository('KTUForestBundle:Lot')->find($row->id);
            $exists = true;
        } catch (Missing404Exception $e) {

        }

        return $exists;
    }

    private function lotUpdate($row) 
    {
        $repository = $this->manager->getRepository('KTUForestBundle:Lot');
        /** @var Lot $document */
        $document = $repository->find($row->id);
        $layer = new Layer();
        $document->addLayer($layer);

        $this->manager->persist($document);
    }
}
