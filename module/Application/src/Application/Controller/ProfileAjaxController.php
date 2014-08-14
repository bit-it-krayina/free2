<?php

namespace Application\Controller;


use Zend\Mvc\Controller\AbstractActionController;
use Application\Service\EntityManagerAwareInterface;
use Application\Service\EntityManagerAwareTrait;
use Application\Service\ControlUtils;
use Application\Entity\User;
use Application\Entity\Info\UserPrivate;
use Application\Service\Notification\Service as NotificationService;

use Zend\View\Model\JsonModel;

class ProfileAjaxController extends AbstractActionController implements EntityManagerAwareInterface
{

    use EntityManagerAwareTrait, ControlUtils;


	public function editFieldAction()
	{
		if(!$user = $this->identity())
		{
			return $this->redirect()->toRoute($this->getOptions()->getLoginRedirectRoute());
		}

		$form = $this->getUserFormHelper()->createUserForm($user, 'EditProfile');

        $message = null;
        if($this->getRequest()->isPost()) {
            $form->setValidationGroup('csrf');
            $form->setData($this->getRequest()->getPost());

            if($form->isValid()) {
				$entityManager = $this->getEntityManager();

				foreach ($this->getRequest()->getPost() as $key => $value)
				{
					switch ($key) {
						case 'workExperience':
							$workExperience = $entityManager->find('Application\Entity\WorkExperience', $value);
							$user->$key = $workExperience;
							break;
						case 'location':
						case 'resume':
							$privateInfo = $user->getPrivateInfo();
							if ( empty ( $privateInfo ) ) {
								$privateInfo = new UserPrivate();
								$entityManager->persist($privateInfo);
								$entityManager->flush();
								$user->setPrivateInfo($privateInfo);
							}

							$privateInfo->$key = $value;
							break;
						case 'birthDay':
							$privateInfo = $user->getPrivateInfo();
							if ( empty ( $privateInfo ) ) {
								$privateInfo = new UserPrivate();
								$entityManager->persist($privateInfo);
								$entityManager->flush();
								$user->setPrivateInfo($privateInfo);
							}
							$privateInfo->setBirthDay(new \DateTime($value));
							break;
						default:
							$user->$key = $value;
							break;
					}

				}
                $entityManager->persist($user);
                $entityManager->flush();

				$notificationService = $this -> getServiceLocator () -> get('Application\Notification\Service');
				$notificationService->trigger(NotificationService::EVENT_USER_INFO_UPDATE, $user);

				$message =  $this->getTranslatorHelper()->translate('Your profile has been edited');
            }
        }

        return new JsonModel(array(
            'form' => $form,
            'message' => $message,
        ));
	}


	public function changeStatusAction()
	{
		if(!$user = $this->identity())
		{
			return $this->redirect()->toRoute($this->getOptions()->getLoginRedirectRoute());
		}

		$params = $this->getRequest()->getPost();
		$entityManager = $this->getEntityManager();
		$user -> setEmployment($entityManager->find('\Application\Entity\Employment',$params->status ));
		$entityManager->persist($user);
		$entityManager->flush();
		$employments = $entityManager
			-> getRepository ( 'Application\Entity\Employment' )
			-> findAll ();
		$viewHtml = $this->getServiceLocator ()
						->get ('ZfcTwigRenderer')
						->render ($this->createViewModel('application/bits/profile/status-dropdown', [
							'user' => $user,
							'employments' =>$employments
						]));
		return new JsonModel(array(
				'profileDropdownBlock' => $viewHtml,
        ));
	}


	public function getAvailableTagsAction()
	{

		return \Zend\Json\Json::encode(['mysql, sql, zf2, zf, zend framework']);
	}

	public function deletePhotoAction()
	{
		if(!$user = $this->identity())
		{
			return $this->redirect()->toRoute($this->getOptions()->getLoginRedirectRoute());
		}

		$params = $this->getRequest()->getPost();
		$entityManager = $this->getEntityManager();
		$user = $entityManager->find('\Application\Entity\User',$params->user );
		$user -> setPicture('');
		$entityManager->persist($user);
		$entityManager->flush();

		$viewHtml = $this->getServiceLocator ()
						->get ('ZfcTwigRenderer')
						->render ($this->createViewModel('appplication/bits/profile/photo', [
							'user_id' => $user->getId(),
							'picture' => $user->getPicture()
						]));

		return new JsonModel(array(
				'photo_block' => $viewHtml,
        ));
	}
}
