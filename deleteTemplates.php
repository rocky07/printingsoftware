<?php
ob_start();
include("autoload.php");
$register = new Session();
$id=$_REQUEST['id'];
$objMain=new MainDAO();
$returnString="";
if($objMain->deleteTemplates($id)){
	$returnString="[{status:true,msg:'Template Deleted Successfully'}]";
}
else{
	$returnString="[{status:false,msg:'Unable to Delete Template'$id}]";
}
echo $returnString;
?>

