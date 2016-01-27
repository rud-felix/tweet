<?php

namespace Brd4\CommonBundle\Controller\Api\V1;

use Brd4\CommonBundle\Controller\BaseApiController;
use Brd4\CommonBundle\Model\Credential;
use Brd4\CommonBundle\Model\Login;
use Brd4\CommonBundle\Model\Registration;
use Brd4\UserBundle\Entity\User;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Controller\Annotations\View as RestView;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\ConstraintViolationListInterface;

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

        return $this->view([
            'apiKey' => $user->getApiKey(),
            'username' => $user->getUsername(),
            'email' => $user->getEmail()
        ],
            Codes::HTTP_OK
        );
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
     * @param Request $request
     * @RestView
     * @return View
     */
    public function logoutAction(Request $request)
    {
        $extractor = $this->get('uecode.api_key.extractor');
        if (!$extractor->hasKey($request)) {
            return $this->view(['error' => 'not_found_api_key'], Codes::HTTP_UNAUTHORIZED);
        }

        $user = $this->get('brd4.user.repository.user')->findByApiKey($extractor->extractKey($request));
        if (!$user) {
            return $this->view(['error' => 'check_api_key'], Codes::HTTP_UNAUTHORIZED);
        }

        try {
            $tokenGenerator = $this->get('fos_user.util.token_generator');
            $user->setApiKey($tokenGenerator->generateToken());

            $em = $this->getEntityManager();
            $em->persist($user);
            $em->flush();
        } catch (\Exception $e) {
            // TODO: crate error handler
            return $this->view([], Codes::HTTP_BAD_REQUEST);
        }

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
     * @ParamConverter(name="registration", class="Brd4\CommonBundle\Model\Registration")
     * @param Registration $registration
     * @RestView
     * @return View
     */
    public function registrationAction(Registration $registration, ConstraintViolationListInterface $validationErrors)
    {
        if (count($validationErrors) > 0) {
            return $this->view($this->prepareViolationErrors($validationErrors), Codes::HTTP_BAD_REQUEST);
        }

        try {
            $shifter = $this->get('sleepness.shifter');
            /** @var User $user */
            $user = $shifter->fromDto($registration, new User());
            $user->setEnabled(true);

            $em = $this->getEntityManager();
            $em->persist($user);
            $em->flush();

        } catch (\Exception $e) {
            // TODO: refactoring errors and add translations
            return $this->view(['error' => 'check_username_and_email'], Codes::HTTP_BAD_REQUEST);
        }

        return $this->view([
            'apiKey' => $user->getApiKey()
        ],
            Codes::HTTP_OK
        );
    }
}