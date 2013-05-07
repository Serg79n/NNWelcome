<?php

namespace NNWelcome\NticBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

class PriceRepository extends EntityRepository
{
    public function retrivePrice($request){
         $query = $this->_em->createQueryBuilder()
            ->select("c")
            ->from("NticBundle:Price", "c")
            ->getQuery()->execute();

        return $query;
     }
}
