<?php

namespace Application\Model;

class Contact
{

    private $phones = [];
    private $name = '';
    private $email = '';
    private $address = '';
    private $skype = '';
    
    function __construct (array $params = [])
    {
	if ( isset ( $params['name'] ) )    {     $this -> name	    = $params['name']; }
	if ( isset ( $params['phones'] ) )  {     $this -> phones   = $params['phones']; }
	if ( isset ( $params['email'] ) )   {     $this -> email    = $params['email']; }
	if ( isset ( $params['address'] ) ) {     $this -> address  = $params['address']; }
	if ( isset ( $params['skype'] ) )   {     $this -> skype    = $params['skype']; }
    }
    
    function getName( )
    {
	return $this -> name;
    }
    
    function getEmail( )
    {
	return $this -> email;
    }
    
    function getAddress( )
    {
	return $this -> address;
    }
    
    function getSkype( )
    {
	return $this -> skype;
    }
    
    function getPhones( )
    {
	return $this -> phones;
    }

}