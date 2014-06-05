<?php

namespace Application\Form;

use Zend\InputFilter\InputFilter;

/**
 * Description of LoginFilter
 *
 * @author mice
 */
class UserFilter extends InputFilter
{

	public function __construct ()
	{
		$this -> add ( array (
			'name' => 'username',
			'required' => true,
		) );
		$this -> add ( array (
			'name' => 'password',
			'required' => true,
		) );

		$this -> add ( array (
			'name' => 'role_id',
			'required' => true,
		) );
	}

	//put your code here
}

