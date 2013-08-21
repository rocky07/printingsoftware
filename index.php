<?php
ob_start();
include("autoload.php");
$register = new Session();

$objMain=new MainDAO();
$templateList=$objMain->fetchAllTemplates();
?>
<html>
<head>

<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=iso-8859-6">
<META HTTP-EQUIV="Content-language" CONTENT="ar">

<link rel="stylesheet" type="text/css" href="lib/ext/resources/css/ext-all.css"/>
<script type="text/javascript" src="lib/ext/adapter/ext/ext-base.js"></script>
<script type="text/javascript" src="lib/ext/ext-all.js"></script>
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

function loadDBTemplates(templateId){
url="loaddbtemplates.php";
Ext.Ajax.request({
url:url,
params:{templateId:templateId},
	success:function(response){
		document.getElementById("layout").innerHTML=response.responseText;
	}
	});
}

function extAjaxSave(url,params){
Ext.Ajax.request({
url:url,
params:params,
	success:function(response){
		var respObj=eval(response.responseText)[0];
		if(respObj.status=="true"){
			Ext.Msg.alert("Success",respObj.msg);
			}
		else if(respObj.status=="duplicate"){
			Ext.Msg.alert("Duplicate",respObj.msg);
			}
		else if(respObj.status=="false"){
			Ext.Msg.alert("Failure",respObj.msg);
			}
		else{
			Ext.Msg.alert("",respObj.msg);
			}
	},
	failure:function(){
		Ext.Msg.alert("Server Error","Server Error! Please try again");
	}
	});

}

function saveAsTemplate(){
var templateName=document.getElementById("templateName").value;
var template=document.getElementById("layout").innerHTML;
	if(templateName!=""){
		extAjaxSave("saveTemplate.php",{name:templateName,template:template});	
		}
	else{
		Ext.Msg.alert("Name required");
		}
}

function deleteTemplate(){
var templateId=document.getElementById("dbTemplates").value;
	if(templateId!=""){//empty check for select
		extAjaxSave("deleteTemplate.php",{id:templateId});	
	}else{
		Ext.Msgalert("Name Required");
	}
}

</script>
<style type="text/css">
/* use Scheherazade - Regular in .woff format */

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

<select id="dbTemplates" onchange="loadDBTemplates(this.value);">
<option value="0">Select</option>
<?php for($c=0;$c<count($templateList);$c++){?>
<option value="<?php echo $templateList[$c]["id"]; ?>"><?php echo $templateList[$c]["name"]; ?></option>
<?php
}
?>
</select>
<input type="button" name="Delete" onclick="deleteTemplate()" />
Name : <input type="text" name="templateName" id="templateName"/> 
<input type="button" oncliCK="saveAsTemplate();" value="Save Template"/>
<input type="button" value="Print" onclick="window.print();"/>
</div>
<div id="layout">
</div>
</div>
</body>
</html>
