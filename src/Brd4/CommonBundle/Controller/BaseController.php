<?php

namespace Brd4\CommonBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BaseController extends Controller
{
    /**
     * @return \Doctrine\Common\Persistence\ObjectManager|object
     */
    public function getEntityManager()
    {
        $em = $this->getDoctrine()->getManager();

        return $em;
    }

}
