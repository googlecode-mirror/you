
<div class="article">
	
	<h2><a href="<ion:url />"><ion:title /></a></h2>
	
	<p class="article-date"><ion:date format="d.m.Y" /></p>
	
	<ion:content />
	
	<div class="pictures">
	
		<ion:medias type="picture">
		
			<a class="picture mb<ion:id_article />" href="<ion:src />" title="<ion:title />" rel="[images]"><img title="<ion:title />" alt="<ion:alt />" src="<ion:src folder="small" />" /></a>
		
		</ion:pictures>
	
	</div>

	<script type="text/javascript">
	
		var box<ion:id_article />;
		
		window.addEvents({
			domready: function()
			{
				box<ion:id_article /> = new multiBox({
					mbClass: '.mb<ion:id_article />',
					container: $(document.body),
					addChain: false,
					recalcTop: false,
					useOverlay: true
				});
			}
		});
		
		
	</script>
	
	<p><a href="<ion:url />"><ion:translation term="article_read_more" /></a></p>
	
	<p class="note">view : <b>articles/intro_article</b></p>

</div>