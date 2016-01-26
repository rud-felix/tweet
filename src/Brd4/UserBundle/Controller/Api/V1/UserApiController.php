<?php

namespace Brd4\UserBundle\Controller\Api\V1;

use Brd4\CommonBundle\Controller\BaseApiController;
use Brd4\UserBundle\Entity\User;
use Brd4\UserBundle\Model\User as UserModel;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Controller\Annotations\View as RestView;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class UserApiController extends BaseApiController
{
    /**
     * Access URI /api/v1/users/pages/{page}
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Returns list of users",
     *  requirements={
     *      {"name"="id",      "dataType"="integer",    "requirement"="true"},
     *      {"name"="page",    "dataType"="integer",    "requirement"="false"}
     *  },
     *  output="Brd4\UserBundle\Model\User",
     *  section="User API",
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
     * @RestView
     * @return View
     */
    public function userListAction($page)
    {
        try{
            $pagination = $this->get('brd4.user.user')
                ->getUsersWithMarkFollower(
                    $this->getUser(),
                    $page
                )
            ;
            $result = $this->get('brd4.common.data_transfer_prepare')
                ->serialize($pagination, UserModel::class, $format = 'json')
            ;
        } catch (\Exception $e) {
            return $this->view($e->getMessage(), Codes::HTTP_BAD_REQUEST);
        }

        return $this->view([
            'total' => '',  // TODO: total count
            'count' => $this->getParameter('user_list.item.count'), // TODO: count with find
            'page' => $page,
            'data' => $result
        ],
            Codes::HTTP_OK
        );
    }

    /**
     * Access URI /api/v1/users/{id}/follow
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Follow same user and returns list of followers",
     *  requirements={
     *      {"name"="id",      "dataType"="integer",    "requirement"="true"}
     *  },
     *  output="Brd4\UserBundle\Model\User",
     *  section="User API",
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
     * @ParamConverter(name="follower", class="Brd4\UserBundle\Entity\User")
     *
     * @param User $follower
     *
     * @RestView
     *
     * @return View
     */
    public function followAction(User $follower)
    {
        /** @var User $user */
        $user = $this->getUser();

        if ($follower !== $user) {
            $user->addFollower($follower);

            $em = $this->getEntityManager();
            $em->persist($user);

            try {
                $em->flush();
            } catch (UniqueConstraintViolationException $e) {
                return $this->view($e->getMessage(), Codes::HTTP_BAD_REQUEST);
            }
        }

        return $this->view([], Codes::HTTP_OK);
    }

    /**
     * Access URI /api/v1/users/followers/pages/{page}
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Returns list of user followers",
     *  requirements={
     *      {"name"="page",    "dataType"="integer",    "requirement"="false"}
     *  },
     *  output="Brd4\UserBundle\Model\User",
     *  section="User API",
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
     * @RestView
     * @return View
     */
    public function followerListAction($page)
    {
        try{
            $user = $this->getUser();
            $pagination = $this->get('knp_paginator')
                ->paginate(
                    $user->getFollowers(),
                    $page,
                    $this->getParameter('followers_list.item.count')
                )
            ;
            $result = $this->get('brd4.common.data_transfer_prepare')
                ->serialize($pagination, UserModel::class, $format = 'json')
            ;
        } catch (\Exception $e) {
            return $this->view($e->getMessage(), Codes::HTTP_BAD_REQUEST);
        }

        return $this->view([
            'total' => '',  // TODO: total count
            'count' => $this->getParameter('followers_list.item.count'), // TODO: count with find
            'page' => $page,
            'data' => $result
        ],
            Codes::HTTP_OK
        );
    }

    /**
     * Access URI /api/v1/users/{id}/unfollow
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Unfollow same user and returns list of user followers",
     *  requirements={
     *      {"name"="id",      "dataType"="integer",    "requirement"="true"}
     *  },
     *  output="Brd4\UserBundle\Model\User",
     *  section="User API",
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
     * @ParamConverter(name="follower", class="Brd4\UserBundle\Entity\User")
     *
     * @param User $follower
     * @RestView
     * @return View
     */
    public function unfollowAction(User $follower)
    {
        try {
            /** @var User $user */
            $user = $this->getUser();
            $user->removeFollower($follower);

            $em = $this->getEntityManager();
            $em->persist($user);
            $em->flush();
        } catch (\Exception $e) {
            return $this->view($e->getMessage(), Codes::HTTP_BAD_REQUEST);
        }

        return $this->view([], Codes::HTTP_OK);
    }

    /**
     * Access URI /api/v1/users/profile
     *
     * @ApiDoc(
     *  resource=true,
     *  description="",
     *  requirements={},
     *  output="",
     *  section="User API",
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
     * @RestView
     * @return View
     */
    public function profileAction()
    {

    }
}