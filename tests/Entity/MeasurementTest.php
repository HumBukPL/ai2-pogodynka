<?php

namespace App\Tests\Entity;

use PHPUnit\Framework\TestCase;
use App\Entity\Measurement;

class MeasurementTest extends TestCase
{
    public function dataGetFahrenheit(): array
    {
        return [
            ['0', 32],
            ['-100', -148],
            ['100', 212],
            ['-17.5', 0.5],
            ['-10', 14],
            ['25', 77],
            ['50', 122],
            ['-50', -58],
            ['75.4', 167.72],
            ['100.1', 212.18],

        
    ];
}
    /**
     * @dataProvider dataGetFahrenheit
     */
    public function testGetFahrenheit($celsius, $expectedFahrenheit): void
    {
        $measurement = new Measurement();
        $measurement->setTemperature($celsius);
        $this->assertEquals($expectedFahrenheit,
         $measurement->getFahrenheit(), 
         "Expected $expectedFahrenheit Fahrenheit for $celsius Celsius, got {$measurement->getFahrenheit()}");
    }
}
