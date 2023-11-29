<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\WeatherUtil;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Measurement;

class WeatherApiController extends AbstractController
{
    #[Route('/api/v1/weather', name: 'app_weather_api')]
    public function index(   
        WeatherUtil $util,
    #[MapQueryParameter('country')] string $country,
    #[MapQueryParameter('city')] string $city,
    #[MapQueryParameter('format')] string $format = 'json',
    #[MapQueryParameter('twig')] bool $twig = false,
    
    ): Response
    {
        $measurements = $util->getWeatherForCountryAndCity($country, $city);

    if($twig){
        if($format === 'json') {
            return $this->render('weather_api/index.json.twig', [
                'city' => $city,
                'country' => $country,
                'measurements' => $measurements,
            ]);
        }

        if($format === 'csv') {
            return $this->render('weather_api/index.csv.twig', [
                'city' => $city,
                'country' => $country,
                'measurements' => $measurements,
            ]);
        }

    } else {
        if($format === 'json') {
            return $this->json([
                'city' => $city,
                'country' => $country,
                'measurements' => array_map(fn(Measurement $m) => [
                    'date' => $m->getDate(),
                    'temperatureInCelsius' => $m->getTemperature(),
                    'temperatureInFahrenheit' => $m->getFahrenheit(),
                    'humidity' => $m->getHumidity(),
                    ], $measurements),
            ]);
        }

        if($format === 'csv') {
            $csv = "city,country,date,temperatureInCelsius,temperatureInFahrenheit,humidity\n";
            $csv .= implode(
                "\n",
                array_map(fn(Measurement $m) => sprintf(
                    '%s,%s,%s,%s,%s',
                    $city,
                    $country,
                    $m->getDate(),
                    $m->getTemperature(),
                    $m->getFahrenheit(),
                    $m->getHumidity(),
                ), $measurements)
            );

            return new Response($csv, 200, [
            ]);
        }}


        
    }
}
