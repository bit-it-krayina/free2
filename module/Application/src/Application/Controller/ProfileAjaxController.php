<?php

namespace Application\Controller;


use Zend\Mvc\Controller\AbstractActionController;
use Application\Service\EntityManagerAwareInterface;
use Application\Service\EntityManagerAwareTrait;
use Application\Service\ControlUtils;

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

		return new JsonModel();



		 if(!$user = $this->identity()) {
            return $this->redirect()->toRoute($this->getOptions()->getLoginRedirectRoute());
        }


        $form = $this->getUserFormHelper()->createUserForm($user, 'EditProfile');
        $message = null;
        if($this->getRequest()->isPost()) {
            $form->setValidationGroup('firstName', 'lastName', 'csrf');
            $form->setData($this->getRequest()->getPost());
            if($form->isValid()) {
                $phone1 = $this->params()->fromPost('phone1');
                $phone2 = $this->params()->fromPost('phone2');
                $user->setPhone1($phone1);
                $user->setPhone2($phone2);

                $entityManager = $this->getEntityManager();
                $entityManager->persist($user);
                $entityManager->flush();
                $message =  $this->getTranslatorHelper()->translate('Your profile has been edited');
            }
        }

        return new ViewModel(array(
            'form' => $form,
            'securityQuestion' => $user->getQuestion()->getQuestion(),
            'message' => $message,
            'navMenu' => $this->getOptions()->getNavMenu()
        ));
	}
}
