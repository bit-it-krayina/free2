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
use Zend\Db\Sql\Select;
use Zend\Paginator\Paginator;
use Zend\Paginator\Adapter\Iterator;

class ProjectsController extends AbstractActionController implements EntityManagerAwareInterface
{

	use EntityManagerAwareTrait,
	 MenuTrait,
	 ControlUtils
			;
	public function listAction()
	{
		$order_by = $this->params()->fromRoute('order_by') ?
                $this->params()->fromRoute('order_by') : 'id';
        $order = $this->params()->fromRoute('order') ?
                $this->params()->fromRoute('order') : Select::ORDER_ASCENDING;
		$page = $this->params()->fromRoute('page') ? (int) $this->params()->fromRoute('page') : 1;

		$itemsPerPage = 10;

		$projects = $this
						-> getEntityManager ()
						-> getRepository ( 'Application\Entity\Project' )
						-> findBy(array() , array($order_by => $order ));

		$paginator = new Paginator(new Iterator( new \ArrayIterator($projects)));
        $paginator->setCurrentPageNumber($page)
                ->setItemCountPerPage($itemsPerPage)
                ->setPageRange(5);


		$view = new ViewModel ( array (
			'loginForm' => $this -> getLoginForm (),
			'identity' => $this -> getAuthenticationService() -> getIdentity(),
			'loggedUser' => $this -> getLoggedUser (),
			'projects' => $projects,
			'order_by' => $order_by,
			'order' => $order,
			'page' => $page,
			'paginator' => $paginator,
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
		$this->redirect()->toRoute('projects', ['action' => 'list']);
		return $this->createViewModel('application/projects/import', array (
			'projects' => $projectsList
		));
	}
}
