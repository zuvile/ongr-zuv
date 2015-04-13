<?php

namespace KTU\ForestBundle;

use KTU\ForestBundle\DependencyInjection\KTUForestExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class ForestBundle extends Bundle
{
    public function getContainerExtension()
    {
        return new KTUForestExtension();
    }
}
