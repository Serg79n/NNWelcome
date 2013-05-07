<?php

namespace NNWelcome\NticBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

class HotelRepository extends EntityRepository
{
    public function retriveHotel($request){
         $query = $this->_em->createQueryBuilder()
            ->select("c")
            ->from("NticBundle:Hotel", "c");

        return $query;
     }
}

