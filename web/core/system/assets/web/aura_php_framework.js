/**	
 * 
 * Aura PHP Framework
 * PHP version 5
 * 
 * @author			Paulo Martins <phmartins6@gmail.com>
 * @copyright		Copyright 2010/2011
 * @version			1.3.0
 * 
 * Framework js helper
 */

try {
	jQuery(function(){
		auraphp.init();
	});
}
catch(e) {
	alert('Aura PHP: jQuery not found.');
}

var auraphp = {
	
	path: {
		root: '',
		approot: '',
		httproot: ''
	},
	
	html: {
		root: '',
		css: '',
		data: '',
		files: '',
		img: '',
		js: ''
	},
	
	init: function() {
		this.filter();
	},
	
	call: function() {
		$("form[auraframework=filter]").submit(function(){
			if ($(this).attr('method') != 'get') {
				var form = $(this).attr('name');
					form = form ? '[name=' + form + ']' : form;
				
				alert('Aura PHP Framework: Incorrect method from form'+formname+' filter.');
				return false;
			}
			
			auraframework.filter();
		});
	},
	
	filter: function() {
		$("form.framework-filter").submit(function(){
			var action = $(this).attr('action');
			var format_url_framework = '';
			var inputs = $(this).find('input[type=text]');
			var checkboxs = $(this).find('input[type=checkbox]');
			var radios = $(this).find('input[type=radio]');
			var selects = $(this).find('select');
			
			inputs.each(function(){
				if ($(this).val())
					format_url_framework += $(this).attr('name') + '/' + $(this).val() + '/';
			});
			
			selects.each(function(){
				if ($(this).val())
					format_url_framework += $(this).attr('name') + '/' + $(this).val() + '/';
			});
			
			if (format_url_framework)
				location.href = action + '/' + format_url_framework;
			else
				location.href = action;
			
			return false;
		});
	}
	
}