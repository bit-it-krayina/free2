<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Session\SessionManager;
use Zend\Session\Config\StandardConfig;

use Application\Service\EntityManagerAwareInterface;
use Application\Service\EntityManagerAwareTrait;
use Application\Service\MenuTrait;
use Application\Service\ControlUtils;
use Zend\Form\Form;

use Application\Entity\User;
use Application\Options\ModuleOptions;
use Zend\Cache\StorageFactory;


class IndexController extends AbstractActionController implements EntityManagerAwareInterface
{

	use EntityManagerAwareTrait,
	 MenuTrait,
	 ControlUtils
			;


	public function indexAction ()
	{
		ini_set('display_errors', 1); error_reporting(E_ALL & ~E_NOTICE);

		if ($user = $this->identity()) {
            $this->createViewModel('application/index/index');
        }

        $user = new User;
        $form = $this->getUserFormHelper()->createUserForm($user, 'login');
        $registrationForm = $this->getUserFormHelper()->createUserForm($user, 'SignUp');
        $messages = null;

        return $this->createViewModel('application/index/index',
			array(
				'loginForm'	=> $form,
				'lastProjects' => $this -> getLastProjects(),
				'registrationForm'	=> $registrationForm,
			));

	}


	public function notificationsAction()
	{
		if ( !$user = $this -> identity () )
		{
			return $this -> redirect () -> toRoute ( $this -> getOptions () -> getLoginRedirectRoute () );
		}

		$notificationService = $this -> getServiceLocator () -> get('Application\Notification\Service');

		return $this->createViewModel('application/index/notifications', array(
			'user' => $user,
		));

	}

	public function profileAction ()
	{
		if ( !$user = $this -> identity () )
		{
			return $this -> redirect () -> toRoute ( $this -> getOptions () -> getLoginRedirectRoute () );
		}


		$redis = $this->getServiceLocator () -> get('Cache\Redis');

		if ($redis->hasItem ('user'))
		{
			$value = $redis->getItem ('user');
			error_log(
	print_r(
		array(
			'1',
			$value

	), true));
		} else {
			$redis->setItem('user', 1111111);
			$value = $redis->getItem ('user');
			error_log(
	print_r(
		array(
			'2',
			$value,

	), true));
		}

		$form = $this -> getUserFormHelper () -> createUserForm ( $user, 'EditProfile' );
		$email = $user -> getEmail ();
		$username = $user -> getUsername ();
		$message = null;
		if ( $this -> getRequest () -> isPost () )
		{
			$currentFirstName = $user -> getFirstName ();
			$currentLastName = $user -> getLastName ();
			$form -> setValidationGroup ( 'firstName', 'lastName', 'csrf', 'location' );
			$form -> setData ( $this -> getRequest () -> getPost () );
			if ( $form -> isValid () )
			{
				$firstName = $this -> params () -> fromPost ( 'firstName' );
				$lastName = $this -> params () -> fromPost ( 'lastName' );
				$user -> setFirstName ( $firstName );
				$user -> setLastName ( $lastName );
				$entityManager = $this -> getEntityManager ();
				$entityManager -> persist ( $user );
				$entityManager -> flush ();
				$message = $this -> getTranslatorHelper () -> translate ( 'Your profile has been edited' );
			}
		}


		$employments = $this
						-> getEntityManager ()
						-> getRepository ( 'Application\Entity\Employment' )
						-> findAll ();

		return $this->createViewModel('application/index/profile', array(
			'user' => $user,
			'employments' => $employments,
		));


	}


	/**
     * Log out action
     *
     * The method destroys session for a logged user
     *
     * @return redirect to specific action
     */
    public function logoutAction()
    {
        $auth = $this->getServiceLocator()->get('Zend\Authentication\AuthenticationService');
        if ($auth->hasIdentity()) {
            $auth->clearIdentity();
            $sessionManager = new SessionManager();
            $sessionManager->forgetMe();
        }

        return $this->redirect()->toRoute('home');
    }


	/**
     * Log in action
     *
     * The method uses Doctrine Entity Manager to authenticate the input data
     *
     * @return Zend\View\Model\ViewModel|array login form|array messages|array navigation menu
     */
    public function loginAction()
    {
        if ($user = $this->identity()) {
            return $this->redirect()->toRoute('index', ['action'=>'profile']);
        }

        $user = new User;
        $form = $this->getUserFormHelper()->createUserForm($user, 'login');
        $messages = [];
        if ($this->getRequest()->isPost()) {
            $form->setValidationGroup('usernameOrEmail', 'password', 'rememberme', 'csrf');
            $form->setData($this->getRequest()->getPost());
            if ($form->isValid()) {
                $data = $form->getData();
                $authService = $this->getServiceLocator()->get('Zend\Authentication\AuthenticationService');
                $adapter = $authService->getAdapter();
                $usernameOrEmail = $this->params()->fromPost('usernameOrEmail');

                try {
                    $user = $this->getEntityManager()->createQuery("SELECT u FROM Application\Entity\User u WHERE u.email = '$usernameOrEmail' OR u.username = '$usernameOrEmail'")->getResult(\Doctrine\ORM\Query::HYDRATE_OBJECT);
                    $user = $user[0];

                    if(!isset($user)) {
                        $message = 'The username or email is not valid!';
                        return new ViewModel(array(
                            'error' => $this->getTranslatorHelper()->translate('Your authentication credentials are not valid'),
                            'loginForm'	=> $form,
                            'messages' => [$message]
                        ));
                    }

                    if($user->getState()->getId() < 2) {
                        $message = $this->getTranslatorHelper()->translate('Your username is disabled. Please contact an administrator.');
                        return new ViewModel(array(
                            'error' => $this->getTranslatorHelper()->translate('Your authentication credentials are not valid'),
                            'loginForm'	=> $form,
                            'messages' => [$message]
                        ));
                    }

                    $adapter->setIdentityValue($user->getUsername());
                    $adapter->setCredentialValue($this->params()->fromPost('password'));

                    $authResult = $authService->authenticate();
                    if ($authResult->isValid()) {
                        $identity = $authResult->getIdentity();
                        $authService->getStorage()->write($identity);

                        if ($this->params()->fromPost('rememberme')) {
                            $time = 1209600; // 14 days (1209600/3600 = 336 hours => 336/24 = 14 days)
                            $sessionManager = new SessionManager();
                            $sessionManager->rememberMe($time);
                        }

                        return $this->redirect()->toRoute('index', ['action'=>'profile']);
                    }

					$messages = array_merge($messages, $authResult->getMessages());
                } catch (\Exception $e) {
                    return $this->getServiceLocator()->get('csnuser_error_view')->createErrorView(
                        $this->getTranslatorHelper()->translate('Something went wrong during login! Please, try again later.'),
                        $e,
                        $this->getOptions()->getDisplayExceptions(),
                        $this->getOptions()->getNavMenu()
                    );
                }
            }
        }
        return new ViewModel(array(
            'error' => '',
            'loginForm'	=> $form,
            'messages' => $messages,
            'navMenu' => $this->getOptions()->getNavMenu()
        ));
    }




















	/**
	 * Возможно не актуально больше
	 */

	public function facebookAction()
	{

	}

	public function thanksAction ()
	{
		$result = $this -> params () -> fromRoute ( 'result' );
		$orderId = $this -> params () -> fromRoute ( 'order_id' );
		$order = false;

		if ( !empty ( $orderId ) )
		{
			$order = $this
					-> getEntityManager ()
					-> getRepository ( 'Application\Entity\Order' )
					-> find ( $orderId );
//	error_log (
//		var_export ( array ( $order ), true )
//	);
		}

		return new ViewModel (
				array (
			'result' => $result,
			'order_id' => $orderId,
			'order' => $order,
		) );
	}

	public function testImageAction ()
	{
		if(!$user = $this->identity()) {
            return $this->redirect()->toRoute($this->getOptions()->getLoginRedirectRoute());
        }

//		$file = new \Zend\Form\Element\File('picture');
//		$send = new \Zend\Form\Element\Submit('submit');
//		$hid = new \Zend\Form\Element\Hidden('MAX_FILE_SIZE');
//		$hid->setValue(30000);
//
//		$send->setValue('Send');
//
//		$form = new Form('upload-file');
//		$form->setAttributes ( array (
//					'action' => '/test-image',
//					'name' => 'upload-file',
//					'enctype' => "multipart/form-data",
//				) );
//		$form->add($file)->add($send) -> add($hid);

		$form = $this->getUserFormHelper()->createUserForm($user, 'UploadForm');
		if ( $this -> getRequest () -> isPost () )
		{
			$post = array_merge_recursive (
				$this -> getRequest () -> getPost () -> toArray (),
				$this -> getRequest () -> getFiles () -> toArray ()
			);
			$form->setData($this -> getRequest () -> getPost ());
			print_r($post);
			if ( $form -> isValid () )
			{
				$data = $form -> getData ();

			}
		}

		return new ViewModel(array(
			'uploadForm' => $form,
        ));

	}

}
