<?php

	$id = str_replace('/', '', $path).$view;

?>
<form method="post" name="formView<?= $view ?>" id="formView<?= $view ?>">

	<input type="hidden" id="path_<?= $id ?>" name="path" value="<?= $path ?>" />
	<input type="hidden" id="view_<?= $id ?>" name="view" value="<?= $view ?>" />

	<textarea id="edit_<?= $id ?>" name="content" style="height: 420px; width: 100%;"><?= $content ?></textarea>

</form>

