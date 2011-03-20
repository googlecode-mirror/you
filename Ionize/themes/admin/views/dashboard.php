
<div id="maincolumn">

	<!-- Icon create a page -->
	<div class="dashboard-icon" id="iconAddPage">
		<img src="<?=base_url()?>themes/admin/images/icon_48_page.png" />
		<p><a><?= lang('ionize_dashboard_icon_add_page') ?></p>
	</div>
	
	<!-- Icon Media Manager -->
	<div class="dashboard-icon" id="iconMediaManager">
		<img src="<?=base_url()?>themes/admin/images/icon_48_media.png" />
		<p><a><?= lang('ionize_dashboard_icon_mediamanager') ?></a></p>
	</div>
	
	<!-- Icon Static translations -->
	<div class="dashboard-icon" id="iconTranslation">
		<img src="<?=base_url()?>themes/admin/images/icon_48_globe.png" />
		<p><a><?= lang('ionize_dashboard_icon_translation') ?></a></p>
	</div>
	
	
	<div class="dashboard-icon" id="iconGA">
		<img src="<?=base_url()?>themes/admin/images/icon_48_stats.png" />
		<p><a><?= lang('ionize_dashboard_icon_google_analytics') ?></a></p>
	</div>
	
	<br class="clear"/>
	
	
	<div id="infos">	

		<!-- Last connected users -->

		<h3 class="toggler"><?= lang('ionize_dashboard_title_last_connected_users') ?></h3>
		
		<div class="element">
			<table class="list" id="articlesTable">
			
				<thead>
					<tr>
						<th axis="string"><?= lang('ionize_label_screen_name') ?></th>
						<th axis="string"><?= lang('ionize_label_last_visit') ?></th>
						<th axis="string"><?= lang('ionize_label_email') ?></th>				
					</tr>
				</thead>
				<tbody>
			
					<?php foreach($users as $user) :?>
						
						<tr>
							<td><?= $user['screen_name'] ?></td>
							<td><?= getFrenchDatetime($user['last_visit']) ?></td>
							<td><?= mailto($user['email']) ?></td>
						</tr>
			
					<?php endforeach ;?>
			
				</tbody>
				
			</table>
		</div>
		
		
		<!-- Last updated articles list -->
		<h3 class="toggler"><?= lang('ionize_dashboard_title_last_modified_articles') ?></h3>
		
		<div class="element">
		
			<table class="list" id="articlesTable">
			
				<thead>
					<tr>
						<th axis="string"><?= lang('ionize_label_page') ?></th>
						<th axis="string"><?= lang('ionize_label_article') ?></th>
						<th axis="string"><?= lang('ionize_label_author') ?></th>
						<th axis="string"><?= lang('ionize_label_updater') ?></th>
						<th axis="string"><?= lang('ionize_label_created') ?></th>				
						<th axis="string"><?= lang('ionize_label_updated') ?></th>				
						<th></th>
					</tr>
				</thead>
			
				<tbody>
				
				<?php foreach ($articles as $article) :?>
					
					<?php
						$title = ($article['title'] != '') ? $article['title'] : $article['name'];
					?>

					<tr>
						<td <?php if($article['id_page'] == '0') :?>class="alert"<?php endif; ?>><a href="javascript:MUI.updateContent({'element': $('mainPanel'),'loadMethod': 'xhr','url': 'admin/page/edit/<?= $article['id_page'] ?>','title': Lang.get('ionize_title_edit_page') + ' : ' + '<?= $article['page_name'] ?>'});" title="<?= lang('ionize_label_edit') ?>"><?= $article['page_name'] ?></a></td>
						<td><a href="javascript:MUI.updateContent({'element': $('mainPanel'),'loadMethod': 'xhr','url': 'admin/article/edit/<?= $article['id_article'] ?>','title': Lang.get('ionize_title_edit_article') + ' : ' + '<?= $title ?>'});" title="<?= lang('ionize_label_edit') ?>"><?= $title ?></a></td>
						<td><?= $article['author'] ?></td>
						<td><?= $article['updater'] ?></td>
						<td><?= getFrenchDatetime($article['created']) ?></td>
						<td><?= getFrenchDatetime($article['updated']) ?></td>
						<td class="icon"><a class="article<?= $article['id_article'] ?> <?=($article['online'] == '1') ? 'online' : 'offline' ;?>" onclick="javascript:articleManager.switchOnline('<?= $article['id_article'] ?>')"></a></td>
					</tr>
			
				<?php endforeach ;?>
				
				</tbody>
			
			</table>
		</div>


		<!-- orphan pages : Page affected to menu 0 -->
		<?php if ( ! empty($orphan_pages)) :?>

		<h3 class="toggler"><?= lang('ionize_dashboard_title_orphan_pages') ?></h3>

		<div class="element">
			
			<table class="list" id="orphanPagesTable">
			
				<thead>
					<tr>
						<th axis="string"><?= lang('ionize_label_page') ?></th>
						<th axis="string"><?= lang('ionize_label_author') ?></th>
						<th axis="string"><?= lang('ionize_label_updater') ?></th>
						<th axis="string"><?= lang('ionize_label_created') ?></th>				
						<th axis="string"><?= lang('ionize_label_page_delete_date') ?></th>				
					</tr>
				</thead>
			
				<tbody>
				
				<?php foreach ($orphan_pages as $page) :?>

					<?php
						$title = ($page['title'] != '') ? $page['title'] : $page['name'];
					?>

					<tr>
						<td><a href="javascript:MUI.updateContent({'element': $('mainPanel'),'loadMethod': 'xhr','url': 'admin/page/edit/<?= $page['id_page'] ?>','title': Lang.get('ionize_title_edit_page') + ' : ' + '<?= $title ?>'});" title="<?= lang('ionize_label_edit') ?>"><?= $title ?></a></td>
						<td><?= $page['author'] ?></td>
						<td><?= $page['updater'] ?></td>
						<td><?= getFrenchDatetime($page['created']) ?></td>
						<td><?= getFrenchDatetime($page['updated']) ?></td>
					</tr>
			
				<?php endforeach ;?>
				
				</tbody>
			
			</table>

		</div>

		<?php endif ;?>


		<!-- orphan article : Articles without parents -->
		<?php if ( ! empty($orphan_articles)) :?>

		<h3 class="toggler"><?= lang('ionize_dashboard_title_orphan_articles') ?></h3>

		<div class="element">
			
			<table class="list" id="orphanArticlesTable">
			
				<thead>
					<tr>
						<th axis="string"><?= lang('ionize_label_article') ?></th>
						<th axis="string"><?= lang('ionize_label_author') ?></th>
						<th axis="string"><?= lang('ionize_label_updater') ?></th>
						<th axis="string"><?= lang('ionize_label_created') ?></th>				
						<th axis="string"><?= lang('ionize_label_page_delete_date') ?></th>				
					</tr>
				</thead>
			
				<tbody>
				
				<?php foreach ($orphan_articles as $article) :?>
					
					<?php
						$title = ($article['title'] != '') ? $article['title'] : $article['name'];
					?>

					<tr>
						<td><a href="javascript:MUI.updateContent({'element': $('mainPanel'),'loadMethod': 'xhr','url': 'admin/article/edit/<?= $article['id_article'] ?>','title': Lang.get('ionize_title_edit_article') + ' : ' + '<?= $title ?>'});" title="<?= lang('ionize_label_edit') ?>"><?= $title ?></a></td>
						<td><?= $article['author'] ?></td>
						<td><?= $article['updater'] ?></td>
						<td><?= getFrenchDatetime($article['created']) ?></td>
						<td><?= getFrenchDatetime($article['updated']) ?></td>
					</tr>
			
				<?php endforeach ;?>
				
				</tbody>
			
			</table>

		</div>

		<?php endif ;?>
	
	</div>

</div>


<script type="text/javascript">

	/**
	 * Panel toolbox
	 * Init the panel toolbox is mandatory !!! 
	 *
	 */
	MUI.initToolbox();


	/**
	 * Options Accordion
	 *
	 */
	MUI.initAccordion('.toggler', 'div.element');


	/**
	 * Article itemManager
	 *
	 */
	articleManager = new IonizeItemManager(
	{
		element: 	'article'
	});


	// Icons actions
	$('iconAddPage').addEvent('click', function(e){
		new Event(e).stop();
		MUI.updateContent({
			element: $('mainPanel'),
			title: Lang.get('ionize_title_new_page'),
			url : base_url + 'admin/page/create/0'
		});
	});

	$('iconMediaManager').addEvent('click', function(e){
		new Event(e).stop();
		MUI.updateContent({
			element: $('mainPanel'),
			title: Lang.get('ionize_menu_media_manager'),
			url : base_url + 'admin/media/get_media_manager',
			padding: {top:0, left:0, right:0}
		});
	});

	$('iconTranslation').addEvent('click', function(e){
		new Event(e).stop();
		MUI.updateContent({
			element: $('mainPanel'),
			title: Lang.get('ionize_title_translation'),
			url : base_url + 'admin/translation/'
		});
	});
	
	$('iconGA').addEvent('click', function(e){
		new Event(e).stop();
		window.location.href = 'http://www.google.com/analytics/'
	});

</script>