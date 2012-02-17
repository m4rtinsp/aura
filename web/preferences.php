<?php
	// Define the default application
	define('AURA_DEFAULT_APP', 'test');


	// Errors
	ini_set('error_reporting', E_ALL);
	ini_set('display_errors', 1);
	
	// Framework cookies
	define('AURA_PREFIX_COOKIE', '');
	define('AURA_POSFIX_COOKIE', '');

	// Enable or disable logs
	define('AURA_LOGS', true);
	define('AURA_LOGS_FOLDER', 'log');
	
	// Set default time zone
	date_default_timezone_set('America/Sao_Paulo');

	// Define charset of mysql result
	define('AURA_DEFAULT_MYSQL_CHARSET', 'utf8');
	
	// Enable or disable natural 404 (app OR controller not found)
	define('AURA_NATURAL_404', true);
	// Change header to return 404 status
	define('AURA_HEADER_404', true);

	// Set console password
	define('AURA_CONSOLE', true);

	// Framework test
	define('AURA_FRAMEWORK_TEST', true);
?>