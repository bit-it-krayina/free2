<?php

namespace Application\Entity\Info;

use Doctrine\ORM\Mapping as ORM;
use Zend\Form\Annotation;
use Application\Entity\User;
use Application\Entity\Project;
/**
 * Role
 *
 * @ORM\Table(name="tag")
 * @ORM\Entity
 */
class Tag
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
     * @ORM\Column(name="tag", type="string", length=50, nullable=false)
     * @Annotation\Type("Zend\Form\Element\Text")
     * @Annotation\Filter({"name":"StripTags"})
     * @Annotation\Filter({"name":"StringTrim"})
     * @Annotation\Validator({"name":"StringLength", "options":{"encoding":"UTF-8", "max":50}})
     */
    protected $tag;


    /**
     * @ORM\ManyToMany(targetEntity="Application\Entity\Offer", mappedBy="tags")
     */
    protected $offers;

    /**
     * @ORM\ManyToMany(targetEntity="Application\Entity\User", mappedBy="tags")
     */
    protected $users;

	/**
     * @ORM\ManyToMany(targetEntity="Application\Entity\Project", mappedBy="tags")
     */
    protected $projects;

    public function __construct() {
        $this->users = new \Doctrine\Common\Collections\ArrayCollection();
        $this->projects = new \Doctrine\Common\Collections\ArrayCollection();
		$this->offers = new \Doctrine\Common\Collections\ArrayCollection();
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
