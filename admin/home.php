<?
	$curPage = "admin";
	$adminModule = "home";
	
	$sFirstSelectedMenuName = "admin";
    $sFirstSelectedMenuID = "divAdmin";
	
	session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>WuGuFeng - Administrator Module</title>
    <? include("header.php") ?>
    <style type="text/css">
        body
        {
            margin: 7px 10px 0px 10px;
            padding: 0px 0px 0px 0px;
            min-width:700px;
        }

        a, a:link, a:visited {
	        font-family:Arial, Helvetica, sans-serif;
	        color:#5ca0bc;
	        text-decoration:none;
	        font-weight:bold;
	        font-size:13px;
	        line-height:18px;
        }

        a:hover {
	        font-family:Arial, Helvetica, sans-serif;
	        color:#1F6DB9;
	        text-decoration:none;
	        font-weight:bold;
	        font-size:13px;
	        line-height:18px;
        }
    </style>
    <script type="text/javascript">
        var selectedMenu = '<? echo $sFirstSelectedMenuName; ?>';
        var selectObj = null;
        var selectObjID = '<? echo $sFirstSelectedMenuID; ?>';

        function menuOnClick(type, obj) {
            if (type != selectedMenu) {
                if (selectObj == null) selectObj = document.getElementById(selectObjID);
                try { selectObj.setAttribute('class', 'div-selection-none'); } catch (ex) { };
                try { selectObj.className = 'div-selection-none'; } catch (ex) { };
                selectObj = obj;
                selectedMenu = type;
                try { selectObj.setAttribute('class', 'div-selection-selected'); } catch (ex) { };
                try { selectObj.className = 'div-selection-selected'; } catch (ex) { };
                switch (type) {
                    case 'admin': document.getElementById('frmContent').src = 'admin.php'; break;
					case 'productcategory': document.getElementById('frmContent').src = 'product_category.php'; break;
                    case 'featured': document.getElementById('frmContent').src = 'featured.php'; break;
					case 'gst': document.getElementById('frmContent').src = 'gst.php'; break;
                }
            }
        }

        function menuOnMouseOut(type, obj) {
            if (selectObj == null) selectObj = document.getElementById(selectObjID);
            if (type != selectedMenu) {
                try { obj.setAttribute('class', 'div-selection-none'); } catch (ex) { };
                try { obj.className = 'div-selection-none'; } catch (ex) { };
            }
        }

        function menuOnMouseOver(type, obj) {
            if (selectObj == null) selectObj = document.getElementById(selectObjID);
            if (type != selectedMenu) {
                try { obj.setAttribute('class', 'div-selection-hover'); } catch (ex) { };
                try { obj.className = 'div-selection-hover'; } catch (ex)  { };
            }
        }

		function showDetails()
		{
			document.getElementById("lbUserEmail").innerHTML = "<? echo $_SESSION["administrator_email"]; ?>";
			document.getElementById("lbName").innerHTML = "<? echo $_SESSION["administrator_name"]; ?>";
			document.getElementById("lbLastLogin").innerHTML = "<? echo $_SESSION["administrator_lastlogin"]; ?>";
			
			if ("<? echo $_SESSION["administrator_superadmin"]; ?>" == "false")
			{
				//normal admin
				var sFirstSelectedMenuID = "divProductCategory"; 
				var sFirstSelectedMenuName = "productcategory"; 
				
				document.getElementById("divAdmin").style.visibility = "hidden";
				document.getElementById("divAdmin").style.display = "none";
				
				try { document.getElementById("divProductCategory").setAttribute('class', 'div-selection-selected'); } catch (ex) { };
				try { document.getElementById("divProductCategory").className = 'div-selection-selected'; } catch (ex) { };
						
				document.getElementById("frmContent").src = "product_category.php";
			}
			else
			{
				//super admin
				var sFirstSelectedMenuID = "divAdmin";
				var sFirstSelectedMenuName = "admin";
				
				try { document.getElementById("divAdmin").setAttribute('class', 'div-selection-selected'); } catch (ex) { };
				try { document.getElementById("divAdmin").className = 'div-selection-selected'; } catch (ex) { };
				try { document.getElementById("divProductCategory").setAttribute('class', 'div-selection-none'); } catch (ex) { };
				try { document.getElementById("divProductCategory").className = 'div-selection-none'; } catch (ex) { };
				
				document.getElementById("frmContent").src = "admin.php";
			}
			
			try { document.getElementById("divFeatured").setAttribute('class', 'div-selection-none'); } catch (ex) { };
			try { document.getElementById("divFeatured").className = 'div-selection-none'; } catch (ex) { };
			try { document.getElementById("divGST").setAttribute('class', 'div-selection-none'); } catch (ex) { };
			try { document.getElementById("divGST").className = 'div-selection-none'; } catch (ex) { };
			
			selectedMenu = sFirstSelectedMenuName;
        	selectObjID = sFirstSelectedMenuID;
		}
    </script>
</head>
<body onload="showDetails();">
  <form id="form" name="form">
  <div style="width:100%">
  <center>
    <? include("topnav.php") ?>
    <div id="div-admin-top-panel" style="width:100%; height:33px;">
      <div style="width:400px; height:33px; float:right; color:#FFFFFF; text-align:right; line-height:33px; margin-right:12px;" class="content"><label id="lbUserEmail" style="font-size:13px; font-weight:bold;"></label></div>
      <div style="width:180px; height:33px; float:left; color:#FFFFFF; text-align:left; line-height:33px; margin-left:12px;" class="content">Administrator Access Panel</div>
    </div>
    <div class="div-horizontal-divider"></div>
    <div style="width:100%;">
    <table border="0" cellpadding="0" cellspacing="0" class="content">
      <tr>
        <td height="1"><img src="images/blank.png" width="190" height="1" /></td>
        <td><img src="images/blank.png" width="10" height="1" /></td>
        <td width="100%"></td>
      </tr>
      <tr>
        <td valign="top">
        <div id="div-left-mainmenu" style="width:100%;">
          <div style="width:100%; height:8px;"></div>
          <div style="width:100%; height:27px;">
          <table border="0" cellpadding="0" cellspacing="0" width="100%">
            <tr>
              <td><img src="images/blank.png" width="8" height="1" /></td>
              <td><img src="images/blank.png" width="8" height="1" /></td>
              <td width="100%"></td>
              <td><img src="images/blank.png" width="8" height="1" /></td>
            </tr>
            <tr>
              <td></td>
              <td></td>
              <td align="left" valign="middle"><label id="lbName" style="font-family:Arial, Helvetica, sans-serif; font-size:13px; line-height:13px; font-weight:bold;"></label></td>
              <td></td>
            </tr>
          </table>
          </div>
          <div class="div-horizontal-divider"></div>
          <div style="width:175px; height:27px; margin-left:8px; background-color:#a5bfda; line-height:27px; text-align:left; color:#FFFFFF;">&nbsp;&nbsp;Last Login Date/Time</div>
          <div style="width:175px; height:27px; margin-left:8px; background-color:#FFFFFF; line-height:27px; text-align:left; color:#000000;">&nbsp;&nbsp;<label id="lbLastLogin" style="font-family:Arial, Helvetica, sans-serif; font-size:13px; line-height:13px; font-weight:bold;"></label></div>
          <div class="div-horizontal-divider"></div>
          <div style="width:175px; height:27px; margin-left:8px; background-color:#a5bfda; line-height:27px; text-align:left; color:#FFFFFF;">&nbsp;&nbsp;Modules</div>
          <div style="width:100%; height:5px;"></div>
          <div runat="server" id="divAdmin" onclick="menuOnClick('admin',this);" onmouseout="menuOnMouseOut('admin',this);" onmouseover="menuOnMouseOver('admin',this);">&nbsp;&nbsp;Administrators</div>
          <div runat="server" id="divProductCategory" onclick="menuOnClick('productcategory',this);" onmouseout="menuOnMouseOut('productcategory',this);" onmouseover="menuOnMouseOver('productcategory',this);">&nbsp;&nbsp;Product Category</div>
          <div runat="server" id="divFeatured" class="div-selection-none" onclick="menuOnClick('featured',this);" onmouseout="menuOnMouseOut('featured',this);" onmouseover="menuOnMouseOver('featured',this);">&nbsp;&nbsp;Featured</div>
          <div runat="server" id="divGST" class="div-selection-none" onclick="menuOnClick('gst',this);" onmouseout="menuOnMouseOut('gst',this);" onmouseover="menuOnMouseOver('gst',this);">&nbsp;&nbsp;GST</div>
          <div style="width:100%; height:15px;"></div>
        </div>
        </td>
        <td></td>
        <td valign="top"><iframe runat="server" id="frmContent" name="frmContent" frameborder="0" width="100%" height="800"></iframe></td>
      </tr>
    </table>
    </div>
    <? include("copyright.php") ?>
    </center>
  </div>
  </form>
</body>
</html>
