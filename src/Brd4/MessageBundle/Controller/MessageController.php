<?php

namespace Brd4\MessageBundle\Controller;

use Brd4\CommonBundle\Controller\BaseController;
use Brd4\MessageBundle\Entity\Message;
use Brd4\UserBundle\Entity\User;
use Symfony\Component\HttpFoundation\Request;

class MessageController extends BaseController
{
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

        if ($form->isValid()) {
            /** @var Message $message */
            $message = $form->getData();
            $message->setUser($this->getUser());

            $em = $this->getEntityManager();
            $em->persist($message);
            $em->flush();

            if ($message->getId()) {
                return $this->redirectToRoute('brd4_message_user_list');
            }
        }

        return $this->render('@Brd4Message/Message/add.html.twig', [
           'form' => $form->createView()
        ]);
    }

    public function userListAction()
    {
        $this->denyAccessUnlessGranted("ROLE_USER");

        /** @var User $user */
        $user = $this->getUser();
        $messages = $user->getMessages();

        return $this->render('@Brd4Message/Message/user_list.html.twig', [
            'messages' => $messages
        ]);
    }

    public function followerListAction()
    {
        $this->denyAccessUnlessGranted("ROLE_USER");

        /** @var User $user */
        $user = $this->getUser();
        $followers[] = $user->getId();

        /** @var User $follower */
        foreach ($user->getFollowers() as $follower) {
            $followers[] = $follower->getId();
        }

        $followers = array_unique($followers);

        $messages = $this->get('brd4.message.repository.message')
            ->findByIDs($followers)
        ;

        return $this->render('@Brd4Message/Message/follower_list.html.twig', [
            'messages' => $messages
        ]);
    }

    public function searchAction()
    {

    }
}
