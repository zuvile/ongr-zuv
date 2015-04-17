<?php

namespace KTU\ForestBundle;

use KTU\ForestBundle\DependencyInjection\KTUForestExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class KTUForestBundle extends Bundle
{
    public function getContainerExtension()
    {
        return new KTUForestExtension();
    }
}
