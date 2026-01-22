<?php

namespace App\Services\UnitConversion;

use PhpUnitsOfMeasure\PhysicalQuantity\Mass;
use PhpUnitsOfMeasure\PhysicalQuantity\Volume;
use InvalidArgumentException;

class UnitConverter
{
    public function toBase(
        float $value,
        string $symbol,
        string $unitType
    ): float {
        return match ($unitType) {
            'mass'   => $this->massToGrams($value, $symbol),
            'volume' => $this->volumeToMillilitres($value, $symbol),
            'count'  => $this->countToPieces($value, $symbol),
            default  => throw new InvalidArgumentException('Unsupported unit type'),
        };
    }

    protected function massToGrams(float $value, string $symbol): float
    {
        $mass = new Mass($value, $symbol);
        return $mass->toUnit('g');
    }

    protected function volumeToMillilitres(float $value, string $symbol): float
    {
        if (isset(CulinaryMap::VOLUME[$symbol])) {
            return $value * CulinaryMap::VOLUME[$symbol];
        }

        $volume = new Volume($value, $symbol);
        return $volume->toUnit('ml');
    }

    protected function countToPieces(float $value, string $symbol): float
    {
        if (! isset(CountMap::COUNT[$symbol])) {
            throw new InvalidArgumentException("Unsupported count unit: {$symbol}");
        }

        return $value * CountMap::COUNT[$symbol];
    }
}
