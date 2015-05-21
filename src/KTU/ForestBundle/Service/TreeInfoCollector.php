<?php

namespace KTU\ForestBundle\Service;

class TreeInfoCollector
{
    private $images = [
        'Eglė' => '/images/egle.jpg',
        'Pušis bankso' => '/images/bankso_pusis.jpg',
        'Pušis kalninė' => '/images/kalnine_pusis.jpg',
        'Pušis juodoji' => '/images/juodoji_pusis.jpg',
        'Pocūgė' => '/images/pocuge.jpg',
        'Kėnis' => '/images/kenis.jpg',
        'Maumedis' => '/images/maumedis.jpg',
        'Ąžuolas' => '/images/azuolas.jpg',
        'Ąžuolas raudonasis' => '/images/raudonasis_azuolas.jpg',
        'Uosis' => '/images/uosis.jpg',
        'Klevas' => '/images/klevas.jpg',
        'Skroblas' => '/images/skroblas.jpg',
        'Guoba' => '/images/guoba.jpg',
        'Skirpstas' => '/images/skirpstas.jpg',
        'Baltalksnis' => '/images/baltalksnis.jpg',
        'Juodalksnis' => '/images/juodalksnis.jpg',
        'Drebulė' => '/images/drebule.jpg',
        'Tuopa' => '/images/tuopa.jpg',
        'Blindė' => '/images/blinde.jpg',
        'Gluosnis' => '/images/gluosnis.jpg',
        'Kedras' => '/images/kedras.jpg',
        'Vinkšna' => '/images/vinksna.jpg',
        'Bukas' => '/images/bukas.jpg',
    ];
    private $descriptions = [
        'Eglė' => 'Eglės yra visžaliai ir vienkamieniai medžiai. Eglių laja kūgiška, smailiaviršūnė. Krūmo pavidalo kamienas susidaro tik ypatingomis augimo sąlygomis. Išauga iki 20-60 m aukščio, atskirais atvejais dar aukštesnės, ypač tai būdinga sitkinėms eglėms, kurios vienas individas išmatuotas esantis 96,7 m aukščio.',
        'Pušis bankso' => 'Spygliai šiek tiek susisukę. Auga gana greitai, ūgliai driekiasi pažeme ir sudaro kilimą. Atsparus sausroms, visiškai nereiklus dirvožemiui, mėgsta saulėtas vietas.bank',
        'Pušis kalninė' => 'Kalninės pušys (Pinus mugo) – Lietuvos pajūrio, miškuose ir net kopose augantčios pušys. Tai augalas, kuris gali augti nederlingame, sausame dirvožemyje, net ir pustomame smėlyje. ',
        'Pušis juodoji' => 'Tai 20-40 metrų aukščio medis, natūraliose buveinėse užaugantis net iki 50 metrų aukščio. Paplitusi Vidurio bei Pietų Europoje ir Mažojoje Azijoje. Laja ovali ar skėtiška. Spygliai tamsiai žali, ilgi, 8-14 cm ilgio, tankiai apaugę šakelę.',
        'Pocūgė' => 'Visada žaliuojantieji dideli medžiai. Savo tėvynėje užauga iki 80—115 m aukščio. Laja netaisyklingo menturinio šakojimosi. Spygliai ties pagrindu susiaurėję į trumpą kotelį, kuriuo priaugę prie šakučių. Kankorėžiai cilindriški arba kiaušiniški, trumpakočiai, nusvirę; dengiamieji žvyneliai iš kan­korėžio išsikišę; prinoksta žydėjimo metais rudenį.',
        'Kėnis' => 'Pušinių (Pinaceae) šeimos, visada žaliuojantieji aukšti medžiai, tiesiu liemeniu, tankia, neaiškiai menturiškai išsišakojusia piramidiška arba cilindriška, kai kurių rūšių viršūnėje nusmailėjusia laja.',
        'Maumedis' => 'Tai vidutinio stambumo ar stambūs, netaisyklingo šakojimosi, su retoka laja medžiai. Spygliai minkšti, švelnūs, rudenį nukrinta. Maumedžiai - vienanamiai medžiai. Vyriškieji kankorėžiai geltoni, sukrauti trumpuosiuose ūgliuose, pamate apsupti pumpurų žvynų. Moteriškieji kankorėžiai rausvi arba žalsvi, sukrauti trumpuosiuose ūgliuose, pamate apsupti spyglių. ',
        'Ąžuolas' => 'Ąžuolas yra lapuotis medis, kuris auga lėtai, bet užauga aukštas, stiprus, galingas ir didingas. Šis medis užauga iki 30-50 metrų aukščio. Jo kamienas yra storas (kamieno skersmuo siekia 1,5 -2 metrai), tiesus, pilkšvai rudas ir nuo jo atsišakoja daug plačių šakų. ',
        'Ąžuolas raudonasis' => 'Raudonasis ąžuolas nuo paprastojo mažai kuo skiriasi. Panašaus medienos kietumo, lajos, lapų iškarpymo, tik rudenį šie nusidažo ryškesniu raudoniu.',
        'Uosis' => 'Tai vidutinio dydžio, labai greitai augantis medis, užaugantis iki 20-35 m aukščio, kartais iki 48 m. Kamieno skersmuo iki 2 m, kartais pasitaiko iki 3,5 m skersmens.',
        'Klevas' => 'Klevai – medžiai arba krūmai nemažais priešiniais lapais, smulkiais, neryškiais žiedais, skeltiniais sparnavaisiais. Tarp įvairių rūšių skirtumų gali būti daugiau, nei panašumų. Jos skiriasi dydžiu, lapų forma, o kartais tik iš sparnavaisių, kurie visų klevų gana panašūs, ir teatpažinsi, koks tai augalas.',
        'Skroblas' => 'Skroblo vardas daugeliui girdėtas, bet medis mažai kam matytas. O Lietuvoje yra net nemaži skroblynai. Tačiau medis išplitęs ne visur. Jo paplitimo riba – Pietų ir Vakarų Lietuva. Tokių augalų, turinčių arealų paribius pas mus yra ir daugiau, tačiau medžių – tik vienas kitas. Mediena šviesi, tvirta, vertinga.',
        'Guoba' => 'Aukštis iki 30 m, laja tanki, plačiai rutuliška. Lapai pražanginiai, pjūkliškais kraštais. Žiedai pavieniai kuokštuose. Vaisius – riešutėlis. Auga greitai, išgyvena iki 300 metų.',
        'Skirpstas' => 'Natūraliai paplitęs Lietuvoje, skirspstas gana apyretis medis. Dažniausiai sutinkamas miškuose, šlaituose ar upių pakrantėse, taip pat plačiai auginamas parkuose ir soduose kaip dekoratyinis medis. Užauga 20-30 metrų aukščio, kamieno skersmuo siekia iki 1,5 metro. Laja ovali, lapai  taip pat ovalūs, 4-10 cm ilgio, pjūklišku kraštu ir ryškiomis gyslomis, sodriai žalios spalvos. Rudenį gelsta ar rausta ir atrodo itin puošniai. Žiedai rudi ar rausvi, smulkūs, pasirodo prieš lapams skeidžiantis. ',
        'Baltalksnis' => '
Tai natūraliai Lietuvoje auganti rūšis, dažniausiai sutinkama mišriuose miškuose. Gerai auga drėgnose vietose, tačiau sutinkama ir sausesnėse augimvietėse. Priešingai nei juodalksnis, negali augti nuolat užmirkusiuose dirvose. Baltalksnis gali užaugti iki 20  metrų, o kartais susiformuoja kaip daugiakamienis, itin stambus krūmas. Kamieno skersmuo siekia iki 40 cm.',
        'Juodalksnis' => 'Juodalksnis paplitęs visoje Europoje, auga Skandinavijoje, Vakarų Sibire, Šiaurės Afrikoje ir Mažojoje Azijoje. Užauga iki 35 metrų aukščio. Gyvena apie 200 metų. ',
        'Drebulė' => 'Drebulė - tai 10-25 m. aukščio su nedidele laja medis. Drebulės žievė iš pradžių lygi, pilka arba pilkai žalia, vėliau suaižėja, pajuosta. Pumpurai pražanginiai, apie 1 cm. ilgio, lipnūs, pliki arba plaukuoti, labai nusmailėję, kvapūs.',
        'Tuopa' => 'Stambūs medžiai su stora ir giliai suaižėjusia žieve. Žydi lapams skleidžiantis. Žiedai sukrauti žirginiuose ir apdulkinami vėjo.',
        'Blindė' => 'Dvinamis augalas auga kaip neaukštas medis, rečiau kaip krūmas iki 2-12 m, retai iki 22 m aukščio. Laja plačiai kiaušiniška arba skėtiška. Žievė pilkai žalia, lygi, apatinėje liemens dalyje suaižėjusi. Pumpurus dengia tik vienas žvynelis. ',
        'Gluosnis' => 'Tai sparčiai augantis lapuotis medis, užaugantis iki 20-25 m aukščio, o kamieno skersmuo siekia 1 m. Jaunos šakelės svyrančios, gelsvos. Didelės šakos taip pat svyrančios, rudos, pilkos spalvos.',
        'Kedras' => 'Tai visžalis, spygliuotis medis, kurio aukštis siekia 40–50 m (kartais iki 60 m), kamieno skersmuo iki 3 m. Medžio laja kūgiška, su horizontaliomis šakomis bei žemyn nusvirusiomis šakelėmis. Spygliai 2,5–5 cm ilgio, apie 1 mm storio, susitelkę po 20–30 ant šakelės.',
        'Vinkšna' => 'Medis užauga iki 35 m aukščio. Laja tanki, plačiai elipsiška. Žiedai rausvi, smulkūs, dvilyčiai ant trumpo kotelio. Mediena prastos kokybės, nors tinka stalių gaminiams, apdailai, papuošalams. Nuo seno sodinama miestų ir kaimų apželdinimui.',
        'Bukas' => 'Miškuose augančių paprastųjų bukų laja siaura, pakilusi aukštai, atvirose vietose išplatėjusi, kūgio formos. Žievė lygi, plona ir sidabriškai pilka. Ūgliai apvalūs, tamsiai rausvai rudi, pradžioje plaukuoti, vėliau pliki. Pumupurai 15-20 mm ilgio, liauni, šviesiai rudi su daug žvynelių, pražanginiai, verpstiški, nusmailėjusiais aštriais galais, išsidėstę dviem eilutėmis. Paprastasis bukas žydėti pradeda 30-80 metų amžiaus.',
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
