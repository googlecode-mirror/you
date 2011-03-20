<?php

/**
 * View used by Article controller to display articles list in "ordering after" select dropdown
 * When the article parent is changed, the article "ordering after" select dropdown is reloaded
 *
 */
?>


<?php foreach($articles as $article) :?>
	<?php
	$title = ($article['title'] != '') ? $article['title'] : $article['name'];
	?>
	<option value="<?= $article['id_article'] ?>"><?= $title ?></option>
<?php endforeach ;?>

