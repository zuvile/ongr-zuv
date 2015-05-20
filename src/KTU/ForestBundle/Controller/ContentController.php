<?php

namespace KTU\ForestBundle\Controller;

use KTU\ForestBundle\Service\DataCollectorService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ContentController extends Controller
{
    /**
     * App index page controller.
     *
     * @return Response
     */
    public function homePageAction()
    {
        /** @var DataCollectorService $service */
        $service = $this->get('forest.data.collector');
        $provinceRatios = $service->getProvincesRatios('Eglė');

        $provincesInfo = $service->collectAllProvincesInfo();

        return $this->render(
            'KTUForestBundle::index.html.twig',
            [
                'provinceRatiosJSON' => json_encode($provinceRatios),
                'provincesInfo' => $provincesInfo
            ]
        );
    }

    public function provincesAction()
    {
        /** @var DataCollectorService $service */
        $service = $this->get('forest.data.collector');
        $provinceRatios = $service->getProvincesRatios($this->getRequest()->query->get('tree_type'));
        $provincesInfo = $service->collectAllProvincesInfo();

        return $this->render(
            'KTUForestBundle::provinces.html.twig',
            [
                'provinceRatiosJSON' => json_encode($provinceRatios),
                'provincesInfo' => $provincesInfo
            ]
        );
    }



}
