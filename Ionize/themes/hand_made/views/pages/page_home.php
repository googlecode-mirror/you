<ion:partial path="pages/header_home" />

			
		<div id="main">
	
			<div id="sidebar-home">
			
				<div>
					<div class="content-top"></div>
					<div class="content-left"></div>
				
					<!-- Login form / info example -->
					<div class="side-bloc">
					
						<h2>Login</h2>
					
						<div id="user-control">
							<!-- Login / Register Forms -->
							<ion:login form_view="access/login_form" logged_view="access/login_info" />
						</div>
					
					</div>
					
					<!--
						Example of User menu
						This menu is not linked to the main menu.
						It can be called from any view
						Notice than the "menu" attibute must be set to the menu name. See "Manage menus" in Ionize backend
					-->
						
					<div class="side-bloc">
						
						<h2><ion:translation term="title_user_menu" /></h2>

						<ul class="sidelist">
						
							<ion:navigation level="0" menu="usermenu" active_class="active" >
								
								<li><a class="<ion:active_class />" href="<ion:url />"><ion:title /></a></li>
							
							</ion:navigation>
						
						</ul>
						
	
						<h2><ion:translation term="title_last_news" /></h2>
						
						<!-- Last articles attributes : 
							 num : 	Number of wihed last article
							 paragraph : Limit the displayed content paragraph to...
							 from : page name from which you wish to show last articles. If not used, displays the last articles from all website
							 view : View to use for the output
							 filter : filter on the given fields (replace the old "with" attribute)
							 		Example of filters :	filter="title" 					Means articles with title not empty
							 								filter="title:!=''" 				... the same : article title different from ''
							 								filter="title|author='admin'"		Title not empty and author is "admin"
						-->
						
							<ion:articles num="3" paragraph="1" from="news" view="articles/last_news" filter="title:!=''" >
						
								<ion:article />
						
							</ion:articles>
				
					</div>
				
				</div>
			
			</div>			
	
			<div id="content-home">
			
				<div class="content-top"></div>
				<div class="content-left"></div>
			
				<!-- Articles -->
				<div class="articles">
					
										
					<!-- Each article can have its own template.
						 So ion:article is the result of a callback function
					-->
					<ion:articles filter="title:!=''" order_by="date DESC">
					
						<ion:article />
					
					</ion:articles>

					<!-- Pagination links -->
					<ion:pagination filter="title:!=''" full_tag="div" class="pagination" />
				
				</div>

				<ion:partial path="pages/page_footer" />

			</div>
	
		</div>
		
	
	</div>	

			
	<script type="text/javascript">
	
		var scroller = new ImageScroller('hpicturelist', {buttons: {prec:'scrollButtonPrec', next:'scrollButtonNext'}, comment:'picture-comment'});

	</script>
		


</body>

</html>