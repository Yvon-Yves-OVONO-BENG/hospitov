<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension 
{
    #filtre qui permet de segmenter un nombre en bloc de 3 commençant pas ma froite
    public function getFilters()
    {
        return [
            new TwigFilter('number_format', [$this, 'numberFormat']),
            new TwigFilter('age', [$this, 'calculeAge']),
            new TwigFilter('mois_en_lettres', [$this, 'moisEnlettres']),
        ];
    }

    public function numberFormat($number)
    {
        #j'utilise la fonction
        return number_format($number, 0, '', ' ');
    }

    #filtre qui permet de calculer l'âge
    public function calculeAge(\DateTimeInterface $dateNaissance): int
    {
        $aujourdhui = new \DateTime();
        $age = $aujourdhui->diff($dateNaissance)->y;

        return $age;
    }

    public function moisEnlettres($mois): string
    {
        $noms = [
            1 => 'Janvier',
            2 => 'Février',
            3 => 'Mars',
            4 => 'Avril',
            5 => 'Mai',
            6 => 'Juin',
            7 => 'Juillet',
            8 => 'Août',
            9 => 'Septembre',
            10 => 'Octobre',
            11 => 'Novembre',
            12 => 'Décembre',
        ];

        return $noms[(int)$mois] ?? 'mois inconnu';
    }


}