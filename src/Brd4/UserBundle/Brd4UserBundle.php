<?php

namespace Brd4\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class Brd4UserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
