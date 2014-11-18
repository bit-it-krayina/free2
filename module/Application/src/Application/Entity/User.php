<?php

/**
 * CsnUser - Coolcsn Zend Framework 2 User Module
 *
 */

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zend\Form\Annotation;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Application\Entity\Employment;
use Application\Entity\Info\UserPrivate;
use Application\Entity\Info\Tag;
use Application\Entity\Extras\MagicInterface;
use Application\Entity\Extras\MagicTrait;

/**
 * Doctrine ORM implementation of User entity
 *
 * @ORM\Table(name="user")
 * @ORM\Entity
 */
class User implements MagicInterface
{
	use MagicTrait;

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
	 * @ORM\Column(name="email", type="string", length=60, nullable=false, unique=true)
	 * @Annotation\Type("Zend\Form\Element\Email")
	 * @Annotation\Filter({"name":"StripTags"})
	 * @Annotation\Filter({"name":"StringTrim"})
	 * @Annotation\Validator({"name":"EmailAddress"})
	 * @Annotation\Required(true)
	 * @Annotation\Attributes({
	 *   "type":"email",
	 *   "required":"true"
	 * })
	 */
	protected $email;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="password", type="string", length=60, nullable=false)
	 * @Annotation\Type("Zend\Form\Element\Password")
	 * @Annotation\Filter({"name":"StripTags"})
	 * @Annotation\Filter({"name":"StringTrim"})
	 * @Annotation\Validator({"name":"StringLength", "options":{"encoding":"UTF-8", "min":6, "max":20}})
	 * @Annotation\Required(true)
	 * @Annotation\Attributes({
	 *   "type":"password",
	 *   "required":"true"
	 * })
	 */
	protected $password;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="username", type="string", length=30, nullable=false, unique=true)
	 * @Annotation\Type("Zend\Form\Element\Text")
	 * @Annotation\Filter({"name":"StripTags"})
	 * @Annotation\Filter({"name":"StringTrim"})
	 * @Annotation\Validator({"name":"StringLength", "options":{"encoding":"UTF-8", "min":6, "max":30}})
	 * @Annotation\Validator({"name":"Regex", "options":{"pattern":"/^[a-zA-Z][a-zA-Z0-9\_\-]+$/"}})
	 * @Annotation\Required(true)
	 * @Annotation\Attributes({
	 *   "type":"text",
	 *   "required":"true"
	 * })
	 */
	protected $username;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="first_name", type="string", length=40, nullable=true)
	 * @Annotation\Type("Zend\Form\Element\Text")
	 * @Annotation\Filter({"name":"StripTags"})
	 * @Annotation\Filter({"name":"StringTrim"})
	 * @Annotation\Validator({"name":"StringLength", "options":{"encoding":"UTF-8", "max":40}})
	 */
	protected $firstName;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="last_name", type="string", length=40, nullable=true)
	 * @Annotation\Type("Zend\Form\Element\Text")
	 * @Annotation\Filter({"name":"StripTags"})
	 * @Annotation\Filter({"name":"StringTrim"})
	 * @Annotation\Validator({"name":"StringLength", "options":{"encoding":"UTF-8", "max":40}})
	 */
	protected $lastName;


	/**
	 * @var \DateTime
	 *
	 * @ORM\Column(name="registration_date", type="datetime", nullable=false)
	 * @Annotation\Attributes({"type":"datetime","min":"2010-01-01T00:00:00Z","max":"2020-01-01T00:00:00Z","step":"1"})
	 * @Annotation\Options({"label":"Registration Date:", "format":"Y-m-d\TH:iP"})
	 */
	protected $registrationDate;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="registration_token", type="string", length=32, nullable=false)
	 */
	protected $registrationToken;


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
     * @ORM\Column(name="phone1", type="string", length=20, nullable=true)
     * @Annotation\Type("Zend\Form\Element\Number")
     * @Annotation\Filter({"name":"StripTags"})
     * @Annotation\Filter({"name":"StringTrim"})
     * @Annotation\Validator({"name":"StringLength", "options":{"encoding":"UTF-8", "max":20}})
     * @Annotation\Validator({"name":"Digits"})
     */
    private $phone1;

	/**
     * @var string
     *
     * @ORM\Column(name="phone2", type="string", length=20, nullable=true)
     * @Annotation\Type("Zend\Form\Element\Number")
     * @Annotation\Filter({"name":"StripTags"})
     * @Annotation\Filter({"name":"StringTrim"})
     * @Annotation\Validator({"name":"StringLength", "options":{"encoding":"UTF-8", "max":20}})
     * @Annotation\Validator({"name":"Digits"})
     */
    private $phone2;

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
	 * @var integer
	 *
	 * @ORM\Column(name="email_confirmed", type="integer", nullable=false)
	 */
	protected $emailConfirmed;



	/**
	 * @var Application\Entity\Role
	 *
	 * @ORM\ManyToOne(targetEntity="Application\Entity\Role")
	 * @ORM\JoinColumn(name="role_id", referencedColumnName="id", nullable=false)
	 * @Annotation\Type("DoctrineModule\Form\Element\ObjectSelect")
	 * @Annotation\Filter({"name":"StripTags"})
	 * @Annotation\Filter({"name":"StringTrim"})
	 * @Annotation\Validator({"name":"Digits"})
	 * @Annotation\Required(true)
	 * @Annotation\Options({
	 *   "required":"true",
	 *   "target_class":"Application\Entity\Role",
	 *   "property": "name"
	 * })
	 */
	protected $role;


	/**
	 * @var Application\Entity\State
	 *
	 * @ORM\ManyToOne(targetEntity="Application\Entity\State")
	 * @ORM\JoinColumn(name="state_id", referencedColumnName="id", nullable=false)
	 * @Annotation\Type("DoctrineModule\Form\Element\ObjectSelect")
	 * @Annotation\Filter({"name":"StripTags"})
	 * @Annotation\Filter({"name":"StringTrim"})
	 * @Annotation\Validator({"name":"Digits"})
	 * @Annotation\Required(true)
	 * @Annotation\Options({
	 *   "required":"true",
	 *   "target_class":"Application\Entity\State",
	 *   "property": "state"
	 * })
	 */
	protected $state;

	/**
	 * @var Application\Entity\Question
	 *
	 * @ORM\ManyToOne(targetEntity="Application\Entity\Question")
	 * @ORM\JoinColumn(name="question_id", referencedColumnName="id", nullable=true)
	 * @Annotation\Type("DoctrineModule\Form\Element\ObjectSelect")
	 * @Annotation\Filter({"name":"StripTags"})
	 * @Annotation\Filter({"name":"StringTrim"})
	 * @Annotation\Validator({"name":"Digits"})
	 * @Annotation\Required(true)
	 * @Annotation\Options({
	 *   "required":"true",
	 *   "empty_option": "Security Question",
	 *   "target_class":"Application\Entity\Question",
	 *   "property": "question"
	 * })
	 */
	protected $question;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="answer", type="string", length=100, nullable=true)
	 * @Annotation\Type("Zend\Form\Element\Text")
	 * @Annotation\Filter({"name":"StripTags"})
	 * @Annotation\Filter({"name":"StringTrim"})
	 * @Annotation\Filter({"name":"StringToLower", "options":{"encoding":"UTF-8"}})
	 * @Annotation\Validator({"name":"StringLength", "options":{"encoding":"UTF-8", "min":6, "max":100}})
	 * @Annotation\Validator({"name":"Alnum", "options":{"allowWhiteSpace":true}})
	 * @Annotation\Required(true)
	 * @Annotation\Options({
	 *   "required":"true",
	 *   "autocomplete":"off"
	 * })
	 */
	protected $answer;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="picture", type="string", length=255, nullable=true)
	 * @Annotation\Type("Zend\Form\Element\File")
	 */
	protected $picture;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="qualification", type="string", length=100, nullable=false)
	 * @Annotation\Type("Zend\Form\Element\Text")
	 * @Annotation\Filter({"name":"StripTags"})
	 * @Annotation\Filter({"name":"StringTrim"})
	 * @Annotation\Validator({"name":"StringLength", "options":{"encoding":"UTF-8", "max":100}})
	 */
	private $qualification = "unknown";


	/**
	 * @var Employment
	 *
	 * @ORM\ManyToOne(targetEntity="Application\Entity\Employment")
	 * @ORM\JoinColumn(name="employment_id", referencedColumnName="id", nullable=false)
	 * @Annotation\Type("DoctrineModule\Form\Element\ObjectSelect")
	 * @Annotation\Filter({"name":"StripTags"})
	 * @Annotation\Filter({"name":"StringTrim"})
	 * @Annotation\Validator({"name":"Digits"})
	 * @Annotation\Required(true)
	 * @Annotation\Options({
	 *   "required":"true",
	 *   "target_class":"Application\Entity\Employment",
	 *   "property": "employment"
	 * })
	 */
	protected $employment;



	/**
	 * @var Application\Entity\Info\UserPrivate
	 *
	 * @ORM\ManyToOne(targetEntity="\Application\Entity\Info\UserPrivate")
	 * @ORM\JoinColumn(name="private_info_id", referencedColumnName="id", nullable=true)
	 */
	protected $privateInfo;


	/**
	 * @ORM\ManyToMany(targetEntity="Application\Entity\Info\Tag", inversedBy="users")
	 * @ORM\JoinTable(name="user_tag",
	 *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
	 *      inverseJoinColumns={@ORM\JoinColumn(name="tag_id", referencedColumnName="id")}
	 *      )
	 */
	private $tags;

	/**
	 * @ORM\OneToMany(targetEntity="Application\Entity\Offer", mappedBy="user")
	 */
	private $offers;

	/**
	 * @ORM\OneToMany(targetEntity="Application\Entity\Notification", mappedBy="user")
	 */
	private $notifications;




	/**
	 * @var Application\Entity\WorkExperience
	 *
	 * @ORM\ManyToOne(targetEntity="Application\Entity\WorkExperience")
	 * @ORM\JoinColumn(name="work_experience_id", referencedColumnName="id", nullable=false)
	 * @Annotation\Type("DoctrineModule\Form\Element\ObjectSelect")
	 * @Annotation\Filter({"name":"StripTags"})
	 * @Annotation\Filter({"name":"StringTrim"})
	 * @Annotation\Validator({"name":"Digits"})
	 * @Annotation\Required(true)
	 * @Annotation\Options({
	 *   "required":"true",
	 *   "target_class":"Application\Entity\WorkExperience",
	 *   "property": "experience"
	 * })
	 */
	protected $workExperience;




	public function __construct ()
	{
		$this -> tags = new ArrayCollection();
		$this -> offers = new ArrayCollection();
		$this -> notifications = new ArrayCollection();

	}






	/**
	 * Get id
	 *
	 * @return integer
	 */
	public function getId ()
	{
		return $this -> id;

	}


	/**
	 * Set password
	 *
	 * @param  string $password
	 * @return User
	 */
	public function setPassword ( $password )
	{
		$this -> password = $password;

		return $this;

	}

	/**
	 * Get password
	 *
	 * @return string
	 */
	public function getPassword ()
	{
		return $this -> password;

	}

	/**
	 * Set email
	 *
	 * @param  string $email
	 * @return User
	 */
	public function setEmail ( $email )
	{
		$this -> email = $email;

		return $this;

	}

	/**
	 * Get email
	 *
	 * @return string
	 */
	public function getEmail ()
	{
		return $this -> email;

	}


	public function toArray()
	{
		return [
			'email' => $this->getEmail(),
			'password' => $this->getPassword(),
		];
	}



	/**
	 * Set username
	 *
	 * @param  string $username
	 * @return User
	 */
	public function setUsername ( $username )
	{
		$this -> username = $username;

		return $this;

	}

	/**
	 * Get username
	 *
	 * @return string
	 */
	public function getUsername ()
	{
		return $this -> username;

	}

	/**
	 * Set firstName
	 *
	 * @param  string $firstName
	 * @return User
	 */
	public function setFirstName ( $firstName )
	{
		$this -> firstName = $firstName;

		return $this;

	}

	/**
	 * Get firstName
	 *
	 * @return string
	 */
	public function getFirstName ()
	{
		return $this -> firstName;

	}

	/**
	 * Set lastName
	 *
	 * @param  string $lastName
	 * @return User
	 */
	public function setLastName ( $lastName )
	{
		$this -> lastName = $lastName;

		return $this;

	}

	/**
	 * Get lastName
	 *
	 * @return string
	 */
	public function getLastName ()
	{
		return $this -> lastName;

	}

	/**
	 * Set registrationDate
	 *
	 * @param  string $registrationDate
	 * @return User
	 */
	public function setRegistrationDate ( $registrationDate )
	{
		$this -> registrationDate = $registrationDate;

		return $this;

	}

	/**
	 * Get registrationDate
	 *
	 * @return string
	 */
	public function getRegistrationDate ()
	{
		return $this -> registrationDate;

	}

	/**
	 * Set registrationToken
	 *
	 * @param  string $registrationToken
	 * @return User
	 */
	public function setRegistrationToken ( $registrationToken )
	{
		$this -> registrationToken = $registrationToken;

		return $this;

	}

	/**
	 * Get registrationToken
	 *
	 * @return string
	 */
	public function getRegistrationToken ()
	{
		return $this -> registrationToken;

	}



	public function setSkype ( $skype )
	{
		$this -> skype = $skype;
		return $this;

	}

	public function getPhone1 ()
	{
		return $this -> phone1;

	}

	public function setPhone1 ( $phone1 )
	{
		$this -> phone1 = $phone1;
		return $this;

	}

	public function getPhone2 ()
	{
		return $this -> phone2;

	}

	public function setPhone2 ( $phone2 )
	{
		$this -> phone2 = $phone2;
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



		/**
	 * Set emailConfirmed
	 *
	 * @param  string $emailConfirmed
	 * @return User
	 */
	public function setEmailConfirmed ( $emailConfirmed )
	{
		$this -> emailConfirmed = $emailConfirmed;

		return $this;

	}

	/**
	 * Get emailConfirmed
	 *
	 * @return string
	 */
	public function getEmailConfirmed ()
	{
		return $this -> emailConfirmed;

	}


	/**
	 * Set role
	 *
	 * @param  Role $role
	 * @return User
	 */
	public function setRole ( $role )
	{
		$this -> role = $role;

		return $this;

	}

	/**
	 * Get role
	 *
	 * @return Role
	 */
	public function getRole ()
	{
		return $this -> role;

	}


	/**
	 * Set user state
	 *
	 * @param  boolean $state
	 * @return User
	 */
	public function setState ( $state )
	{
		$this -> state = $state;

		return $this;

	}

	/**
	 * Get user state
	 *
	 * @return boolean
	 */
	public function getState ()
	{
		return $this -> state;

	}


	/**
	 * Set question
	 *
	 * @param  Question $question
	 * @return User
	 */
	public function setQuestion ( $question )
	{
		$this -> question = $question;

		return $this;

	}

	/**
	 * Get question
	 *
	 * @return string
	 */
	public function getQuestion ()
	{
		return $this -> question;

	}

	/**
	 * Set answer
	 *
	 * @param  string $answer
	 * @return User
	 */
	public function setAnswer ( $answer )
	{
		$this -> answer = $answer;

		return $this;

	}

	/**
	 * Get answer
	 *
	 * @return string
	 */
	public function getAnswer ()
	{
		return $this -> answer;

	}

	/**
	 * Set picture
	 *
	 * @param  string $picture
	 * @return User
	 */
	public function setPicture ( $picture )
	{
		$this -> picture = $picture;

		return $this;

	}

	/**
	 * Get picture
	 *
	 * @return string
	 */
	public function getPicture ()
	{
		return $this -> picture;

	}


	/**
	 * Специальность/Квалификация
	 * @return string
	 */
	public function getQualification ()
	{
		return $this -> qualification;

	}

	/**
	 * Специальность/Квалификация
	 * @param string $qualification
	 * @return \Application\Entity\User
	 */
	public function setQualification ( $qualification )
	{
		$this -> qualification = $qualification;

		return $this;

	}

	/**
	 *
	 * @return Employment
	 */
	public function getEmployment ()
	{
		return $this -> employment;

	}

	/**
	 *
	 * @param Employment $employment
	 * @return \Application\Entity\User
	 */
	public function setEmployment ( $employment )
	{
		$this -> employment = $employment;

		return $this;

	}


	/**
	 *
	 * @return WorkExperience
	 */
	public function getWorkExperience ()
	{
		return $this -> workExperience;

	}

	public function setWorkExperience ( $workExperience )
	{
		$this -> workExperience = $workExperience;
		return $this;

	}


	/**
	 *
	 * @return UserPrivate
	 */
	public function getPrivateInfo ()
	{
		return $this -> privateInfo;

	}

	/**
	 *
	 * @param UserPrivate $privateInfo
	 * @return \Application\Entity\User
	 */
	public function setPrivateInfo ( UserPrivate $privateInfo )
	{
		$this -> privateInfo = $privateInfo;

		return $this;

	}


	public function getTags ()
	{
		return $this -> tags;

	}

	public function setTags ( $tags )
	{
		$this -> tags = $tags;

		return $this;

	}

	public function getOffers ()
	{
		return $this -> offers;

	}

	public function setOffers ( $offers )
	{
		$this -> offers = $offers;

		return $this;

	}

	public function getNotifications ()
	{
		return $this -> notifications;

	}

	public function setNotifications ( $notifications )
	{
		$this -> notifications = $notifications;

		return $this;

	}

}
