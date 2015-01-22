function ddPosOnChange(index, obj, id) 
{
	location.href="?action=position&positionid=" + index + "&position=" + obj.value + "&id=" + id;
}
	
// Removes leading whitespaces
function ltrim(value) {

    var re = /\s*((\S+\s*)*)/;
    return value.replace(re, "$1");

}

// Removes ending whitespaces
function rtrim(value) {

    var re = /((\s*\S+)*)\s*/;
    return value.replace(re, "$1");

}

// Removes leading and ending whitespaces
function trim(value) {

    return ltrim(rtrim(value));

}

function sortTable(val,sortField,pageNos)
{
	if (document.getElementById("div" + val + "Asc").style.display == "none" && document.getElementById("div" + val + "Desc").style.display == "none")
	{
		location.href="?pageNos=" + pageNos + "&sortField=" + sortField + "&sortDir=ASC";
	}
	else if (document.getElementById("div" + val + "Asc").style.display == "none")
	{
		location.href="?pageNos=" + pageNos + "&sortField=" + sortField + "&sortDir=ASC";
	}
	else
	{
		location.href="?pageNos=" + pageNos + "&sortField=" + sortField + "&sortDir=DESC";
	}
}

function toggleCheckBox(val)
{
	if (document.getElementById("cb" + val).checked)
	{
		document.getElementById("div" + val).style.display = "block";
	}
	else
	{
		document.getElementById("div" + val).style.display = "none";
	}
}

function changeColor(cur,color)
{
	cur.style.background = color;
}

function checkInput(type, value)
{
	var i = 0;
	var val = value.split(' ');
	var inputName = "";
	var myExp = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
	
	for (i = 0;i < val.length;i++)
	{
		inputName = inputName + val[i];
	}
	
	document.getElementById("em" + inputName).innerHTML = "";
	for (i = 0;i < type.length;i++)
	{
		switch (type.charAt(i))
		{
			case 'V':
			{
				if (trim(document.getElementById("tb" + inputName).value) == "") 
				{ 
					document.getElementById("em" + inputName).innerHTML = "&nbsp;&nbsp;* " + value + " is required!";
				}
				break;
			}
			case 'E':
			{
				if (myExp.test(document.getElementById("tb" + inputName).value) == false) 
				{ 
					document.getElementById("em" + inputName).innerHTML = "&nbsp;&nbsp;* Invalid email address!"; 
				}
				break;
			}
			case 'N':
			{
				if (isNaN(document.getElementById("tb" + inputName).value)) 
				{ 
					document.getElementById("em" + inputName).innerHTML = "&nbsp;&nbsp;* " + value + " must be in number!";
				}
				break;
			}
		}
	}
}

function checkInputs(value)
{
	var i = 0;
	var j = 0;
	var count = 0;
	var val = value.split(',');
	var val2;
	var inputName = "";
	
	for (i = 0;i < val.length;i=i+2)
	{
		checkInput(val[i],val[i+1]);
		val2 = val[i+1].split(' ');
		inputName = "";
		
		for (j = 0;j < val2.length;j++)
		{
			inputName = inputName + val2[j];
		}
		
		if (document.getElementById("em" + inputName).innerHTML == "")
		{
			count++;
		}
	}
	
	if (count == (val.length/2))
	{
		document.getElementById("form").submit();
	}
}

function checkCheckBox(value)
{
	var i = 0;
	var j = 0;
	var count = 0;
	var val = value.split(',');
	var inputName = "";
	
	for (i = 1;i < val.length;i++)
	{
		if (document.getElementById("cb" + val[i]).checked)
		{
			count++;
		}
	}
	
	if (count == 0)
	{
		document.getElementById("em" + val[0]).innerHTML = "&nbsp;&nbsp;* At least one " + val[0] + " must be select!";
	}
	else
	{
		document.getElementById("em" + val[0]).innerHTML = "";
	}
}

function MM_swapImgRestore() { //v3.0
  var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}

function MM_swapImage() { //v3.0
  var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
   if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}

function MM_displayStatusMsg(msgStr)  { //v3.0
	status=msgStr; document.MM_returnValue = true;
}

function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function mmLoadMenus() {
  if (window.mm_menu_0703172323_0) return;
  window.mm_menu_0703172323_0 = new Menu("root",126,17,"Verdana, Arial, Helvetica, sans-serif",11,"#333333","#cc0000","#f2f2f2","#ffffff","left","middle",3,0,1000,-5,7,true,true,true,0,true,true);
  mm_menu_0703172323_0.addMenuItem("ACT!&nbsp;By&nbsp;SAGE&nbsp;2006","window.open('products.asp#act', '_self');");
  mm_menu_0703172323_0.addMenuItem("SAGE&nbsp;Pastel&nbsp;Partner","window.open('products.asp?current2=true#sage', '_self');");
   mm_menu_0703172323_0.hideOnMouseOut=true;
   mm_menu_0703172323_0.menuBorder=1;
   mm_menu_0703172323_0.menuLiteBgColor='#ffffff';
   mm_menu_0703172323_0.menuBorderBgColor='#555555';
   mm_menu_0703172323_0.bgColor='#555555';

  mm_menu_0703172323_0.writeMenus();
} // mmLoadMenus()

function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}

function MM_swapImgRestore() { //v3.0
  var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}

function MM_findObj(n, d) { //v4.01
  var p,i,x; if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_swapImage() { //v3.0
  var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
   if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}

function MM_displayStatusMsg(msgStr)  { //v3.0
	status=msgStr; document.MM_returnValue = true;
}