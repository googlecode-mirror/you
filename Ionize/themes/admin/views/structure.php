<?php

/*
 * Img folder global variable
 * Mandatory for the recursive function getTree()
 */
$GLOBALS['theme_img_folder'] = base_url().Theme::get_theme_path().'images';

/** 
 * Returns the pages tree as pure HTML list
 */
function getTree($items, $first = false, $id_item=false, $root_id = '')
{
	$param = $root = '';
	
	if($first == true)
	{
		$param = ' id="'. $id_item .'" class="tree pageContainer"';
		$root = ' root';
		$root_id = $id_item;
	}
	else {
		$param = ' id="pageContainer'. $id_item .'" class="pageContainer"';
	}
	
	$tree = '<ul'.$param.'>';
	
	foreach($items as $key => $item)
	{
		// Online status
		$status = ( $item['online'] === '0' ) ? 'offline' : 'online';
		
		// Home ?
		$home = ( $item['home'] === '1' ) ? ' home' : '';
		
		$title = ($item['title'] != '') ? $item['title'] : $item['name'];

		// Name
		$tree .= '<li id="page_'.$item['id_page'].'" class="folder'.$root.$home.' page'.$item['id_page'].' page '.$status.'" rel="'.$item['id_parent'].'">'
		
		.'<span class="action">'
			// Online / Offline
			.'<span class="icon"><a title="'.lang('ionize_button_switch_online').'" class="status '.$status.' page'.$item['id_page'].'" rel="'.$item['id_page'].'"></a></span>'

			// Delete button
			.'<span class="icon"><a title="'.lang('ionize_button_delete').'" class="delete" rel="'.$item['id_page'].'"></a></span>'

			// Add article 
			.'<span class="icon"><a class="addArticle article" title="'.lang('ionize_title_create_article').'" rel="'.$item['id_page'].'"></a></span>'
		.'</span>'
		
		// Page Edit Link. "rel" property needed for drag / drop functionnality
		// ID : "pl" : used by callbeck to update name
		.'<span><a id="pl'.$item['id_page'].'" class="pageEditLink page'.$item['id_page'].' '.$status.'" rel="'.$item['id_page'].'" title="'.$title.'">'.$title.'</a></span>';
		
		// Get folders
		if (!empty($item['children']))
			 $tree .= getTree($item['children'], false, $item['id_page'], $root_id);

		// Get files
		if (!empty($item['articles']))
		{
			$tree.= '<ul id="articleContainer'.$item['id_page'].'">';
			
			foreach($item['articles'] as $article)
			{
				// Online status
				$status = ( $article['online'] == '1' ) ? ' online ' : ' offline ';
				
				$title = (trim($article['title']) != '') ? $article['title'] : $article['name'];

				$class = ($article['indexed'] == 1) ? ' doc ' : ' sticky ';
				
				$tree.= '<li id="article_'.$article['id_article'].'" class="file'.$class.$status.' article'.$article['id_article'].'" rel="'.$article['id_page'].'">'
				
					.'<span class="action">'
						// Online / Offline
						.'<span class="icon"><a class="status '.$status.' article'.$article['id_article'].'" rel="'.$article['id_article'].'"></a></span>'

					// Delete icon
						.'<span class="icon"><a class="delete" rel="'.$article['id_article'].'"></a></span>'
					.'</span>'
					
					// Edit link. "rel" property needed for drag / drop functionnality
					.'<span><a id="al'.$article['id_article'].'" class="articleEditLink article'.$article['id_article'].' '.$status.'"  title="'.$article['title'].'" rel="'.$article['id_article'].'">'. $title .'</a></span>'
					
				
				.'</li>';
			}
			$tree.= '</ul>';
		}
		$tree.= '</li>';
	}
	
	$tree .= '</ul>';

	return $tree;
}

?>

<div id="debug"></div>

<!-- Menus -->
<?php foreach($menus as $menu) :?>
	
	<h3 class="treetitle" rel="<?php echo($menu['id_menu']); ?>"><?php echo $menu['title'] ?></h3>
	<?= getTree($menu['items'], true, $menu['name'].'Tree') ?>

<?php endforeach ;?>


<!-- Events -->

<script type="text/javascript">

	/** Build the menus trees
	 *
	 */
	<?php foreach($menus as $menu) :?>
		var <?= $menu['name'] ?>Tree = new ION.Tree('<?= $menu['name'] ?>Tree');
	<?php endforeach ;?>

	
	<?php if($this->connect->is('admins')) :?>
	
	/** Add links to each menu title
	 *
	 */
	$$('.treetitle').each(function(el)
	{
		el.addClass('edit');
		
		el.addEvents(
		{
			'click': function(e)
			{
				e.stop();
				MUI.updateContent({
					element: $('mainPanel'),
					title: Lang.get('ionize_title_menu'),
					url : base_url + 'admin/menu'		
				});
			}
		});
	});
	
	<?php endif; ?>

</script>
