<?php

namespace NNWelcome\NticBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

class ObjectTypeRepository extends EntityRepository
{
     public function retriveObjectType($request){
         $query = $this->_em->createQueryBuilder()
            ->select("c")
            ->from("NticBundle:ObjectType", "c")
            ->getQuery()->execute();

        return $query;
     }
}
