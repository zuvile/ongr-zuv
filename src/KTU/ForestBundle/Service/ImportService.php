<?php

namespace KTU\ForestBundle\Service;

use KTU\ForestBundle\Document\Lot;
use Symfony\Component\Console\Output\OutputInterface;

class ImportService
{
    /** @var   */
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

        foreach ($xml->row as $row)
        {
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
        $lot->setLayer((string)$row->ardas);
        $lot->setDepartment((string)$row->uredija);
        $lot->setDiameter((float)$row->diametras);
        $lot->setForestry((string)$row->girininkija);
        $lot->setHeight((float)$row->aukstis);
        $lot->setAge((int)$row->amzius);
        $lot->setSpecies((string)$row->medzio_rusis);
        $lot->setTerritory((float)$row->plotas);
        $lot->setRatio((float)$row->sudeties_koeficientas);
        $lot->setFrom((string)$row->nuo);
        return $lot;

    }

    private function writeToDB($lot)
    {
        $this->manager->persist($lot);
        $this->manager->commit($lot);
    }
}
