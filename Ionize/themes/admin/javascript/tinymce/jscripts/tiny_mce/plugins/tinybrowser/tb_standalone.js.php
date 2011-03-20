<?php
require_once("config_tinybrowser.php");

$tbpath = pathinfo($_SERVER['SCRIPT_NAME']);
$tbmain = $tbpath['dirname'].'/tinybrowser.php';
?>

function tinyBrowserPopUp(type,formelementid,folder) 
{
	var xPos = (window.screen.availWidth/2) - (<?php echo $tinybrowser['window']['width']+15; ?>/2);
	var yPos = (window.screen.availHeight/2) - (<?php echo $tinybrowser['window']['height']+15; ?>/2);
   tburl = "<?php echo $tbmain; ?>" + "?type=" + type + "&feid=" + formelementid;
   if (folder !== undefined) tburl += "&folder="+folder+"%2F";
   newwindow=window.open(tburl,'tinybrowser','height=<?php echo $tinybrowser['window']['height']+15; ?>,width=<?php echo $tinybrowser['window']['width']+15; ?>, left='+xPos+', top='+yPos+',scrollbars=yes,resizable=yes');
   if (window.focus) {newwindow.focus()}
   return false;
}
