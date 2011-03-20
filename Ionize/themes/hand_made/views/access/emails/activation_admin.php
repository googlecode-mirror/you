<html>
<body>
	<h2>Saperlipopette !</h2>
	
	<p>Un utilisateur s'est enregistré sur le site. </p>
	
	<p>
		Nom : <b><?= $screen_name ?></b><br/>
		Email : <b><?= $email ?></b><br/><br/>
	
		Login : <b><?= $username ?></b><br/>
	</p>
	
	<p>
		Lien d'activation : <br/>
		{unwrap}<a href="<?= $url?><?= $username ?>/<?= $key ?>/admin"><?= $url?><?= $username ?>/<?= $key ?>/admin</a>{/unwrap}
	</p>

</body>
</html>