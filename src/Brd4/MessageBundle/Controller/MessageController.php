<?php

namespace Brd4\MessageBundle\Controller;

use Brd4\CommonBundle\Controller\BaseController;
use Brd4\MessageBundle\Entity\Message;
use Brd4\UserBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;

class MessageController extends BaseController
{
    /**
     * @param User $user
     * @param $page
     * @ParamConverter(name="user", class="Brd4UserBundle:User")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function userListAction(User $user, $page)
    {
        $this->denyAccessUnlessGranted("ROLE_USER");

        $pagination = $this->get('knp_paginator')
            ->paginate(
                $user->getMessages(),
                $page,
                $this->getParameter('user_message.list.item.count')
            );

        return $this->render('@Brd4Message/Message/user_list.html.twig', [
            'pagination' => $pagination
        ]);
    }

    /**
     * @param $page
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function followerListAction($page)
    {
        $this->denyAccessUnlessGranted("ROLE_USER");

        $pagination = $this->get('brd4.message.repository.message')
            ->findFollowersMessages(
                $this->getUser(),
                $page,
                $this->getParameter('followers_mgs.item.count')
            )
        ;

        return $this->render('@Brd4Message/Message/follower_list.html.twig', [
            'pagination' => $pagination
        ]);
    }

    /**
     * @param Request $request
     * @param integer $page
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function searchAction(Request $request, $page)
    {
        $this->denyAccessUnlessGranted("ROLE_USER");

        $form = $this->get('form.factory')
            ->create($this->get('brd4.message.form.search_message'))
        ;
        $form->handleRequest($request);

        $text = $form->getData()? $form->getData()->getText(): '';

        $result = $this->get('brd4.message.search')->search($text, $page);

        return $this->render('@Brd4Message/Message/search.html.twig', [
            'pagination' => $result,
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function addAction(Request $request)
    {
        $this->denyAccessUnlessGranted("ROLE_USER");

        $form = $this->get('form.factory')->create(
            $this->get('brd4.message.form.add_message')
        );
        $form->handleRequest($request);

        // TODO: refactoring
        if ($form->isValid()) {
            /** @var Message $message */
            $message = $form->getData();
            $message->setUser($this->getUser());

            $em = $this->getEntityManager();
            $em->persist($message);
            $em->flush();

            if ($message->getId()) {
                return $this->redirectToRoute('brd4_message_user_list', [
                    'id' => $this->getUser()->getId()
                ]);
            }
        }

        return $this->render('@Brd4Message/Message/add.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
