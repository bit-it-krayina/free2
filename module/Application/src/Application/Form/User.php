<?php

namespace Application\Form;

use Zend\Form\Form;

/**
 * Description of Login
 *
 * @author mice
 */
class User extends Form
{

	public function __construct ( $name = 'user', $options = array ( ) )
	{
		parent::__construct ( $name, $options );

//		$this -> setInputFilter ( new UserFilter () );

		$this -> add (
				array (
					'name' => 'username',
					'attributes' => array (
						'type' => 'text',
						'class' => 'form-control',
						'placeholder' => 'Name:'
					),
					'options' => array (
						'label' => 'UserName:',
					)
				)
		);

		$this -> add (
				array (
					'name' => 'password',
					'attributes' => array (
						'type' => 'password',
						'class' => 'form-control',
						'placeholder' => 'Password:'
					),
					'options' => array (
						'label' => 'Password:'
					),
				)
		);

		$this -> add (
				array (
					'name' => 'firstname',
					'attributes' => array (
						'type' => 'text',
						'class' => 'form-control',
						'placeholder' => 'FirstName:'
					),
					'options' => array (
						'label' => 'FirstName:',
					)
				)
		);

		$this -> add (
				array (
					'name' => 'lastname',
					'attributes' => array (
						'type' => 'text',
						'class' => 'form-control',
						'placeholder' => 'LastName:'
					),
					'options' => array (
						'label' => 'LastName:',
					)
				)
		);

		$this -> add (
				array (
					'name' => 'phone1',
					'attributes' => array (
						'type' => 'text',
						'class' => 'form-control',
						'placeholder' => 'Phone:'
					),
					'options' => array (
						'label' => 'Phone:',
					)
				)
		);

		$this -> add (
				array (
					'name' => 'phone2',
					'attributes' => array (
						'type' => 'number',
						'class' => 'form-control',
						'placeholder' => 'Phone:'
					),
					'options' => array (
						'label' => 'Phone:',
						'class' => 'form-control',
					)
				)
		);


		$this -> add (
				array (
					'name' => 'skype',
					'attributes' => array (
						'type' => 'text',
						'placeholder' => 'Skype:',
						'class' => 'form-control',
					),
					'options' => array (
						'label' => 'Skype:',
					)
				)
		);

		$this -> add (
				array (
					'name' => 'facebook_url',
					'attributes' => array (
						'type' => 'text',
						'class' => 'form-control',
						'placeholder' => 'Facebook:'
					),
					'options' => array (
						'label' => 'Facebook:',
					)
				)
		);

		$this -> add (
				array (
					'name' => 'twitter_url',
					'attributes' => array (
						'type' => 'text',
						'class' => 'form-control',
						'placeholder' => 'Twitter:'
					),
					'options' => array (
						'label' => 'Twitter:',
					)
				)
		);

		$this -> add (
				array (
					'name' => 'linkedin_url',
					'attributes' => array (
						'type' => 'text',
						'class' => 'form-control',
						'placeholder' => 'LinkedIn:'
					),
					'options' => array (
						'label' => 'LinkedIn:',
					)
				)
		);

		$this -> add (
				array (
					'name' => 'submit',
					'attributes' => array (
						'type' => 'submit',
						'class' => 'form-control',
						'value' => 'Save'
					),
				)
		);
	}

}
