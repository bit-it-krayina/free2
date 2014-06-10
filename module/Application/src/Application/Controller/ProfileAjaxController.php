<?php

namespace Application\Controller;


use Zend\Mvc\Controller\AbstractActionController;
use Application\Service\EntityManagerAwareInterface;
use Application\Service\EntityManagerAwareTrait;
use Application\Service\ControlUtils;
use CsnUser\Entity\User;

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
							$privateInfo->$key = $value;
							break;
						case 'birthDay':
							$privateInfo = $user->getPrivateInfo();
							$privateInfo->setBirthDay(new \DateTime($value));
							break;
						default:
							$user->$key = $value;
							break;
					}

				}

//                $phone1 = $this->params()->fromPost('phone1');
//                $phone2 = $this->params()->fromPost('phone2');
//                $user->setPhone1($phone1);
//                $user->setPhone2($phone2);


                $entityManager->persist($user);
                $entityManager->flush();
                $message =  $this->getTranslatorHelper()->translate('Your profile has been edited');
            }
        }

        return new JsonModel(array(
			'valid' => $form->isValid(),
			'post' => $this->getRequest()->getPost(),
            'form' => $form,
            'message' => $message,
        ));
	}


	public function getAvailableTagsAction()
	{
		
		return \Zend\Json\Json::encode(['mysql, sql, zf2, zf, zend framework']);
	}
}
