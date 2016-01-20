<?php

namespace Brd4\UserBundle\Controller;

use Brd4\CommonBundle\Controller\BaseController;
use Brd4\UserBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class UserController extends BaseController
{
    public function followerListAction()
    {
        $this->denyAccessUnlessGranted("ROLE_USER");

        /** @var User $user */
        $user = $this->getUser();
        $followers = $user->getFollowers();

        return $this->render('@Brd4User/User/follower_list.html.twig', [
            'followers' => $followers
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
            $em->flush();
        }

        return $this->redirectToRoute('brd4_user_list');
    }

    /**
     * @param $page
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction($page)
    {
        $this->denyAccessUnlessGranted("ROLE_USER");

        $pagination = $this->get('brd4.user.repository.user')
            ->findAllUsers(
                $id = $this->getUser()->getId(),
                $page,
                $count = $this->getParameter('user_list.item.count'))
        ;

        return $this->render('@Brd4User/User/user_list.html.twig', [
            'pagination' => $pagination
        ]);
    }
}
