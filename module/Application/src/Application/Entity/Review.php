<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * A teacher data
 *
 * @ORM\Entity
 * @ORM\Table(name="yas_review")
 * @property string $reviewer
 * @property string $review_content
 * @property string $review_date
 * @property int $moderated
 * @property int $id
 */
class Review
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
    protected $reviewer;

    /**
     * @ORM\Column(type="string")
     */
    protected $review_content;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $review_date;

    /**
     * @ORM\Column(type="integer")
     */
    protected $moderated;

    
    
    
    function getId()
    {
	return $this->id;
    }
    
    
    /**
     * Возвращает имя владельца отзыва
     * @return string
     */
    function getReviewer()
    {
	return $this->reviewer;
    }
    
    /**
     * Возвращает описание
     * @return string
     */
    function getReviewContent()
    {
	return $this->review_content;
    }
    
    /**
     * Возвращает описание
     * @return integer
     */
    function getModerated()
    {
	return $this->moderated;
    }
    
    /**
     * Возвращает дату отзыва
     * @return \DateTime
     */
    function getReviewDate()
    {
	return $this->review_date;
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