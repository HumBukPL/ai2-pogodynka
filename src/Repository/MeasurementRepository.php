<?php

namespace App\Repository;

use App\Entity\Measurement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Location;

class MeasurementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Measurement::class);
    }

    public function findByLocation(Location $location)
{
    $qb = $this->createQueryBuilder('m');
    $qb->where('m.location = :location')
        ->setParameter('location', $location);


    $query = $qb->getQuery();
    $result = $query->getResult();
    return $result;
}

public function findByCityOrCode(string $city, string $country)
{

    if($country === null)
    {
        $country = 'PL';
    }

    $qb = $this->createQueryBuilder('m');
    $qb->join('m.location', 'l')
        ->where('l.city = :city')
        ->andWhere('l.country = :country')
        ->setParameter('city', $city)
        ->setParameter('country', $country);

    $query = $qb->getQuery();
    $result = $query->getResult();
    return $result;
}
}
