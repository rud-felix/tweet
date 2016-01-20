<?php


namespace Brd4\UserBundle;


use Brd4\UserBundle\Repository\UserRepository;
use Knp\Component\Pager\Paginator;

class User
{
    private $pagination;

    private $userRepository;

    private $count;

    public function __construct(Paginator $pagination, UserRepository $userRepository, $count)
    {
        $this->pagination = $pagination;
        $this->userRepository = $userRepository;
        $this->count = $count;
    }

    /**
     * @param \Brd4\UserBundle\Entity\User $user
     * @param $page
     * @return array
     */
    public function getUsersWithMarkFollower(\Brd4\UserBundle\Entity\User $user, $page)
    {
        $pagination = $this->userRepository
            ->findAllUsers(
                $user->getId(),
                $page,
                $count = $this->count
            )
        ;

        $followers = $user->getFollowers();
        $followersIds = [];

        foreach ($followers as $follower) {
            $followersIds[] = $follower->getId();
        }

        foreach ($pagination as $item) {
            if ( in_array($item->getId(), $followersIds) )
                $item->isFollow = true;
            else {
                $item->isFollow = false;
            }
        }

        return $pagination;
    }
}