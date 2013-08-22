<?php
ob_start();
include("autoload.php");
$register = new Session();
$name=$_REQUEST["name"];
$template=$_REQUEST["template"];
$objMain=new MainDAO();
$returnString="";
if($objMain->saveAsTemplates($name,$template)){
	$returnString="[{status:true,msg:'Template Saved Successfully'}]";
}
else{
	$returnString="[{status:false,msg:'Unable to Save Template'}]";
}
echo $returnString;
?>

