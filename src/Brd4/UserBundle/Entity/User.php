<?php

namespace Brd4\UserBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="Brd4\UserBundle\Repository\UserRepository")
 */
class User extends BaseUser
{
    public function __construct()
    {
        parent::__construct();

        $this->messages = new ArrayCollection();
        $this->followers = new ArrayCollection();
    }

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToMany(
     *     targetEntity="Brd4\MessageBundle\Entity\Message",
     *     mappedBy="user",
     *     cascade={"persist", "remove"}
     * )
     * @ORM\OrderBy({"createdAt" = "DESC"})
     */
    protected $messages;

    /**
     * @ORM\ManyToMany(targetEntity="Brd4\UserBundle\Entity\User", cascade={"persist", "remove"})
     * @ORM\JoinTable(name="user_followers",
     *     joinColumns={
     *         @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     *     },
     *     inverseJoinColumns={
     *         @ORM\JoinColumn(name="follower_user_id", referencedColumnName="id")
     *     }
     * )
     */
    protected $followers;

    /**
     * Add messages
     *
     * @param \Brd4\MessageBundle\Entity\Message $messages
     * @return User
     */
    public function addMessage(\Brd4\MessageBundle\Entity\Message $messages)
    {
        $this->messages[] = $messages;

        return $this;
    }

    /**
     * Remove messages
     *
     * @param \Brd4\MessageBundle\Entity\Message $messages
     */
    public function removeMessage(\Brd4\MessageBundle\Entity\Message $messages)
    {
        $this->messages->removeElement($messages);
    }

    /**
     * Get messages
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMessages()
    {
        return $this->messages;
    }

    /**
     * Add followers
     *
     * @param \Brd4\UserBundle\Entity\User $followers
     * @return User
     */
    public function addFollower(\Brd4\UserBundle\Entity\User $followers)
    {
        $this->followers[] = $followers;

        return $this;
    }

    /**
     * Remove followers
     *
     * @param \Brd4\UserBundle\Entity\User $followers
     */
    public function removeFollower(\Brd4\UserBundle\Entity\User $followers)
    {
        $this->followers->removeElement($followers);
    }

    /**
     * Get followers
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getFollowers()
    {
        return $this->followers;
    }
}
