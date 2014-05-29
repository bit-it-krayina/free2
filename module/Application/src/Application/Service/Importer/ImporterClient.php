<?php

namespace Application\Service\Importer;

use Zend\Http\Client;

/**
 * Description of Client
 *
 * @author mice
 */
class ImporterClient extends Client
{

	/**
	 * Constructor
	 *
	 * @param string $uri
	 * @param array|Traversable $options
	 */
	public function __construct ( $uri = null, $options = null )
	{

		parent::__construct ( $uri, array (
			'maxredirects' => 0,
			'timeout' => 30
		) );

	}

}

