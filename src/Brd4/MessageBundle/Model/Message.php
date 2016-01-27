<?php

namespace Brd4\MessageBundle\Model;

use JMS\Serializer\Annotation\Type;
use Brd4\UserBundle\Model\User as UserModel;

class Message
{
    /**
     * @var integer
     */
    public $id;

    /**
     * @var string
     */
    public $text;

    /**
     * @Type("DateTime")
     */
    public $createdAt;

    /**
     * @Type("\Brd4\UserBundle\Model\User")
     */
    public $user;

    /**
     * @param UserModel $user
     */
    public function setUser(UserModel $user)
    {
        $this->user = $user;
    }
}