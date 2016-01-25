<?php

namespace Brd4\UserBundle\Controller\Api\V1;

use Brd4\CommonBundle\Controller\BaseApiController;
use Brd4\UserBundle\Entity\User;
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
     * @ParamConverter(name="user", class="Brd4\UserBundle\Entity\User")
     *
     * @param User $user
     * @param $page
     *
     * @RestView
     *
     * @return View
     */
    public function userListAction(User $user, $page)
    {

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
     * @ParamConverter(name="user", class="Brd4\UserBundle\Entity\User")
     *
     * @param User $user
     * @param $page
     *
     * @RestView
     *
     * @return View
     */
    public function followAction(User $user, $page)
    {

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
     * @ParamConverter(name="user", class="Brd4\UserBundle\Entity\User")
     *
     * @param User $user
     * @RestView
     * @return View
     */
    public function unfollowAction(User $user)
    {

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