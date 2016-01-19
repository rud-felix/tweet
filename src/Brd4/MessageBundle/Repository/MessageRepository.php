<?php

namespace Brd4\MessageBundle\Repository;

use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityRepository;

class MessageRepository extends EntityRepository
{
    public function findByIDs(array $ids)
    {
        $qb = $this->createQueryBuilder('m');
        $query = $qb->select('u, m')
            ->where('m.user IN (:ids)')
            ->andWhere('m.createdAt >= :from')
            ->join('m.user', 'u')
            ->orderBy('m.createdAt', 'DESC')
            ->setParameter('ids', $ids)
            ->setParameter('from', new \DateTime('-24 hours'))
            ->getQuery()
        ;

        return $query->getResult($hydrationMode = AbstractQuery::HYDRATE_ARRAY);
    }
}
