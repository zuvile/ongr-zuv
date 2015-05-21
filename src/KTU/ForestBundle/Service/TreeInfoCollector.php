<?php

namespace KTU\ForestBundle\Service;

class TreeInfoCollector
{
    private $images = [
        'Eglė' => 'http://www.highcountrychristmastrees.com/Pictures/High_Country_Frasier_Fir_Tree_Home_lrg2.jpg'
    ];
    private $descriptions = [
        'Eglė' => 'Eglės yra visžaliai ir vienkamieniai medžiai. Eglių laja kūgiška, smailiaviršūnė. Krūmo pavidalo kamienas susidaro tik ypatingomis augimo sąlygomis.

Išauga iki 20-60 m aukščio, atskirais atvejais dar aukštesnės, ypač tai būdinga sitkinėms eglėms, kurios vienas individas išmatuotas esantis 96,7 m aukščio.'
    ];

    public function getTreeInfo($treeType)
    {
        $out = ['image' => $this->loadImage($treeType),
        'description' => $this->loadDescription($treeType)];

        return $out;
    }

    private function loadImage($treeType)
    {
        $image = $this->images[$treeType];

        return $image;
    }

    private function loadDescription($treeType)
    {
        $description = $this->descriptions[$treeType];;

        return $description;
    }
}
