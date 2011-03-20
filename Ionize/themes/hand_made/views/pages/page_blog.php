<ion:partial path="pages/header" />

		<div id="main">
	
			<div id="sidebar">
			
				<div>
				
					<div class="side-bloc">
	
						<h2><ion:translation term="title_categories" /></h2>
						
						<!-- Categories link example -->
						<ul id="categories" class="sidelist">
							
							<ion:categories>
							
								<li><a class="<ion:active_class />" href="<ion:url />"><ion:title /></a></li>
							
							</ion:categories>
							
						</ul>
						
						
						<!-- Archives links example -->
						<h2><ion:translation term="title_archives" /></h2>
						
						<ul id="archives" class="sidelist">
							
							<ion:archives with_month="true">
							
								<li><a class="<ion:active_class />" href="<ion:url />"><ion:period /> - (<ion:nb /> articles)</a></li>
							
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
								
					<!-- Pagination links -->
					<ion:pagination filter="title:!=''" first_link="" last_link="" full_tag="div" class="pagination" />

					<!-- 
						Loop into articles linked to this page
					-->
					<ion:articles filter="title:!=''">
					
							<!-- Display each article with its own view -->
							<ion:article />
					
					</ion:articles>
				
				</div>

				<div id="footer">
					<p><ion:translation term="footer_copyright" /> <span><ion:translation term="footer_studio" /></span> | <ion:translation term="footer_studio_description" /> | <ion:translation term="footer_email" /></p>
				</div>

			</div>
	
		</div>
	
	</div>	

</body>

</html>