<?php

namespace App\Services\UnitConversion;

class CulinaryMap
{
    public const VOLUME = [
        // small units
        'tsp'   => 5,
        'tbsp'  => 15,
        'dsp'   => 10,
        'cup'   => 240,
        'fl_oz' => 28.413,

        // large units
        'pt'    => 568,
        'qt'    => 1137,
        'gal'   => 4546,

        // litres
        'l'     => 1000,
        'liter' => 1000,
        'litre' => 1000,
    ];
}
