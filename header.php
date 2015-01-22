<!-- ****************************************** HEADER ****************************************** -->
<head>
    <title>WuGuFeng</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta http-equiv="cache-control" content="no-cache" />
	<meta http-equiv="pragma" content="no-cache" />
    <!--<meta charset="utf-8">-->
    <!-- WPC. Google Font -->
    <link href='http://fonts.googleapis.com/css?family=Playfair+Display:400,700,900,400italic,700italic,900italic' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Playball' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Cinzel:400,700,900' rel='stylesheet' type='text/css'>
    <!-- Bootstrap -->
	<link href="css/erosIntro.css" rel="stylesheet" media="screen">
    <link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
	<link href="css/style.css" rel="stylesheet" media="screen">
    <link href="css/custom.css" rel="stylesheet">
    <link href="css/animation.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet" >
    <link href="css/isotope.css" rel="stylesheet">
    <link href="css/hermesMenu.css" rel="stylesheet">
    <link href="css/apolloGallery.css" rel="stylesheet">
    <link href="css/athenaSlide.css" rel="stylesheet">
    <link rel="stylesheet" href="css/owl.carousel.css">
    <link rel="stylesheet" href="css/owl.theme.css">
    <link rel="shortcut icon" href="images/favicon.png">
    
    <!-- WPC. jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://code.jquery.com/jquery.js"></script>
    <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
     <!-- WPC. Products Category -->
	<script src="js/owl.carousel.js"></script>
    <script type="text/javascript">
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
	
	var curLogo = 0;
	var fadeInTimer;
	var fadeOutTimer;
	var autoMoveTimer;
	
	function autoDisplay()
	{
		if (curLogo == 0 || curLogo == 2)
		{
			fadeIn(document.getElementById("logo_image01").name,0);
			
			if (curLogo == 0)
			{
				fadeOut(document.getElementById("logo_image02").name,0);
			}
			else
			{
				fadeOut(document.getElementById("logo_image02").name,100);
			}
			curLogo = 1;
		}
		else
		{
			fadeIn(document.getElementById("logo_image02").name,0);
			fadeOut(document.getElementById("logo_image01").name,100);
			
			curLogo = 2;
		}
	}
	
	function fadeIn(objId,opacity) 
	{
		if (document.getElementById) 
		{
			obj = document.getElementById(objId);
			
			if (opacity <= 100) 
			{
				setOpacity(obj, opacity);
				opacity += 20; // the level of darkness (100 - high, 0 - low)
				fadeInTimer = window.setTimeout("fadeIn('"+objId+"',"+opacity+") ", 100);
			}
			else
			{
				autoMoveTimer = window.setTimeout("autoDisplay()", 5000);
			}
		}
	}
	
	function fadeOut(objId,opacity) 
	{
		if (document.getElementById) 
		{
			obj = document.getElementById(objId);
			
			if (opacity >= 0) 
			{
				setOpacity(obj, opacity);
				opacity -= 20; // the level of darkness (100 - high, 0 - low)
				fadeOutTimer = window.setTimeout("fadeOut('"+objId+"',"+opacity+") ", 100);
			}
		}
	}
	
	function setOpacity(obj, opacity) 
	{
	  opacity = (opacity == 100)?99.999:opacity;
	
	  // IE/Win
	  obj.style.filter = "alpha(opacity:"+opacity+")";
	
	  // Safari<1.2, Konqueror
	  obj.style.KHTMLOpacity = opacity/100;
	
	  // Older Mozilla and Firefox
	  obj.style.MozOpacity = opacity/100;
	
	  // Safari 1.2, newer Firefox and Mozilla, CSS3
	  obj.style.opacity = opacity/100;
	}
	
	function displayContent(contentID) 
	{
		try 
		{	
			var count = 0;
			
			while (document.getElementById("content" + count) != null)
			{
				if (count == contentID)
				{	
					document.getElementById("content" + count).style.display = "block";
				}
				else
				{
					document.getElementById("content" + count).style.display = "none";
				}
				count++;
			}
		}
        catch (ex) {
        }
	}
	
	// delete item
	function deleteItem(productID)
	{
		try 
		{	
			var count = 0;
			var found = false;
			
			while (document.getElementById("divItem" + count) != null && document.getElementById("divItem" + count).style.display != "none")
			{
				if (found == false)
				{
					if (document.getElementById("hfPrice" + productID).innerHTML == document.getElementById("hfPrice" + count).innerHTML)
					{
						document.getElementById("ifUpdate").src="deleteitem.php?value=" + count;
						found = true;
					}
				}
				else
				{
					document.getElementById("divImage" + (count-1)).src = document.getElementById("divImage" + count).src;
					document.getElementById("divTitle" + (count-1)).innerHTML = document.getElementById("divTitle" + count).innerHTML;
					document.getElementById("divTitleChinese" + (count-1)).innerHTML = document.getElementById("divTitleChinese" + count).innerHTML;
					document.getElementById("divSpec" + (count-1)).innerHTML = document.getElementById("divSpec" + count).innerHTML;
					document.getElementById("tbQuantity" + (count-1)).value = document.getElementById("tbQuantity" + count).value;
					document.getElementById("divPrice" + (count-1)).innerHTML = document.getElementById("divPrice" + count).innerHTML;
					document.getElementById("hfPrice" + (count-1)).innerHTML = document.getElementById("hfPrice" + count).innerHTML
					document.getElementById("divSubTotal" + (count-1)).innerHTML = document.getElementById("divSubTotal" + count).innerHTML;
				}
				count++;
			}
			document.getElementById("divItem" + (count-1)).style.display = "none";
		}
        catch (ex) {
        }
	}
	
	// update cart
	function updateCart()
	{
		try 
		{	
			var count = 0;
			var proceed = true;
			var updatecart = "";
			
			if (document.getElementById("divItem" + count) != null && document.getElementById("divItem" + count).style.display != "none")
			{
				while (document.getElementById("divItem" + count) != null && document.getElementById("divItem" + count).style.display != "none" && proceed == true)
				{
					checkInput('VN','Quantity' + count);
							
					if (document.getElementById("emQuantity" + count).innerHTML != "" || document.getElementById("tbQuantity" + count).value == "0")
					{
						document.getElementById("emQuantity" + count).innerHTML = "";
						proceed = false;
					}
					else
					{
						if (updatecart == "")
						{
							updatecart = document.getElementById("hfPrice" + count).innerHTML + "," + document.getElementById("tbQuantity" + count).value;
						}
						else
						{
							updatecart = updatecart + "," + document.getElementById("hfPrice" + count).innerHTML + "," + document.getElementById("tbQuantity" + count).value;
						}
					}
					count++;
				}
				
				if (proceed == false)
				{
					alert("Quantity must be in number and more than zero!");
				}
				else
				{
					document.getElementById("ifUpdate").src="updatecart.php?values=" + updatecart;
				}
			}
			else
			{
				 alert("Currently no item available!");
			}
		}
        catch (ex) {
        }
	}
	
	// check out
	function checkOut()
	{
		try 
		{	
			var count = 0;
			var updatecart = "";
			
			if (document.getElementById("divItem" + count) != null && document.getElementById("divItem" + count).style.display != "none")
			{
				location.href="checkout.php#checkout";
			}
			else
			{
				 alert("Currently no item available!");
			}
		}
        catch (ex) {
        }
	}
	
	// add to cart
	function addToCart(productID) 
	{
		try 
		{	
			var count = 0;
			var quantity = 0;
			var proceed = true;
			var addtocart = "";
			while (document.getElementById("tbQuantity" + productID + "_" + count) != null)
			{
				checkInput('VN','Quantity' + productID + '_' + count);
						
				if (document.getElementById("emQuantity" + productID + "_" + count).innerHTML != "")
				{
					document.getElementById("emQuantity" + productID + "_" + count).innerHTML = "";
					proceed = false;
				}
				else
				{
					quantity = quantity + Number(document.getElementById("tbQuantity" + productID + "_" + count).value);
					
					if (Number(document.getElementById("tbQuantity" + productID + "_" + count).value) > 0)
					{
						if (addtocart == "")
						{
							addtocart = document.getElementById("hfPrice" + productID + "_" + count).innerHTML + "," + document.getElementById("tbQuantity" + productID + "_" + count).value;
						}
						else
						{
							addtocart = addtocart + "," + document.getElementById("hfPrice" + productID + "_" + count).innerHTML + "," + document.getElementById("tbQuantity" + productID + "_" + count).value;
						}
						document.getElementById("tbQuantity" + productID + "_" + count).value = "0";
					}
				}
				count++;
			}
			
			if (proceed == false)
			{
				alert("Quantity must be in number!");
			}
			else if(count == 0)
			{
				alert("Currently no pricing available!");
			}
			else if (quantity == 0)
			{
				alert("Please select at least one quantity.");
			}
			else
			{
				document.getElementById("ifAddToCart").src="addtocart.php?values=" + addtocart;
			}
		}
        catch (ex) {
        }
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
	
	// check textfield
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
	
	// valid form fieldboxs
	function validFrm(str) 
	{
		// General declare for messages.
		var i,j,p,nm,counter,condition,errors='',args,title,val,input_name;
		counter = 0;
		args = str.split(",");
		// loop through all the fields
		for (i=1; i<(args.length-1); i+=2) 
		{  
			input_name = "";
			if (counter == 0) 
			{
				var temp_val = args[i].split(' ');
		
				for (j = 0;j < temp_val.length;j++)
				{
					input_name = input_name + temp_val[j];
				}
				condition=args[i+1];
				nm=(findObj("tb" + input_name)); 
				title=args[i];
				check=nm.checked;
				val=nm.value;
				// check for empty fields
				if (val !="") 
				{
					if (condition.indexOf('P')!=-1) 
					{
						if (val.length < 4)
						{
							errors=title+' must be at least 4 characters.\n';
							counter = i;
						}
					}
					else if (condition.indexOf('C')!=-1) 
					{
						if (check == false)
						{
							errors=title+' box is not check.\n';
							counter = i;
						}
					}
					// verify number
					else if (condition.indexOf('N')!=-1) 
					{
						if (isNaN(val))
						{
							errors=title+' must be a number.\n';
							counter = i;
						}
					}
					// verify email
					else if (condition.indexOf('E')!=-1) 
					{ 
						p=val.indexOf('@');
						x=val.indexOf('.');
						myExp = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
					
						// if (p<1 || p==(val.length-1)) 
						if(myExp.test(val) == false)
						{
							errors=title+' must be a valid e-mail address.\n';
							counter = i;
						}
					}
				}
				else if (condition.charAt(0) == 'V') 
				{
					errors =title+' is required.\n'; 
					counter = i;
				}
			}
		} 
		
		// 	displays error message
		if (counter != 0) 
		{
			alert(errors);
			nm.focus();
			return false;
		}
		else
		{
			if (confirm("Confirm " + args[0] + "?") == 0)
			{
				if (args[1] == null)
				{
					return false;
				}
				else
				{
					(findObj("tb" + args[1])).focus();
				}
				return false;
			}
			else
			{
				return true;
			}
		}
	}
	
	function findObj(n, d) 
	{
		var p,i,x;  
		if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) 
		{
			d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);
		}
		if(!(x=d[n])&&d.all) x=d.all[n]; 
		for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
		for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=findObj(n,d.layers[i].document);
		if(!x && d.getElementById) x=d.getElementById(n); return x;
	}
	
	function deliveryMode(val)
	{
		if (val == "Delivery Required")
		{
			if (document.getElementById("divDeliveryPrice") != null)
			{
				document.getElementById("divDeliveryPrice").innerHTML = "32.10";
				document.getElementById("divFinalPrice").innerHTML = (Number(document.getElementById("divTotalPrice").innerHTML) + 32.10).toFixed(2);
			}
		}
		else
		{
			if (document.getElementById("divDeliveryPrice") != null)
			{
				document.getElementById("divDeliveryPrice").innerHTML = "0.00";
				document.getElementById("divFinalPrice").innerHTML = document.getElementById("divTotalPrice").innerHTML;
			}
		}
	}
	</script>
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
    <script>
		(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		})(window,document,'script','//www.google-analytics.com/analytics.js','ga');
		
		ga('create', 'UA-58699817-1', 'auto');
		ga('require', 'displayfeatures');
		ga('send', 'pageview');
	</script>
</head>
<!-- ****************************************** END HEADER ****************************************** -->