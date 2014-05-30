<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zend\Form\Annotation;

/**
 * A project data
 *
 * @ORM\Entity
 * @ORM\Table(name="offer")
 */
class Offer
{

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="id", type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="IDENTITY")
	 * @Annotation\Exclude()
	 */
	protected $id;

	/**
	 * @var Application\Entity\OfferState
	 *
	 * @ORM\ManyToOne(targetEntity="Application\Entity\OfferState")
	 * @ORM\JoinColumn(name="offer_state_id", referencedColumnName="id", nullable=false)
	 * @Annotation\Type("DoctrineModule\Form\Element\ObjectSelect")
	 * @Annotation\Filter({"name":"StripTags"})
	 * @Annotation\Filter({"name":"StringTrim"})
	 * @Annotation\Validator({"name":"Digits"})
	 * @Annotation\Required(true)
	 * @Annotation\Options({
	 *   "required":"true",
	 *   "target_class":"Application\Entity\OfferState",
	 *   "property": "state"
	 * })
	 */
	protected $state;

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
	 * @var \DateTime
	 *
	 * @ORM\Column(name="registration_date", type="datetime", nullable=true)
	 * @Annotation\Attributes({"type":"datetime","min":"2014-01-01","max":"2030-01-01","step":"1"})
	 * @Annotation\Options({"label":"Registration Date:", "format":"Y-m-d"})
	 */
	protected $registrationDate;

	/**
	 * @var \DateTime
	 *
	 * @ORM\Column(name="start_date", type="datetime", nullable=true)
	 * @Annotation\Attributes({"type":"datetime","min":"2014-01-01","max":"2030-01-01","step":"1"})
	 * @Annotation\Options({"label":"Registration Date:", "format":"Y-m-d"})
	 */
	protected $startDate;

	/**
	 * @var \DateTime
	 *
	 * @ORM\Column(name="finish_date", type="datetime", nullable=true)
	 * @Annotation\Attributes({"type":"datetime","min":"2014-01-01","max":"2030-01-01","step":"1"})
	 * @Annotation\Options({"label":"Registration Date:", "format":"Y-m-d"})
	 */
	protected $finishDate;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="role", type="string", length=50, nullable=true)
	 * @Annotation\Type("Zend\Form\Element\Text")
	 * @Annotation\Filter({"name":"StringTrim"})
	 * @Annotation\Validator({"name":"StringLength", "options":{"encoding":"UTF-8", "max":50}})
	 */
	private $role;

	/**
	 * @var Application\Entity\OfferState
	 *
	 * @ORM\ManyToOne(targetEntity="Application\Entity\Project")
	 * @ORM\JoinColumn(name="project_id", referencedColumnName="id", nullable=false)
	 * @Annotation\Type("DoctrineModule\Form\Element\ObjectSelect")
	 * @Annotation\Filter({"name":"StripTags"})
	 * @Annotation\Filter({"name":"StringTrim"})
	 * @Annotation\Validator({"name":"Digits"})
	 * @Annotation\Required(true)
	 * @Annotation\Options({
	 *   "required":"true",
	 *   "target_class":"Application\Entity\Project",
	 *   "property": "project"
	 * })
	 */
	protected $project;

	/**
	 * @var CsnUser\Entity\User
	 *
	 * @ORM\ManyToOne(targetEntity="CsnUser\Entity\User", inversedBy="offers")
	 * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
	 * @Annotation\Type("DoctrineModule\Form\Element\ObjectSelect")
	 * @Annotation\Filter({"name":"StripTags"})
	 * @Annotation\Filter({"name":"StringTrim"})
	 * @Annotation\Validator({"name":"Digits"})
	 * @Annotation\Required(true)
	 * @Annotation\Options({
	 *   "required":"true",
	 *   "target_class":"CsnUser\Entity\User",
	 *   "property": "username"
	 * })
	 */
	protected $user;

	public function getId ()
	{
		return $this -> id;

	}

	public function getState ()
	{
		return $this -> state;

	}

	public function setState ( Application\Entity\OfferState $state )
	{
		$this -> state = $state;

		return $this;

	}

	public function getResume ()
	{
		return $this -> resume;

	}

	public function setResume ( $resume )
	{
		$this -> resume = $resume;

		return $this;

	}

	public function getRegistrationDate ()
	{
		return $this -> registrationDate;

	}

	public function setRegistrationDate ( \DateTime $registrationDate )
	{
		$this -> registrationDate = $registrationDate;

		return $this;

	}

	public function getStartDate ()
	{
		return $this -> startDate;

	}

	public function setStartDate ( \DateTime $startDate )
	{
		$this -> startDate = $startDate;

		return $this;

	}

	public function getFinishDate ()
	{
		return $this -> finishDate;

	}

	public function setFinishDate ( \DateTime $finishDate )
	{
		$this -> finishDate = $finishDate;

		return $this;

	}

	public function getRole ()
	{
		return $this -> role;

	}

	public function setRole ( $role )
	{
		$this -> role = $role;

		return $this;

	}

	public function getProject ()
	{
		return $this -> project;

	}

	public function setProject ( Application\Entity\OfferState $project )
	{
		$this -> project = $project;

		return $this;

	}

	public function getUser ()
	{
		return $this -> user;

	}

	public function setUser ( CsnUser\Entity\User $user )
	{
		$this -> user = $user;

		return $this;

	}

}