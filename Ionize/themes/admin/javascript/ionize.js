/**
 * Ionize Core Object
 *
 *
 */
var ION = new Hash({

	baseUrl: base_url,

	// Temporary object Item Managers : Used by ION.insertTreeArticle() to add Delete, SwitchOnline link, etc.
	articleManager: new IonizeItemManager({ baseUrl: base_url, element:'article' }),
	
	pageManager: new IonizeItemManager({ baseUrl: base_url, element:'page' }),
	
	/**
	 * Updates the info about the link
	 *
	 */
	updateLinkInfo: function(link_type, link_id, link_text)
	{
		if (link_type == '') link_type = 'external';
		
		var url = base_url + 'admin/' + link_type + '/edit/' + link_id;
		var a = new Element('a').set('text', link_text);

		// Empty the link_info DL
		var dl = $('link_info');
		dl.empty();
		
		// Title
		var dt = new Element('dt', {'class': 'small'});
		dt.adopt(new Element('label').set('text', Lang.get('ionize_label_linkto')));
		dt.inject(dl, 'top');

		// Icon & link
		var dd = new Element('dd').inject(dl, 'bottom');
		var span = new Element('span', {'class': 'link-img ' + link_type}).inject(dd);
		
		// Link build
		if (link_type == 'external')
		{
			a.setProperty('href', link_text);
			a.setProperty('target', '_blank');
		}
		else
		{
			a.removeEvent('click').addEvent('click', function(e)
			{
				e.stop();
	
				MUI.updateContent({
					'element': $('mainPanel'),
					'loadMethod': 'xhr',
					'url': url,
					'title': Lang.get('ionize_title_edit_' + link_type)
				});
			});
		}
		
		a.inject(dd, 'bottom');
	},

	
	/**
	 * Removes link and link info
	 *
	 */
	removeLink: function()
	{
		// remove form data
		$('link').set('text','');
		$('link_type').value='';
		$('link_id').value='';
		
		// Empty the link_info DL
		$('link_info').empty();
	},
	
});


ION.options = {
	mainpanel: 		'mainPanel',
	baseUrl:		base_url
};


/**
 *  Ionize Tree class
 *  Build each website structure tree
 * 
 *  @author	Partikule Studio
 *  @since	0.9.5
 * 
 */
ION.Tree = new Class({

	Implements: [Events, Options],
	
	options: ION.options,

	initialize: function(element, options)
	{
		this.setOptions(options);
		this.element = element;
		
		var options = this.options;
		
		this.mainpanel = $(options.mainPanel);
		
		// Array of itemManagers
		this.pageItemManagers = new Array();
		
		// Article Item Manager
		this.articleManager = new IonizeItemManager({ baseUrl: options.baseUrl, element:'article' });

		// Root Page Item Manager
		this.pageItemManagers[this.element] = new IonizeItemManager({ baseUrl: options.baseUrl, element:'page', container: element });
		
		var opened = new Array();
		if (Cookie.read('tree')) opened = (Cookie.read('tree')).split(',');
		
		var folders = $$('#' + element + ' li.folder');
		
		// Pages
		folders.each(function(folder, idx)
		{
			var folderContents = folder.getChildren('ul');
			
			var homeClass = (folder.hasClass('home')) ? ' home' : '' ;
			
			var folderImage = new Element('div', {'class': 'tree-img drag folder' + homeClass}).inject(folder, 'top');
	
			// Define which open and close graphic ( + - ) each folder gets
			var addclass = '';
			if (idx == 0) {	addclass = 'first';	}
			else {
				if ( !folder.getNext()) { 
					addclass = 'last';
				}
			}

			var image = new Element('div', {'class': 'tree-img plus ' + addclass});
			
			image.addEvent('click', this.openclose).inject(folder, 'top');

			if (opened.contains(folder.id))
			{
				folder.addClass('f-open');
				image.removeClass('plus').addClass('minus');
			}
			else
			{
				folderContents.each(function(el){ el.setStyle('display', 'none'); });
			}

			// Add connecting branches to each file node
			folderContents.each(function(element){
				var docs = element.getChildren('li.doc').extend(element.getChildren('li.sticky'));
				docs.each(function(el) {
					// Last branche
					if (el == docs.getLast() && !el.getNext()) { new Element('div', {'class': 'tree-img line last'}).inject(el.getElement('span'), 'before');}
					// Tree branche
					else { new Element('div', {'class': 'tree-img line node'}).inject(el.getElement('span'), 'before');}
				});
			});
			
			this.addEditLink(folder, 'page');
			this.addPageActionLinks(folder);
			this.makeLinkDraggable(folder, 'page');

		}.bind(this));

		
		$$('#'+element+' li').each(function(node, idx)
		{
			// Add connecting branches to each node
			node.getParents('li').each(function(parent){
				if (parent.getNext() || !parent.hasClass('last')) {	new Element('div', {'class': 'tree-img line'}).inject(node, 'top');}
				else { new Element('div', {'class': 'tree-img line empty'}).inject(node, 'top');}
			});
			
			var typeClass = (node.hasClass('doc')) ? 'file' : 'sticky' ;
			
			if (node.hasClass('file'))
			{
				var link = node.getElement('span');
			
				new Element('div', {'class': 'tree-img ' + typeClass}).inject(link, 'before');
			
				this.addEditLink(node, 'article');
				this.addArticleActionLinks(node);
				this.makeLinkDraggable(node, 'article');
			}
			
			// Mouse over effect
			this.addMouseOver(node);
			
		}.bind(this));
	
		
		$$('#' + element + ' li span.action').setStyle('display','none');
		
		// Create PageItemManagers and make them sortable
		this.pageItemManagers[this.element].makeSortable();

		$$('#' + element + ' .pageContainer').each(function(item, idx) { 
			this.pageItemManagers[item.id] = this.createPageItemManager(item.id); 
		}.bind(this));
		
	},


	openclose:function(evt)
	{
		evt.stop();
		el = evt.target;
		var folder = el.getParent();
		var folderContents = folder.getChildren('ul');
		var folderIcon = el.getNext('.folder');

		if (folder.hasClass('f-open')) {
			el.addClass('plus').removeClass('minus');
			folderIcon.removeClass('open');
			folderContents.each(function(ul){ ul.setStyle('display', 'none');});
			folder.removeClass('f-open');
			ION.treeDelFromCookie(folder.getProperty('id'));
		}
		else {
			el.addClass('minus').removeClass('plus');
			folderIcon.addClass('open');
			folderContents.each(function(ul){ ul.setStyle('display', 'block'); });
			folder.addClass('f-open');
			ION.treeAddToCookie(folder.getProperty('id'));
		}
	},


	createPageItemManager: function(id)
	{
		var pim = new IonizeItemManager(
		{
			baseUrl: 	this.options.baseUrl,
			element: 	'page',
			container:	id
		});
		pim.makeSortable();
		
		return pim;
	},

	
	addEditLink: function(el, type)
	{
		var a = el.getLast('span').getElement('a');
		var id = a.rel;
		var p = $(this.options.mainpanel);

		a.addEvent('click', function(e)
		{
			e.stop();

			MUI.updateContent({
				'element': p,
				'loadMethod': 'xhr',
				'url': base_url + 'admin/' + type + '/edit/' + id,
				'title': Lang.get('ionize_title_edit_' + type) + ' : ' + this.getProperty('title')	
			});
		});
	},

	
	addPageActionLinks: function(el)
	{
		var a = el.getElement('a.addArticle');
		var id = a.rel;
		var p = $(this.options.mainpanel);
		
		a.addEvent('click', function(e)
		{
			e.stop();
			MUI.updateContent({
				'element': p,
				'loadMethod': 'xhr',
				'url': base_url + 'admin/article/create/' + id,
				'title': Lang.get('ionize_title_create_article')
			});
		});
	
		a = el.getElement('a.status');
		a.addEvent('click', function(e)
		{
			e.stop();
			this.pageItemManagers[el.getParent('ul').id].switchOnline(id);
		}.bind(this));
		
		a = el.getElement('a.delete');
		a.addEvent('click', function(e)
		{
			e.stop();
			this.pageItemManagers[el.getParent('ul').id].deleteItem(id);
		}.bind(this));
		
	},

	
	addArticleActionLinks: function(el)
	{
		var a = el.getElement('a.status');
		var id = a.rel;
		var p = $(this.options.mainpanel);

		a.addEvent('click', function(e)
		{
			e.stop();
			this.articleManager.switchOnline(id);
		}.bind(this));
		
		a = el.getElement('a.delete');
		a.addEvent('click', function(e)
		{
			e.stop();
			this.articleManager.deleteItem(id);
		}.bind(this));
	},

	
	addMouseOver: function(node)
	{
		node.addEvent('mouseover', function(ev){
			ev.stopPropagation();
			ev.stop();
			this.addClass('highlight');
			this.getParent().getParent().getChildren('.action').setStyle('display', 'none');
			this.getChildren('.action').setStyle('display', 'block');
		});
		node.addEvent('mouseout', function(ev){
			this.removeClass('highlight');
		});
		node.addEvent('mouseleave', function(e)
		{
			this.getChildren('.action').setStyle('display', 'none');
		});
	},

	
	/**
	 * Makes the page / article tree element draggable and droppable to the "link" field
	 * of another page / article
	 * @param	String		Type of element : 'page' or 'article'
	 *
	 */
	makeLinkDraggable: function(el, type)
	{
		var a = el.getLast('span').getElement('a');

		// Make the link draggable
		a.makeCloneDraggable(
		{
			// The "link" field of each page / article has the .droppable class
			droppables: '.droppable',
			snap: 10,
			
			onDrop: function(element, droppable, event)
			{
				if (!droppable) {}
				else
				{
					droppable.highlight();
					
					// Drop the page as link for another page or article
					if (droppable.id == 'link')
					{
						// Check if link is invalid : No circulr link
						if ($('element').value == type && $('id_' + type).value == element.getProperty('rel') )
						{
							MUI.notification('error', Lang.get('ionize_message_no_circular_link'));
						}
						else
						{
							// Link form data
							$('link_type').value = type;
							$('link_id').value = element.getProperty('rel');
							$('link').set('text',element.get('text'));
							
							ION.updateLinkInfo(type, element.getProperty('rel'), element.get('text'));
						}
					}
				}
			},
			onEnter: function(el, droppable) { droppable.setStyles({'background-color':'#fdfced'}); },
			onLeave: function(el, droppable) { droppable.setStyles({'background-color':'#fff'}); }
		});
	
	},


	/**
	 * Updates one element in the structure tree
	 *
	 */
	updateTreeNode: function(options)
	{
		var title = (options.title !='') ? options.title : options.url;
		var el_type = options.element;
		var id = (el_type == 'page') ? options.id_page : options.id_article;
		var id_parent = (el_type == 'page') ? options.id_parent : options.id_page;
		var status = (options.online == '1') ? 'online' : 'offline';
		var home_page = (options.home && options.home == '1') ? true : false;
		var sticky = (options.indexed && options.indexed == '1') ? false : true;
		var element = $(el_type + '_' + id);
		var rel = element.getProperty('rel');
		
		var id_tree = options.menu.name + 'Tree';
		var parent = (id_parent != '0') ? $('page_' + id_parent) : $(options.menu.name + 'Tree');
		var id_container = (id_parent != '0') ? el_type + 'Container' + id_parent : id_tree ;
		
		// link Title in tree (A tag)
		var el_link = (el_type == 'page') ? 'pl' + id : 'al' + id;

		// Update the link text
		$(el_link).set('text', title);
		
		// Update  Online/Offline class
		$$('.' + el_type + id).removeClass('offline').removeClass('online').addClass(status);
		

// Check if page 
//Check if parent Tree can fin the el : If not, it is not in this tree, so move it .

		
		// if the container doesn't exists, create it
		if ( ! (container = $(id_container)))
		{
			container = new Element('ul', {'id': el_type + 'Container' + id_parent});
			
			// If the parent already contains an article container, inject the page container before.
			if (el_type == 'page' && (articleContainer = $('articleContainer' + id_parent)))
			{
				container.inject(articleContainer, 'before');
			}
			else
			{
				container.inject($('page_' + id_parent), 'bottom');
			}
		
			// Update visibility of container regarding the parent
			if ( ! (parent.hasClass('f-open'))) { container.setStyle('display', 'none');	}
		}


		
		// Move Element in the tree
		if ((rel != id_parent) || ($(id_container) && $type($(id_container).getElement('#' + el_type + '_' + id)) == false))
		{
			var childs = container.getChildren();
			
			// Inject in good position
			if (childs.length < 1)
			{ 
				container.adopt(element);
			}
			else
			{
				var pos = (el_type != 'page') ? parseInt(options.ordering) : 100;
				
				// We don't obtain the ordering for a page.
				if (pos > childs.length || el_type == 'page')
				{
					element.inject(container, 'bottom');
				}
				else
				{
					element.inject(childs[pos-1], 'before');
				}
				
			}
			
			// Update number of tree lines
			var pNbLines = parent.getChildren('.tree-img').length;
			var eNbLines = element.getChildren('.tree-img').length;
			
			var treeline = 	new Element('div', {'class': 'tree-img line'});
			var lis = element.getElements('li');
			lis.push(element);
			
			lis.each(function(li)
			{
				for (var i=0; i < eNbLines -2; i++) { (li.getFirst()).dispose();}
				for (var i=0; i < pNbLines -1; i++) { treeline.clone().inject(li, 'top'); }
			});
			
			// Update the relevant parent
			element.setProperty('rel', id_parent);
		}
		
		// Update the icon : home for page ? Must see...
		if (el_type == 'page')
		{
			$$('.folder').removeClass('home');

			if (home_page == true)
			{
				element.getFirst('.folder').addClass('home');
			}
		}

		// Update the icon : sticky for article ? Must see...
		if (el_type == 'article')
		{
			var file = element.getFirst('.action').getPrevious();
			
			if (sticky == true && file.hasClass('sticky') == false)
			{
				file.removeClass('file').addClass('sticky');
			}
			else
			{
				if (file.hasClass('sticky'))
				{
					file.removeClass('sticky').addClass('file');
				}
			}
		}
	},
	
	
	/**
	 * Insert One article in the tree
	 *
	 */
	insertTreeArticle: function(options)
	{
		var title = (options.title !='') ? options.title : options.url;

		var page = $('page_' + options.id_page);

		var id = options.id_article;
		var id_page = options.id_page;
		var status = (options.online == '1') ? ' online ' : ' offline '; 
		
		/* Main elements */
		var li = 		new Element('li', {'id': 'article_' + id, 'class': 'file doc' + status + ' article' + id });
		var action = 	new Element('span', {'class': 'action', 'styles': { 'display':'none' }});
		var icon = 		new Element('span', {'class': 'icon'	});
		var link = 		new Element('span');
		var a =			new Element('a', {'id':'al' + id, 'class': 'articleEditLink' + status + ' article' + id, 'rel': id }).set('text', title);
		var treeline = 	new Element('div', {'class': 'tree-img'});
		
		/* Action element */
		var iconOnline = icon.clone().adopt(new Element('a', {'class': 'status ' + status, 'rel': id}));
		var iconDelete = icon.clone().adopt(new Element('a', {'class': 'delete', 'rel': id}));
		action.adopt(iconOnline, iconDelete);
		
		this.addArticleActionLinks(action);
		
		/* Link element */
		link.adopt(a);
		li.adopt(action, link);
		this.addEditLink(li, 'article');
		
		var icon = treeline.clone().addClass('file');
		icon.inject(li, 'top');
		
		/* Get the parent and the tree lines (nodes) */
		var parent = $('page_' + id_page);
		var treeLines = $$('#page_' + id_page + ' > .tree-img');
		
		/* item tree line */
		var nodeLine = treeline.clone();
		nodeLine.addClass('line').addClass('node');
		
		// Try to get the articles UL container
		if ( container = $('articleContainer' + id_page))
		{
			var lis = container.getChildren('li');
			
			// Node lines
			if (options.ordering > lis.length) nodeLine.removeClass('node').addClass('last');
			nodeLine.inject(li, 'top');
			for (var i=0; i < treeLines.length -1; i++)	{ 
				treeline.clone().inject(li, 'top'); 
			}

			// Inject LI at the correct pos
			if (options.ordering == '1') li.inject(container, 'top');
			else li.inject(lis[options.ordering -2], 'after');
			
			// Correct the upper article nodeline
			if (nodeBefore = li.getPrevious())
			{
				nodeTree = nodeBefore.getChildren('.tree-img');
				nodeTree[nodeTree.length-2].removeClass('last').addClass('node');
			}
		}
		// if no article container, we will create one.
		else
		{
			// Node lines
			nodeLine.addClass('last');
			nodeLine.inject(li, 'top');
			for (var i=0; i < treeLines.length -1; i++)	{ treeline.clone().inject(li, 'top'); }

			container = new Element('ul', {'id':'articleContainer' + id_page});				
			container.adopt(li);
			container.inject(page, 'bottom');
			
			if ( ! (parent.hasClass('f-open'))) { container.setStyle('display', 'none');	}
		}
			
		// Add Mouse over effects
		this.addMouseOver(li);
	},
	
	
	/**
	 * Insert One page in the tree
	 *
	 */
	insertTreePage: function(options)
	{
		var title = (options.title !='') ? options.title : options.url;
		var menu = $(options.menu.name + 'Tree');
		var id = options.id_page;
		var id_parent = options.id_parent;
		var status = (options.online == '1') ? ' online ' : ' offline '; 
		var home_page = (options.home && options.home == '1') ? true : false;
		var containerName = (id_parent != '0') ? 'pageContainer' + id_parent : options.menu.name + 'Tree';

		/* Main elements */
		var li = 		new Element('li', {'id': 'page_' + id, 'class': 'folder page' + status + ' page' + id, 'rel':id_parent});
		var action = 	new Element('span', {'class': 'action', 'styles': { 'display':'none' }});
		var icon = 		new Element('span', {'class': 'icon'	});
		var link = 		new Element('span');
		var a =			new Element('a', {'id':'pl' + id, 'class': 'pageEditLink' + status + ' page' + id, 'rel': id }).set('text', title);
		var treeline = 	new Element('div', {'class': 'tree-img'});
		

		/* Action element */
		var iconOnline = icon.clone().adopt(new Element('a', {'class': 'status ' + status, 'rel': id}));
		var iconDelete = icon.clone().adopt(new Element('a', {'class': 'delete', 'rel': id}));
		var iconArticle = icon.clone().adopt(new Element('a', {'class': 'addArticle article', 'rel': id}));
		action.adopt(iconOnline, iconDelete, iconArticle);
		this.addPageActionLinks(action);

		/* Link element */
		link.adopt(a);
		li.adopt(action, link);
		this.addEditLink(li, 'page');
		
		// drag folder icon
		var icon = treeline.clone().addClass('folder').addClass('drag');
		
		// if home page, remove hom from the old home page
		if (home_page == true)
		{
			$$('.folder.home').removeClass('home');
			icon.addClass('home');
		}
		icon.inject(li, 'top');

		// plus / minus icon
		var pm = treeline.clone().addClass('plus').addEvent('click', this.openclose.bind(this)).inject(li, 'top');
		
		/* Get the parent and the tree lines (nodes) */
		var parent = $('page_' + id_parent);
		var treeLines = $$('#page_' + id_parent + ' > .tree-img');
		
		/* Make the li draggable */
		this.makeLinkDraggable(li, 'page');

	
		// Try to get the parent UL container
		if ( container = $(containerName))
		{
			var lis = container.getChildren('li');
			
			// Node lines
			for (var i=0; i < treeLines.length -1; i++)	{ treeline.clone().inject(li, 'top'); }

			// Inject LI at the correct pos
			li.inject(container, 'bottom');
			
			// Correct the upper article nodeline
			if (nb = li.getPrevious() && ! container.getElement('articleContainer' + id_parent))
			{
				// MUI.notification('', containerName);
				nb = li.getPrevious()
				nodeTree = nb.getChildren('.tree-img');
				nodeTree[nodeTree.length-2].removeClass('last').addClass('node');
			}
			
			// Add the page to the page item manager
			this.pageItemManagers[container.id].addItem(li);
		}
		// if no parent container, we will create one.
		else
		{
			// Node lines
			for (var i=0; i < treeLines.length -1; i++)	{ treeline.clone().inject(li, 'top'); }
			
			// container
			container	 = new Element('ul', {'id':containerName});				
			container.adopt(li);
			container.inject(parent.getLast('span'), 'after');

			if ( ! (parent.hasClass('f-open'))) { container.setStyle('display', 'none');	}
			
			// Add one pageItemManager
			this.pageItemManagers[containerName] = this.createPageItemManager(containerName);
		}
		
		// Add Mouse over effects
		this.addMouseOver(li);
	}
	

});


ION.extend({

	treeAddToCookie: function(value)
	{
		var opened = Array();
		if (Cookie.read('tree'))
			opened = (Cookie.read('tree')).split(',');
		if (!opened.contains(value))
		{
			opened.push(value);
			Cookie.write('tree', opened.join(','));
		}
	},
	
	treeDelFromCookie: function(value)
	{
		var opened = Array();
		if (Cookie.read('tree'))
			opened = (Cookie.read('tree')).split(',');
		if (opened.contains(value))
		{
			opened.erase(value);
			Cookie.write('tree', opened.join(','));
		}
	},
	
	editAreaSave: function(id, content)
	{
		MUI.showSpinner();
		
		var id = id.replace('edit_','');
		
		var data = 'view=' + $('view_' + id).value + '&path=' + $('path_' + id).value + '&content=' + content;
		
		new Request.JSON(
		{
			url:base_url + 'admin/setting/save_view',
			data: data,
			onSuccess: function(responseJSON, responseText)
			{
				MUI.hideSpinner();
	
				// Notification
				MUI.notification(responseJSON.message_type, responseJSON.message);
			},
			onFailure: function(xhr)
			{
				MUI.hideSpinner();
	
				// Error notification
				MUI.notification('error', xhr.responseJSON);
			
			}
		}).send();
		
	}
	
});
