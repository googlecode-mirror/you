
<div class="article">
	

	<!-- Formatted date -->
 	<div class="date">
 		<p class="day"><ion:date format="d" manip="ucfirst" /></p>
 		<p class="month"><ion:date format="M" /></p>
 		<p class="year"><ion:date format="Y" /></p>
 	</div>

	<div class="article-content" style="margin-left:55px;">
	
		<!-- Article link on title -->
		<h2><a href="<ion:url />"><ion:title /></a></h2>
		
		<!-- Display the content -->
		<ion:content />		
		
		
		<!--
			Article Picture gallery example
			This gallery is powered by the Multibox script
		-->		
		<div class="pictures">
		
			<ion:medias type="picture">
			
				<a class="picture mb<ion:id_article />" href="<ion:src />" title="<ion:title />" rel="[images]"><img title="<ion:title />" alt="<ion:alt />" src="<ion:src folder="small" />" /></a>
			
			</ion:medias>
		
		</div>


		<!-- 
			MP3 integration example
		-->	
		<ion:medias type="music">
		
			<p>MP3 : <ion:title /></p>
			
			<div class="music"  id="m<ion:id_media />">
				<p>Your browser has not the flashplayer installed or javascript is disabled.
				<br/>You can :
				</p>
				<ol>
					<li><a href="http://get.adobe.com/fr/flashplayer/">Get the Flash Player</a> to ear this music.</li>
					<li>Enable javascript if it is disabled</li>
				</ol>
			</div>
		
			<script type="text/javascript">
	
					var m<ion:id_media />;
					m<ion:id_media /> = new SWFObject('<ion:theme_url />flash/mediaplayer/player.swf','player','','20','9');
					m<ion:id_media />.addParam('allowscriptaccess','always');
					m<ion:id_media />.addParam('flashvars',"file=<ion:base_url /><ion:path />&controlbar=bottom&autostart=false&image=<ion:base_url /><ion:base_path /><ion:file_name />.jpg");
					m<ion:id_media />.write("m<ion:id_media />");
							
			</script>
		</ion:medias>


		<!-- 
			Video integration example
		-->	
		<ion:medias type="video">
	
			<div class="video" style="width:455px; height:<ion:field name="height" from="media" />px" id="v<ion:id_media />">
				<p>Your browser has not the flashplayer installed or javascript is disabled.
				<br/>You can :
				</p>
				<ol>
					<li><a href="http://get.adobe.com/fr/flashplayer/">Get the Flash Player</a> to see this video.</li>
					<li>Enable javascript if it is disabled</li>
				</ol>
			</div>
	
			<script type="text/javascript">
	
					var s<ion:id_media />;
					s<ion:id_media /> = new SWFObject('<ion:theme_url />flash/mediaplayer/player.swf','player','455','340','9');
					s<ion:id_media />.addParam('allowfullscreen','true');
					s<ion:id_media />.addParam('allowscriptaccess','always');
					s<ion:id_media />.addParam('flashvars',"file=<ion:base_url /><ion:path />&controlbar=bottom&stretching=fill&image=<ion:base_url /><ion:base_path /><ion:file_name />.jpg");
					s<ion:id_media />.write("v<ion:id_media />");
							
			</script>
	
		</ion:medias>


		<!-- 
			Other files example
		-->	
		<ion:medias type="file" extension="doc">

			<p><a href="<ion:src />" title="<ion:title />" ><ion:file_name /></a></p>	
	
		</ion:medias>



		<!-- 
			Multibox gallery initialization.
			Each gallery has an ID, which is the article ID.
		 -->	
		<script type="text/javascript">
		
			var box<ion:id_article />;
			
			window.addEvents({
				domready: function()
				{
					box<ion:id_article /> = new multiBox({
						'mbClass': '.mb<ion:id_article />',
						'container': $(document.body),
						'addChain': false,
						'recalcTop': false,
						'useOverlay': true
					});
				}
			});
			
		</script>
		
		
		<p class="note">view : <b>articles/article_blog</b></p>

	</div>

</div>