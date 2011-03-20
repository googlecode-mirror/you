<ion:partial path="pages/header" />


		<div id="main">
	
			<div id="sidebar">
			
				<div>
				
					<div class="side-bloc">
	
						<!-- Level 1 sub navigation -->
						<ul id="subNavigation" class="sidelist">
						
							<ion:navigation level="1" active_class="active" >
								
								<li><a class="<ion:active_class />" href="<ion:url lang="true" />"><ion:title /></a></li>
							
							</ion:navigation>
					
						</ul>

						<!-- Level 1 navigation 
							 This is also possible (remove the \ before "ion" in the tag ):
						<\ion:navigation level="1" active_class="active" view="default/subnavigation" />
						-->


						<!-- Categories -->
						<h2><ion:translation term="categories" /></h2>
						
						<ul class="sidelist">
						
							<ion:categories>
							
								<li><a href="<ion:url lang="true" />"><ion:name /></a></li>
							
							</ion:categories>
						
						</ul>

						
						<!-- Archives -->
						<h2><ion:translation term="archives" /></h2>
						
						<ul class="sidelist">
						
							<ion:archives>
							
								<li><a href="<ion:url lang="true" />"><ion:period /> - <ion:nb /></a></li>
							
							</ion:archives>
						
						</ul>

					
					</div>


				
				</div>
			
			</div>			
	
			<div id="content">
			
				<div class="content-top"></div>
				<div class="content-left"></div>
			
				<!-- Articles -->
				<div class="articles">
					
										
					<!-- Each article can have its own template.
						 So ion:article is he result of a callback function
					-->
					<ion:articles filter="title:!=''">
					
						<ion:article />
					
					</ion:articles>
				
					<!-- Pagination links -->
					<ion:pagination filter="title:!=''" full_tag="div" class=".pagination" next_link="&raquo;" />
				
				</div>

				<ion:partial path="pages/page_footer" />
			
			</div>
	
		</div>
	
	</div>	

</body>

</html>