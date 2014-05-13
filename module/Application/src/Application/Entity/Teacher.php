<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * A teacher data
 *
 * @ORM\Entity
 * @ORM\Table(name="yas_teacher")
 * @property string $name
 * @property string $description
 * @property string $imageUrl
 * @property string $urlName
 * @property int $id
 */
class Teacher
{

    const DEFAULT_YASILDA_ID = 1;
    
    /**
     * @ORM\Id
     * @ORM\Column(type="integer");
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     */
    protected $name;

    /**
     * @ORM\Column(type="string")
     */
    protected $description;

    /**
     * @ORM\Column(type="string")
     */
    protected $imageUrl;
    
    /**
     * @ORM\Column(type="string")
     */
    protected $urlName;

    
    function getId()
    {
	return $this->id;
    }
    
    
    /**
     * Возвращает имя преподавателя
     * @return string
     */
    function getName()
    {
	return $this->name;
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
     * Возвращает url картинки
     * @return string
     */
    function getImageUrl()
    {
	return $this->imageUrl;
    }
    
    /**
     * Возвращает url страницы
     * @return string
     */
    function getUrlName()
    {
	return $this->urlName;
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
    
    

}