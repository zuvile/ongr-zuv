<?php

namespace KTU\ForestBundle\Service;

use ONGR\ElasticsearchBundle\DSL\Aggregation\GlobalAggregation;
use ONGR\ElasticsearchBundle\DSL\Aggregation\NestedAggregation;
use ONGR\ElasticsearchBundle\DSL\Aggregation\StatsAggregation;
use ONGR\ElasticsearchBundle\DSL\Aggregation\TermsAggregation;
use ONGR\ElasticsearchBundle\DSL\Query\Query;
use ONGR\ElasticsearchBundle\DSL\Query\TermQuery;
use ONGR\ElasticsearchBundle\ORM\Manager;
use ONGR\ElasticsearchBundle\ORM\Repository;
use ONGR\ElasticsearchBundle\Result\Aggregation\ValueAggregation;

class DataCollectorService
{

    /** @var Manager */
    private $manager;

    public function __construct(Manager $manager)
    {
        $this->manager = $manager;
    }

    public function collectMunicipalities()
    {
        $manager = $this->manager;
        $repository = $manager->getRepository('KTUForestBundle:Lot');
        $search = $repository->createSearch();
        $aggs = new TermsAggregation('municipality');
        $aggs->setField('municipality');
        $search->addAggregation($aggs);

        $documents = $repository->execute($search, Repository::RESULTS_ARRAY);

        return $documents->getAggregations()->find('municipality');
    }

    public function calculateRatio($municipality, $treeType)
    {
        $manager = $this->manager;
        $repository = $manager->getRepository('KTUForestBundle:Lot');
        $search = $repository->createSearch();

        $query = new TermQuery('municipality', $municipality);
        $search->addQuery($query);

        $species = new TermQuery('layers.species', $treeType);
        $search->addQuery($species);

        $stats = new StatsAggregation('ratio_stats');
        $stats->setField('layers.ratio');

        $search->addAggregation($stats);

        $documents = $repository->execute($search, Repository::RESULTS_ARRAY);

        return $documents->getAggregations();

    }
    public function collectMunicipalityData($municipality)
    {
        $manager = $this->manager;
        $repository = $manager->getRepository('KTUForestBundle:Lot');
        $search = $repository->createSearch();

        $query = new TermQuery('municipality', $municipality);
        $search->addQuery($query);

        $aggsDep = new TermsAggregation('department');
        $aggsDep->setField('department');

        $search->addAggregation($aggsDep);

        $aggsForestry = new TermsAggregation('forestry');
        $aggsForestry->setField('forestry');

        $search->addAggregation($aggsForestry);

        $documents = $repository->execute($search, Repository::RESULTS_ARRAY);

        return $documents->getAggregations()->find('forestry');
    }
}
