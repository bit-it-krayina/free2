<?php
/**
 * CsnUser - Coolcsn Zend Framework 2 User Module
 *
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Mail\Message;
use Zend\Validator\Identical as IdenticalValidator;

use Zend\Validator\File\Size;
use Application\Options\ModuleOptions;
use Application\Service\UserService as UserCredentialsService;
use Application\Service\ControlUtils;
use Application\Service\EntityManagerAwareInterface;
use Application\Service\EntityManagerAwareTrait;
use Application\Entity\Info\UserPrivate;
use Application\Entity\User;

/**
 * Registration controller
 */
class RegistrationController extends AbstractActionController implements EntityManagerAwareInterface
{

	use ControlUtils, EntityManagerAwareTrait;



    /**
     * Register Index Action
     *
     * Displays user registration form using Doctrine ORM and Zend annotations
     *
     * @return Zend\View\Model\ViewModel
     */
    public function indexAction()
    {
        if($this->identity()) {
            return $this->redirect()->toRoute($this->getOptions()->getLoginRedirectRoute());
        }

        $user = new User();
        $form = $this->getUserFormHelper()->createUserForm($user, 'SignUp');
        if($this->getRequest()->isPost()) {
            $form->setValidationGroup('email','password', 'password_verify', 'csrf');
            $form->setData($this->getRequest()->getPost());
            if($form->isValid()) {
                $entityManager = $this->getEntityManager();
                $user->setState($entityManager->find('Application\Entity\State', 1));
                $user->setUsername($user->getEmail());
                $user->setRole($entityManager->find('Application\Entity\Role', 2));
				$user->setEmployment( $entityManager->find('Application\Entity\Employment', 1));
				$user->setWorkExperience($entityManager->find('Application\Entity\WorkExperience', 1));
                $user->setEmailConfirmed(0);
                $user->setRegistrationDate(new \DateTime());
                $user->setRegistrationToken(md5(uniqid(mt_rand(), true)));
                $user->setPassword(UserCredentialsService::encryptPassword($user->getPassword()));


    		    try {
    		        $fullLink = $this->getBaseUrl() . $this->url()->fromRoute('user-register', array('action' => 'confirm-email', 'id' => $user->getRegistrationToken()));
    		        $this->sendEmail(
    		            $user->getEmail(),
    		            $this->getTranslatorHelper()->translate('Please, confirm your registration!'),
    		            sprintf($this->getTranslatorHelper()->translate('Please, click the link to confirm your registration => %s'), $fullLink)
    		        );
    		        $entityManager->persist($user);
                    $entityManager->flush();

					/**
					 * Добавим нотификацию для юзера
					 */
					$notificationService = $this -> getServiceLocator () -> get('Application\Notification\Service');

					$url = $this->url()->fromRoute('user-register', ['action'=>'edit-profile']);
					$notificationService->addNotification($user, 'registration', 'Your Profile is Empty', 'Please, fill profile', $url);

                    $viewModel = new ViewModel(array(
                        'email' => $user->getEmail(),
                    ));
					$this->logger()->info('User Registered '. $user->getEmail(), ['project_id' => 0]);
                    $viewModel->setTemplate('application/registration/registration-success');
                    return $viewModel;
    		    } catch (\Exception $e) {
                    return $this->getServiceLocator()->get('csnuser_error_view')->createErrorView(
    				    $this->getTranslatorHelper()->translate('Something went wrong when trying to send activation email! Please, try again later.'),
    					$e,
    					$this->getOptions()->getDisplayExceptions(),
    					$this->getOptions()->getNavMenu()
    				);
    			}
            }
        }

        $viewModel = new ViewModel(array(
            'registrationForm' => $form,
        ));
        $viewModel->setTemplate('application/registration/registration');
        return $viewModel;
    }

    /**
     * Edit Profile Action
     *
     * Displays user edit profile form
     *
     * @return Zend\View\Model\ViewModel
     */
    public function editProfileAction()
    {
        if(!$user = $this->identity()) {
            return $this->redirect()->toRoute($this->getOptions()->getLoginRedirectRoute());
        }

        $form = $this->getUserFormHelper()->createUserForm($user, 'EditProfile');

		if ($user->getPrivateInfo()) {
			$form->setData(['birthDay' => $user->getPrivateInfo()->getBirthDay(),
				'location' => $user->getPrivateInfo()->getLocation(),
				'resume' => $user->getPrivateInfo()->getResume()]);
		}
		$tags = array_map(function ( $tag ) { return $tag->getTag();}, $user->getTags()->toArray());
		$form->setData(['userTags' => implode(',', $tags)]);

        $message = null;
		$error = array();
		$uploadForm = $this->getUserFormHelper()->createUserForm($user, 'UploadForm');
        if($this->getRequest()->isPost())
		{
			$post = array_merge_recursive(
				$this->getRequest()->getPost()->toArray(),
				$this->getRequest()->getFiles()->toArray()
			);
			$uploadForm->setValidationGroup( 'csrf');
            $uploadForm->setData($post);
            if($uploadForm->isValid()) {
				$size = new Size(array('max'=>1024*500)); //max bytes filesize

				$adapter = new \Zend\File\Transfer\Adapter\Http();
				//validator can be more than one...
				$File = $this->params()->fromFiles('picture');
				$adapter->setValidators(array($size), $File['name']);

				if (!$adapter->isValid()){
					$dataError = $adapter->getMessages();

					foreach($dataError as $key=>$row)
					{
						$error[] = $row;
					}
					$uploadForm->setMessages(array('picture'=>$error ));
				} else {
					;
					$adapter->setDestination($this->getServiceLocator()->get('config')['profile_photos_dir']);
					if ($adapter->receive($File['name'])) {
						$entityManager = $this->getEntityManager();
						$user->setPicture('/profile_photos/' .$File['name'] );
						$entityManager->persist($user);
						$entityManager->flush();
					}
				}
			}
		}

        return new ViewModel(array(
            'form' => $form,
			'user' => $user,
			'uploadForm' => $uploadForm,
            'message' => $error,
            'navMenu' => $this->getOptions()->getNavMenu()
        ));
    }

    /**
     * Change Email Action
     *
     * Displays user change password form
     *
     * @return Zend\View\Model\ViewModel
     */
    public function changePasswordAction()
    {
        if(!$user = $this->identity()) {
            return $this->redirect()->toRoute($this->getOptions()->getLoginRedirectRoute());
        }

        $form = $this->getUserFormHelper()->createUserForm($user, 'ChangePassword');
        $message = null;
        if($this->getRequest()->isPost()) {
            $currentAnswer = $user->getAnswer();
            $form->setValidationGroup('password', 'newPasswordVerify', 'answer', 'csrf');
            $form->setData($this->getRequest()->getPost());
            if($form->isValid()) {
                $data = $form->getData();
                $identicalValidator = new IdenticalValidator(array('token' => $currentAnswer));
                if($identicalValidator->isValid($data->getAnswer())) {
                    $user->setPassword(UserCredentialsService::encryptPassword($this->params()->fromPost('password')));
                    $entityManager = $this->getEntityManager();
                    $entityManager->persist($user);
                    $entityManager->flush();

                    $viewModel = new ViewModel(array('navMenu' => $this->getOptions()->getNavMenu()));
                    $viewModel->setTemplate('application/registration/change-password-success');
                    return $viewModel;
                } else {
                    $message = $this->getTranslatorHelper()->translate('Your answer is wrong. Please provide the correct answer.');
                }
            }
        }

        return new ViewModel(array('form' => $form, 'navMenu' => $this->getOptions()->getNavMenu(), 'message' => $message, 'question' => $user->getQuestion()->getQuestion()));
    }

    /**
     * Reset Password Action
     *
     * Send email reset link to user
     *
     * @return Zend\View\Model\ViewModel
     */
    public function resetPasswordAction()
    {
        if($user = $this->identity()) {
            return $this->redirect()->toRoute($this->getOptions()->getLoginRedirectRoute());
        }

        $user = new User;
        $form = $this->getUserFormHelper()->createUserForm($user, 'ResetPassword');
        $message = null;
        if($this->getRequest()->isPost()) {
            $form->setValidationGroup('csrf');
            $form->setData($this->getRequest()->getPost());
            if($form->isValid()) {
                $data = $form->getData();
                $usernameOrEmail = $this->params()->fromPost('usernameOrEmail');
                $entityManager = $this->getEntityManager();
                $user = $entityManager->createQuery("SELECT u FROM Application\Entity\User u WHERE u.email = '$usernameOrEmail' OR u.username = '$usernameOrEmail'")->getResult(\Doctrine\ORM\Query::HYDRATE_OBJECT);
                $user = $user[0];

                if(isset($user)) {
                    try {
                        $user->setRegistrationToken(md5(uniqid(mt_rand(), true)));
                        $fullLink = $this->getBaseUrl() . $this->url()->fromRoute('user-register', array( 'action' => 'confirm-email-change-password', 'id' => $user->getRegistrationToken()));
                        $this->sendEmail(
                                $user->getEmail(),
                                $this->getTranslatorHelper()->translate('Please, confirm your request to change password!'),
                                sprintf($this->getTranslatorHelper()->translate('Hi, %s. Please, follow this link %s to confirm your request to change password.'), $user->getUsername(), $fullLink)
                        );
                        $entityManager->persist($user);
                        $entityManager->flush();

                        $viewModel = new ViewModel(array(
                            'email' => $user->getEmail(),
                            'navMenu' => $this->getOptions()->getNavMenu()
                        ));

                        $viewModel->setTemplate('application/registration/password-change-success');
                        return $viewModel;
                    } catch (\Exception $e) {
                        return $this->getServiceLocator()->get('csnuser_error_view')->createErrorView(
                                $this->getTranslatorHelper()->translate('Something went wrong when trying to send activation email! Please, try again later.'),
                                $e,
                                $this->getOptions()->getDisplayExceptions(),
                                $this->getOptions()->getNavMenu()
                        );
                    }
                } else {
                    $message = 'The username or email is not valid!';
                }
            }
        }

        return new ViewModel(array('form' => $form, 'navMenu' => $this->getOptions()->getNavMenu(), 'message' => $message));
    }

    /**
     * Change Email Action
     *
     * Displays user change email form
     *
     * @return Zend\View\Model\ViewModel
     */
    public function changeEmailAction()
    {
        if(!$user = $this->identity()) {
            return $this->redirect()->toRoute($this->getOptions()->getLoginRedirectRoute());
        }

        $form = $this->getUserFormHelper()->createUserForm($user, 'ChangeEmail');
        $message = null;
        if($this->getRequest()->isPost()) {
            $currentPassword = $user->getPassword();
            $form->setValidationGroup('password', 'newEmail', 'newEmailVerify', 'csrf');
            $form->setData($this->getRequest()->getPost());
            if($form->isValid()) {
                $data = $form->getData();
                $user->setPassword($currentPassword);
                if(UserCredentialsService::verifyHashedPassword($user, $this->params()->fromPost('password'))) {
                    $newMail = $this->params()->fromPost('newEmail');
                    $email = $user->setEmail($newMail);
                    $entityManager = $this->getEntityManager();
                    $entityManager->persist($user);
                    $entityManager->flush();

                    $viewModel = new ViewModel(array(
                        'email' => $newMail,
                        'navMenu' => $this->getOptions()->getNavMenu()
                    ));
                    $viewModel->setTemplate('application/registration/change-email-success');
                    return $viewModel;
                } else {
                    $message = $this->getTranslatorHelper()->translate('Your current password is not correct.');
                }
            }
        }

        return new ViewModel(array('form' => $form, 'navMenu' => $this->getOptions()->getNavMenu(), 'message' => $message));
    }

    /**
     * Change Security Question
     *
     * Displays user change security question form
     *
     * @return Zend\View\Model\ViewModel
     */
    public function changeSecurityQuestionAction()
    {
        if(!$user = $this->identity()) {
          return $this->redirect()->toRoute($this->getOptions()->getLoginRedirectRoute());
        }

        $form = $this->getUserFormHelper()->createUserForm($user, 'ChangeSecurityQuestion');
        $message = null;
        if($this->getRequest()->isPost()) {
          $currentPassword = $user->getPassword();
          $form->setValidationGroup('password', 'question', 'answer', 'csrf');
          $form->setData($this->getRequest()->getPost());
          if($form->isValid()) {
            $data = $form->getData();
            $user->setPassword($currentPassword);

            if(UserCredentialsService::verifyHashedPassword($user, $this->params()->fromPost('password'))) {
              $entityManager = $this->getEntityManager();
              $entityManager->persist($user);
              $entityManager->flush();

              $viewModel = new ViewModel(array('navMenu' => $this->getOptions()->getNavMenu()));
              $viewModel->setTemplate('application/registration/change-security-question-success');
              return $viewModel;
            } else {
              $message = $this->getTranslatorHelper()->translate('Your password is wrong. Please provide the correct password.');
            }
          }
        }

        return new ViewModel(array('form' => $form, 'navMenu' => $this->getOptions()->getNavMenu(), 'message' => $message, 'questionSelectedId' => $user->getQuestion()->getId()));
    }

    /**
     * Confirm Email Action
     *
     * Checks for email validation through given token
     *
     * @return Zend\View\Model\ViewModel
     */
    public function confirmEmailAction()
    {
        $token = $this->params()->fromRoute('id');
        try {
            $entityManager = $this->getEntityManager();
            if($token !== '' && $user = $entityManager->getRepository('Application\Entity\User')->findOneBy(array('registrationToken' => $token))) {
                $user->setRegistrationToken(md5(uniqid(mt_rand(), true)));
                $user->setState($entityManager->find('Application\Entity\State', 2));
                $user->setEmailConfirmed(1);
                $entityManager->persist($user);
                $entityManager->flush();

                $viewModel = new ViewModel(array(
                    'navMenu' => $this->getOptions()->getNavMenu(),
                ));
                $viewModel->setTemplate('application/registration/confirm-email-success');
                return $viewModel;
            } else {
                return $this->redirect()->toRoute('index', array('action' => 'login'));
            }
        } catch (\Exception $e) {
            return $this->getServiceLocator()->get('csnuser_error_view')->createErrorView(
                $this->getTranslatorHelper()->translate('Something went wrong during the activation of your account! Please, try again later.'),
                $e,
                $this->getOptions()->getDisplayExceptions(),
                $this->getOptions()->getNavMenu()
            );
        }
    }

    /**
     * Confirm Email Change Action
     *
     * Confirms password change through given token
     *
     * @return Zend\View\Model\ViewModel
     */
    public function confirmEmailChangePasswordAction()
    {
      $token = $this->params()->fromRoute('id');
      try {
        $entityManager = $this->getEntityManager();
        if($token !== '' && $user = $entityManager->getRepository('Application\Entity\User')->findOneBy(array('registrationToken' => $token))) {
          $user->setRegistrationToken(md5(uniqid(mt_rand(), true)));
          $password = $this->generatePassword();
          $user->setPassword(UserCredentialsService::encryptPassword($password));
          $email = $user->getEmail();
          $fullLink = $this->getBaseUrl() . $this->url()->fromRoute('index', array( 'action' => 'login'));
          $this->sendEmail(
              $user->getEmail(),
              'Your password has been changed!',
              sprintf($this->getTranslatorHelper()->translate('Hello again %s. Your new password is: %s. Please, follow this link %s to log in with your new password.'), $user->getUsername(), $password, $fullLink)

          );
          $entityManager->persist($user);
          $entityManager->flush();

          $viewModel = new ViewModel(array(
              'email' => $email,
              'navMenu' => $this->getOptions()->getNavMenu()
          ));
          return $viewModel;
        } else {
          return $this->redirect()->toRoute('index');
        }
      } catch (\Exception $e) {
        return $this->getServiceLocator()->get('csnuser_error_view')->createErrorView(
            $this->getTranslatorHelper()->translate('An error occured during the confirmation of your password change! Please, try again later.'),
            $e,
            $this->getOptions()->getDisplayExceptions(),
            $this->getOptions()->getNavMenu()
        );
      }
    }

    /**
     * Generate Password
     *
     * Generates random password
     *
     * @return String
     */
    private function generatePassword($l = 8, $c = 0, $n = 0, $s = 0)
    {
        $count = $c + $n + $s;
        $out = '';
        if(!is_int($l) || !is_int($c) || !is_int($n) || !is_int($s)) {
            trigger_error('Argument(s) not an integer', E_USER_WARNING);
            return false;
        } else if($l < 0 || $l > 20 || $c < 0 || $n < 0 || $s < 0) {
            trigger_error('Argument(s) out of range', E_USER_WARNING);
            return false;
        } else if($c > $l) {
            trigger_error('Number of password capitals required exceeds password length', E_USER_WARNING);
            return false;
        } else if($n > $l) {
            trigger_error('Number of password numerals exceeds password length', E_USER_WARNING);
            return false;
        } else if($s > $l) {
            trigger_error('Number of password capitals exceeds password length', E_USER_WARNING);
            return false;
        } else if($count > $l) {
            trigger_error('Number of password special characters exceeds specified password length', E_USER_WARNING);
            return false;
        }

        $chars = "abcdefghijklmnopqrstuvwxyz";
        $caps = strtoupper($chars);
        $nums = "0123456789";
        $syms = "!@#$%^&*()-+?";

        for ($i = 0; $i < $l; $i++) {
            $out .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }

        if($count) {
            $tmp1 = str_split($out);
            $tmp2 = array();

            for ($i = 0; $i < $c; $i++) {
                array_push($tmp2, substr($caps, mt_rand(0, strlen($caps) - 1), 1));
            }

            for ($i = 0; $i < $n; $i++) {
                array_push($tmp2, substr($nums, mt_rand(0, strlen($nums) - 1), 1));
            }

            for ($i = 0; $i < $s; $i++) {
                array_push($tmp2, substr($syms, mt_rand(0, strlen($syms) - 1), 1));
            }

            $tmp1 = array_slice($tmp1, 0, $l - $count);
            $tmp1 = array_merge($tmp1, $tmp2);
            shuffle($tmp1);
            $out = implode('', $tmp1);
        }

        return $out;
    }

    /**
     * Send Email
     *
     * Sends plain text emails
     *
     */
    private function sendEmail($to = '', $subject = '', $messageText = '')
    {
        $transport = $this->getServiceLocator()->get('mail.transport');
        $message = new Message();

        $message->addTo($to)
                ->addFrom($this->getOptions()->getSenderEmailAdress())
                ->setSubject($subject)
                ->setBody($messageText);

        $transport->send($message);
    }

    /**
     * Get Base Url
     *
     * Get Base App Url
     *
     */
    private function getBaseUrl() {
        $uri = $this->getRequest()->getUri();
        return sprintf('%s://%s', $uri->getScheme(), $uri->getHost());
    }


}
