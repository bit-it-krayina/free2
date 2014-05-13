<?php

namespace Application\Entity;


use Doctrine\ORM\Mapping as ORM;

/**
 * A user data
 *
 * @ORM\Entity
 * @ORM\Table(name="users")
 * @property string $username
 * @property string $password
 * @property int $id
 */
class User
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
	protected $username;

	/**
	 * @ORM\Column(type="string")
	 */
	protected $password;

	function getId ()
	{
		return $this -> id;
	}

	/**
	 * Возвращает имя 
	 * @return string
	 */
	function getUserName ()
	{
		return $this -> username;
	}

	function getPassword()
	{
		return $this -> password;
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

	
	public function setUserName ($userName)
	{
		$this -> username = $userName;
		return $this;
	}
	
	public function setPassword ($password)
	{
		$this -> password = md5($password);
		return $this;
	}
	
}