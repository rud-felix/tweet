<?php

namespace Brd4\MessageBundle\Controller\Api\V1;

use Brd4\MessageBundle\Model\UserMessage;
use Brd4\UserBundle\Entity\User;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Controller\Annotations\View as RestView;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class ApiController extends FOSRestController
{
    /**
     * Access URI /api/v1/users/{id}/messages/{page}.
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Returns list of user messages",
     *  requirements={
     *      {"name"="id",      "dataType"="integer",    "requirement"="true"},
     *      {"name"="page",    "dataType"="integer",    "requirement"="false"}
     *  },
     *  output="Brd4\MessageBundle\Model\UserMessage",
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
     * @param Request $request
     * @param User $user
     * @param $page
     *
     * @RestView
     *
     * @return View
     */
    public function userMessageListAction(Request $request, User $user, $page)
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
                ->serialize($pagination, UserMessage::class, $format = 'json')
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

//    /**
//     * @ParamConverter("message", converter="fos_rest.request_body")
//     */
//    public function addMessage(Message $message)
//    {
//
//    }

}