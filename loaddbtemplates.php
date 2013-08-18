<?php

ob_start();
include("autoload.php");
$register = new Session();
$id=$_REQUEST["templateId"];
$objMain=new MainDAO();
$templateList=$objMain->fetchTemplates($id);
echo $templateList[0]["templates"];
?>

