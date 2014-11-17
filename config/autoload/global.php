<?php
/**
 * Global Configuration Override
 *
 * You can use this file for overriding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * @NOTE: In practice, this file will typically be INCLUDED in your source
 * control, so do not include passwords or other sensitive information in this
 * file.
 */

return array(
    'doctrine' => array (
		'connection' => array (
			'orm_default' => array (
				'driverClass' => 'Doctrine\DBAL\Driver\PDOMySql\Driver',
				'params' => array (
					'host' => 'localhost',
					'port' => '3306',
					'user' => 'free',
					'password' => 'pass',
					'dbname' => 'free',
					'charset' => 'utf8',
					'driverOptions' => array(
							1002=>'SET NAMES utf8'
					)
		) ) ) ),
	'redis' =>array(
		'cloud' => 'AWS/us-east-1',
		'resource_name' =>  'beta0',
		'pass' => 'SFghrcv4#2SCrrtte@#ikfaw5vb',
		'endpoint' => 'pub-redis-17933.us-east-1-3.1.ec2.garantiadata.com:17933'
	),
	'my-redis-cache' => array (
            'adapter' => array (
                    'name' => 'redis',
                    'options' => array (
						'persistent_id' => 'beta0',
						'server' => [
								'host' => 'pub-redis-17933.us-east-1-3.1.ec2.garantiadata.com',
								'port' => 17933,
								'persistent_id' => 'beta0',
								'password' => 'SFghrcv4#2SCrrtte@#ikfaw5vb'
						]
                    )
            ),
    )
);
