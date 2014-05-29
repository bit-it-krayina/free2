<?php

namespace Application\Service\Importer;


/**
 * Description of Service
 *
 * @author mice
 */
class Service
{

	/**
	 *
	 * @var CommandInterface
	 */
	private $command;

	/**
	 *
	 * @var ImporterClient
	 */
	private $client;

	function getProjects()
	{

		$this->command = new GetProgectsCommand();
		$this->client = new ImporterClient();

		$projects = [];

		do
		{

			$this->client->setUri($this->command->getUri());
			$this->command->setResponse($this->client -> send());

			if ( $this->command->isSuccess() )
			{
				$projects = array_merge( $projects, $this->command->getObjects());
			}


		} while ($this->command->hasNextPage());



		return $projects;
	}

}
