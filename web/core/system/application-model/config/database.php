<?php
	/**
	 * Drivers:
	 * 		mysql for MySql
	 * 		mssql for Microsoft Sql Server
	 */

	class APP_DATABASE
	{
		static $set = 'dev';
		
		var $production = Array(
			'driver' => '',
			'host' => '',
			'login' => '',
			'password' => '',
			'database' => '',
			'prefix' => ''
		);
				
		var $dev = Array(
			'driver' => '',
			'host' => '',
			'login' => '',
			'password' => '',
			'database' => '',
			'prefix' => ''
		);
				
		var $local = Array(
			'driver' => '',
			'host' => '',
			'login' => '',
			'password' => '',
			'database' => '',
			'prefix' => ''
		);
	}
?>