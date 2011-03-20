<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
	"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?= Settings::get_lang(); ?>" lang="<?= Settings::get_lang(); ?>">
<head>
	<title><?= Settings::get('site_title'); ?></title>

	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta http-equiv="Content-Language" content="<?= Settings::get_lang(); ?>" />
	<meta http-equiv="imagetoolbar" content="no" />
	<meta name="revisit-after" content="15 days" />

	<link rel="shortcut icon" href="<?= base_url().Theme::get_theme_path() ?>assets/images/favicon.ico" type="image/x-icon" >

	<link rel="stylesheet" href="<?= base_url().Theme::get_theme_path() ?>assets/css/screen.css" />
	<script type="text/javascript" src="<?= base_url().Theme::get_theme_path() ?>javascript/mootools-1.2.4-core-yc.js"></script>
	<script type="text/javascript" src="<?= base_url().Theme::get_theme_path() ?>javascript/mootools-1.2.4.2-more-yc.js"></script>
</head>

<body>

<div class="wrap">


	<div id="header">
		<div id="logo">
			<h1><a href="<?= base_url(); ?>"><?= Settings::get('site_title') ?></a></h1>
		</div>
	</div>
	
	
	<div id="page">
		<div id="content">
		
			<div class="article">

				<h2><?= $title ?></h2>

				<p><?= $message ?></p>

				<p><a href="<?= base_url() ?>"><?= lang('access_home_page'); ?></a></p>
			</div>
		</div>
	</div>
</div>

</body>
</html>