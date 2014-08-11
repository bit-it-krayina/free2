<?php
/**
 * Netglue Log Module
 * @author George Steel <george@net-glue.co.uk>
 * @copyright Copyright (c) 2013 Net Glue Ltd (http://netglue.co)
 * @license http://opensource.org/licenses/MIT
 * @package	NetglueLog
 * @link https://bitbucket.org/netglue/zf2-log-module
 */

namespace NetglueLog;

/**
 * Autoloader
 */
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\Loader\AutoloaderFactory;
use Zend\Loader\StandardAutoloader;

/**
 * Service Provider
 */
use Zend\ModuleManager\Feature\ServiceProviderInterface;

/**
 * Config Provider
 */
use Zend\ModuleManager\Feature\ConfigProviderInterface;

/**
 * Controller Plugin Provider
 */
use Zend\ModuleManager\Feature\ControllerPluginProviderInterface;

/**
 * Bootstrap Listener
 */
use Zend\ModuleManager\Feature\BootstrapListenerInterface;
use Zend\EventManager\EventInterface as Event;


/**
 * Netglue Log Module
 * @author George Steel <george@net-glue.co.uk>
 * @copyright Copyright (c) 2012 Net Glue Ltd (http://netglue.co)
 * @license http://opensource.org/licenses/MIT
 * @package	Netglue_LogModule
 * @link https://bitbucket.org/netglue/zf2-log-module
 */
class Module implements
	AutoloaderProviderInterface,
	ServiceProviderInterface,
	ConfigProviderInterface,
	BootstrapListenerInterface,
	ControllerPluginProviderInterface
{

	/**
	 * Return autoloader configuration
	 * @link http://framework.zend.com/manual/2.0/en/user-guide/modules.html
	 * @return array
	 */
	public function getAutoloaderConfig() {
    return array(
			AutoloaderFactory::STANDARD_AUTOLOADER => array(
				StandardAutoloader::LOAD_NS => array(
					__NAMESPACE__ => __DIR__,
				),
			),
		);
	}

	/**
	 * Include/Return module configuration
	 * @return array
	 * @implements ConfigProviderInterface
	 */
	public function getConfig() {
		return include __DIR__ . '/../../config/module.config.php';
	}

	/**
	 * Return Service Config
	 * @return array
	 * @implements ServiceProviderInterface
	 */
	public function getServiceConfig() {
		return include __DIR__ . '/../../config/services.config.php';
	}

	/**
	 * Return controller plugin config
	 * @return array
	 */
	public function getControllerPluginConfig() {
		return array(
			'factories' => array(
				/**
				 * Factory closure initialises the controller plugin with an instance of the logger
				 */
				'NetglueLog\Controller\Plugin\Log' => function($sm) {
					$sl = $sm->getServiceLocator();
					$logger = $sl->get('NetglueLog\Service\CentralLogFactory');
					$plugin = new Controller\Plugin\Log;
					$plugin->setLogger($logger);
					return $plugin;
				},
			),
			/**
			 * Alias the controller plugin to 'logger'
			 * This allows us to `$this->logger()->info('Some Message');` from within any controller
			 */
			'aliases' => array(
				'logger' => 'NetglueLog\Controller\Plugin\Log',
			),
		);
	}

	public function getViewHelperConfig() {
		return array(
			'invokables' => array(
				'logLevelLabel' => 'NetglueLog\View\Helper\LogLevelLabel',
			),
		);
	}

	/**
	 * MVC Bootstrap Event
	 *
	 * @param Event $e
	 * @return void
	 * @implements BootstrapListenerInterface
	 */
	public function onBootstrap(Event $e) {
		$app = $e->getApplication();
		$serviceLocator = $app->getServiceManager();

	}

}
