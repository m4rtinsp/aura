<?php
	/**
	 * Drivers:
	 * 		mysql for MySql
	 * 		mssql for Microsoft Sql Server
	 */

	class APP_DATABASE
	{
		static $set = 'local';
		
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
					'driver' => 'mysql',
					'host' => 'localhost',
					'login' => 'root',
					'password' => '',
					'database' => 'aura',
					'prefix' => ''
				);
	}
?>