<?php
/**
 * Factory class for returning a logger as configured
 * @author George Steel <george@net-glue.co.uk>
 * @copyright Copyright (c) 2012 Net Glue Ltd (http://netglue.co)
 * @license http://opensource.org/licenses/MIT
 * @package	Netglue_LogModule
 * @link https://bitbucket.org/netglue/zf2-log-module
 */

namespace NetglueLog\Service;

use Traversable;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\InitializerInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\ArrayUtils;

use Zend\Log as Log;

use Zend\EventManager\EventInterface as Event;
use Zend\Mvc\MvcEvent;

/**
 * Factory class for returning a logger as configured
 * @author George Steel <george@net-glue.co.uk>
 * @copyright Copyright (c) 2012 Net Glue Ltd (http://netglue.co)
 * @license http://opensource.org/licenses/MIT
 * @package	Netglue_LogModule
 * @link https://bitbucket.org/netglue/zf2-log-module
 */
class CentralLogFactory implements 
	FactoryInterface,
	InitializerInterface {
	
	
	/**
	 * Implement Factory Interface
	 * @implements FactoryInterface
	 * @param ServiceLocatorInterface $services
	 * @return Zend\Log\LoggerInterface
	 */
	public function createService(ServiceLocatorInterface $services) {
		
		/**
		 * Configuration Options
		 */
		$options = $services->get('NetglueLog\Service\Options');
		
		/**
		 * If logging is disabled in config, return a logger with a null writer
		 */
		if($options->getLoggingEnabled() === false) {
			$logger = new Log\Logger;
			$logger->addWriter(new Log\Writer\Null);
			return $logger;
		}
		
		
		$spec = $options->getLoggerSpec();
		$logger = new Log\Logger($spec);
		$logger->addWriter($services->get('NetglueLog\Log\Writer\Db'));
		
		/**
		 * Add Processors
		 */
		$procs = $options->getProcessors();
		if(is_array($procs)) {
			foreach($procs as $name) {
				if($services->has($name)) {
					$logger->addProcessor($services->get($name));
				} elseif(is_string($name) && class_exists($name)) {
					try {
						$proc = new $name();
						$logger->addProcessor($proc);
					} catch(\Exception $prev) {
						$e = new \RuntimeException("Could not create an instance of {$name} as a processor for log events");
						$e->setPrevious($prev);
						throw $e;
					}
				} else {
					throw new \RuntimeException("Don't know how to create an instance of {$name} as a processor for log events");
				}
			}
		}
		
		/**
		 * Setup Error Handler
		 */
		if($options->getLogErrors()) {
			$failed = Log\Logger::registerErrorHandler($logger);
			if($failed === false) {
				// An error handler was already registered, what are we going to do?
				// Nothing. We could unregister it, but there might be other preferred 
				// writers doing this job, so let's leave it alone...
			}
		}
		
		/**
		 * Setup Exception Handler
		 */
		if($options->getLogExceptions()) {
			$failed = Log\Logger::registerExceptionHandler($logger);
			if($failed !== false) {
				// In order to actually log exceptions from the MVC stack, we need to hook into events
				$this->configureExceptionHandler($logger, $services);
			} else {
				// An exception handler was already registered
			}
		}
		
		return $logger;
	}
	
	/**
	 * Configure a logger as an exception handler - not really an exception handler - just logs exceptions that come through the MVC stack
	 * @param Log\Logger $logger
	 * @param ServiceLocatorInterface $services
	 * @return void
	 */
	protected function configureExceptionHandler(Log\Logger $logger, ServiceLocatorInterface $services) {
		$em = $services->get('EventManager')->getSharedManager();
		$em->attach('Zend\Mvc\Application', MvcEvent::EVENT_DISPATCH_ERROR, function($event) use ($logger) {
			$params = $event->getParams();
			if(isset($params['exception'])) {
				$ex = $params['exception'];
				$trace = current($ex->getTrace());
				$function = '';
				if(isset($trace['function'])) {
					$function = $trace['function'];
				}
				$logger->log(Log\Logger::CRIT, $ex->getMessage(), array(
					'exception' => $ex,
					'code' => $ex->getCode(),
					'file' => $ex->getFile(),
					'line' => $ex->getLine(),
					'function' => $function,
					'class' => get_class($ex),
					'trace' => $ex->getTrace(),
				));
			}
		}, 100);
	}
	
	/**
	 * Implement Initializer Interface so we can inject our logger into anything that supports the LoggerAwareInterface
	 * @implements InitializerInterface
	 * @param mixed $instance
	 * @param ServiceLocatorInterface $serviceLocator
	 * @return void
	 */
	public function initialize($instance, ServiceLocatorInterface $serviceLocator) {
		/**
		 * Any service created in the service manager will get piped through this method
		 */
		if($instance instanceof Log\LoggerAwareInterface) {
			/**
			 * Check for reasonable methods that might indicate a logger is already set
			 */
			if(method_exists($instance, 'getLogger')) {
				if($instance->getLogger() instanceof Log\LoggerInterface) {
					// Already has a logger, let's not overwrite it
					return;
				}
			}
			if(method_exists($instance, 'hasLogger')) {
				if(true === $instance->hasLogger()) {
					// Already has a logger, let's not overwrite it
					return;
				}
			}
			$logger = $serviceLocator->get('NetglueLog\Service\CentralLogFactory'); //i.e. call $this->createService()
			$instance->setLogger($logger);
		}
	}
	
}
