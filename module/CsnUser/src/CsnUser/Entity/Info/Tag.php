<?php

namespace CsnUser\Entity\Info;

use Doctrine\ORM\Mapping as ORM;
use Zend\Form\Annotation;
use CsnUser\Entity\User;
/**
 * Role
 *
 * @ORM\Table(name="user_tag")
 * @ORM\Entity
 */
class Tag
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
     * @ORM\Column(name="tag", type="string", length=50, nullable=false)
     * @Annotation\Type("Zend\Form\Element\Text")
     * @Annotation\Filter({"name":"StripTags"})
     * @Annotation\Filter({"name":"StringTrim"})
     * @Annotation\Validator({"name":"StringLength", "options":{"encoding":"UTF-8", "max":50}})
     */
    protected $tag;


    /**
     * @ORM\ManyToMany(targetEntity="CsnUser\Entity\User", mappedBy="tags")
     */
    protected $users;

    public function __construct() {
        $this->users = new \Doctrine\Common\Collections\ArrayCollection();
    }

	public function getId ()
	{
		return $this -> id;

	}

	public function getTag ()
	{
		return $this -> tag;

	}

	public function setTag ( $tag )
	{
		$this -> tag = $tag;

		return $this;
	}


}