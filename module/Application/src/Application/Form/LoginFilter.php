<?php

namespace Application\Form;

use Zend\InputFilter\InputFilter;

/**
 * Description of LoginFilter
 *
 * @author mice
 */
class LoginFilter extends InputFilter
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
	}

	//put your code here
}

