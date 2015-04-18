<?php

namespace KTU\ForestBundle\Controller;

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
        return $this->render(
            'KTUForestBundle::index.html.twig',
            []
        );
    }

}