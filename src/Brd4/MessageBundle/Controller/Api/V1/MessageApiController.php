<?php

namespace Brd4\MessageBundle\Controller\Api\V1;

use Brd4\CommonBundle\Controller\BaseApiController;
use Brd4\MessageBundle\Entity\Message;
use Brd4\MessageBundle\Model\Message as MessageModel;
use Brd4\MessageBundle\Model\Search;
use Brd4\UserBundle\Entity\User;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Controller\Annotations\View as RestView;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class MessageApiController extends BaseApiController
{
    /**
     * Access URI /api/v1/messages/users/{id}/pages/{page}
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Returns list of user messages",
     *  requirements={
     *      {"name"="id",      "dataType"="integer",    "requirement"="true"},
     *      {"name"="page",    "dataType"="integer",    "requirement"="false"}
     *  },
     *  output="Brd4\MessageBundle\Model\Message",
     *  section="Message API",
     *  statusCodes={
     *      200="Returned when request was handled with success",
     *      400="Returned when bad request",
     *      500="Returned when there is a server side error",
     *  },
     *  tags={
     *      "beta" = "#10A54A"
     *  }
     * )
     *
     * @ParamConverter(name="user", class="Brd4\UserBundle\Entity\User")
     *
     * @param User $user
     * @param $page
     *
     * @RestView
     *
     * @return View
     */
    public function userMessageListAction(User $user, $page)
    {
        try {
            // TODO: remove to same service and write query
            $pagination = $this->get('knp_paginator')
                ->paginate(
                    $user->getMessages(),
                    $page,
                    $this->getParameter('user_message.list.item.count') // TODO: set count from uri
                )
            ;

            $result = $this->get('brd4.common.data_transfer_prepare')
                ->serialize($pagination, MessageModel::class, $format = 'json')
            ;

        } catch (\Exception $e) {
            return $this->view($e->getMessage(), Codes::HTTP_BAD_REQUEST);
        }

        return $this->view([
            'total' => '',
            'count' => $this->getParameter('user_message.list.item.count'),
            'page' => $page,
            'data' => $result
        ],
            Codes::HTTP_OK
        );
    }

    /**
     * Access URI /api/v1/messages
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Returns created message",
     *  requirements={
     *      {"name"="text",    "dataType"="text",    "requirement"="true"}
     *  },
     *  output="Brd4\MessageBundle\Model\Message",
     *  section="Message API",
     *  statusCodes={
     *      200="Returned when request was handled with success",
     *      400="Returned when bad request",
     *      500="Returned when there is a server side error",
     *  },
     *  tags={
     *      "beta" = "#10A54A"
     *  }
     * )
     *
     * @ParamConverter("message", converter="fos_rest.request_body")
     *
     * @param MessageModel $message
     * @param ConstraintViolationListInterface $validationErrors
     * @RestView
     * @return View
     */
    public function createMessageAction(MessageModel $message, ConstraintViolationListInterface $validationErrors)
    {
        if (count($validationErrors) > 0) {
            return $this->view($this->prepareViolationErrors($validationErrors), Codes::HTTP_BAD_REQUEST);
        }

        // $this->denyAccessUnlessGranted("ROLE_USER"); // TODO: refactoring

        $shifter = $this->get('sleepness.shifter');
        $message = $shifter->fromDto($message, new Message());

        $validator = $this->get('validator');
        if ($validator->validate($message)) {
            /** @var Message $message */
            $message->setUser($this->getUser());

            $em = $this->get('doctrine.orm.entity_manager');
            $em->persist($message);
            $em->flush();

            if ($message->getId()) {
                $result = $this->get('brd4.common.data_transfer_prepare')
                    ->serialize([$message], MessageModel::class, $format = 'json')
                ;

                return $this->view(['data' => $result], Codes::HTTP_OK);
            }
        }

        // TODO: refactoring. return correct error
        return $this->view('error', Codes::HTTP_BAD_REQUEST);
    }

    /**
     * Access URI /api/v1/messages/search/pages/{page}/{text}
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Returns list of messages",
     *  requirements={
     *      {"name"="page",    "dataType"="integer",    "requirement"="false"}
     *  },
     *  output="Brd4\MessageBundle\Model\Message",
     *  section="Message API",
     *  statusCodes={
     *      200="Returned when request was handled with success",
     *      400="Returned when bad request",
     *      500="Returned when there is a server side error",
     *  },
     *  tags={
     *      "beta" = "#10A54A"
     *  }
     * )
     *
     *
     * @param string $text
     * @param integer $page
     * @RestView
     * @return View
     */
    public function searchAction($text, $page)
    {
        //$this->denyAccessUnlessGranted("ROLE_USER");    // TODO: refactoring

//        if (count($validationErrors) > 0) {
//            return $this->view($this->prepareViolationErrors($validationErrors), Codes::HTTP_BAD_REQUEST);
//        }

        try{
            $searchResult = $this->get('brd4.message.search')->search($text, $page);
            $result = $this->get('brd4.common.data_transfer_prepare')
                ->serialize($searchResult, MessageModel::class, $format = 'json')
            ;
        } catch (\Exception $e) {
            return $this->view($e->getMessage(), Codes::HTTP_BAD_REQUEST);
        }

        return $this->view([
            'total' => '',  // TODO: total count
            'count' => $this->getParameter('search.item.count'),
            'page' => $page,
            'data' => $result
        ],
            Codes::HTTP_OK
        );
    }

    /**
     * Access URI /api/v1/messages/followers/pages/{page}
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Returns user followers message list",
     *  requirements={
     *      {"name"="page",    "dataType"="integer",    "requirement"="false"}
     *  },
     *  output="Brd4\MessageBundle\Model\Message",
     *  section="Message API",
     *  statusCodes={
     *      200="Returned when request was handled with success",
     *      400="Returned when bad request",
     *      500="Returned when there is a server side error",
     *  },
     *  tags={
     *      "beta" = "#10A54A"
     *  }
     * )
     *
     * @param $page
     *
     * @RestView
     * @return View
     */
    public function followersMessageListAction($page)
    {
        //$this->denyAccessUnlessGranted("ROLE_USER"); // TODO: refactoring

        try{
            $pagination = $this->get('brd4.message.repository.message')
                ->findFollowersMessages(
                    $this->getUser(),
                    $page,
                    $this->getParameter('followers_mgs.item.count')
                )
            ;

            $result = $this->get('brd4.common.data_transfer_prepare')
                ->serialize($pagination, MessageModel::class, $format = 'json')
            ;
        } catch (\Exception $e) {
            return $this->view($e->getMessage(), Codes::HTTP_BAD_REQUEST);
        }

        return $this->view([
            'total' => '',  // TODO: total count
            'count' => $this->getParameter('followers_mgs.item.count'),
            'page' => $page,
            'data' => $result
        ],
            Codes::HTTP_OK
        );
    }

}