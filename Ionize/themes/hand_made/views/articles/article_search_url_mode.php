<!-- 

	Search module view : URL mode
	
	The form sends the data to the Search module function : find_pure_php()
	This function get the data, do the search and prints out a pure PHP view.
	
	Because we are in a standard controller call, the returned view has no access to page and article data.
	
	This way of using module is useful for XHR calls (Ajax).

	Notice : The Ajax part is not included in this example

-->

<div class="article">

	<ion:title tag="h2" />

	<ion:subtitle tag="h3" class="subtitle" />
		
	<ion:content />

	<!-- 
		The form will be processed by the function find_pure_php() of the /modules/Search/controllers/search.php controller
		In this example, the "find_pure_php" function will display the view /modules/Search/views/results_pure_php.php
	-->
	<form method="post" action="<ion:base_url lang="true" />search/find_pure_php">
	
		<input id="search-input" type="text" name="realm" value="" />
	
		<input type="submit" class="searchbutton" value="<ion:translation term="module_search_button_start" />" />
	
	</form>

</div>
