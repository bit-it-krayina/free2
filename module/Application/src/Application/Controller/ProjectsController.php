<?php

namespace Application\Controller;


use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Model\Contact;
use Application\Service\EntityManagerAwareInterface;
use Application\Service\EntityManagerAwareTrait;
use Application\Service\MenuTrait;
use Application\Service\ControlUtils;

class ProjectsController extends AbstractActionController implements EntityManagerAwareInterface
{

	use EntityManagerAwareTrait,
	 MenuTrait,
	 ControlUtils
			;
	public function listAction()
	{
		$projects = $this
						-> getEntityManager ()
						-> getRepository ( 'Application\Entity\Project' )
						-> findAll ();

		$view = new ViewModel ( array (
			'loginForm' => $this -> getLoginForm (),
			'identity' => $this -> getAuthenticationService() -> getIdentity(),
			'loggedUser' => $this -> getLoggedUser (),
			'projects' => $projects
		) );
		return $view;
	}
	
	public function parseAction()
	{
		$html = file_get_contents('http://davidwalsh.name/');
	}
}
