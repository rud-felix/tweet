<?php

namespace Brd4\CommonBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class BaseApiController extends FOSRestController
{
    /**
     * @param ConstraintViolationListInterface $violations
     * @return array
     */
    protected function prepareViolationErrors(ConstraintViolationListInterface $violations)
    {
        $errors = array();
        foreach ($violations as $violation) {
            $violationMessage = $violation->getPropertyPath() ?
                sprintf('%s: %s', $violation->getPropertyPath(), $violation->getMessage()) :
                $violation->getMessage()
            ;
            $errors[] = $violationMessage;
        }

        return $errors;
    }

    /**
     * @return \Doctrine\Common\Persistence\ObjectManager|object
     */
    public function getEntityManager()
    {
        $em = $this->getDoctrine()->getManager();

        return $em;
    }
}