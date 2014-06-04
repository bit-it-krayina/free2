<?php

namespace Application\Controller;


use Zend\Mvc\Controller\AbstractActionController;
use Zend\Http\Client;
use Zend\Http\Request;
use Zend\Stdlib\Parameters;

use Application\Service\EntityManagerAwareInterface;
use Application\Service\EntityManagerAwareTrait;
use Application\Service\MenuTrait;
use Application\Service\ControlUtils;




class FacebookController extends AbstractActionController implements EntityManagerAwareInterface
{

	use EntityManagerAwareTrait,
	 MenuTrait,
	 ControlUtils
			;

	public function loginAction()
	{
		$facebookAuth = $this->getServiceLocator()->get('config')['facebookAuth'];

		echo '<pre>';
		var_dump( $_REQUEST);
		$params = $this->params()->fromQuery();
		var_dump($params['code']);

		/**
		 * Check response code
		 */
		$client = new Client();
		$adapter = new Zend\Http\Client\Adapter\Curl();
		$adapter->setOptions(array(
			'curloptions' => array(
				CURLOPT_SSL_VERIFYPEER => true,
				CURLOPT_SSL_VERIFYHOST => 2,
				CURLOPT_CAINFO => '/etc/ssl/certs/ca-bundle.pem'
			)
		));

		$client->setAdapter($adapter);
		
		$params = new Parameters(array(
			'client_id' => $facebookAuth['apiId'],
			'redirect_uri' => $this->url()->fromRoute('facebook', ['action' => 'login'], array('force_canonical' => true)),
			'client_secret' => $facebookAuth['secret'],
			'code' => $params['code']
		));

		$request = new Request();
		$request -> setMethod('GET')
				->setUri('https://graph.facebook.com/oauth/access_token')
				->setQuery($params)
		;

		$client->setRequest($request);
		$response = $client->send();

		var_dump($response);

		return new \Zend\View\Model\ViewModel();
	}

}
