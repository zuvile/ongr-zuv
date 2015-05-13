<?php

namespace KTU\ForestBundle\Service;

use Elasticsearch\Common\Exceptions\Missing404Exception;
use KTU\ForestBundle\Document\Layer;
use KTU\ForestBundle\Document\Lot;
use ONGR\ElasticsearchBundle\ORM\Manager;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Yaml;

class ImportService
{
    /** @var Manager */
    private $manager;

    /** @var string */
    private $file;

    /**
     * @return OutputInterface
     * @codeCoverageIgnore
     */
    public function getOutput()
    {
        return $this->output;
    }

    /**
     * @param OutputInterface $output
     * @codeCoverageIgnore
     */
    public function setOutput($output)
    {
        $this->output = $output;
    }

    /**
     * @var ProgressBar
     */
    private $progress;

    /**
     * @param ProgressBar $progress
     */
    public function setProgress($progress)
    {
        $this->progress = $progress;
    }

    /** @var OutputInterface */
    private $output;

    private $provinceMapFile;

    /**
     * @param mixed $provinceMapFile
     */
    public function setProvinceMapFile($provinceMapFile)
    {
        $this->provinceMapFile = $provinceMapFile;
    }

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


    public function execute()
    {
        $xmlReader = new \XMLReader();
        $xmlReader->open($this->file);

        while ($xmlReader->read() && $xmlReader->name !== 'row') ;

        while ($xmlReader->name === 'row') {
            $row = new \SimpleXMLElement($xmlReader->readOuterXML());

            if (!$this->lotExists($row)) {
                $lot = $this->loadLot($row);
                $this->manager->persist($lot);
                $this->advance($this->output);
            } else {
                $this->lotUpdate($row);
                $this->advance($this->output);
            }

            $this->manager->commit();
            $xmlReader->next('row');
        }

    }

    private function loadLot($row)
    {
        $lot = new Lot();
        $lot->setId((string)$row->id);
        $lot->setProvince($this->getProvinceMap()['provinces'][(string)$row->savivaldybe]);
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
        $layer->setRatio((floatval($row->sudeties_koeficientas)));
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
        $layer = $this->formLayer($row);

        if (!$this->layerExists($document, $layer)) {
            $document->addLayer($layer);

            $this->manager->persist($document);
        }

    }

    private function layerExists(Lot $document, $layer)
    {
        $exists = false;
        $layers = $document->getLayers();

        foreach ($layers as $existingLayer) {
            if ($layer == $existingLayer) {
                $exists = true;
                break;
            }
        }
        return $exists;
    }

    private function getProvinceMap()
    {
        $provinceYml = Yaml::parse(file_get_contents($this->provinceMapFile));
        return $provinceYml;
    }

    /**
     * @param OutputInterface $output
     * @codeCoverageIgnore
     */
    private function advance($output)
    {
        if ($this->progress == null) {
            $this->progress = new ProgressBar($output);
            $output->writeln("");
            $output->writeln("<info>Importing lot data</info>");
            $this->progress->start();
        }
        $this->progress->advance();
    }
}
