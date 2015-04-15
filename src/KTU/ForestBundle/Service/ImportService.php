<?php

namespace KTU\ForestBundle\Service;

use Symfony\Component\Console\Output\OutputInterface;

class ImportService
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
        $this->file;
    }
}
