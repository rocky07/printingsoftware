<?php

ob_start();
include("autoload.php");
$register = new Session();

$objMain=new MainDAO();
$templateList=$objMain->fetchAllTemplates();
?>
<html>
<head>
<script language="javascript" src="jquery-1.5.1.min.js"></script>
<script language="javascript">
function loadTemplate(tab){
	var basePath="template/";
	var filePath=basePath+tab.value;
	if(tab.value!=0){
    	$.post( filePath,"",
  	      function( data ) {	      	
		       $("#layout").html(data);
  	      }
  	    );	
	}
}

</script>
<style type="text/css">
@MEDIA print {
	#layout{
	
	}
	#toolbar{
	visibility:hidden;
	}
}
</style>
</head>
<body>
<div id="wrapper">
<div id="toolbar">
<select onchange="loadTemplate(this);">
<option value="0">None</option>
<option value="smarttab30.html"> Smart Tab30</option>
<option value="smarttab60.html">Smart Tab60</option>
</select>

<select>
<option value="">Select</option>
<?php for($c=0;$c<count($templateList);$c++){?>
<option value="<?php echo $templateList[$c]["id"]; ?>"><?php echo $templateList[$c]["name"]; ?></option>
<?php
}
?>
</select>

<input type="button" value="Print" onclick="window.print();"/>
</div>
<div id="layout">
</div>
</div>
</body>
</html>