<?php

namespace KTU\ForestBundle\Service;

use KTU\ForestBundle\Document\Lot;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Console\Output\OutputInterface;

class ImportService extends Controller
{
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
//        $this->file;
        $lot = new Lot();
        $lot->setAge(5);
        $manager = $this->get("es.manager");
        $manager->persist($lot);
        $manager->commit($lot);
    }
}
