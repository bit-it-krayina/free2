<?php

namespace CsnUser\Entity\Info;

use Doctrine\ORM\Mapping as ORM;
use Zend\Form\Annotation;

/**
 * Role
 *
 * @ORM\Table(name="user_info_contact")
 * @ORM\Entity
 */
class Contact
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
     * @ORM\Column(name="email", type="string", length=60, nullable=true)
     * @Annotation\Type("Zend\Form\Element\Email")
     * @Annotation\Filter({"name":"StripTags"})
     * @Annotation\Filter({"name":"StringTrim"})
     * @Annotation\Validator({"name":"EmailAddress"})
     * @Annotation\Required(true)
     * @Annotation\Attributes({
     *   "type":"email",
     *   "required":"false"
     * })
     */
	protected $email;

	/**
     * @var string
     *
     * @ORM\Column(name="skype", type="string", length=60, nullable=true)
     * @Annotation\Type("Zend\Form\Element\Text")
     * @Annotation\Filter({"name":"StripTags"})
     * @Annotation\Filter({"name":"StringTrim"})
     * @Annotation\Validator({"name":"StringLength", "options":{"encoding":"UTF-8", "max":60}})
     */
    protected $skype;


    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=20, nullable=true)
     * @Annotation\Type("Zend\Form\Element\Text")
     * @Annotation\Filter({"name":"StripTags"})
     * @Annotation\Filter({"name":"StringTrim"})
     * @Annotation\Validator({"name":"StringLength", "options":{"encoding":"UTF-8", "max":20}})
     * @Annotation\Validator({"name":"Digits"})
     */
    private $phone;

	/**
     * @var string
     *
     * @ORM\Column(name="facebook_url", type="string", length=150, nullable=true)
     * @Annotation\Type("Zend\Form\Element\Text")
     * @Annotation\Filter({"name":"StringTrim"})
     * @Annotation\Validator({"name":"StringLength", "options":{"encoding":"UTF-8", "max":150}})
     */
    private $facebookUrl;

	/**
     * @var string
     *
     * @ORM\Column(name="twitter_url", type="string", length=150, nullable=true)
     * @Annotation\Type("Zend\Form\Element\Text")
     * @Annotation\Filter({"name":"StringTrim"})
     * @Annotation\Validator({"name":"StringLength", "options":{"encoding":"UTF-8", "max":150}})
     */
    private $twitterUrl;

	/**
     * @var string
     *
     * @ORM\Column(name="linkedin_url", type="string", length=150, nullable=true)
     * @Annotation\Type("Zend\Form\Element\Text")
     * @Annotation\Filter({"name":"StringTrim"})
     * @Annotation\Validator({"name":"StringLength", "options":{"encoding":"UTF-8", "max":150}})
     */
    private $linkedInUrl;





    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

	public function getEmail ()
	{
		return $this -> email;

	}

	public function setEmail ( $email )
	{
		$this -> email = $email;

		return $this;

	}

	public function getSkype ()
	{
		return $this -> skype;

	}

	public function setSkype ( $skype )
	{
		$this -> skype = $skype;

		return $this;

	}

	public function getPhone ()
	{
		return $this -> phone;

	}

	public function setPhone ( $phone )
	{
		$this -> phone = $phone;

		return $this;

	}

	public function getFacebookUrl ()
	{
		return $this -> facebookUrl;

	}

	public function setFacebookUrl ( $facebookUrl )
	{
		$this -> facebookUrl = $facebookUrl;

		return $this;

	}

	public function getTwitterUrl ()
	{
		return $this -> twitterUrl;

	}

	public function setTwitterUrl ( $twitterUrl )
	{
		$this -> twitterUrl = $twitterUrl;

		return $this;

	}

	public function getLinkedInUrl ()
	{
		return $this -> linkedInUrl;

	}

	public function setLinkedInUrl ( $linkedInUrl )
	{
		$this -> linkedInUrl = $linkedInUrl;

		return $this;

	}

}
