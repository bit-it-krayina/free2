<?php

namespace Application\Form;


use Zend\InputFilter\InputFilter;
use Zend\Validator\Identical;

/**
 * Description of LoginFilter
 *
 * @author mice
 */
class RegistrationFilter extends InputFilter
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
			'name' => 'retype',
			'required' => true,
			'validators' => array (
				array (
					'name' => 'Identical',
					'options' => array (
						'token' => 'password' //I have tried $_POST['password'], but it doesnt work either
					)
				)
			)
		) );
	}

	//put your code here
}

