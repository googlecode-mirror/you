<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
	"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<ion:current_lang />" lang="<ion:current_lang />">
<head>
	<title><ion:site_title />, <ion:meta_title /></title>

	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

	<meta http-equiv="Content-Language" content="<ion:current_lang />" />
	<meta name="keywords" content="<ion:meta_keywords />" />
	<meta name="description" content="<ion:meta_description />" />
	
	<meta http-equiv="imagetoolbar" content="no" />
	<meta name="revisit-after" content="15 days" />
	
	<link rel="shortcut icon" href="<ion:theme_url />assets/images/favicon.ico" type="image/x-icon" >

	<link rel="stylesheet" href="<ion:theme_url />assets/css/screen.css" />
	<link rel="stylesheet" href="<ion:theme_url />javascript/multibox/multibox.css" />
	
	<script type="text/javascript" src="<ion:theme_url />javascript/mootools-1.2.4-core-yc.js"></script>
	<script type="text/javascript" src="<ion:theme_url />javascript/mootools-1.2.4.1-more.js"></script>
	<script type="text/javascript" src="<ion:theme_url />javascript/imageScroller.js"></script>
	<script type="text/javascript" src="<ion:theme_url />javascript/multibox/overlay.js"></script>
	<script type="text/javascript" src="<ion:theme_url />javascript/multibox/multibox.js"></script>
	<script type="text/javascript" src="<ion:theme_url />flash/mediaplayer/swfobject.js"></script>

	<!-- 
		Displays the Google code defined in Ionize's Advanced settings panel
	-->
	<ion:google_analytics />

</head>


<body>

	<div id="container">

		<!-- 
			Lang menu
			The "url" chid tag returns the complete absolute URL to the page, for each language
		-->
		<div id="languages">
			
			<ul>
			
				<ion:languages>
			
					<li><a class="<ion:active_class />" href="<ion:url />"><ion:name /></a></li>
			
				</ion:languages>
			
			</ul>
		
		</div>


		<!-- 
			Level 0 navigation 
			
			If the attribute "menu" is not set, the tag will take the "main" menu content
			Notice : The "menu" attributes uses the menu name, not the menu title !
			
			The "url" chid tag returns the complete absolute URL to the page, for the current language
			If the page is defined as "home page" in ionize, the returned URL for this page will be : 
			- "/" 	for the default language
			- "/xx" for other languages
			
		-->
		<ul id="navigation">
		
			<ion:navigation level="0" active_class="active" menu="main" >
			
				<li><a class="<ion:active_class />" href="<ion:url />"><ion:title /></a></li>
			
			</ion:navigation>
		
		</ul>
		

		<div id="header">
		
			<div id="header-content">
				
				<a id="logo" title="<ion:translation term="home_logo" />" href="<ion:base_url /><ion:current_lang />"><img src="<ion:theme_url />assets/images/logo_handmade.gif" alt="<ion:translation term="home_logo" />" /></a>
						
				<div id="hpicture-container">
					
					<div id="hpicture">
					
						<div id="hpicturelist" class="internal">
							
							<div class="scroller">
							
								<!--
									No diaporama in this page : Limit to one picture
								-->
								<ion:medias type="picture">
									<img src="<ion:src folder="medium" />" num="1" />
								</ion:medias>
								
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
