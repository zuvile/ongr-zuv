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
use ONGR\ElasticsearchBundle\DSL\Query\WildcardQuery;
use ONGR\ElasticsearchBundle\ORM\Manager;
use ONGR\ElasticsearchBundle\Result\Aggregation\AggregationIterator;
use ONGR\ElasticsearchBundle\Result\Aggregation\ValueAggregation;
use ONGR\ElasticsearchBundle\Result\DocumentIterator;

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

    /** @var  array */
    private $provinceCodes = [
        'Kauno apskritis' => 'LT-KU',
        'Alytaus apskritis' => 'LT-AL',
        'Klaipėdos apskritis' => 'LT-KL',
        'Marijampolės apskritis' => 'LT-MR',
        'Panevėžio apskritis' => 'LT-PN',
        'Šiaulių apskritis' => 'LT-SA',
        'Tauragės apskritis' => 'LT-TA',
        'Telšių apskritis' => 'LT-TE',
        'Utenos apskritis' => 'LT-UT',
        'Vilniaus apskritis' => 'LT-VL',
    ];

    public function __construct(Manager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @return array
     */
    public function getProvinces()
    {
        return $this->provinces;
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

        $search->addAggregation($stats);

        $documents = $repository->execute($search);

        $aggs = $documents->getAggregations();

        foreach ($aggs as $agg) {
            /** @var $agg ValueAggregation */
            $aggs2 = $agg->getAggregations();
            foreach ($aggs2 as $agg2) {
                /** @var $agg2 ValueAggregation */
                $agg3 = $agg2->getAggregations();
                foreach ($agg3 as $aggFinal) {
                    $final = $aggFinal->getValue()['sum'];
                }
            }
        }
        $layerCount = $this->getLayerCount($province);

        if ($layerCount != 0 && isset($final)) {
            $average = round($final / $layerCount, 4);
        }

        return $average;
    }

    public function collectProvinceData($province)
    {
        $this->collectLotCount($province);
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

        $aggsLushness = new TermsAggregation('lushness');
        $aggsLushness->setField('lushness');

        $search->addAggregation($aggsLushness);

        $documents = $repository->execute($search);

        $department = $this->loadDepartmentData($documents->getAggregations()->find('department'));
        $forestry = $this->loadForestryData($documents->getAggregations()->find('forestry'));
        $territory = $this->loadTerritoryData($province);
        $lushness = $this->loadLushnessData($province);

        $code = $this->provinceCodes[$province];

        return ['code' => $code, 'teritory' => $territory, 'forestries' => $forestry,
            'departments' => $department, 'average_lushness' => $lushness, 'province' => $province,
        'layerCount' => $this->getLayerCount($province), 'lotCount' => $this->collectLotCount($province)];
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
        $value = 0;
        $manager = $this->manager;
        $repository = $manager->getRepository('KTUForestBundle:Lot');
        $search = $repository->createSearch();

        $query = new TermQuery('province', $province);
        $search->addQuery($query);

        $speciesQuery = new WildcardQuery('layers.species', '*');

        $species = new NestedQuery('layers', $speciesQuery);
        $search->addQuery($species);

        $termFilter = new TermFilter('layers.species', '*');
        $stats = new NestedAggregation('ratio_stats');
        $stats->setPath('layers');

        $filter = new FilterAggregation('filter');
        $filter->setFilter($termFilter);

        $statsAggs = new StatsAggregation('stats');
        $statsAggs->setField('layers.ratio');

        $filter->addAggregation($statsAggs);
        $stats->addAggregation($filter);
        $search->addAggregation($stats);
        $documents = $repository->execute($search);
        $aggs = $documents->getAggregations();

        foreach ($aggs as $agg) {
            $value = $agg->getValue()['doc_count'];
        }

        return $value;
    }

    private function loadDepartmentData(AggregationIterator $agg)
    {
        $departmentCount = 0;
        foreach ($agg as $aggregation) {
            $departmentCount++;
        }
        return $departmentCount;
    }

    private function loadForestryData($agg)
    {
        $forestryCount = 0;
        foreach ($agg as $aggregation) {
            $forestryCount++;
        }
        return $forestryCount;

    }

    private function loadTerritoryData($province)
    {
        $territory = null;
        $manager = $this->manager;
        $repository = $manager->getRepository('KTUForestBundle:Lot');
        $search = $repository->createSearch();
        $query = new TermQuery('province', $province);
        $search->addQuery($query);
        $statsAggs = new StatsAggregation('stats');
        $statsAggs->setField('territory');
        $search->addAggregation($statsAggs);
        $documents = $repository->execute($search);

        /** @var AggregationIterator $aggs */
        $aggs = $documents->getAggregations();

        foreach ($aggs as $aggregation) {
            $territory = $aggregation->getValue()['sum'];
        }

        return round($territory, 2);
    }

    private function loadLushnessData($province)
    {
        $lushness = null;
        $manager = $this->manager;
        $repository = $manager->getRepository('KTUForestBundle:Lot');
        $search = $repository->createSearch();
        $query = new TermQuery('province', $province);
        $search->addQuery($query);
        $statsAggs = new StatsAggregation('stats');
        $statsAggs->setField('lushness');
        $search->addAggregation($statsAggs);
        $documents = $repository->execute($search);

        /** @var AggregationIterator $aggs */
        $aggs = $documents->getAggregations();

        foreach ($aggs as $aggregation) {
            $lushness = $aggregation->getValue()['avg'];
        }

        return round($lushness, 2);
    }

    public function collectAllProvincesInfo()
    {
        $provincesInfo = [];
        foreach ($this->provinces as $province) {
            $provinceInfo = $this->collectProvinceData($province);
            $code = $provinceInfo['code'];
            $provincesInfo[$code] = $provinceInfo;
        }

        return $provincesInfo;
    }

    public function collectLotCount($province)
    {
        $manager = $this->manager;
        $repository = $manager->getRepository('KTUForestBundle:Lot');
        $search = $repository->createSearch();

        $query = new TermQuery('province', $province);
        $search->addQuery($query);
        /** @var DocumentIterator $documents */
        $documents = $repository->execute($search);
        return $documents->getTotalCount();
    }

}
