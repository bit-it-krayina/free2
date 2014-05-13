<?php

namespace Application\Entity;


use Doctrine\ORM\Mapping as ORM;

/**
 * An order data
 *
 * @ORM\Entity
 * @ORM\Table(name="yas_lesson_order")
 * @property string $customer_email
 * @property string $customer_name
 * @property string $order_description
 * @property datetime $created
 * @property int $state_id
 * @property int $teacher_id
 * @property int $id
 * 
 */
class Order
{
    const STATE_NEW = 1;
    const STATE_CLOSED = 2;
    const STATE_IN_PROGRESS = 3;
    const STATE_REJECTED = 4;
    
    
    
    /**
     * @ORM\Id
     * @ORM\Column(type="integer");
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     */
    protected $customer_name;

    /**
     * @ORM\Column(type="string")
     */
    protected $customer_email;

    /**
     * @ORM\Column(type="string")
     */
    protected $order_description;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $created;

    /**
     * @ORM\Column(type="integer")
     */
    protected $state_id;
    
    /**
     * @ORM\Column(type="integer")
     */
    protected $teacher_id;

    /**
     * Возвращает имя владельца заказа
     * @return string
     */
    function getCustomerName ()
    {
	return $this -> customer_name;
    }

    /**
     * Возвращает email владельца заказа
     * @return string
     */
    function getCustomerEmail ()
    {
	return $this -> customer_email;
    }

    /**
     * Возвращает описание
     * @return string
     */
    function getOrderDescription ()
    {
	return $this -> order_description;
    }

    /**
     * Возвращает описание
     * @return integer
     */
    function getStateId ()
    {
	return $this -> state_id;
    }

    /**
     * Возвращает дату отзыва
     * @return \DateTime
     */
    function getCreated ()
    {
	return $this -> created;
    }

    /**
     * Возвращает id учителя к которому записывается клиент
     * @return integer
     */
    function getTeacherId ()
    {
	return $this -> teacher_id;
    }

    
    function getId()
    {
	return $this->id;
    }
    
    /**
     * Magic setter to save protected properties.
     *
     * @param string $property
     * @param mixed $value
     */
    public function __set ( $property, $value )
    {
	$this -> $property = $value;
    }

    /**
     * Convert the object to an array.
     *
     * @return array
     */
    public function getArrayCopy ()
    {
	return get_object_vars ( $this );
    }

    /**
     * Задает имя заказчика
     * @param string $name
     * @return \Application\Entity\Order
     */
    public function setCustomerName ( $name )
    {
	$this -> customer_name = $name;
	return $this;
    }
    
    /**
     * Задает email заказчика
     * @param string $email
     * @return \Application\Entity\Order
     */
    public function setCustomerEmail ( $email )
    {
	$this -> customer_email = $email;
	return $this;
    }
    
    /**
     * Задает дату создания заказа
     * @param string $created
     * @return \Application\Entity\Order
     */
    public function setCreated ( $created )
    {
	$this -> created = \DateTime::createFromFormat('U', $created);
	return $this;
    }
    
    
    /**
     * Задает описание заказа
     * @param string $description
     * @return \Application\Entity\Order
     */
    public function setOrderDescription ( $description )
    {
	$this -> order_description = $description;
	return $this;
    }
    
    /**
     * Задает описание заказа
     * @param string $stateId
     * @return \Application\Entity\Order
     */
    public function setStateId ( $stateId )
    {
	$this -> state_id = $stateId;
	return $this;
    }
    
    /**
     * Задает id учителя к которому записывается клиент
     * @param string $teacherId
     * @return \Application\Entity\Order
     */
    public function setTeacherId ( $teacherId )
    {
	$this -> teacher_id = $teacherId;
	return $this;
    }

}