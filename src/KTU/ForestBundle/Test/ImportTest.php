<?php

namespace KTU\ForestBundle\Test;

use Elasticsearch\Common\Exceptions\Missing404Exception;
use KTU\ForestBundle\Document\Layer;
use KTU\ForestBundle\Document\Lot;
use KTU\ForestBundle\Service\ImportService;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ImportTest extends WebTestCase {

    protected function getFixture($fixture)
    {
        $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1);

        return realpath(dirname($backtrace[0]['file'])) . '/fixture/' . $fixture;
    }

    public function getTestData()
    {
        $out = [];

        $expected = new Lot();

        $expected->setId('398560');
        $expected->setProvince('Kauno apskritis');
        $expected->setLushness(0.90);
        $expected->setForestry('Girionių m-ja');
        $expected->setDepartment('Dubravos eksperimentinė mokomoji miškų urėdija');
        $expected->setMunicipality('Kauno r. sav.');
        $expected->setTerritory(2.200000);
        $expected->setFrom('1989-01-01 00:00:00.000');
        $layer = new Layer();
        $layer->setSpecies('Eglė');
        $layer->setRatio(1.0);
        $layer->setAge(30);
        $layer->setHeight(10.00);
        $layer->setDiameter(10.0);
        $layer->setLayer('Medyno 1 ardas');
        $expected->addLayer($layer);

        $out[] = [$expected, 'data.xml'];

        $existingLot = new Lot();

        $existingLot->setId('398560');
        $existingLot->setProvince('Kauno apskritis');
        $existingLot->setLushness(0.90);
        $existingLot->setForestry('Girionių m-ja');
        $existingLot->setDepartment('Dubravos eksperimentinė mokomoji miškų urėdija');
        $existingLot->setMunicipality('Kauno r. sav.');
        $existingLot->setTerritory(2.200000);
        $existingLot->setFrom('1989-01-01 00:00:00.000');
        $layer = new Layer();
        $layer2 = new Layer();
        $layer->setSpecies('Eglė');
        $layer->setRatio(1.0);
        $layer->setAge(30);
        $layer->setHeight(10.00);
        $layer->setDiameter(10.0);
        $layer->setLayer('Medyno 1 ardas');
        $existingLot->addLayer($layer);
        $existingLot->addLayer($layer2);
        $out[] = [$expected, 'data.xml', $existingLot];

        return $out;
    }

    /**
     * @dataProvider getTestData()
     */
    public function testImport($expected, $fixture, $existingLot = null)
    {
        $importService = new ImportService($this->getManagerMock($expected, $existingLot));
        $importService->setFile($this->getFixture($fixture));
        $importService->setProvinceMapFile($this->getFixture('provinces.yml'));
        $progressBarMock = $this->getMock('Symfony\Component\Console\Helper\ProgressBar', [], [], '', false);
        $importService->setProgress($progressBarMock);
        $outputMock = $this->getMockForAbstractClass('Symfony\Component\Console\Output\OutputInterface')->expects($this->any())->method('advance');
        $importService->setOutput($outputMock);
        $importService->execute();
    }

    public function getManagerMock($lot, $existingLot = null)
    {
        $mock = $this->getMockBuilder('ONGR\ElasticsearchBundle\ORM\Manager')->disableOriginalConstructor()->getMock();
        $repositoryMock = $this->getMockBuilder('ONGR\ElasticsearchBundle\ORM\Repository')->disableOriginalConstructor()->getMock();
        $mock->expects($this->any())->method('persist')->with($lot);
        $mock->expects($this->any())->method('getRepository')->willReturn($repositoryMock);
        $mock->expects($this->any())->method(('advance'));

        if ($existingLot) {
            $repositoryMock->expects($this->any())->method('find')->willReturn($existingLot);
        } else {
            $repositoryMock->expects($this->any())->method('find')->willThrowException(new Missing404Exception());
        }

        return $mock;
    }
}
