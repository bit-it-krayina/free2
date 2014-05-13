<?php

namespace Application\Form;

use Zend\Form\Form;

/**
 * Description of Login
 *
 * @author mice
 */
class Registration extends Form
{

	public function __construct ( $name = null, $options = array ( ) )
	{
		parent::__construct ( 'registration', $options );

		$this -> setAttribute ( 'action', '/registration' );
		$this -> setAttribute ( 'method', 'post' );
		$this -> setInputFilter ( new RegistrationFilter () );

		$this -> add (
				array (
					'name' => 'username',
					'attributes' => array (
						'type' => 'text',
					),
					'options' => array (
						'label' => 'Name:',
					)
				)
		);

		$this -> add (
				array (
					'name' => 'password',
					'attributes' => array (
						'type' => 'password',
					),
					'options' => array (
						'label' => 'Password:'
					),
				)
		);
		
		$this -> add (
				array (
					'name' => 'retype',
					'attributes' => array (
						'type' => 'password',
					),
					'options' => array (
						'label' => 'Re-type:'
					),
				)
		);

		$this -> add (
				array (
					'name' => 'submit',
					'attributes' => array (
						'type' => 'submit',
						'value' => 'Send'
					),
				)
		);
	}

}
