<?php

namespace Application\Controller;


use Zend\Mvc\Controller\AbstractActionController;
use Application\Service\EntityManagerAwareInterface;
use Application\Service\EntityManagerAwareTrait;
use Application\Service\ControlUtils;
use Zend\Json\Json;
use CsnUser\Entity\Info\UserPrivate;

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
							$workExperience = $entityManager->find('CsnUser\Entity\WorkExperience', $value);
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
		$user -> setEmployment($entityManager->find('\CsnUser\Entity\Employment',$params->status ));
		$entityManager->persist($user);
		$entityManager->flush();
		$employments = $entityManager
			-> getRepository ( 'CsnUser\Entity\Employment' )
			-> findAll ();
		$viewHtml = $this->getServiceLocator ()
						->get ('ZfcTwigRenderer')
						->render ($this->createViewModel('csn-user/bits/profile/status-dropdown', [
							'user' => $user,
							'employments' =>$employments
						]));
		return new JsonModel(array(
				'profileDropdownBlock' => $viewHtml,
        ));
	}

	/**
	 * Вывод тегов в json.
	 *
	 * @return mixed
	 */
	public function getAvailableSkillsAction()
	{
        $entityManager = $this->getEntityManager();
        $tagsRepo = $entityManager->getRepository('\CsnUser\Entity\Info\Tag');
        $tags = $tagsRepo->findAll();

        /**
         * @var \CsnUser\Entity\Info\Tag $tag
         */
        $data = array();
        foreach ($tags as $tag) {
            $data[] = array(
                'id' => $tag->getId(),
                'text' => $tag->getTag()
            );
        }

		$response = $this->getResponse();
		$response->getHeaders()->addHeaderLine('Content-Type', 'application/json');

		return $response->setContent(Json::encode($data));
	}

	public function deletePhotoAction()
	{
		if(!$user = $this->identity())
		{
			return $this->redirect()->toRoute($this->getOptions()->getLoginRedirectRoute());
		}

		$params = $this->getRequest()->getPost();
		$entityManager = $this->getEntityManager();
		$user = $entityManager->find('\CsnUser\Entity\User',$params->user );
		$user -> setPicture('');
		$entityManager->persist($user);
		$entityManager->flush();

		$viewHtml = $this->getServiceLocator ()
						->get ('ZfcTwigRenderer')
						->render ($this->createViewModel('csn-user/bits/profile/photo', [
							'user_id' => $user->getId(),
							'picture' => $user->getPicture()
						]));

		return new JsonModel(array(
				'photo_block' => $viewHtml,
        ));
	}
}
