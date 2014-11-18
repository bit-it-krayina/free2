<?php

namespace Application\Controller\REST;

use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;
use Application\Service\EntityManagerAwareInterface;
use Application\Service\EntityManagerAwareTrait;
use Application\Entity\Project;

class ProjectsController extends AbstractRestfulController implements EntityManagerAwareInterface
{

	use EntityManagerAwareTrait	;
	
	public function getList()
    {
		$projects = array_map( function ($p) {
				$p = new \Application\Presenter\Project($p);
				return $p->__toArray();
			}, $this
				-> getEntityManager ()
				-> getRepository ( 'Application\Entity\Project' )
				-> findAll()
		);
		return $this->createView($projects);
    }
 
    public function get($id)
    {
		$project = $this-> getEntityManager ()
			-> getRepository ( 'Application\Entity\Project' )
			-> find($id);
		if ( empty( $project) ) 
		{
			return $this->createView(['error' => 'Project with id = '. $id . ' not exists.']);
		}
		$projectPresenter = new \Application\Presenter\Project($project);
		return $this->createView($projectPresenter->__toArray());
    }
 
    public function create($data)
    {
        $project = new Project();
		$project -> setHeader($data['header'])
			->setDescription($data['description'])
			->setOuterId($data['id'])
			->setUrl($data['url'] );
		$entityManager = $this -> getEntityManager ();
		$entityManager -> persist ( $project );
		$entityManager -> flush ();
		
		return $this->get($project->getId());
    }
 
    public function update($id, $data)
    {
		$project = $this-> getEntityManager ()
			-> getRepository ( 'Application\Entity\Project' )
			-> find($id);
		
		if ( empty( $project) )
		{
			return $this->createView(['error' => 'Project with id = '. $id . ' not exists.']);
		}
		
		$project -> setHeader($data['header'])
			->setDescription($data['description'])
			->setOuterId($data['outer_id'])
			->setUrl($data['url'] );
		$entityManager = $this -> getEntityManager ();
		$entityManager -> persist ( $project );
		$entityManager -> flush ();
		return $this->get($project->getId());
    }
 
    public function delete($id)
    {
        $entityManager = $this -> getEntityManager ();
		$project = $entityManager -> getRepository ( 'Application\Entity\Project' ) -> find ( $id );
		
		if ( empty( $project ) )
		{
			return $this->createView(['error' => 'Project with id = '. $id . ' not exists.']);
		}
		
		$entityManager -> remove ( $project );
		$entityManager -> flush ();
		return $this->createView('deleted');
    }
	
	
	
	
	
	
	private function createView($data)
	{
		return new JsonModel(['data' => $data]);
	}
	
	public function onDispatch(\Zend\Mvc\MvcEvent $e)
	{
		parent::onDispatch($e);
	}
}
