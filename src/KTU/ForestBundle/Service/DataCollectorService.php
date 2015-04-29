<?php

namespace KTU\ForestBundle\Service;

use ONGR\ElasticsearchBundle\DSL\Aggregation\FilterAggregation;
use ONGR\ElasticsearchBundle\DSL\Aggregation\NestedAggregation;
use ONGR\ElasticsearchBundle\DSL\Aggregation\StatsAggregation;
use ONGR\ElasticsearchBundle\DSL\Aggregation\TermsAggregation;
use ONGR\ElasticsearchBundle\DSL\Filter\TermFilter;
use ONGR\ElasticsearchBundle\DSL\Query\FilteredQuery;
use ONGR\ElasticsearchBundle\DSL\Query\NestedQuery;
use ONGR\ElasticsearchBundle\DSL\Query\TermQuery;
use ONGR\ElasticsearchBundle\ORM\Manager;
use ONGR\ElasticsearchBundle\Result\Aggregation\AggregationIterator;
use ONGR\ElasticsearchBundle\Result\Aggregation\ValueAggregation;

class DataCollectorService
{

    /** @var Manager */
    private $manager;

    /** @var  array */
    private $provinces = [
        'Kauno apskritis',
        'Alytaus apskritis',
        'Klaipėdos apskritis',
        'Marijampolės apskritis',
        'Panevėžio apskritis',
        'Šiaulių apskritis',
        'Tauragės apskritis',
        'Telšių apskritis',
        'Utenos apskritis',
        'Vilniaus apskritis',
    ];

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
        $average = 0;
        $manager = $this->manager;
        $repository = $manager->getRepository('KTUForestBundle:Lot');
        $search = $repository->createSearch();

        $query = new TermQuery('province', $province);
        $search->addQuery($query);

        $speciesQuery = new TermQuery('layers.species', $treeType);

        $species = new NestedQuery('layers', $speciesQuery);
        $search->addQuery($species);

        $termFilter = new TermFilter('layers.species', $treeType);
        $stats = new NestedAggregation('ratio_stats');
        $stats->setPath('layers');

        $filter = new FilterAggregation('filter');
        $filter->setFilter($termFilter);

        $statsAggs = new StatsAggregation('stats');
        $statsAggs->setField('layers.ratio');

        $filter->addAggregation($statsAggs);
        $stats->addAggregation($filter);

//        $stats->addAggregation()

        $search->addAggregation($stats);


        $documents = $repository->execute($search);

        //TODO: account for regions without the tree type


        print_r($documents->getAggregations());

        $aggs = $documents->getAggregations();
        $layerCount = $this->getLayerCount($province);

        if ($layerCount != 0) {
            foreach ($aggs as $agg) {
                /** @var ValueAggregation $agg */
                $stats = $agg->getValue();

                $average = round($stats['sum'], 4) * $layerCount / 100;
            }

        }

        return $average;
    }

    public function collectProvinceData($province)
    {
        $manager = $this->manager;
        $repository = $manager->getRepository('KTUForestBundle:Lot');
        $search = $repository->createSearch();

        $query = new TermQuery('province', $province);
        $search->addQuery($query);

        $aggsDep = new TermsAggregation('department');
        $aggsDep->setField('department');

        $search->addAggregation($aggsDep);

        $aggsForestry = new TermsAggregation('forestry');
        $aggsForestry->setField('forestry');

        $search->addAggregation($aggsForestry);

        $documents = $repository->execute($search);
        $aggs = $documents->getAggregations();

        return ['code' => 'LT-UT', 'some_field' => 'some_data'];
//        var_dump($documents->getAggregations()->find('forestry'));
    }

    public function getProvincesRatios($treeType)
    {
        $provincesRatios = [];
        $provinces = $this->provinces;

        foreach ($provinces as $provinceName) {
            /** @var ValueAggregation $province */
            $ratio = $this->calculateRatio($provinceName, $treeType);

            $ratioNormalised = $ratio === null ? 0 : $ratio;
            $provincesRatios[$provinceName] = $ratioNormalised;
        }
        return $provincesRatios;
    }

    public function getLayerCount($province)
    {
        $manager = $this->manager;
        $repository = $manager->getRepository('KTUForestBundle:Lot');
        $search = $repository->createSearch();

        $query = new TermQuery('province', $province);
        $search->addQuery($query);

        $stats = new StatsAggregation('ratio_stats');
        $stats->setField('layers.ratio');

        $search->addAggregation($stats);

        $documents = $repository->execute($search);

        var_dump($documents->getAggregations());

        $aggs = $documents->getAggregations();

        $count = 0;

        foreach ($aggs as $agg) {
            /** @var ValueAggregation $agg */
            $stats = $agg->getValue();

            $count = $stats['sum'];
        }

        return $count;
    }
}
