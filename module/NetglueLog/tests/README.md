# Running Tests

Make sure you have installed phpunit. You can do this from the top level of the project with composer:

	$ php composer.phar update --dev
	
Next, cd to the tests directory:

	$ cd path/to/project/vendor/netglue/zendframework/tests

Then run php unit. The example creates a junit log file:

	$ php ../../../bin/phpunit --log-junit ./log.xml

