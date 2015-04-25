<?php

namespace KTU\ForestBundle\Service;

use ONGR\ElasticsearchBundle\DSL\Aggregation\GlobalAggregation;
use ONGR\ElasticsearchBundle\DSL\Aggregation\NestedAggregation;
use ONGR\ElasticsearchBundle\DSL\Aggregation\StatsAggregation;
use ONGR\ElasticsearchBundle\DSL\Aggregation\TermsAggregation;
use ONGR\ElasticsearchBundle\DSL\Query\Query;
use ONGR\ElasticsearchBundle\DSL\Query\TermQuery;
use ONGR\ElasticsearchBundle\ORM\Manager;
use ONGR\ElasticsearchBundle\Result\Aggregation\ValueAggregation;

class DataCollectorService
{

    /** @var Manager */
    private $manager;

    public function __construct(Manager $manager)
    {
        $this->manager = $manager;
    }

    public function collectProvinces()
    {
        $manager = $this->manager;
        $repository = $manager->getRepository('KTUForestBundle:Lot');
        $search = $repository->createSearch();
        $aggs = new TermsAggregation('province');
        $aggs->setField('province');
        $search->addAggregation($aggs);

        $documents = $repository->execute($search);

        return $documents->getAggregations()->find('province');
    }

    public function calculateRatio($province, $treeType)
    {
        $manager = $this->manager;
        $repository = $manager->getRepository('KTUForestBundle:Lot');
        $search = $repository->createSearch();

        $query = new TermQuery('province', $province);
        $search->addQuery($query);

        $species = new TermQuery('layers.species', $treeType);
        $search->addQuery($species);

        $stats = new StatsAggregation('ratio_stats');
        $stats->setField('layers.ratio');

        $search->addAggregation($stats);
        $documents = $repository->execute($search);

        var_dump($documents->getAggregations());

        $aggs = $documents->getAggregations();

        return $aggs;

    }
    public function collectProvinceData($province)
    {
        $manager = $this->manager;
        $repository = $manager->getRepository('KTUForestBundle:Lot');
        $search = $repository->createSearch();

        $query = new TermQuery('municipality', $province);
        $search->addQuery($query);

        $aggsDep = new TermsAggregation('department');
        $aggsDep->setField('department');

        $search->addAggregation($aggsDep);

        $aggsForestry = new TermsAggregation('forestry');
        $aggsForestry->setField('forestry');

        $search->addAggregation($aggsForestry);

        $documents = $repository->execute($search);

//        var_dump($documents->getAggregations()->find('forestry'));
    }

    public function getProvincesRatios($treeType)
    {
        $provincesRatios = [];
        $provinces = $this->collectProvinces();

        foreach ($provinces as $province) {
            /** @var ValueAggregation $province */
            $provinceName = $province->getValue();
            $ratio = $this->calculateRatio($provinceName['key'], $treeType);

            $provincesRatios = ['provinceName' => $provinceName,
                'provinceRatio' => $ratio];
        }
        return $provincesRatios;
    }
}
