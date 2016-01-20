<?php

namespace Brd4\UserBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Knp\Component\Pager\Paginator;

class UserRepository extends EntityRepository
{
    /** @var Paginator $paginator */
    private $paginator;

    /**
     * @param $userId
     * @param $page
     * @param $count
     * @return array
     */
    public function findAllUsers($userId, $page, $count)
    {
        $qb = $this->createQueryBuilder('u');
        $query = $qb->add('where',
            $qb->expr()
                ->neq('u.id', $userId)
            )
            ->orderBy('u.username', 'ASC')
            ->getQuery()

        ;

        $userPaginate = $this->getPaginator()
            ->paginate(
                $query,
                $page,
                $count
            )
        ;

        return $userPaginate;
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
