<?php

namespace Brd4\UserBundle\Controller;

use Brd4\CommonBundle\Controller\BaseController;
use Brd4\UserBundle\Entity\User;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class UserController extends BaseController
{
    /**
     * @param $page
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function followerListAction($page)
    {
        $this->denyAccessUnlessGranted("ROLE_USER");

        /** @var User $user */
        $user = $this->getUser();
        $pagination = $this->get('knp_paginator')
            ->paginate(
                $user->getFollowers(),
                $page,
                $this->getParameter('followers_list.item.count')
            )
        ;

        return $this->render('@Brd4User/User/follower_list.html.twig', [
            'pagination' => $pagination
        ]);
    }

    /**
     * @param User $follower
     * @ParamConverter("follower", class="Brd4UserBundle:User")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function followAction(User $follower)
    {
        $this->denyAccessUnlessGranted("ROLE_USER");

        /** @var User $user */
        $user = $this->getUser();

        if ($follower !== $user) {
            $user->addFollower($follower);

            $em = $this->getEntityManager();
            $em->persist($user);

            try {
                $em->flush();
            } catch (UniqueConstraintViolationException $e) {
                // below redirect
            }
        }

        return $this->redirectToRoute('brd4_user_list');
    }

    /**
     * @param User $follower
     * @ParamConverter(name="follower", class="Brd4UserBundle:User")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function unfollowAction(User $follower)
    {
        $this->denyAccessUnlessGranted("ROLE_USER");

        /** @var User $user */
        $user = $this->getUser();
        $user->removeFollower($follower);

        $em = $this->getEntityManager();
        $em->persist($user);
        $em->flush();

        return $this->redirectToRoute('brd4_user_followers');
    }

    /**
     * @param $page
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction($page)
    {
        $this->denyAccessUnlessGranted("ROLE_USER");

        $pagination = $this->get('brd4.user.user')
            ->getUsersWithMarkFollower(
                $this->getUser(),
                $page
            )
        ;

        return $this->render('@Brd4User/User/user_list.html.twig', [
            'pagination' => $pagination
        ]);
    }
}
