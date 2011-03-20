<html>
<body>

	<h2>Bonjour <?= $screen_name ?>,</h2>
	
	<p>Votre compte d'accès au site <b><?= Settings::get('$site_title') ?></b> est activé.</p>
	
	<p>Vous pouvez vous connecter sur <a href="<?=base_url()?>"><?= Settings::get('$site_title') ?></a>.</p>


</body>
</html>