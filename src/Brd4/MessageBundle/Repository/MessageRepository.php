<?php

namespace Brd4\MessageBundle\Repository;

use Brd4\UserBundle\Entity\User;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityRepository;
use Knp\Component\Pager\Paginator;

class MessageRepository extends EntityRepository
{
    /** @var Paginator $paginator */
    private $paginator;

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

    /**
     * @param mixed $paginator
     */
    public function setPaginator(Paginator $paginator)
    {
        $this->paginator = $paginator;
    }

    /**
     * @return Paginator
     */
    public function getPaginator()
    {
        return $this->paginator;
    }
}
