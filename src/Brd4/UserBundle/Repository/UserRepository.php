<?php

namespace Brd4\UserBundle\Repository;

use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
{
    public function findAllUsers($itemCount = 10)
    {
        // TODO: paginate
//        $paginator  = $this->get('knp_paginator');
//        $pagination = $paginator->paginate(
//            $query, /* query NOT result */
//            $request->query->getInt('page', 1)/*page number*/,
//            10/*limit per page*/
//        );

        return $this->findAll();
    }

    public function findAllFollowers()
    {
//        $query = $this->createQueryBuilder('u')
//            ->select('u')
//            ->orderBy('u.username', 'ASC')
//            ->leftJoin('f.user', 'u')
//            ->getQuery()
//        ;
//
//        return $query->getResult();
    }
}
