<?php
ob_start();
include("autoload.php");
$register = new Session();
$objMain=new MainDAO();
$templateList=$objMain->fetchAllTemplates();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Teejan Label printer</title>
<link href="css/style.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="lib/ext/resources/css/ext-all.css"/>
<link rel="stylesheet" type="text/css" href="css/print.css"/>
<script type="text/javascript" src="lib/ext/adapter/ext/ext-base.js"></script>
<script type="text/javascript" src="lib/ext/ext-all.js"></script>
<script language="javascript" src="jquery-1.10.2.min.js"></script>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript" src="js/unicode.js"></script> 
<script language="javascript"><!--
var count=0;
function toggleAlignText(){
	count++;
	count=count%3;	
	var align=["left","center","right"];
	$("input[id^='text_a']").each(function(){
    	//console.log(this);
    	this.style.textAlign=align[count];
    	//this.setAttribute("value",this.value);
    });	
}

function switchLanguage(lang){
	if(lang=="eng"){
		location.reload();
		}
	else{
		var language="ar";
		var count=0;
		$(".unicode").each(function(){
			this.setAttribute("lang",language);
			var id="text_a"+count++;
			this.setAttribute("id",id);	    	
	    	$("#"+id).addClass("unicode");
	    	changeLanguage(id, language);
	    });
		
		/*$(document).on('keyup','input[type="text"]',function(a){
		var element=a.target;
		if(element.setSelectionRange){
			element.setSelectionRange(0,0);
			}
		});*/
	}
} 

function loadTemplate(tabUrl){
	var basePath="template/";
	var filePath=basePath+tabUrl;
    	$.post( filePath,"",
  	      function( data ) {	      	
		       $("#layout").html(data);
  	      }
  	    );	
}

function loadDBTemplates(templateId){
url="loaddbtemplates.php";
if(templateId!=0){
	Ext.Ajax.request({
	url:url,
	params:{templateId:templateId},
		success:function(response){
			document.getElementById("layout").innerHTML=response.responseText;
		}
		});
	}
}

function extAjaxSave(url,params,callback){
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
	},
	callback:callback
	});

}

function saveAsTemplate(){
var templateName=document.getElementById("templateName").value;
var template=document.getElementById("layout").innerHTML;
	if(templateName!=""){
		extAjaxSave("saveTemplate.php",{name:templateName,template:template},updateTemplatesList);	
		}
	else{
		Ext.Msg.alert("Name required");
		}
}

function deleteTemplate(){
var templateId=document.getElementById("dbTemplates").value;
	if(templateId!="0"){//empty check for select
		extAjaxSave("deleteTemplates.php",{id:templateId},updateTemplatesList);	
	}else{
		Ext.Msg.alert("Select a Template to Delete");
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
		var count=1;
		$(".unicode").each(function(){
			    	console.log(this);
			    	this.value=(count++)*parseInt(autoIncNo);
			    	this.setAttribute("value",this.value);
			    });		    
	}else{
		Ext.Msg.alert("Error","Enter a Valid Number");
	
		}
}

function clearTemplate(){		
	$('input[type="text"]').each(function(){
    	this.value="";
    	this.setAttribute("value","");
    });	
}

function setDateField(){	
	var dateField=document.getElementById("dateField").value;
	if(dateField!=""){
		$("input#text_b").each(function(){
	    	//console.log(this);
	    	this.value=dateField;
	    	this.setAttribute("value",this.value);
	    });	
	}else{
		Ext.Msg.alert("Error","Enter a Text");
		}
}

function toggleLines(buttonObj){		
	if(buttonObj.firstChild.data=="Double Line"){
			buttonObj.firstChild.data="Single Line";
			$(".sub_input_box").show();
			$("#root").removeClass("single_line");
			$("#dateDiv").show();			
			}
	else{
			buttonObj.firstChild.data="Double Line";
			$(".sub_input_box").hide();	
			$("#root").addClass("single_line");			
			$("#dateDiv").hide();		
			}	
}

function resetPrintPossition(){
	var form=Ext.getCmp('myForm').getForm();
	sessionStorage.setItem("top",form.findField('top').getValue());
	sessionStorage.setItem("side",form.findField('side').getValue());
	var newStyle="#layout { margin-top :"+form.findField('top').getValue()+"px; margin-left: "+form.findField('side').getValue()+"px }";
	//var newStyle="#layout{margin-top:}"
	//var newStyle=".input_box { p}";
	
	//var ret=document.styleSheets[2].cssRules[0].cssText.replace(".select_box {",replaceStyle);
	var sheet=document.styleSheets[2];
	sheet.media.appendMedium("print");
	sheet.insertRule(newStyle, sheet.cssRules.length);
}

function alignPrinter(){
	new Ext.Window({
		//	width:400,
		//	height:300,
		id:'alignprinter',
			items:[
				 new Ext.FormPanel({
			        labelWidth: 75, // label settings here cascade unless overridden
			        url:'save-form.php',
			        frame:true,
			        title: 'Simple Form',
			        bodyStyle:'padding:5px 5px 0',
			        width: 350,
			        defaults: {width: 230},
			        defaultType: 'numberfield',
			        id:'myForm',

			        items: [{
			                fieldLabel: 'Top Setting',
			                name: 'top',			            
			                allowBlank:false,
			                value:sessionStorage.getItem("top")!=""?sessionStorage.getItem("top"):5
			            },{
			                fieldLabel: 'Side Setting',
			                name: 'side',
			                allowBlank:false,
			                value:sessionStorage.getItem("side")!=""?sessionStorage.getItem("side"):5
			            }
			        ],

			        buttons: [{
			            text: 'Save',
			            handler:function(btn){
			        		resetPrintPossition();
			            	Ext.getCmp('alignprinter').close();
			            }
			       		 },{
			            text: 'Cancel',
			            handler:function(){
							Ext.getCmp('alignprinter').close();
			            }
				            
			        }]
			    })
					]
			
			}).show();	
}

function updateTemplatesList(){
	var dbTemplatesObj=document.getElementById("dbTemplates");
	dbTemplatesObj.options.length=0;
	Ext.Ajax.request({
		url:"templateList.php",
			success:function(response){
				var respObj=eval(response.responseText);
				for(var i=0;i<respObj.length;i++){
					dbTemplatesObj.add(new Option(respObj[i].name,respObj[i].id));
					}
			},
			failure:function(){
				Ext.Msg.alert("Server Error","Server Error! Please try again");
			}
			});
}



--></script>
<style lang="stylesheet" type="text/css">


</style>


</head>

<body>
	<div class="wapper">
    	<div class="top_part">
        	<div class="logo"><img src="img/logo.jpg" /></div>
            <div class="top_tab_box">
            	<div class="tab">
                	<a href="#" onclick="loadTemplate('smarttab60.html')">SmartTAB60</a>
                </div>
                <div class="tab">
                	<a href="#" onclick="loadTemplate('smarttab30.html')" class="active">SmartTAB30</a>
                </div>
            </div>
            <div class="clear"></div>
            <div class="box2">
            	<div class="sub_box1">
                	<div class="select_box">
                        <select id="dbTemplates" onchange="loadDBTemplates(this.value);">
							<option value="0">Select</option>
							<?php for($c=0;$c<count($templateList);$c++){?>
							<option value="<?php echo $templateList[$c]["id"]; ?>"><?php echo $templateList[$c]["name"]; ?></option>
							<?php
							}
							?>
						</select>
                    </div>
                    <div class="button">
                    	<a href="#" onclick="deleteTemplate()">Del</a>
                    </div>
                    <div class="clear"></div>
                </div>
                
                <div class="sub_box1">
                	<div class="select_box">
                    	<input type="text" id="templateName" />
                    </div>
                    <div class="button">
                    	<a href="#" onclick="saveAsTemplate();">Add</a>
                    </div>
                    <div class="clear"></div>
                </div>
                
                <div class="sub_box3">
                	<div class="select_box">
                    	<select onchange="switchLanguage(this.value);"  id="languageSelect">
                        	<option value="eng">English</option>
                            <option value="ab">Arabic</option>
                        </select>
                    </div>
                    <div class="button">
                    	<a href="#" onclick="toggleAlignText()">--</a>
                    </div>
                    <div class="clear"></div>
                </div>
                
                <div class="box2_tab">
                	<div class="button">
                    	<a href="#" onclick="alignPrinter();">Align Print</a>
                    </div>
                	
                    <div class="button">
                    	<a href="#" onclick="window.print();">Print</a>
                    </div>
                    <div class="button">
                    	<a href="#" onclick="toggleLines(this);">Single Line</a>
                    </div>
                    
                    <div class="clear"></div>
                </div>
                
                <div class="clear"></div>
            </div>
            
            <div class="box3">
            	<div class="box3_sub">
                	<p>Auto Number lncrement</p>
                    <input type="text" id="autoIncNo"/>
                    <div class="button">
                    	<a href="#" onclick="setAutoIncNo();">Apply</a>
                    </div>
                    <div class="clear"></div>
                </div>
                <div id="dateDiv" class="box3_sub">
                	<p>Auto Add Date</p>
                    <input type="text" id="dateField" />
                    <div class="button">
                    	<a href="#" onclick="setDateField();">Apply</a>
                    </div>
                    <div class="clear"></div>
                </div>
                
                <div class="clear_box">
                	<div class="button">
                    	<a href="#" onclick="clearTemplate();">Clear Template</a>
                    </div>
                </div>
                
                <div class="clear"></div>
            </div>
        </div>
        
        <div id="layout">
        contents goes here ...
        
        </div>
        <div class="clear"></div>
    </div>
</body>
<script language="javascript">
$(document).on('focusout','input[type="text"]',function(a){
	console.log(a.target.value);
	a.target.setAttribute("value",a.target.value);
	});
//int function 
//switchLanguage($("#languageSelect").val());
loadTemplate('smarttab30.html');
</script>
</html>
