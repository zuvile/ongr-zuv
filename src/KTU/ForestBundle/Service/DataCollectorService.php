<?php

namespace KTU\ForestBundle\Service;

use ONGR\ElasticsearchBundle\DSL\Aggregation\StatsAggregation;
use ONGR\ElasticsearchBundle\DSL\Aggregation\TermsAggregation;
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

        $species = new TermQuery('layers.species', $treeType);
        $search->addQuery($species);

        $stats = new StatsAggregation('ratio_stats');
        $stats->setField('layers.ratio');

        $search->addAggregation($stats);

        $documents = $repository->execute($search);

        //TODO: account for regions without the tree type


        $aggs = $documents->getAggregations();

        if ($this->getLayerCount($province) != 0) {
            foreach ($aggs as $agg) {
                /** @var ValueAggregation $agg */
                $stats = $agg->getValue();

                $average = round($stats['avg'], 4);
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

        //TODO: find layer count

        $query = new TermQuery('province', $province);
        $search->addQuery($query);
//
//        $stats = new NestedAggregation('layer_stats');
//        $stats->setField('layers');

//        $search->addAggregation($stats);

        $documents = $repository->execute($search);
//
//        var_dump($documents->getAggregations());

        return $documents->count();
    }
}
