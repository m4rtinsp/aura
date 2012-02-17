/**
 * Aura PHP Framework - Console
 * 
 * @author			Paulo Martins <phmartins6@gmail.com>
 * @copyright		2010 ~ 2011
 * @version			Framework 1.3.0 / PHP > 5.2
 */

$(function(){ AuraFramework.console.init(); });

AuraFramework = {
	
	path: '',
	version: '',
	php_version: '',
	smarty_version: '',
	command_history: new Array(),
	command_history_line: null,
	
	messages: {
		console_started: ['console started', 'title'],
		command_not_found: ['Command not found', 'error'],
		invalid_app_name: ['Sintaxe error. Invalid app name', 'error'],
		invalid_controller_name: ['Sintaxe error. Controller name not found', 'error'],
		invalid_app_name_spaces: ['Sintaxe error. Invalid app name, don\'t use spaces', 'error'],
		invalid_database_name_spaces: ['Sintaxe error. Invalid database name, don\'t use spaces', 'error'],
		invalid_controller_name_spaces: ['Sintaxe error. Invalid controller name, don\'t use spaces', 'error']
	},
	
	console: {
		
		command_list: ['create app', 'create models', 'create controller', 'clear', 'help', 'info'],
		
		init: function() {
			AuraFramework.console.write(
				null, 
				AuraFramework.messages.console_started[0], 
				AuraFramework.messages.console_started[1]
			);
									
			$this = $('input#command');
			$this.focus();
			
			$this.keypress(function(e){
				AuraFramework.console.keyCode(e, $this);

				if (e.which == 13 && $this.val() != '') {
					var i = 0;
					var exec = false;
					var command_list = AuraFramework.console.command_list;
					var command_line = $this.val();
					$this.val('');
					
					for (i; i < command_list.length; i++) {
						if (command_line.indexOf(command_list[i]) === 0) {
							var cmd = command_list[i].replace(' ', '_');
							eval('AuraFramework.console.command.' + cmd + '(\'' + command_line + '\')');
							AuraFramework.command_history.push(command_line);
							AuraFramework.command_history_line = null;
							exec = true;
							break;
						}
					}
					
					if (!exec)
						AuraFramework.console.write(
							command_line, 
							AuraFramework.messages.command_not_found[0], 
							AuraFramework.messages.command_not_found[1]
						);
				}
			});
		},
		
		keyCode: function(e, $this) {
			if (e.keyCode == 38) {
				if (AuraFramework.command_history_line === null)
					AuraFramework.command_history_line = 0;
				else if (AuraFramework.command_history_line >= 0)
					AuraFramework.command_history_line++;

				if (AuraFramework.command_history[AuraFramework.command_history_line])
					$this.val(AuraFramework.command_history[AuraFramework.command_history_line]);
				else
					AuraFramework.command_history_line = AuraFramework.command_history.length > 0 ? AuraFramework.command_history.length-1 : 0;
			}
			else if (e.keyCode == 40) {
				if (AuraFramework.command_history_line > 0) {
					AuraFramework.command_history_line--;

					$this.val(AuraFramework.command_history[AuraFramework.command_history_line]);
				}
				else {
					AuraFramework.command_history_line = null;
					$this.val('');
				}
					
			}
		},

		command: {
			
			help: function(line) {
				var message = '';
				AuraFramework.console.write(line);
				
				message += "clear - clear console";
				message += "<br/>";
				message += "info - show information about the framework";
				message += "<br/>";
				message += "create ";
				message += "<br/>";
				message += "&nbsp;&nbsp;&nbsp; app - use: create app [app_name]";
				message += "<br/>";
				message += "&nbsp;&nbsp;&nbsp; models - use: create models [optional model_name] [app_name]";
				message += "<br/>";
				message += "&nbsp;&nbsp;&nbsp; controller - use: create controller [controller_name] [app_name]";
				
				AuraFramework.console.write(
					null, 
					message, 
					'message'
				);
			},
			
			create_app: function(line) {
				var app = $.trim(line.split('create app')[1]);
				AuraFramework.console.write(line);
				
				if (app) {
					app = app.split(' ');
					
					if (app.length > 1) {
						AuraFramework.console.write(null, AuraFramework.messages.invalid_app_name_spaces[0], AuraFramework.messages.invalid_app_name_spaces[1]);
					}
					else {
						$.ajax({
							type: 'POST',
							url: AuraFramework.path + 'core/console/functions.php',
							data: 'action=create_app&app=' + app,
							dataType: 'json',
							success: function(json){
								if (json.status) 
									AuraFramework.console.write(null, 'App created.', 'success');
								else 
									AuraFramework.console.write(null, 'Could not create the app. <br/>' + json.message, 'error');
							}
						});
					}
				}
				else if (app == '') {
					AuraFramework.console.write(
						null, 
						AuraFramework.messages.invalid_app_name[0], 
						AuraFramework.messages.invalid_app_name[1]
					);
				}
			},
			
			create_controller: function(line) {
				var parameters = $.trim(line.split('create controller')[1]);
					parameters = parameters.split(' ');
				var name = $.trim(parameters[0]);
				var app = $.trim(parameters[(parameters.length-1)]);
				
				AuraFramework.console.write(line);
				
				if (app && name) {
					app = app.split(' ');
					name = name.split(' ');
					
					if (app.length > 1) {
						AuraFramework.console.write(null, AuraFramework.messages.invalid_app_name_spaces[0], AuraFramework.messages.invalid_app_name_spaces[1]);
					}
					else if (name.length > 1) {
						AuraFramework.console.write(null, AuraFramework.messages.invalid_controller_name_spaces[0], AuraFramework.messages.invalid_controller_name_spaces[1]);
					}
					else {
						$.ajax({
							type: 'POST',
							url: AuraFramework.path + 'core/console/functions.php',
							data: 'action=create_controller&app=' + app + '&name=' + name,
							dataType: 'json',
							success: function(json){
								if (json.status) {
									message = '';

									if (json.message)
										message = json.message;
									
									AuraFramework.console.write(null, 'Controller created. ' + message, 'success');
								}
								else 
									AuraFramework.console.write(null, 'Could not create the controller. <br/>' + json.message, 'error');
							}
						});
					}
				}
				else if (app == '') {
					AuraFramework.console.write(
						null, 
						AuraFramework.messages.invalid_app_name[0], 
						AuraFramework.messages.invalid_app_name[1]
					);
				}
				else if (name == '') {
					AuraFramework.console.write(
						null, 
						AuraFramework.messages.invalid_controller_name[0], 
						AuraFramework.messages.invalid_controller_name[1]
					);
				}
			},
			
			create_models: function(line) {
				var data = $.trim(line.split('create models')[1]);
					data = data.split(' ');
				var app = data[data.length-1];
					data.pop();
				var model_name = data.join('');

				AuraFramework.console.write(line);
				
				if (app) {
					app = app.split(' ');
					
					if (app.length > 1) {
						AuraFramework.console.write(null, AuraFramework.messages.invalid_app_name_spaces[0], AuraFramework.messages.invalid_app_name_spaces[1]);
					}
					else {
						$.ajax({
							type: 'POST',
							url: AuraFramework.path + 'core/console/functions.php',
							data: 'action=create_models&app=' + app + '&model_name=' + model_name,
							dataType: 'json',
							success: function(json){
								if (json.status) {
									var models = '';
									
									if (json.message) {
										$.each(json.message, function(i){
											models += json.message[i] + ' ';
										});
									}
									
									if (models && models != '') {
										if (model_name != '')
											AuraFramework.console.write(null, 'Model created: ' + models, 'success');
										else
											AuraFramework.console.write(null, 'Models created: ' + models, 'success');
									}
									else
										AuraFramework.console.write(null, 'No models created', 'success');
								}
								else {
									if (model_name != '')
										AuraFramework.console.write(null, 'Could not create the model. <br/>' + json.message, 'error');
									else
										AuraFramework.console.write(null, 'Models are not created. <br/>' + json.message, 'error');
								}
							}
						});
					}
				}
				else if (app == '') {
					AuraFramework.console.write(
						null, 
						AuraFramework.messages.invalid_app_name[0], 
						AuraFramework.messages.invalid_app_name[1]
					);
				}
			},
			
			clear: function(line) {
				$('div#console div ul').empty();
				
				AuraFramework.console.write(
					null, 
					AuraFramework.messages.console_started[0], 
					AuraFramework.messages.console_started[1]
				);
			},
			
			info: function(line) {
				var message = '';
				AuraFramework.console.write(line);
				
				message += "Aura PHP Framework ";
				message += "<br/>";
				message += "&nbsp;&nbsp;&nbsp; version - " + AuraFramework.version;
				message += "<br/>";
				message += "&nbsp;&nbsp;&nbsp; php version - " + AuraFramework.php_version;
				message += "<br/>";
				message += "&nbsp;&nbsp;&nbsp; template engine: " + AuraFramework.smarty_version;
				
				AuraFramework.console.write(
					null, 
					message, 
					'message'
				);
			}
			
		},
		
		write: function(command_line, response, type) {
			if (command_line)
				$('div#console div ul').append('<li class=\'command\'>' + 'Commad: ' + command_line + '</li>');
			
			if (response)
				$('div#console div ul').append('<li class=\'' + type + '\'>' + response + '</li>');
				
			$('div#console div').attr({scrollTop: $('div#console div').attr('scrollHeight')});
		}
	}
	
}