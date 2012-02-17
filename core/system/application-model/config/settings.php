<?php
	// Defines default controller in app
	define('AURA_DEFAULT_CONTROLLER', 'index');
	// Defines default action in app
	define('AURA_DEFAULT_ACTION', 'index');
	
	// Defines SMTP configuration
	define('AURA_SMTP_HOST', '');
	define('AURA_SMTP_AUTH', ''); 	 // (bolean) smtp authentication
	define('AURA_SMTP_LANG', 'br'); 
	define('AURA_SMTP_USERNAME', ''); // email
	define('AURA_SMTP_PASSWORD', '');
	define('AURA_SMTP_FROM', '');
	define('AURA_SMTP_FROMNAME', ''); // title message
	
	// Defines folder for master pages
	define('AURA_SMARTY_MASTERPAGES_FOLDER', '_MasterPages');
	define('AURA_SMARTY_MASTERPAGE_DEFAULT', 'master');
	
	// Token to be added to md5
	define('AURA_MD5_TOKEN', 'auraframework');
?>