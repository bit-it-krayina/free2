<?php

namespace CsnUser\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Role
 *
 * @ORM\Table(name="employment")
 * @ORM\Entity
 */
class Employment
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="employment", type="string", length=20, nullable=false)
     */
    protected $employment;


    /**
     * @var string
     *
     * @ORM\Column(name="class", type="string", length=20, nullable=false)
     */
    protected $class;




    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set employment
     *
     * @param  string   $state
	 * @return Employment Description
     */
    public function setEmployment($employment)
    {
        $this->employment = $employment;

        return $this;
    }

    /**
     * Get state
     *
     * @return string
     */
    public function getEmployment()
    {
        return $this->employment;
    }

	/**
	 *
	 * @return string
	 */
	public function getClass ()
	{
		return $this -> class;

	}

	/**
	 *
	 * @return Employment Description
	 */
	public function setClass ( $class )
	{
		$this -> class = $class;

		return $this;

	}

}
