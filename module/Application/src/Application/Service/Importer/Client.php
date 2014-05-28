<?php

namespace Application\Service\Importer;

use Zend\Http\Client;

/**
 * Description of Client
 *
 * @author mice
 */
class Client extends Client
{

	/**
	 * Constructor
	 *
	 * @param string $uri
	 * @param array|Traversable $options
	 */
	public function __construct ( $uri = null, $options = null )
	{

		parent::__construct ( 'https://userecho.com/api/v2/forums/30751/topics.json?access_token=82258f7b25b53ef65940a403f3bc2eb7c1e0c2ed', array (
			'maxredirects' => 0,
			'timeout' => 30
		) );

	}

}

