<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?php echo lang('title_ionize_installation')?></title>

<link rel="stylesheet" href="../themes/admin/css/installer.css" type="text/css" />
<link rel="stylesheet" href="../themes/admin/css/form.css" type="text/css" />

</head>
<body>

<div id="page">

	<div id="content-top"></div>

	<div id="content">

		<div id="lang">
			<?php foreach($languages as $l) :?>
				<img src="../themes/admin/images/world_flags/flag_<?php echo $l ?>.gif" onclick="javascript:location.href='<?php echo $current_url ?>&lang=<?php echo $l ?>';" />
			<?php endforeach ;?>
		</div>

		<img src="../themes/admin/images/ionize_logo_install.jpg" />

		<p class="version">version <?php echo $version ?></p>




