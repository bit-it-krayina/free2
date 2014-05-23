<?php

namespace CsnUser\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * UserResume
 *
 * @ORM\Table(name="user_resume")
 * @ORM\Entity
 */
class UserResume
{
    /**
     * @var integer
     *
     * @ORM\Column(name="user_id", type="integer")
     * @ORM\Id
     */
    protected $user_id;

    /**
     * @var string
     *
     * @ORM\Column(name="resume", type="string", length=1024, nullable=false)
     */
    protected $resume;



	/**
	 * @ORM\OneToOne(targetEntity="User", inversedBy="userResume")
	 * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
	 * @var User
	 */
	protected $user;


    /**
     * Get id
     *
     * @return integer
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * Set user_id
     *
     * @param  string $user_id
     * @return UserResume
     */
    public function setUserId($user_id)
    {
        $this->user_id = $user_id;

        return $this;
    }
    /**
     * Get user
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set user
     *
     * @param  string $user
     * @return UserResume
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Set resume
     *
     * @param  string $resume
     * @return UserResume
     */
    public function setResume($resume)
    {
        $this->resume = $resume;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getResume()
    {
        return $this->resume;
    }


}
