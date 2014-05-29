<?php

namespace Application\Controller;


use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Model\Contact;
use Application\Service\EntityManagerAwareInterface;
use Application\Service\EntityManagerAwareTrait;
use Application\Service\MenuTrait;
use Application\Service\ControlUtils;
use Application\Service\Importer\Service as ImporterService;
use Application\Entity\Project;

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

	public function importAction()
	{
		/**
		 * @var ImporterService $service
		 */
		$service = $this->getServiceLocator()->get('importer');
		$projects = $service->getProjects();

		$entityManager = $this -> getEntityManager ();

		$projectsList = [];
		foreach ( $projects as  $object)
		{

			$project = $this
						-> getEntityManager ()
						-> getRepository ( 'Application\Entity\Project' )
						-> findOneBy (['outerId' => $object->id]);
			if (empty ($project))
			{
				$project = new Project();
				$project -> setHeader($object->header)
					->setDescription($object->description)
					->setOuterId($object->id)
					->setUrl($this->getServiceLocator()->get('config')['projectsApi']['url'] . 'topic/' . $object->id . '-' . $object->author->title );
				$projectsList[] = $project;


			}
			else
			{
				$project -> setHeader($object->header)
					->setDescription($object->description)
					->setOuterId($object->id)
					->setUrl($this->getServiceLocator()->get('config')['projectsApi']['url'] . 'topic/' . $object->id . '-' . $object->author->title );
			}

			$entityManager -> persist ( $project );
			$entityManager -> flush ();
		}
//		var_dump( count($projectsList));
		return $this->createViewModel('application/projects/import', array (
			'projects' => $projectsList
		));
	}
}
