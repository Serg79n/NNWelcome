<?php

namespace NNWelcome\LocationBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

class AreaRepository extends EntityRepository
{
    public function retriveArea($request, $city_id){
        $query = $this->_em->createQueryBuilder()
            ->select("c")
            ->from("LocationBundle:Area", "c")
            ->innerJoin('LocationBundle:City', 'a')
            ->where('a.id = :city_id')
            ->setParameter('city_id', $city_id)
            ->orderBy('c.title', 'asc');

        return $query;
    }
}
