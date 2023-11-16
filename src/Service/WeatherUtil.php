<?php
declare(strict_types=1);

namespace App\Service;

use App\Entity\Location;
use App\Entity\Measurement;
use App\Repository\LocationRepository;
use App\Repository\MeasurementRepository;

class WeatherUtil
{
    public function __construct(
        private readonly LocationRepository $locationRepository,
        private readonly MeasurementRepository $measurementRepository,
    )
    {
    }

    /**
     * @return Measurement[]
     */
    public function getWeatherForLocation(Location $location): array
    {
        $measurements = $this->measurementRepository->findByLocation($location);
        return $measurements;
    }

    /**
     * @return Measurement[]
     */
    public function getWeatherForCountryAndCity(string $country, string $city): array
    {
        $location = $this->locationRepository->findOneBy([
            'country' => $country,
            'city' => $city,
        ]);

        $measurements = $this->getWeatherForLocation($location);

        return $measurements;
    }
}