<style>
<!--
@page {
   size: A4;
   margin-top:70px;
   margin-left:0px;
}

-->
</style>
<body onload="window.print()">
<div>
<?php 
for($i=0;$i<=20;$i++){
	$j=$i*10;
?>
<div style="border-top:2px	solid black;float:left;width:30px; margin-top:<?php echo $j ?>"><?php echo $j?></div>
<?php 
}
?>
<div>
</div>
<div style="clear:both"></div>
<center><h2>Print Template Page</h2></center>
<?php 
for($i=0;$i<=20;$i++){
	$j=$i*5;
	?>
<div style="height: 5px; text-align: center;margin: 0 <?php echo $j?> 0 <?php echo $j?>">
<hr color="black">
  <span style="position: relative; top: -0.5em;">
    Side <?php echo $j?> 
  </span>
</div>
<br>
	<?php 	
}
?>
</div>
</body>