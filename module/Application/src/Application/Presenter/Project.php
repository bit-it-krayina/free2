<?php
namespace Application\Presenter;

use Application\Entity\Project as ProjectModel;


/**
 * Description of Project
 *
 * @author mice
 */
class Project implements PresenterInterface
{
	/**
	 *
	 * @var ProjectModel 
	 */
	private $project;
			
	public function __construct( ProjectModel $project )
	{
		$this->project = $project;
	}
	
	public function __toArray()
	{
		return [
			'id' => $this->project->getId(),
			'header' => $this->project->getHeader(),
			'description' => $this->project->getDescription(),
			'outer_id' => $this->project->getOuterId(),
			'url' => $this->project->getUrl(),
		];
	}
}
