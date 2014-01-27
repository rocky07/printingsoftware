<?php
ob_start();
include("autoload.php");
$register = new Session();
$objMain=new MainDAO();
$returnString=$objMain->fetchAllTemplates();
//echo $returnString;
echo  json_encode($returnString);
?>

