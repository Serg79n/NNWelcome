<?php

namespace NNWelcome\LocationBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

class CityRepository extends EntityRepository
{
    public function retriveCity($request){
        $query = $this->_em->createQueryBuilder()
            ->select("c")
            ->from("LocationBundle:City", "c")
            ->orderBy('c.title', 'asc');

        return $query;
    }
}
