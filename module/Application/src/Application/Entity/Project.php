<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zend\Form\Annotation;

/**
 * A project data
 *
 * @ORM\Entity
 * @ORM\Table(name="projects")
 */
class Project
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
	 * @ORM\Column(name="header", type="string", length=255, nullable=false)
	 * @Annotation\Type("Zend\Form\Element\Textarea")
	 * @Annotation\Filter({"name":"StringTrim"})
	 * @Annotation\Validator({"name":"StringLength", "options":{"encoding":"UTF-8", "max":255}})
	 * @Annotation\Required(true)
	 * @Annotation\Attributes({
	 *   "type":"text",
	 *   "required":"true"
	 * })
	 */
	protected $header;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="description", type="string", length=1024, nullable=false)
	 * @Annotation\Type("Zend\Form\Element\Textarea")
	 * @Annotation\Filter({"name":"StripTags"})
	 * @Annotation\Filter({"name":"StringTrim"})
	 * @Annotation\Validator({"name":"StringLength", "options":{"encoding":"UTF-8", "max":1024}})
	 * @Annotation\Attributes({
	 *   "type":"text",
	 *   "required":"true"
	 * })
	 */
	protected $description;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="url", type="string", length=1024, nullable=false)
	 * @Annotation\Type("Zend\Form\Element\Textarea")
	 * @Annotation\Filter({"name":"StripTags"})
	 * @Annotation\Filter({"name":"StringTrim"})
	 * @Annotation\Validator({"name":"StringLength", "options":{"encoding":"UTF-8", "max":1024}})
	 * @Annotation\Attributes({
	 *   "type":"text",
	 *   "required":"true"
	 * })
	 */
	protected $url;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="outer_id", type="string", length=20, nullable=false)
	 * @Annotation\Type("Zend\Form\Element\Text")
	 * @Annotation\Filter({"name":"StripTags"})
	 * @Annotation\Filter({"name":"StringTrim"})
	 * @Annotation\Validator({"name":"StringLength", "options":{"encoding":"UTF-8", "max":20}})
	 * @Annotation\Validator({"name":"Digits"})
	 */
	private $outerId;

	public function getHeader ()
	{
		return $this -> header;

	}

	public function setHeader ( $header )
	{
		$this -> header = $header;

		return $this;

	}

	public function getDescription ()
	{
		return $this -> description;

	}

	public function setDescription ( $description )
	{
		$this -> description = $description;

		return $this;

	}

	public function getUrl ()
	{
		return $this -> url;

	}

	public function setUrl ( $url )
	{
		$this -> url = $url;

		return $this;

	}

	public function getOuterId ()
	{
		return $this -> outerId;

	}

	public function setOuterId ( $outerId )
	{
		$this -> outerId = $outerId;

		return $this;

	}

	public function getId ()
	{
		return $this -> id;

	}

}