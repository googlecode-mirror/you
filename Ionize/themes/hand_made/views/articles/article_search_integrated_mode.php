<!-- 

	Search module view : Integrated mode
	
-->

<div class="article">
	
	<!-- Standard article's data -->
	
	<ion:title tag="h2" />
	
	<ion:subtitle tag="h3" class="subtitle" />
	
	<ion:content />
	
	<!--
		
		Tags are defined in /modules/Search/tags.php class.
		
		ion:search 			is the module's parent tag.
		ion:searchform 		return the form view /modules/Search/views/search_form.php
		ion:results 		is the enclosing tag for results.
		ion:title 			returns the result article's title
		ion:url 			returns the result article's URL
		
	-->
	
	<ion:search>
		

		<!-- Form tag : displays the form only of no POST data are catched by the module -->
		<ion:searchform />
		
		
		<!-- Results tags : Display results only if POST data are catched by the module -->
		<ion:searchresults >
		
			<h3><ion:translation term="module_search_results_title" /> <ion:realm /></h3>
			
			<ion:results>
			
				<p><ion:result field="page_title" /> : <a href="<ion:base_url lang="true" /><ion:result field="page_url" />/<ion:result field="url" />"><ion:result field="title" /></a> </p>
			
			</ion:results>
		
		</ion:searchresults>

	
	</ion:search>

	
</div>
