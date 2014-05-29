<?php

namespace Application\Service\Importer;


/**
 * Description of Service
 *
 * @author mice
 */
interface  CommandInterface
{

	public function getUri($page = 1, $limit = 10);

	public function hasNextPage();

	public function setResponse ( $response );

	public function getResponse (  );

	public function isSuccess();

	public function getObjects();
}
