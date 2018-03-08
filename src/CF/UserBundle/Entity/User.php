<?php

namespace CF\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\Common\Collections\ArrayCollection;

/**
* @ORM\Table(name="cf_user")
* @ORM\Entity(repositoryClass="CF\UserBundle\Repository\UserRepository")
*/
class User extends BaseUser
{
  /**
  * @ORM\Column(name="id", type="integer")
  * @ORM\Id
  * @ORM\GeneratedValue(strategy="AUTO")
  */
  protected $id;

  /**
  * @ORM\OneToOne(targetEntity="CF\MarsubookBundle\Entity\Marsu", cascade={"persist"})
  * @ORM\JoinColumn(nullable=false)
  */
  private $marsu;

  /**
  * @ORM\ManyToMany(targetEntity="User", mappedBy="myFriends")
  */
  private $friendsWithMe;

  /**
  * @ORM\ManyToMany(targetEntity="User", inversedBy="friendsWithMe")
  * @ORM\JoinTable(name="friends",
  *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
  *      inverseJoinColumns={@ORM\JoinColumn(name="friend_user_id", referencedColumnName="id")}
  *      )
  */
  private $myFriends;

  public function __construct()
  {
    parent::__construct();
    $this->friendsWithMe = new ArrayCollection();
    $this->friends = new ArrayCollection();
  }



  /**
  * Set marsu
  *
  * @param \CF\MarsubookBundle\Entity\Marsu $marsu
  *
  * @return User
  */
  public function setMarsu(\CF\MarsubookBundle\Entity\Marsu $marsu)
  {
    $this->marsu = $marsu;

    return $this;
  }

  /**
  * Get marsu
  *
  * @return \CF\MarsubookBundle\Entity\Marsu
  */
  public function getMarsu()
  {
    return $this->marsu;
  }

  /**
  * Add friendsWithMe
  *
  * @param \CF\UserBundle\Entity\User $friendsWithMe
  *
  * @return User
  */
  public function addFriendsWithMe(\CF\UserBundle\Entity\User $friendsWithMe)
  {
    $this->friendsWithMe[] = $friendsWithMe;

    return $this;
  }

  /**
  * Remove friendsWithMe
  *
  * @param \CF\UserBundle\Entity\User $friendsWithMe
  */
  public function removeFriendsWithMe(\CF\UserBundle\Entity\User $friendsWithMe)
  {
    $this->friendsWithMe->removeElement($friendsWithMe);
  }

  /**
  * Get friendsWithMe
  *
  * @return \Doctrine\Common\Collections\Collection
  */
  public function getFriendsWithMe()
  {
    return $this->friendsWithMe;
  }

  /**
  * Add myFriend
  *
  * @param \CF\UserBundle\Entity\User $myFriend
  *
  * @return User
  */
  public function addMyFriend(\CF\UserBundle\Entity\User $myFriend)
  {
    if (!$this->myFriends->contains($myFriend)) {
        $this->myFriends->add($myFriend);
        $myFriend->addMyFriend($this);
    }
    return $this;
  }

  /**
  * Remove myFriend
  *
  * @param \CF\UserBundle\Entity\User $myFriend
  */
  public function removeMyFriend(\CF\UserBundle\Entity\User $myFriend)
  {
    if ($this->myFriends->contains($myFriend)) {
        $this->myFriends->removeElement($myFriend);
        $myFriend->removeMyFriend($this);
    }
  }

  /**
  * Get myFriends
  *
  * @return \Doctrine\Common\Collections\Collection
  */
  public function getMyFriends()
  {
    return $this->myFriends->toArray();
  }
}
