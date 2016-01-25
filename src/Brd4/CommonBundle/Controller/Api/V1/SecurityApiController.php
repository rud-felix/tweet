<?php

namespace Brd4\CommonBundle\Controller\Api\V1;

use Brd4\CommonBundle\Controller\BaseApiController;
use Brd4\CommonBundle\Model\Credential;
use Brd4\CommonBundle\Model\Login;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Controller\Annotations\View as RestView;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class SecurityApiController extends BaseApiController
{
    /**
     * Access URI /api/v1/login
     *
     * @ApiDoc(
     *  resource=true,
     *  requirements={
     *      {"name"="username",      "dataType"="integer",    "requirement"="true"},
     *      {"name"="password",      "dataType"="integer",    "requirement"="true"}
     *  },
     *  output="",
     *  section="Security API",
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
     * @ParamConverter(name="credential", class="Brd4\CommonBundle\Model\Credential")
     *
     * @param Credential $credential
     * @RestView
     * @return View
     */
    public function loginAction(Credential $credential)
    {
        try {
            $securityManager = $this->get('fos_user.user_manager');
            $user = $securityManager->loadUserByUsername($credential->username);

            $security = $this->get('security.password_encoder');

            if (!$security->isPasswordValid($user, $credential->password)) {
                return $this->view([], Codes::HTTP_UNAUTHORIZED);
            }
        } catch (\Exception $e) {
            return $this->view($e->getMessage(), Codes::HTTP_UNAUTHORIZED);
        }

        return $this->view(['api-key' => $user->getApiKey()], Codes::HTTP_OK);
    }

    /**
     * Access URI /api/v1/logout
     *
     * @ApiDoc(
     *  resource=true,
     *  requirements={
     *      {"name"="token",    "dataType"="text",    "requirement"="true"}
     *  },
     *  output="",
     *  section="Security API",
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
    public function logoutAction()
    {
        return $this->view([], Codes::HTTP_OK);
    }

    /**
     * Access URI /api/v1/registration
     *
     * @ApiDoc(
     *  resource=true,
     *  requirements={
     *      {"name"="username",    "dataType"="string",    "requirement"="true"},
     *      {"name"="email",       "dataType"="string",    "requirement"="true"},
     *      {"name"="password",    "dataType"="string",    "requirement"="true"}
     *  },
     *  output="",
     *  section="Security API",
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
    public function registrationAction()
    {
        return $this->view([],Codes::HTTP_OK);
    }
}