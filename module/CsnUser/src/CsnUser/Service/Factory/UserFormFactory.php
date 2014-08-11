<?php

/**
 * CsnUser - Coolcsn Zend Framework 2 User Module
 *
 * @link https://github.com/coolcsn/CsnUser for the canonical source repository
 * @copyright Copyright (c) 2005-2013 LightSoft 2005 Ltd. Bulgaria
 * @license https://github.com/coolcsn/CsnUser/blob/master/LICENSE BSDLicense
 * @author Stoyan Cheresharov <stoyan@coolcsn.com>
 * @author Svetoslav Chonkov <svetoslav.chonkov@gmail.com>
 * @author Nikola Vasilev <niko7vasilev@gmail.com>
 * @author Stoyan Revov <st.revov@gmail.com>
 * @author Martin Briglia <martin@mgscreativa.com>
 */

namespace CsnUser\Service\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use DoctrineORMModule\Form\Annotation\AnnotationBuilder as DoctrineAnnotationBuilder;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use DoctrineModule\Validator\NoObjectExists as NoObjectExistsValidator;
use CsnUser\Entity\User;

class UserFormFactory implements FactoryInterface
{

	/**
	 * @var Zend\Form\Form
	 */
	private $form;

	/**
	 * @var ServiceLocatorInterface
	 */
	private $serviceLocator;

	/**
	 * @var ModuleOptions
	 */
	protected $options;

	/**
	 * @var Doctrine\ORM\EntityManager
	 */
	protected $entityManager;

	/**
	 * @var Zend\Mvc\I18n\Translator
	 */
	protected $translatorHelper;

	/**
	 * @var Zend\Mvc\I18n\Translator
	 */
	protected $url;

	public function createService ( ServiceLocatorInterface $serviceLocator )
	{
		$this -> serviceLocator = $serviceLocator;
		return $this;

	}

	/**
	 * Create admin user form
	 *
	 * Method to create the Doctrine ORM user form for edit/create users
	 *
	 * @return Zend\Form\Form
	 */
	public function createUserForm ( $userEntity, $formName = 'LogIn' )
	{
		$entityManager = $this -> getEntityManager ();
		$builder = new DoctrineAnnotationBuilder ( $entityManager );
		$this -> form = $builder -> createForm ( $userEntity );
		$this -> form -> setHydrator ( new DoctrineHydrator ( $entityManager ) );
		$this -> form -> setAttribute ( 'method', 'post' );

		$this -> addCommonFields ();
		$this -> addSubFields ();


		switch ( $formName )
		{
			case 'UploadForm' :

				$this -> form -> setAttributes ( array (
					'enctype' => "multipart/form-data",
					'action' => $this -> getUrlPlugin () -> fromRoute ( 'user-register', ['action' => 'edit-profile'] ),
//					'action' => $this -> getUrlPlugin () -> fromRoute ( 'testImage' ),
					'name' => 'upload-file',
				) );
				$this -> form -> add ( array (
					'name' => 'picture',
					'type' => 'Zend\Form\Element\File',
					'attributes' => array (
//					'onchange' => 'savePicture(this.form)'
					'onchange' => 'this.form.submit()'
						)
				) );
				$this -> form -> add ( array (
					'name' => 'MAX_FILE_SIZE',
					'type' => 'Zend\Form\Element\Hidden',
				) );

				$this->form->get('MAX_FILE_SIZE')->setValue(1024*500);

				$this -> form -> get ( 'submit' ) -> setAttributes ( array (
					'class' => 'btn btn-primary pull-right',
					'value' => $this -> getTranslatorHelper () -> translate ( 'Upload' )
				) );
				break;
			case 'SignUp':
				$this -> addSignUpFields ();
				$this -> addSignUpFilters ();
				$this -> form -> setAttributes ( array (
					'action' => $this -> getUrlPlugin () -> fromRoute ( 'user-register' ),
					'name' => 'register'
				) );

				$this -> form -> setAttributes ( array (
					'class' => 'form'
				) );

				$this -> form -> get ( 'email' ) -> setAttributes ( array (
					'class' => 'form-control input-lg',
					'placeholder' => $this -> getTranslatorHelper () -> translate ( 'Email' )
				) );

				$this -> form -> get ( 'password' ) -> setAttributes ( array (
					'class' => 'form-control input-lg',
					'placeholder' => $this -> getTranslatorHelper () -> translate ( 'Password' )
				) );

				$this -> form -> get ( 'password_verify' ) -> setAttributes ( array (
					'class' => 'form-control input-lg',
					'placeholder' => $this -> getTranslatorHelper () -> translate ( 'Confirm Password' )
				) );

				$this -> form -> get ( 'submit' ) -> setAttributes ( array (
					'class' => 'btn btn-add-color btn-reg',
					'value' => $this -> getTranslatorHelper () -> translate ( 'SIGN UP' )
				) );

				break;

			case 'EditProfile':

				$this -> form -> setAttributes ( array (
					'class' => 'form',
					'role' => 'form form-horizontal'
 				) );

				$this -> form -> get ( 'firstName' ) -> setAttributes ( array (
					'required' => 'true',
					'class' => 'form-control js-profile-form-field',
					'label' => $this -> getTranslatorHelper () -> translate ( 'First Name' ),
					'placeholder' => $this -> getTranslatorHelper () -> translate ( 'First Name' ),
				) );

				$this -> form -> get ( 'lastName' ) -> setAttributes ( array (
					'required' => 'false',
					'class' => 'form-control js-profile-form-field',
					'label' => $this -> getTranslatorHelper () -> translate ( 'Last Name' ),
					'placeholder' => $this -> getTranslatorHelper () -> translate ( 'Last Name' )
				) );

				$this -> form -> get ( 'qualification' ) -> setAttributes ( array (
					'required' => 'false',
					'class' => 'form-control js-profile-form-field',
					'label' => $this -> getTranslatorHelper () -> translate ( 'Qualification' ),
					'placeholder' => $this -> getTranslatorHelper () -> translate ( 'Qualification' )
				) );

				$this -> form -> get ( 'workExperience' ) -> setAttributes ( array (
					'required' => 'false',
					'class' => 'form-control js-profile-form-field',
					'label' => $this -> getTranslatorHelper () -> translate ( 'Work Experience' ),
					'placeholder' => $this -> getTranslatorHelper () -> translate ( 'Work Experience' )
				) );



				$this -> form -> get ( 'phone1' ) -> setAttributes ( array (
					'required' => 'false',
					'type' => 'tel',
					'class' => 'form-control js-profile-form-field',
					'label' => $this -> getTranslatorHelper () -> translate ( 'Phone' ),
				) );

				$this -> form -> get ( 'phone2' ) -> setAttributes ( array (
					'required' => 'false',
					'type' => 'tel',
					'class' => 'form-control js-profile-form-field',
					'label' => $this -> getTranslatorHelper () -> translate ( 'Extra phone' ),
				) );

				$this -> form -> get ( 'skype' ) -> setAttributes ( array (
					'required' => 'false',
					'class' => 'form-control js-profile-form-field',
					'label' => $this -> getTranslatorHelper () -> translate ( 'Skype' ),
				) );
				$this -> form -> get ( 'facebookUrl' ) -> setAttributes ( array (
					'required' => 'false',
					'class' => 'form-control js-profile-form-field',
					'label' => $this -> getTranslatorHelper () -> translate ( 'Facebook' ),
				) );
				$this -> form -> get ( 'twitterUrl' ) -> setAttributes ( array (
					'required' => 'false',
					'class' => 'form-control js-profile-form-field',
					'label' => $this -> getTranslatorHelper () -> translate ( 'Twitter' ),
				) );
				$this -> form -> get ( 'linkedInUrl' ) -> setAttributes ( array (
					'required' => 'false',
					'class' => 'form-control js-profile-form-field',
					'label' => $this -> getTranslatorHelper () -> translate ( 'Linkedin' ),
				) );


				$this -> form -> get ( 'submit' ) -> setAttributes ( array (
					'class' => 'btn btn-primary pull-right',
					'value' => $this -> getTranslatorHelper () -> translate ( 'Update Profile' )
				) );

				$this -> form -> setAttributes ( array (
					'action' => $this -> getUrlPlugin () -> fromRoute ( 'user-register', array ( 'action' => 'edit-profile' ) ),
					'name' => 'edit-profile'
				) );
				break;

			case 'ChangePassword':
				$this -> addChangePasswordFields ();
				$this -> addChangePasswordFilters ();

				$this -> form -> get ( 'submit' ) -> setAttributes ( array (
					'class' => 'btn btn btn-success btn-lg',
					'value' => $this -> getTranslatorHelper () -> translate ( 'Change Password' )
				) );
				$this -> form -> setAttributes ( array (
					'action' => $this -> getUrlPlugin () -> fromRoute ( 'user-register', array ( 'action' => 'change-password' ) ),
					'name' => 'change-password'
				) );
				break;

			case 'ResetPassword':

				$this -> addResetPasswordFields ();
				$this -> addResetPasswordFilters ();

				$this -> form -> setAttributes ( array (
					'class' => 'form'
				) );

				$this -> form -> get ( 'usernameOrEmail' ) -> setAttributes ( array (
					'class' => 'form-control input-lg',
					'placeholder' => $this -> getTranslatorHelper () -> translate ( 'Write Your Username or Email' )
				) );

				$this -> form -> get ( 'submit' ) -> setAttributes ( array (
					'class' => 'btn btn btn-success btn-lg',
					'value' => $this -> getTranslatorHelper () -> translate ( 'Send Reset Email' )
				) );


				$this -> form -> setAttributes ( array (
					'action' => $this -> getUrlPlugin () -> fromRoute ( 'user-register', array ( 'action' => 'reset-password' ) ),
					'name' => 'reset-password'
				) );
				break;

			case 'ChangeEmail':

				$this -> addChangeEmailFields ();
				$this -> addChangeEmailFilters ();

				$this -> form -> get ( 'submit' ) -> setAttributes ( array (
					'class' => 'btn btn btn-success btn-lg',
					'value' => $this -> getTranslatorHelper () -> translate ( 'Change Email' )
				) );
				$this -> form -> setAttributes ( array (
					'action' => $this -> getUrlPlugin () -> fromRoute ( 'user-register', array ( 'action' => 'change-email' ) ),
					'name' => 'change-email'
				) );
				break;

			case 'ChangeSecurityQuestion':

				$this -> form -> setAttributes ( array (
					'class' => 'form'
				) );

				$this -> form -> get ( 'question' ) -> setAttributes ( array (
					'class' => 'form-control input-lg',
					'value' => $this -> questionSelectedId
				) );

				$this -> form -> get ( 'submit' ) -> setAttributes ( array (
					'class' => 'btn btn btn-success btn-lg',
					'value' => $this -> getTranslatorHelper () -> translate ( 'Change Security Question' )
				) );
				$this -> form -> setAttributes ( array (
					'action' => $this -> getUrlPlugin () -> fromRoute ( 'user-register', array ( 'action' => 'change-security-question' ) ),
					'name' => 'change-security-question'
				) );
				break;

			case 'CreateUser':
				$this -> form -> setAttributes ( array (
					'class' => 'form'
				) );

				$this -> addCreateUserFields ();
				$this -> addCreateUserFilters ();


				$this -> form -> setAttributes ( array (
					'action' => $this -> getUrlPlugin () -> fromRoute ( 'user-admin', array ( 'action' => 'create-user' ) ),
					'name' => 'register'
				) );
				break;

			case 'EditUser':

				$this -> form -> setAttributes ( array (
					'class' => 'form'
				) );

				$this -> form -> get ( 'location' ) -> setAttributes ( array (
					'required' => 'false',
					'class' => 'form-control input-lg',
					'placeholder' => $this -> getTranslatorHelper () -> translate ( 'Location' )
				) );

				$this -> form -> get ( 'username' ) -> setAttributes ( array (
					'required' => 'true',
					'class' => 'form-control input-lg',
					'placeholder' => $this -> getTranslatorHelper () -> translate ( 'Username' )
				) );

				$this -> form -> get ( 'firstName' ) -> setAttributes ( array (
					'required' => 'false',
					'class' => 'form-control input-lg',
					'placeholder' => $this -> getTranslatorHelper () -> translate ( 'First Name' )
				) );

				$this -> form -> get ( 'lastName' ) -> setAttributes ( array (
					'required' => 'false',
					'class' => 'form-control input-lg',
					'placeholder' => $this -> getTranslatorHelper () -> translate ( 'Last Name' )
				) );

				$this -> form -> get ( 'email' ) -> setAttributes ( array (
					'required' => 'true',
					'class' => 'form-control input-lg',
					'placeholder' => $this -> getTranslatorHelper () -> translate ( 'Your Email' )
				) );


				$this -> form -> get ( 'state' ) -> setAttributes ( array (
					'required' => 'true',
					'class' => 'form-control input-lg',
				) );

				$this -> form -> get ( 'role' ) -> setAttributes ( array (
					'required' => 'true',
					'class' => 'form-control input-lg',
				) );

				$this -> form -> get ( 'question' ) -> setAttributes ( array (
					'required' => 'true',
					'class' => 'form-control input-lg',
				) );

				$this -> form -> get ( 'answer' ) -> setAttributes ( array (
					'required' => 'true',
					'class' => 'form-control input-lg',
					'placeholder' => $this -> getTranslatorHelper () -> translate ( 'Type Your Answer' )
				) );


				$this -> form -> get ( 'submit' ) -> setAttributes ( array (
					'class' => 'btn btn btn-success btn-lg',
					'value' => $this -> getTranslatorHelper () -> translate ( 'Edit User' )
				) );

				$this -> form -> setAttributes ( array (
					'name' => 'register'
				) );
				break;
			case 'login' :
				$this -> addLoginFields ();
				$this -> addLoginFilters ();
				$this -> form -> setAttributes ( array (
					'action' => $this -> getUrlPlugin () -> fromRoute ( 'user-index', array ( 'action' => 'login' ) ),
					'name' => 'login'
				) );

				$this -> form -> setAttributes ( array (
					'class' => 'form'
				) );

				$this -> form -> get ( 'usernameOrEmail' ) -> setAttributes ( array (
					'class' => 'form-control input-lg',
					'placeholder' => $this -> getTranslatorHelper () -> translate ( 'Username or Email' )
				) );

				$this -> form -> get ( 'password' ) -> setAttributes ( array (
					'class' => 'form-control input-lg',
					'placeholder' => $this -> getTranslatorHelper () -> translate ( 'Password' )
				) );

				$this -> form -> get ( 'rememberme' ) -> setAttributes ( array (
					'id' => 'check-save',
				) );

				$this -> form -> get ( 'submit' ) -> setAttributes ( array (
					'class' => 'btn btn-add-color btn-auth',
					'value' => $this -> getTranslatorHelper () -> translate ( 'LOG IN' )
				) );
				break;
			default:
				$this -> addLoginFields ();
				$this -> addLoginFilters ();
				$this -> form -> setAttributes ( array (
					'action' => $this -> getUrlPlugin () -> fromRoute ( 'user-index', array ( 'action' => 'login' ) ),
					'name' => 'login'
				) );
				break;
		}

		$this -> form -> bind ( $userEntity );

		return $this -> form;

	}

	/**
	 *
	 * Common Fields
	 *
	 */
	private function addCommonFields ()
	{
		$this -> form -> add ( array (
			'name' => 'csrf',
			'type' => 'Zend\Form\Element\Csrf',
			'options' => array (
				'csrf_options' => array (
					'timeout' => 600
				)
			)
		) );

//		$this -> form -> add ( array (
//			'name' => 'captcha',
//			'type' => 'Zend\Form\Element\Captcha',
//			'options' => array (
//				'captcha' => new \Zend\Captcha\Figlet ( array (
//					'wordLen' => $this -> getOptions () -> getCaptchaCharNum (),
//						) ),
//			),
//		) );

		$this -> form -> add ( array (
			'name' => 'submit',
			'type' => 'Zend\Form\Element\Submit',
			'attributes' => array (
				'type' => 'submit',
			),
		) );

	}

	/**
	 *
	 * Fields for User Log In
	 *
	 */
	private function addLoginFields ()
	{
		$this -> form -> add ( array (
			'name' => 'usernameOrEmail',
			'type' => 'Zend\Form\Element\Text',
			'attributes' => array (
				'type' => 'text',
			),
		) );

		$this -> form -> add ( array (
			'name' => 'rememberme',
			'type' => 'Zend\Form\Element\Checkbox',
			'options' => array (
				'label' => $this -> getTranslatorHelper () -> translate ( 'Remember me' ),
			),
		) );

	}



	/**
	 *
	 * Fields of embeded entities for User Edit Profile form
	 *
	 */
	private function addSubFields ()
	{

		$this -> form -> add ( array (
			'name' => 'birthDay',
			'type' => 'Zend\Form\Element\Date',
			'attributes' => array (
				'required' => false,
				'type' => 'date',
				'class' => 'form-control js-profile-form-field js-datepicker',
				'label' => $this -> getTranslatorHelper () -> translate ( 'Birth Day' ),
				'placeholder' => $this -> getTranslatorHelper () -> translate ( 'Birth Day' )
			),
		) );

		$this -> form -> add ( array (
			'name' => 'location',
			'type' => 'Zend\Form\Element\Text',
			'attributes' => array (
				'required' => false,
				'class' => 'form-control js-profile-form-field',
				'label' => $this -> getTranslatorHelper () -> translate ( 'Location' ),
				'placeholder' => $this -> getTranslatorHelper () -> translate ( 'Location' )
			),
		) );

		$this -> form -> add ( array (
			'name' => 'userTags',
			'type' => 'Zend\Form\Element\Text',
			'attributes' => array (
				'required' => false,
				'class' => 'form-control js-profile-form-field js-tagsinput',
				'label' => $this -> getTranslatorHelper () -> translate ( 'Professional skils' ),
//				'placeholder' => $this -> getTranslatorHelper () -> translate ( 'Professional skils' )
			),
		) );

		$this -> form -> add ( array (
			'name' => 'resume',
			'type' => 'Zend\Form\Element\Textarea',
			'attributes' => array (
				'required' => false,
				'rows' => 10,
				'colls' => 30,
				'class' => 'form-control js-profile-form-field',
				'label' => $this -> getTranslatorHelper () -> translate ( 'About me' ),
				'placeholder' => $this -> getTranslatorHelper () -> translate ( 'About me' )
			),
		) );




	}
	/**
	 *
	 * Fields for User Sign Up
	 *
	 */
	private function addSignUpFields ()
	{
		$this -> form -> add ( array (
			'name' => 'password_verify',
			'type' => 'Zend\Form\Element\Password',
			'attributes' => array (
				'required' => true,
				'type' => 'password',
			),
		) );

		$this -> form -> add ( array (
			'name' => 'login',
			'type' => 'Zend\Form\Element\Button',
			'attributes' => array (
				'class' => 'btn btn btn-warning btn-lg',
				'onclick' => 'window.location="' . $this -> getUrlPlugin () -> fromRoute ( 'user-index', array ( 'action' => 'login' ) ) . '"',
			),
			'options' => array (
				'label' => $this -> getTranslatorHelper () -> translate ( 'Sign In' ),
			)
		) );

	}

	/**
	 *
	 * Fields for User Change Password
	 *
	 */
	private function addChangePasswordFields ()
	{
		$this -> form -> add ( array (
			'name' => 'newPasswordVerify',
			'type' => 'Zend\Form\Element\Password',
			'attributes' => array (
				'required' => true,
				'type' => 'password',
				'class' => 'form-control input-lg',
				'placeholder' => $this -> getTranslatorHelper () -> translate ( 'Confirm New Password' )
			),
		) );

	}

	/**
	 *
	 * Fields for User Password Reset
	 *
	 */
	private function addResetPasswordFields ()
	{
		$this -> form -> add ( array (
			'name' => 'usernameOrEmail',
			'type' => 'Zend\Form\Element\Text',
			'attributes' => array (
				'type' => 'text',
				'required' => 'true',
			),
		) );

	}

	/**
	 *
	 * Fields for User Change Email
	 *
	 */
	private function addChangeEmailFields ()
	{

		$this -> form -> add ( array (
			'name' => 'newEmail',
			'type' => 'Zend\Form\Element\Email',
			'attributes' => array (
				'type' => 'email',
				'required' => 'true',
				'class' => 'form-control input-lg',
				'placeholder' => $this -> getTranslatorHelper () -> translate ( 'New Email' )
			),
		) );

		$this -> form -> add ( array (
			'name' => 'newEmailVerify',
			'type' => 'Zend\Form\Element\Email',
			'attributes' => array (
				'type' => 'email',
				'required' => 'true',
				'class' => 'form-control input-lg',
				'placeholder' => $this -> getTranslatorHelper () -> translate ( 'Confirm New Email' )
			),
		) );

	}

	/**
	 *
	 * Input fields for User Create
	 *
	 */
	private function addCreateUserFields ()
	{
		$this -> form -> add ( array (
			'name' => 'password_verify',
			'type' => 'Zend\Form\Element\Password',
			'attributes' => array (
				'required' => true,
				'type' => 'password',
			),
		) );



		$this -> form -> get ( 'username' ) -> setAttributes ( array (
			'required' => 'true',
			'class' => 'form-control input-lg',
			'placeholder' => $this -> getTranslatorHelper () -> translate ( 'Username' )
		) );

		$this -> form -> get ( 'firstName' ) -> setAttributes ( array (
			'required' => 'false',
			'class' => 'form-control input-lg',
			'placeholder' => $this -> getTranslatorHelper () -> translate ( 'First Name' )
		) );

		$this -> form -> get ( 'lastName' ) -> setAttributes ( array (
			'required' => 'false',
			'class' => 'form-control input-lg',
			'placeholder' => $this -> getTranslatorHelper () -> translate ( 'Last Name' )
		) );

		$this -> form -> get ( 'location' ) -> setAttributes ( array (
			'required' => 'false',
			'class' => 'form-control input-lg',
			'placeholder' => $this -> getTranslatorHelper () -> translate ( 'Location' )
		) );

		$this -> form -> get ( 'email' ) -> setAttributes ( array (
			'required' => 'true',
			'class' => 'form-control input-lg',
			'placeholder' => $this -> getTranslatorHelper () -> translate ( 'Your Email' )
		) );

		$this -> form -> get ( 'password' ) -> setAttributes ( array (
			'class' => 'form-control input-lg',
			'placeholder' => $this -> getTranslatorHelper () -> translate ( 'Password' )
		) );

		$this -> form -> get ( 'password_verify' ) -> setAttributes ( array (
			'class' => 'form-control input-lg',
			'placeholder' => $this -> getTranslatorHelper () -> translate ( 'Confirm Password' )
		) );


		$this -> form -> get ( 'role' ) -> setAttributes ( array (
			'required' => 'true',
			'class' => 'form-control input-lg',
		) );

		$this -> form -> get ( 'state' ) -> setAttributes ( array (
			'required' => 'true',
			'class' => 'form-control input-lg',
		) );

		$this -> form -> get ( 'question' ) -> setAttributes ( array (
			'required' => 'true',
			'class' => 'form-control input-lg',
		) );

		$this -> form -> get ( 'answer' ) -> setAttributes ( array (
			'required' => 'true',
			'class' => 'form-control input-lg',
			'placeholder' => $this -> getTranslatorHelper () -> translate ( 'Type Your Answer' )
		) );

		$this -> form -> get ( 'submit' ) -> setAttributes ( array (
			'class' => 'btn btn btn-success btn-lg',
			'value' => $this -> getTranslatorHelper () -> translate ( 'Create User' )
		) );

	}

	/**
	 *
	 * Input filters for User Log In
	 *
	 */
	private function addLoginFilters ()
	{
		$this -> form -> getInputFilter () -> add ( $this -> form -> getInputFilter () -> getFactory () -> createInput ( array (
			'name' => 'usernameOrEmail',
			'required' => true,
			'filters' => array (
				array ( 'name' => 'StripTags' ),
				array ( 'name' => 'StringTrim' ),
			),
		) ) );

		$this -> form -> getInputFilter () -> add ( $this -> form -> getInputFilter () -> getFactory () -> createInput ( array (
			'name' => 'rememberme',
			'required' => true,
			'filters' => array (
				array ( 'name' => 'StripTags' ),
				array ( 'name' => 'StringTrim' ),
			),
			'validators' => array (
				array (
					'name' => 'InArray',
					'options' => array (
						'haystack' => array ( '0', '1' ),
					),
				),
			)
		) ) );

	}

	/**
	 *
	 * Input filters for User SignUp
	 *
	 */
	private function addSignUpFilters ()
	{
		$entityManager = $this -> getEntityManager ();
		$this -> form -> getInputFilter () -> get ( 'username' ) -> getValidatorChain () -> attach (
		new NoObjectExistsValidator ( array (
			'object_repository' => $entityManager -> getRepository ( 'CsnUser\Entity\User' ),
			'fields' => array ( 'username' ),
			'messages' => array (
				'objectFound' => $this -> getTranslatorHelper () -> translate ( 'This username is already taken' ),
			),
		) )
		);

		$this -> form -> getInputFilter () -> get ( 'email' ) -> getValidatorChain () -> attach (
		new NoObjectExistsValidator ( array (
			'object_repository' => $entityManager -> getRepository ( 'CsnUser\Entity\User' ),
			'fields' => array ( 'email' ),
			'messages' => array (
				'objectFound' => $this -> getTranslatorHelper () -> translate ( 'An user with this email already exists' ),
			),
		) )
		);

	}

	/**
	 *
	 * Input filters for User Change password
	 *
	 */
	private function addChangePasswordFilters ()
	{
		$this -> form -> getInputFilter () -> add ( $this -> form -> getInputFilter () -> getFactory () -> createInput ( array (
			'name' => 'newPasswordVerify',
			'filters' => array (
				array ( 'name' => 'StripTags' ),
				array ( 'name' => 'StringTrim' ),
			),
			'validators' => array (
				array (
					'name' => 'StringLength',
					'options' => array (
						'encoding' => 'UTF-8',
						'min' => 6,
						'max' => 20,
					),
				),
				array (
					'name' => 'Identical',
					'options' => array (
						'token' => 'password',
					),
				),
			)
		) ) );

	}

	/**
	 *
	 * Input filters for User Reset Password
	 *
	 */
	private function addResetPasswordFilters ()
	{
		$this -> form -> getInputFilter () -> add ( $this -> form -> getInputFilter () -> getFactory () -> createInput ( array (
			'name' => 'usernameOrEmail',
			'required' => true,
			'filters' => array (
				array ( 'name' => 'StripTags' ),
				array ( 'name' => 'StringTrim' ),
			),
		) ) );

	}

	/**
	 *
	 * Input filters for User Change email
	 *
	 */
	private function addChangeEmailFilters ()
	{
		$this -> form -> getInputFilter () -> add ( $this -> form -> getInputFilter () -> getFactory () -> createInput ( array (
			'name' => 'newEmail',
			'required' => true,
			'filters' => array (
				array ( 'name' => 'StripTags' ),
				array ( 'name' => 'StringTrim' ),
			),
			'validators' => array (
				array (
					'name' => 'EmailAddress'
				),
				array (
					'name' => 'DoctrineModule\Validator\NoObjectExists',
					'options' => array (
						'object_repository' => $this -> getEntityManager () -> getRepository ( 'CsnUser\Entity\User' ),
						'fields' => array ( 'email' ),
						'messages' => array (
							'objectFound' => $this -> getTranslatorHelper () -> translate ( 'An user with this email already exists' ),
						),
					),
				),
			),
		) ) );

		$this -> form -> getInputFilter () -> add ( $this -> form -> getInputFilter () -> getFactory () -> createInput ( array (
			'name' => 'newEmailVerify',
			'required' => true,
			'filters' => array (
				array ( 'name' => 'StripTags' ),
				array ( 'name' => 'StringTrim' ),
			),
			'validators' => array (
				array (
					'name' => 'EmailAddress'
				),
				array (
					'name' => 'Identical',
					'options' => array (
						'token' => 'newEmail',
					),
				),
			),
		) ) );

	}

	/**
	 *
	 * Input filters for User Create
	 *
	 */
	private function addCreateUserFilters ()
	{
		$this -> form -> getInputFilter () -> add ( $this -> form -> getInputFilter () -> getFactory () -> createInput ( array (
			'name' => 'password_verify',
			'filters' => array (
				array ( 'name' => 'StripTags' ),
				array ( 'name' => 'StringTrim' ),
			),
			'validators' => array (
				array (
					'name' => 'StringLength',
					'options' => array (
						'encoding' => 'UTF-8',
						'min' => 6,
						'max' => 20,
					),
				),
				array (
					'name' => 'Identical',
					'options' => array (
						'token' => 'password',
					),
				),
			)
		) ) );

	}

	/**
	 * get options
	 *
	 * @return ModuleOptions
	 */
	private function getOptions ()
	{
		if ( null === $this -> options )
		{
			$this -> options = $this -> serviceLocator -> get ( 'csnuser_module_options' );
		}

		return $this -> options;

	}

	/**
	 * get entityManager
	 *
	 * @return Doctrine\ORM\EntityManager
	 */
	private function getEntityManager ()
	{
		if ( null === $this -> entityManager )
		{
			$this -> entityManager = $this -> serviceLocator -> get ( 'doctrine.entitymanager.orm_default' );
		}

		return $this -> entityManager;

	}

	/**
	 * get translatorHelper
	 *
	 * @return  Zend\Mvc\I18n\Translator
	 */
	private function getTranslatorHelper ()
	{
		if ( null === $this -> translatorHelper )
		{
			$this -> translatorHelper = $this -> serviceLocator -> get ( 'MvcTranslator' );
		}

		return $this -> translatorHelper;

	}

	/**
	 * get urlPlugin
	 *
	 * @return  Zend\Mvc\Controller\Plugin\Url
	 */
	private function getUrlPlugin ()
	{
		if ( null === $this -> url )
		{
			$this -> url = $this -> serviceLocator -> get ( 'ControllerPluginManager' ) -> get ( 'url' );
		}

		return $this -> url;

	}

}
