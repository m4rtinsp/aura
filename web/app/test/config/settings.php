<?php
	// Defina o controler padrão a ser chamado, caso nenhum seja especificado na url
	define('AURA_DEFAULT_CONTROLLER', 'home'); // Principal
	define('AURA_DEFAULT_ACTION', 'index');
	
	// Defina se o framework pode gerar os models automaticamente
	define('AURA_GENERATE_MODEL', true);
	
	// Defina a configuração SMTP para envio de emails
	define('AURA_SMTP_HOST', '');
	define('AURA_SMTP_AUTH', ''); 	 // (bolean) smtp authentication?
	define('AURA_SMTP_LANG', 'br'); 
	define('AURA_SMTP_USERNAME', ''); // email
	define('AURA_SMTP_PASSWORD', '');
	define('AURA_SMTP_FROM', '');
	define('AURA_SMTP_FROMNAME', ''); // title message
	
	// Defina a pasta onde ficarão as Master pages
	define('AURA_SMARTY_MASTERPAGES_FOLDER', '_MasterPages');
	define('AURA_SMARTY_MASTERPAGE_DEFAULT', 'master');
	
	// Token a ser agregado ao hash md5
	define('AURA_MD5_TOKEN', 'auraframework');
?>