<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
	"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<ion:current_lang />" lang="<ion:current_lang />">
<head>
	<title><ion:site_title /> , <ion:meta_title /></title>

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

	<ion:google_analytics />

</head>


<body>

<div class="wrap">


	<div id="container">

		<!-- Lang menu -->
		<div id="languages">
			<ul>
				<ion:languages>
					<li><a class="<ion:active_class />" href="<ion:base_url /><ion:code />/<ion:page:name />"><ion:name /></a></li>
				</ion:languages>
			</ul>
		</div>


		<!-- Level 0 navigation -->
		<ul id="navigation">
		
			<ion:navigation level="0" active_class="active" >
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
								<ion:medias type="picture">
									<img src="<ion:src folder="medium" />" />
								</ion:medias>
							</div>
													
						</div>
					
					</div>
				</div>
			
			</div>

		</div>


		<div id="main">
	
			<div id="sidebar">
			
				<div>
					<div class="side-bloc">
					
						<ion:articles>
						
							<ion:title tag="h2" />
							
							<ion:content />
						
						</ion:articles>
					
					</div>
				</div>

			</div>
		
			<div id="content">
			
				<div class="content-top"></div>
				<div class="content-left"></div>
			
				<!-- Articles -->
				<div class="articles">

					<div class="article">
						
						<h2><ion:title /></h2>
						
						<ion:validation_errors tag="p" class="login-error-message" />
						
						<form id="form-register" name="" class="form" method="post" action="<ion:base_url />user/register">
		
							<h3 class="legend">Vos coordonnées</h3>
		
							<!-- Screen Name -->
							<dl>
								<dt>
									<label for="screen_name" class="required <ion:form_error_class input="screen_name" class="errorlabel"/>">Prénom / Nom</label>
								</dt>
								<dd>
									<span class="required">
										<input id="screen_name" name="screen_name" class="inputtext <ion:form_error_class input="screen_name" class="error-field"/>" type="text" value="<ion:set_value input="screen_name" />" />
										<ion:form_error input="screen_name" tag="p" class="error-message " />
									</span>
								</dd>
							</dl>
		
							<!-- Email -->
							<dl>
								<dt>
									<label for="email" class="required <ion:form_error_class input="email" class="errorlabel"/>">Email</label>
								</dt>
								<dd>
									<span class="required">
										<input id="email" name="email" class="inputtext w200 <ion:form_error_class input="email" class="error-field"/>" type="text" value="<ion:set_value input="email" />" />
										<ion:form_error input="email" tag="p" class="error-message " />
									</span>
								</dd>
							</dl>
		
		
							<h3 class="legend">Votre compte d'accès</h3>
		
							<!-- Login -->
							<dl>
								<dt>
									<label for="username" class="required <ion:form_error_class input="username" class="errorlabel"/>">Login</label>
								</dt>
								<dd>
									<span class="required">
										<input id="username" name="username" class="inputtext <ion:form_error_class input="username" class="error-field"/>" type="text" value="<ion:set_value input="username" />" />
										<ion:form_error input="username" tag="p" class="error-message " />
									</span>
								</dd>
							</dl>
						
							<!-- Password -->
							<dl>
								<dt>
									<label for="password" class="required <ion:form_error_class input="password" class="errorlabel"/>" >Mot de passe</label>
								</dt>
								<dd>
									<span class="required">
										<input id="password" name="password" class="inputtext <ion:form_error_class input="password" class="error-field"/>" type="password" value="<ion:set_value input="password" />" />
										<ion:form_error input="password" tag="p" class="error-message " />
									</span>
								</dd>
							</dl>
							
							<!-- Password 2 -->
							<dl>
								<dt>
									<label for="password2"  class="required <ion:form_error_class input="password2" class="errorlabel"/>">Confirmez votre mot de passe</label>
								</dt>
								<dd>
									<span class="required">
										<input id="password2" name="password2" class="inputtext <ion:form_error_class input="password2" class="error-field"/>" type="password" value="<ion:set_value input="password2" />" />
										<ion:form_error input="password2" tag="p" class="error-message " />
									</span>
								</dd>
							</dl>
							
							
							<!-- Submit -->
							<dl>
								<dt><label></label></dt>
								<dd>
									<input id="loginSubmit" type="submit" class="inputsubmit" value="<ion:translation term="loginSubmit" />"/>
								</dd>
							</dl>
						
						</form>
					
					</div>
					
					<p class="note">template : <b>pages/page_register</b></p>


				</div>

				<ion:partial path="pages/page_footer" />
			
			</div>
		
		</div>
	
	</div>	

</body>

</html>