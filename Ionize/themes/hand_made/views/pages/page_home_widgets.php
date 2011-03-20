<ion:partial path="pages/header_home" />

			
		<div id="main">
	
			<div id="sidebar-home">
			
				<div>
					<div class="content-top"></div>
					<div class="content-left"></div>
				
					<div class="side-bloc">
	
						<h2><ion:translation term="title_last_news" /></h2>
						
						<!-- Last articles attributes : 
							 num : 	Number of wihed last article
							 paragraph : Limit the displayed content paragraph to...
							 from : page name from which you wish to show last articles. If not used, displays the last articles from all website
							 view : View to use for the output
							 with : filter on the given fields
							 		Example of filters :	with="title" 					Means articles with title not empty
							 								with="title:!=''" 				... the same : article title different from ''
							 								with="title|author='admin'"		Title not empty and author is "admin"
						-->
						
						<ion:articles num="3" paragraph="1" from="news" view="articles/last_news" filter="title:!=''" >
					
							<ion:article />
					
						</ion:articles>
						
					</div>

				</div>


				<!-- Widgets example : Your php.ini must have allow_url_fopen set to "On" -->

				<div style="margin-top:20px;">
					<div class="content-top"></div>
					<div class="content-left small"></div>
				
					<div class="side-bloc">
	
						<ion:widget name="weather" id="FRXX0076" unit="c" />
						
						<p class="note"><ion:translation term="widget_weather_text" /></p>

					</div>
				
				</div>


				<div style="margin-top:20px;">
					<div class="content-top"></div>
					<div class="content-left small"></div>
				
					<div class="side-bloc">
	
						<ion:widget name="rss" url="http://www.ecrans.fr/spip.php?page=backend" nb="3" />
						
						<p class="note"><ion:translation term="widget_rss_ecrans_text" /></p>

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
					<ion:articles filter="title:!=''">
					
						<ion:article />
					
					</ion:articles>

					<!-- Pagination links -->
					<ion:pagination filter="title:!=''" first_link="" last_link="" />
				
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