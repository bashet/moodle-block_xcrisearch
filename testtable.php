<html>
<head>
<style>


.hidedetail	{
	visibility:hidden;
	display:none;
}

.showdetail	{
	visibility:block;
	display:inline;
}


.minus	{
	background-image:url('minus.gif');
}

.plus	{
	background-image:url('plus.gif');
}




</style>


<script type="text/javascript" src="http://testsrv01.local/yahoo-dom-event.js"></script>

<script type="text/javascript">

function showhide(e,ele)	{
	console.log(ele);
	
	//YAHOO.util.Dom.setStyle(ele, 'display', 'none');
	if (YAHOO.util.Dom.hasClass(ele.id+"_details", 'showdetail'))	{
		YAHOO.util.Dom.removeClass(ele.id+"_details", 'showdetail'); 
		YAHOO.util.Dom.addClass(ele.id+"_details", 'hidedetail'); 
		
		YAHOO.util.Dom.removeClass(ele.id, 'minus'); 
		YAHOO.util.Dom.addClass(ele.id, 'plus'); 
	} else {
		YAHOO.util.Dom.removeClass(ele.id+"_details", 'hidedetail'); 
		YAHOO.util.Dom.addClass(ele.id+"_details", 'showdetail'); 
		
		YAHOO.util.Dom.removeClass(ele.id, 'plus'); 
		YAHOO.util.Dom.addClass(ele.id, 'minus'); 
	}
	element	=	YAHOO.util.Dom.get(ele.id+"_details");
	console.log(element);
}

YAHOO.util.Event.onDOMReady(function(){
cbuttons	=	YAHOO.util.Dom.getElementsByClassName('changebutton');

for(i=0;i <cbuttons.length;i++)	{
	console.log(cbuttons[i].id);
	YAHOO.util.Event.on(cbuttons[i], 'click', showhide,cbuttons[i]); 
	YAHOO.util.Dom.addClass(cbuttons[i].id+"_details", 'hidedetail'); 
}

});
</script>
</head>
<body>

<table>
<tr>
<td><a href="#" class="changebutton" id="changerow1">changer text</a></td>
<td></td>
</tr>
<tr id="changerow1_details" >
<td colspan="2">Hide show text</td>
</tr>
<tr>
<td><a href="#" class="changebutton" id="changerow2">changer text</a></td>
<td></td>
</tr>
<tr id="changerow2_details" >
<td colspan="2">Hide show text</td>
</tr>
</table>


</body>
</html>