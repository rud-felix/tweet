<?php

namespace Brd4\MessageBundle\Repository;

use Brd4\UserBundle\Entity\User;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityRepository;

class MessageRepository extends EntityRepository
{
    /**
     * @param User $user
     * @return array
     */
    public function findFollowersMessages(User $user)
    {
        $followers[] = $user->getId();

        /** @var User $follower */
        foreach ($user->getFollowers() as $follower) {
            $followers[] = $follower->getId();
        }

        $followers = array_unique($followers);

        $qb = $this->createQueryBuilder('m');
        $query = $qb->select('u, m')
            ->where('m.user IN (:ids)')
            ->andWhere('m.createdAt >= :from')
            ->join('m.user', 'u')
            ->orderBy('m.createdAt', 'DESC')
            ->setParameter('ids', $followers)
            ->setParameter('from', new \DateTime('-24 hours'))
            ->getQuery()
        ;

        return $query->getResult($hydrationMode = AbstractQuery::HYDRATE_ARRAY);
    }
}
