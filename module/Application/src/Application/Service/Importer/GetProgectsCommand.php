<?php


namespace Application\Service\Importer;

/**
 * Description of GetProgectsCommand
 *
 * @author mice
 */

use Zend\Json\Json;

class GetProgectsCommand implements CommandInterface
{
	private $uri  = 'http://userecho.com/api/v2/forums/30751/topics.json?access_token=82258f7b25b53ef65940a403f3bc2eb7c1e0c2ed';

	private $currentPage = 1;

	private $currentLimit = 10;

	private $commandResponse = null;

	private $data;

	public function getUri ( $page = 1, $limit = 10 )
	{
		return $this->uri .'&'. http_build_query($this->getQueryData());
	}

	public function hasNextPage ()
	{

		if (! empty ($this->commandResponse) )
		{
			if ( $this->isSuccess() && ( $this->getDatails()->limit * $this->getDatails()->page  ) <= $this->getDatails()->count )
			{
				return true;
			}

			return false;
		}

		return true;
	}

	private function getQueryData()
	{
		return ['page' => $this->currentPage, 'limit' => $this->currentLimit];
	}

	public function setResponse ( $response )
	{
		if ( $response -> isSuccess()) {
			$this->commandResponse = $response->getBody();
			$this->currentPage ++;
		}

	}

	public function getResponse (  )
	{
		return Json::decode($this->commandResponse);
	}

	public function isSuccess()
	{
		if ($this->getResponse()->status == 'success' && !empty($this->getResponse()->data))
		{
			return true;
		}

		return false;
	}

	public function getObjects()
	{
		$projects = [];

		foreach ( $this-> getData() as $project)
		{
			$projects[$project->id] = $project;
		}

		return $projects;
	}


	private function getData()
	{
		return $this->getResponse()->data;
	}

	private function getDatails()
	{
		return $this->getResponse()->details;
	}
}
