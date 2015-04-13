<?php

namespace KTU\ForestBundle\Command;

use KTU\ForestBundle\Service\ImportService;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * IndexImportCommand class.
 */
class DataImportCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('forest:data:import')
            ->setDescription('Imports data to Elasticsearch index')
            ->addArgument(
                'filename',
                InputArgument::REQUIRED,
                'Select file to import'
            );
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /* @var ImportService $importService */
        $importService = $this->getContainer()->get('forest.data.import');
        $importService->setFile($input->getArgument('filename'));
        $importService->setOutput($output);
        $importService->execute();
    }
}
