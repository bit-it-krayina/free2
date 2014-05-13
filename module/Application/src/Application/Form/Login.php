<?php

namespace Application\Form;

use Zend\Form\Form;

/**
 * Description of Login
 *
 * @author mice
 */
class Login extends Form
{

	public function __construct ( $name = null, $options = array ( ) )
	{
		parent::__construct ( 'login', $options );

		$this -> setAttribute ( 'action', '/login' );
		$this -> setAttribute ( 'method', 'post' );
		$this -> setInputFilter ( new LoginFilter () );

		$this -> add (
				array (
					'name' => 'username',
					'attributes' => array (
						'type' => 'text',
						'placeholder' => 'Name:'
					),
					'options' => array (
//						'label' => 'Name:',
					)
				)
		);

		$this -> add (
				array (
					'name' => 'password',
					'attributes' => array (
						'type' => 'password',
						'placeholder' => 'Password:'
					),
					'options' => array (
//						'label' => 'Password:'
					),
				)
		);

		$this -> add (
				array (
					'name' => 'submit',
					'attributes' => array (
						'type' => 'submit',
						'value' => 'Login'
					),
				)
		);
	}

}
