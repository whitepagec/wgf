<? 
	$sPageName = "product_price_new";
	$sPrevPageName = "product_price";
	$sPrevPrevPageName = "product_category";
	
	include("common/function.php"); 
	include("common/setvariables.php"); 
	
	$curPage = "product_price";
	$adminModule = "productcategory";
	$pageName = " - Price";
	$sTable = "product_price";
	$sPrevTable = "product";
	$sPrevPrevTable = "product_category";
	$sRedirect = false;
	
	try
	{
		$conn = ConnSQL();
		$stmt = $conn->stmt_init();
	
		$query = "SELECT * FROM $sPrevTable WHERE id=?";
			
		if(!$stmt->prepare($query))
		{
			throw new Exception('SQL ERROR: Failed to prepare statement');
		}
		else
		{
			$stmt->bind_param("s", $_SESSION[$sPrevPageName.'CatID']);
			$stmt->execute();

			$result = $stmt->get_result();
			if ($row = $result->fetch_assoc()) 
			{
				$pageName = DecodeHTMLEntities($row["title"]).$pageName;
			}
		}
		
		$query = "SELECT * FROM $sPrevPrevTable WHERE id=?";
			
		if(!$stmt->prepare($query))
		{
			throw new Exception('SQL ERROR: Failed to prepare statement');
		}
		else
		{
			$stmt->bind_param("s", $_SESSION[$sPrevPrevPageName.'CatID']);
			$stmt->execute();

			$result = $stmt->get_result();
			if ($row = $result->fetch_assoc()) 
			{
				$sTitle = DecodeHTMLEntities($row["title"])." - ".$sTitle;
			}
		}
	} 
	catch (Exception $e) 
	{
		ErrorLog($e);
	}
	
	if ($action == "Save")
	{
		try
		{
			$sPrice = EncodeHTMLEntities($_POST["tbPrice"],1);
			$sSpecification = EncodeHTMLEntities($_POST["tbSpecification"],1);
			$sMaxPosID = (string)((int)GetMaxPosition($sTable." WHERE pro_id=".$_SESSION[$sPrevPageName.'CatID'])+1);
			
			$conn = ConnSQL();
			$stmt = $conn->stmt_init();
			
			$query = "INSERT INTO $sTable (pro_id,position,price,specification,created_date,created_by,last_updated_date,last_updated_by,status) values (?,?,?,?,now(),?,now(),?,'active')";
				
			if(!$stmt->prepare($query))
			{
				throw new Exception('SQL ERROR: Failed to prepare statement');
			}
			else
			{ 
				$stmt->bind_param("ssssss", $_SESSION[$sPrevPageName.'CatID'], $sMaxPosID, $sPrice, $sSpecification, $_SESSION["administrator_id"], $_SESSION["administrator_id"]);
				$stmt->execute();
				
				$sRedirect = "true";
			}
			
			$query = "SELECT id FROM $sTable ORDER BY id DESC";
			
			if(!$stmt->prepare($query))
			{
				throw new Exception('SQL ERROR: Failed to prepare statement');
			}
			else
			{ 
				$stmt->execute();
				$result = $stmt->get_result();
				if ($row = $result->fetch_assoc()) 
				{
					$sTempID = DecodeHTMLEntities($row["id"]);
				}
			}
			LogEntry($_SESSION["administrator_id"], $_SESSION["administrator_email"], "Added", "Product Price", "product_price_new.php", $sTempID);
				
			$stmt->close();
			$conn->close();
		} 
		catch (Exception $e) 
		{
			ErrorLog($e);
		}
	}
	
	if ($sRedirect) 
	{
?>
<script>
	window.open('<? echo $sPrevPageName; ?>.php','_self');
</script>
<?
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title></title>
    <script type="text/javascript" src="common/wysiwyg.js"></script>
    <script type="text/javascript" src="common/wysiwyg-settings.js"></script>
    <? include("header.php") ?>
    <script type="text/javascript">
        function SaveClick() 
		{
            try {	
				checkInput('VN','Price');		
				if (document.getElementById("emPrice").innerHTML == "")
				{
					document.getElementById("btnSave").disabled = true;
					document.getElementById("btnBack").disabled = true;
					document.getElementById("form").submit();
				}			
				else
				{
					return false;
				}
            }
            catch (ex) {
            }
        }
    </script>
</head>
<body>
  <form id="form" name="form" method="post">
  <input type="hidden" id="action" name="action" value="Save">
  <div style="width:100%;">
    <div id="div-title" class="content" style="width:100%; height:42px; color:#FFFFFF; line-height:42px; font-weight:bold; font-size:16px;">&nbsp;&nbsp;&nbsp;<? echo $pageName; ?> / Add New</div>
	<div class="div-horizontal-divider"></div>
    <input type="button" id="btnSave" name="btnSave" onClick="SaveClick();" value="Save" width="80" />&nbsp;&nbsp;<input id="btnBack" name="btnBack" type="button" value="Go Back" onClick="window.open('<? echo $sPrevPageName; ?>.php','_self');" width="80" />
    <div class="div-horizontal-divider" style="border-bottom:1px dotted #d0d0d0;"></div>
    <div class="div-horizontal-divider"></div>
    <div style="width:100%">
    <table border="0" cellpadding="0" cellspacing="0" class="content">
      <tr>
        <td><img src="images/blank.png" width="10" height="1" /></td>
        <td height="20"><img src="images/blank.png" width="100" height="1" /></td>
        <td><img src="images/blank.png" width="10" height="1" /></td>
        <td><img src="images/blank.png" width="300" height="1" /></td>
        <td><img src="images/blank.png" width="200" height="1" /></td>
      </tr>
      <tr>
        <td></td>
        <td valign="middle">Price: [Please exclude "$"]</td>
        <td>&nbsp;</td>
        <td valign="middle"><input type="text" id="tbPrice" name="tbPrice" value="<? echo $sPrice; ?>" style="width:300px;" maxlength="150" class="textfield" /></td>
		<td valign="middle" align="left"><div id="emPrice" class="error_msg" style="width:200px;"></div></td>
      </tr>
      <tr>
        <td colspan="5">&nbsp;</td>
      </tr>
      <tr>
        <td></td>
        <td valign="top">Specification:</td>
        <td>&nbsp;</td>
        <td valign="middle" width="380px"><textarea id="tbSpecification" name="tbSpecification" rows="15" style="width:500px;" class="textfield"><? echo $sSpecification; ?></textarea></td>
		<td valign="top" align="left"><div id="emSpecification" class="error_msg" style="width:200px;"></div></td>
      </tr>
      <tr>
        <td colspan="5">&nbsp;</td>
      </tr>
    </table>
    </div>
    <div class="div-horizontal-divider" style="height:20px;"></div>
  </div>
  </form>
</body>
</html>
