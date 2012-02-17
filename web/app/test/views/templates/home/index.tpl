<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title>Framework test</title>
		<style>
			ul {
				margin: 10px 0;
			}
			
			ul li label {
				display: inline-block;
				width: 150px;
			}
		</style>
	</head>
	<body>
		<p>{$TITLE}</p>
		
		<br/>
		
		
		<ul>
			{foreach from=$cars item=car}
			<li>
				<label>{$car->brand} {$car->model}</label> 
				{$car->user_data->name} - 
				{$car->user_data->address_data->country} - 
				{$car->user_data->address_data->city} - 
				{$car->user_data->address_data->planet_data->name}
			</li>
			{/foreach}
		</ul>
		{$html->css}
	</body>
</html>