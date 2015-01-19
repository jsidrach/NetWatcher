<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title><?php echo _('404 - Not Found') . ' - ' . APP_NAME; ?></title>
<?php
echo '<link rel="icon" href="' . APP_FAVICON . '">';
echo '<!-- CSS files -->';
foreach (\Core\Config::$CSS_LIBRARIES as $cssLib) {
    echo '<link href="' . CSS_DIR . $cssLib . '" rel="stylesheet">';
}
echo '<link href="' . THEMES_DIR . \Core\Config::$CSS_THEMES[\Core\Config::$DEFAULT_CSS_THEME] . '" rel="stylesheet">';
?>
<style>
h1, p {
	margin-left: auto;
	margin-right: auto;
	width: 70%;
	margin-top: 20px;
}
</style>
</head>
<body>
	<div class="container">
		<div class="row">
			<div class="span12">
				<div class="hero-unit center">
					<h1><?php echo _('Page Not Found'); ?><small><font face="Tahoma"
							color="red"><?php echo '  '. _('Error 404'); ?></font></small>
					</h1>
					<hr>
					<p><?php echo _('Sorry, an error has occured. Requested page not found!'); ?></p>
					<p>
						<a href="." class="btn btn-large btn-info"><span
							class="glyphicon glyphicon-home"></span>   <?php echo _('Take me Home'); ?></a>
					</p>
				</div>
			</div>
		</div>
	</div>
</body>
</html>