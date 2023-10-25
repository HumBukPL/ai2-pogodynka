<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Location;
use App\Repository\MeasurementRepository;
use App\Repository\LocationRepository;

class WeatherController extends AbstractController
{
    #[Route('/weather/{city}/{country?}', name: 'app_weather')]
    public function city(string $city, ?string $country,Location $location, MeasurementRepository $repository): Response
    {
    $measurements = $repository->findByCityOrCode($city, $country);
    return $this->render('weather/city.html.twig', [
    'location' => $location,
    'measurements' => $measurements,
    ]);
    }
    
    

}
