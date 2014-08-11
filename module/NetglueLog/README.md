# Netglue Generic Logger Module

## The Logger

This module provides a ready configured logger that will log to an SQLite database
or any other database you want it to. It's basically an instance of `Zend\Log\Logger`
with a custom db writer and formatter and some extra processors to capture additional
information.

By default, the SQLite database uses it's own adapter, but you can override this to use
the application wide adapter you're using if you want. I like using a separate adapter
because then any exceptions or errors with, say, the MySQL adapter you're using will get logged
by the logger even if there's a connection problem.

The logger is also configured by default to log PHP messages and providing another
logger is not configured already to log exceptions, it'll log those too. Additionally, it will
log MVC exceptions by listening to dispatch error events.

## Controller Plugin

A controller plugin is registered by the module with the alias `logger` so that you can easily log
messages from controllers with `$this->logger()->info('Some Message')`

## Reading Logs _(In Progress)_

The module comes with a controller and views so that you can read the logs - the intention is to be
able to filter them by a number of criteria and also be able to delete old records, empty the database
etc.

## Additional Features to Implement

* Set up a console controller/routes to delete old log records, truncate the log table etc
* Finish the views for reading logs

##Installation

Available as a composer package:
	
	"require": {
		"netglue/zf2-log-module": "dev-master"
	}

Or clone the git repo into your module directory

Enable the module in your app config with 
	
	$config = array(
		'modules' => array(
			'NetglueLog',
			// ... Other Modules ...
		),
	);

There's a dist config file and most of the code/config has a lot of comments but essentially, you should be able to get running simply by
providing a path to a writable SQLite database with the log table ready to go:

	// Inside config/autoload/some.config.php
	'netglue_log' => array(
		'db' => array(
			'database' => __DIR__ . '/../../data/logs.sqlite',
		),
	),

Inside `log-module/data/` you'll find SQL files to create the log table on an SQLite DB or a MySQL DB.
There's also an empty sqlite database that you can copy to wherever you want it, make it writable and point the config at it.

##Dependencies

* [Zend Framework](http://framework.zend.com)

