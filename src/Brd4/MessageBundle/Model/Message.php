<?php

namespace Brd4\MessageBundle\Model;

use JMS\Serializer\Annotation\Type;

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
}