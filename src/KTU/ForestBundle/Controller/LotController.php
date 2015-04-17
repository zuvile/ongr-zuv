<?php

namespace KTU\ForestBundle\Controller;

use KTU\ForestBundle\Document\Lot;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class LotController extends Controller
{
    public function lotAction(Lot $lot)
    {
        $manager = $this->get("es.manager");

        $manager->persist($lot);
        $manager->commit($lot);
    }

}