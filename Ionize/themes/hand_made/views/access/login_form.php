<div id="login">

	<ion:validation_errors tag="p" class="login-error-message" />

	<form action="<ion:base_url />user/login" method="post" name="loginForm" id="loginForm" class="form">
	
		<input type="hidden" id="check" name="check" value="" />
	
		<!-- Login -->
		<label for="username" class="<ion:form_error_class input="username" class="errorlabel"/>">
			<ion:translation term="form_label_name" />
		</label>
		<input type="text" id="username" name="username" alt="<ion:translation term="form_alt_username" />" class="inputtext w120 <ion:form_error_class input="username" class="error-field"/>" value="<ion:set_value input="username" />" />
		<ion:form_error input="username" tag="p" class="error-message " />

		<label for="password" class="<ion:form_error_class input="password" class="errorlabel"/>">
			<ion:translation term="form_label_password" />
		</label>
		<input type="password" id="password" name="password" alt="<ion:translation term="form_alt_password" />" class="inputtext w120 <ion:form_error_class input="password" class="error-field"/>" value="<ion:set_value input="password" />" />
		<ion:form_error input="password" tag="p" class="error-message " />
	
		<input id="loginSubmit" type="submit" class="inputsubmit" value="<ion:translation term="loginSubmit" />"/>
		
	</form>
</div>


<p><a href="<ion:base_url />register"><ion:translation term="register_home_text" /></a></p>




<script type="text/javascript">		
//<![CDATA[
    
	/*
	 * Security
	 */
	$('check').value = '<ion:config item="form_antispam_key" />';


//]]>
</script>
