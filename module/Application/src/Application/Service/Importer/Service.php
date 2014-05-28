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

	public function __construct( CommandInterface $command)
	{
		$this->command = $command;
	}

	
}
