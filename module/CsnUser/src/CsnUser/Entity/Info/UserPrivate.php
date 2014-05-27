<?php

namespace CsnUser\Entity\Info;

use Doctrine\ORM\Mapping as ORM;
use Zend\Form\Annotation;

/**
 * Role
 *
 * @ORM\Table(name="user_info_private")
 * @ORM\Entity
 */
class UserPrivate
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     */
    protected $id;

	/**
     * @var \DateTime
     *
     * @ORM\Column(name="birthDay", type="datetime", nullable=true)
     * @Annotation\Attributes({"type":"datetime","min":"1900-01-01","max":"2000-01-01","step":"1"})
     * @Annotation\Options({"label":"birthDay:", "format":"Y-m-d"})
     */
	protected $birthDay;

	/**
     * @var string
     *
     * @ORM\Column(name="location", type="string", length=40, nullable=true)
     * @Annotation\Type("Zend\Form\Element\Text")
     * @Annotation\Filter({"name":"StripTags"})
     * @Annotation\Filter({"name":"StringTrim"})
     * @Annotation\Validator({"name":"StringLength", "options":{"encoding":"UTF-8", "max":40}})
     */
    protected $location;


    /**
     * @var string
     *
     * @ORM\Column(name="resume", type="string", length=255, nullable=true)
     * @Annotation\Type("Zend\Form\Element\Text")
     * @Annotation\Filter({"name":"StringTrim"})
     * @Annotation\Validator({"name":"StringLength", "options":{"encoding":"UTF-8", "max":255}})
     */
    private $resume;





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
	 *
	 * @return string
	 */
	public function getBirthDay ()
	{
		return $this -> birthDay;

	}

	/**
	 *
	 * @param string $birthDay
	 * @return \CsnUser\Entity\Info\UserPrivate
	 */
	public function setBirthDay (  $birthDay )
	{
		$this -> birthDay = $birthDay;

		return $this;

	}

	/**
	 *
	 * @return string
	 */
	public function getLocation ()
	{
		return $this -> location;

	}

	/**
	 *
	 * @param string $location
	 * @return \CsnUser\Entity\Info\UserPrivate
	 */
	public function setLocation ( $location )
	{
		$this -> location = $location;

		return $this;
	}

	/**
	 *
	 * @return string
	 */
	public function getResume ()
	{
		return $this -> resume;

	}

	/**
	 *
	 * @param string $resume
	 * @return \CsnUser\Entity\Info\UserPrivate
	 */
	public function setResume ( $resume )
	{
		$this -> resume = $resume;

		return $this;

	}

}
