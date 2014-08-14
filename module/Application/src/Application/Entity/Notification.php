<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zend\Form\Annotation;

/**
 * A project data
 *
 * @ORM\Entity()
 * @ORM\Table(name="notification")
 */
class Notification
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
	 * @var string
	 *
	 * @ORM\Column(name="title", type="string", length=1024, nullable=true)
	 * @Annotation\Type("Zend\Form\Element\Text")
	 * @Annotation\Filter({"name":"StringTrim"})
	 * @Annotation\Validator({"name":"StringLength", "options":{"encoding":"UTF-8", "max":1024}})
	 * @Annotation\Required(true)
	 * @Annotation\Attributes({
	 *   "type":"text",
	 *   "required":"false"
	 * })
	 */
	protected $title;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="message", type="string", length=2048, nullable=true)
	 * @Annotation\Type("Zend\Form\Element\Textarea")
	 * @Annotation\Filter({"name":"StripTags"})
	 * @Annotation\Filter({"name":"StringTrim"})
	 * @Annotation\Validator({"name":"StringLength", "options":{"encoding":"UTF-8", "max":2048}})
	 * @Annotation\Attributes({
	 *   "type":"text",
	 *   "required":"false"
	 * })
	 */
	protected $message;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="type", type="string", length=255, columnDefinition="ENUM('info', 'warning', 'error')",  nullable=false)
	 * @Annotation\Type("Zend\Form\Element\Text")
	 * @Annotation\Filter({"name":"StripTags"})
	 * @Annotation\Filter({"name":"StringTrim"})
	 * @Annotation\Validator({"name":"StringLength", "options":{"encoding":"UTF-8", "max":1024}})
	 * @Annotation\Attributes({
	 *   "type":"text",
	 *   "required":"true"
	 * })
	 */
	protected $type;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="fix_url", type="string", length=2048, nullable=true)
	 * @Annotation\Type("Zend\Form\Element\Text")
	 * @Annotation\Filter({"name":"StringTrim"})
	 * @Annotation\Validator({"name":"StringLength", "options":{"encoding":"UTF-8", "max":2048}})
	 * @Annotation\Required(true)
	 * @Annotation\Attributes({
	 *   "type":"text",
	 *   "required":"false"
	 * })
	 */
	protected $fixUrl;


	/**
	 * @var \Application\Entity\User
	 *
	 * @ORM\ManyToOne(targetEntity="\Application\Entity\User")
	 * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
	 * @Annotation\Type("DoctrineModule\Form\Element\ObjectSelect")
	 * @Annotation\Filter({"name":"StripTags"})
	 * @Annotation\Filter({"name":"StringTrim"})
	 * @Annotation\Validator({"name":"Digits"})
	 * @Annotation\Required(true)
	 * @Annotation\Options({
	 *   "required":"true",
	 *   "target_class":"\Application\Entity\User",
	 *   "property": "user"
	 * })
	 */
	protected $user;


	/**
	 * @var string
	 *
	 * @ORM\Column(name="action", type="string", length=255, nullable=false)
	 * @Annotation\Type("Zend\Form\Element\Text")
	 * @Annotation\Filter({"name":"StringTrim"})
	 * @Annotation\Validator({"name":"StringLength", "options":{"encoding":"UTF-8", "max":255}})
	 * @Annotation\Required(true)
	 * @Annotation\Attributes({
	 *   "type":"text",
	 *   "required":"false"
	 * })
	 */
	protected $action;



	public function getId ()
	{
		return $this -> id;

	}

	public function getTitle ()
	{
		return $this -> title;

	}

	public function getMessage ()
	{
		return $this -> message;

	}

	public function getType ()
	{
		return $this -> type;

	}


	public function getFixUrl ()
	{
		return $this -> fixUrl;

	}


	public function getAction ()
	{
		return $this -> action;

	}


	public function getUser ()
	{
		return $this -> user;

	}

	public function setUser ( \Application\Entity\User $user )
	{
		$this -> user = $user;
		return $this;

	}

	public function setTitle ( $title )
	{
		$this -> title = $title;

		return $this;

	}

	public function setMessage ( $message )
	{
		$this -> message = $message;

		return $this;
	}

	public function setType ( $type )
	{
		$this -> type = $type;

		return $this;
	}

	public function setFixUrl ( $fixUrl )
	{
		$this -> fixUrl = $fixUrl;

		return $this;
	}

	public function setAction ( $action )
	{
		$this -> action = $action;

		return $this;
	}

}