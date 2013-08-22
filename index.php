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
		extAjaxSave("deleteTemplates.php",{id:templateId});	
	}else{
		Ext.Msg.alert("Select a Template to Deletes");
	}
}
function findLabelDivs(id){
	var allElements=document.getElementsByTagName("div");
	var divArray=new Array();
	for(var i=0;i<allElements.length;i++){	
		if(allElements[i].id==id){
			divArray.push(allElements[i]);
			}
	}
return divArray;
}

function setAutoIncNo(){	
	var autoIncNo=document.getElementById("autoIncNo").value;
	if(autoIncNo!="" && !isNaN(autoIncNo)){
	var divElements=findLabelDivs("roweditable");
		for(var i=0;i<divElements.length;i++){
				divElements[i].innerHTML=(i+1)*parseInt(autoIncNo);
			}
	}else{
		Ext.Msg.alert("Error","Enter a Valid Number");
	
		}
}

function clearTemplate(){		
	var divElements=findLabelDivs("roweditable");
		for(var i=0;i<divElements.length;i++){
				divElements[i].innerHTML="";
			}	
}

function setDateField(){	
	var dateField=document.getElementById("dateField").value;
	if(dateField!=""){
	var divElements=findLabelDivs("roweditable");
		for(var i=0;i<divElements.length;i++){
				divElements[i].innerHTML+=dateField;
			}
	}else{
		Ext.Msg.alert("Error","Enter a Text");
		}
}

function toggleLines(buttonObj){
	var divElements=findLabelDivs("roweditable");
	if(buttonObj.value=="DoubleLine"){
		buttonObj.value="SingleLine"
			for(var i=0;i<divElements.length;i++){
				divElements[i].innerHTML+="<br/><hr/><br/>";
				}
		}
	else{
		buttonObj.value="DoubleLine";
		for(var i=0;i<divElements.length;i++){
			divElements[i].innerHTML="";
			}
		}
	
}

function alignPrinter(){
	new Ext.Window({
			width:400,
			height:300,
			items:[{
					
					}
					]
			
			}).show();	
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

<select id="dbTemplates" onchange="loadDBTemplates(this.value);">
<option value="0">Select</option>
<?php for($c=0;$c<count($templateList);$c++){?>
<option value="<?php echo $templateList[$c]["id"]; ?>"><?php echo $templateList[$c]["name"]; ?></option>
<?php
}
?>
</select>
<input type="button" value="Delete" onclick="deleteTemplate()" />
Name : <input type="text" name="templateName" id="templateName"/> 
<input type="button" oncliCK="saveAsTemplate();" value="Save Template"/>
<input type="text" id="autoIncNo" />
<input type="button" onclick="setAutoIncNo();" value="Apply"/>
<input type="text" id="dateField" />
<input type="button" onclick="setDateField();" value="Apply"/>
<input type="button" onclick="toggleLines(this);" value="DoubleLines"/>
<input type="button" onclick="clearTemplate();" value="Clear Template"/>
<input type="button" onclick="alignPrinter();" value="Align Printer"/>
<input type="button" value="Print" onclick="window.print();"/>
</div>
<div id="layout">
</div>
</div>
</body>
</html>