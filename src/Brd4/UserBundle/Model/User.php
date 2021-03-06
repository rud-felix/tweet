<?php

namespace Brd4\UserBundle\Model;

class User
{
    /**
     * @var integer
     */
    public $id;

    /**
     * @var string
     */
    public $username;

    /**
     * @var string
     */
    public $email;

    /**
     * @var boolean
     */
    public $isFollow;
}