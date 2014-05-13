<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * A project data
 *
 * @ORM\Entity
 * @ORM\Table(name="projects")
 * @property string $title
 * @property string $description
 * @property string $functions
 * @property string $done
 * @property int $topic_id
 * @property int $id
 */
class Project
{

    
    /**
     * @ORM\Id
     * @ORM\Column(type="integer");
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     */
    protected $title;

    /**
     * @ORM\Column(type="string")
     */
    protected $description;

    /**
     * @ORM\Column(type="string")
     */
    protected $functions;
    
    /**
     * @ORM\Column(type="string")
     */
    protected $done;
	
	/**
     * @ORM\Column(type="integer")
     */
    protected $topic_id;
	

	    
    function getId()
    {
	return $this->id;
    }
    
    
    /**
     * Возвращает имя преподавателя
     * @return string
     */
    function getTitle()
    {
	return $this->title;
    }
    
    /**
     * Возвращает описание
     * @return string
     */
    function getDescription()
    {
	return $this->description;
    }
    
    /**
     * Возвращает 
     * @return string
     */
    function getFunctions()
    {
	return $this->functions;
    }
    
    /**
     * Возвращает то что сделано
     * @return string
     */
    function getDone()
    {
	return $this->done;
    }


    
    /**
     * Magic setter to save protected properties.
     *
     * @param string $property
     * @param mixed $value
     */
    public function __set($property, $value)
    {
	$this->$property = $value;
    }

    /**
     * Convert the object to an array.
     *
     * @return array
     */
    public function getArrayCopy()
    {
	return get_object_vars($this);
    }
    
    public function setTitle ( $title )
	{
		$this -> title = $title;
		return $this;
	}

	public function setDescription ( $description )
	{
		$this -> description = $description;
		return $this;
	}

	public function setFunctions ( $functions )
	{
		$this -> functions = $functions;
		return $this;
	}

	public function setDone ( $done )
	{
		$this -> done = $done;
		return $this;
	}


	public function getTopicId ()
	{
		return $this -> topic_id;
	}

	public function setTopicId ( $topic_id )
	{
		$this -> topic_id = $topic_id;
		return $this;
	}

}