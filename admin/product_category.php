<? 
	$sPageName = "product_category";
	
	include("common/function.php"); 
	include("common/setvariables.php"); 
	
	$sRedirect = false;
	$sContinue = false;
	$curPage = "product_category";
	$adminModule = "productcategory";
	
	$sTable = "product_category";
	$sTitle = "Product Category";
	$sTotal = 0;
	
	$conn = ConnSQL();
	$stmt = $conn->stmt_init();
	
	if ($action == "position")
	{
		SwitchPosition($sTable, $_REQUEST["positionid"], $_REQUEST["position"], "");
		LogEntry($_SESSION["administrator_id"], $_SESSION["administrator_email"], "Position Changed", "Product Category", "product_category.php", $id);
		
		$sRedirect = true;
	}
	else if ($action == "delete")
	{
		try
		{
			$query = "SELECT * FROM product WHERE cat_id=?";
				
			if(!$stmt->prepare($query))
			{
				throw new Exception('SQL ERROR: Failed to prepare statement');
			}
			else
			{
				$stmt->bind_param("s", $id);
				$stmt->execute();
	
				$result = $stmt->get_result();
				while ($row = $result->fetch_assoc()) 
				{
					$query = "DELETE FROM product_price WHERE pro_id=?";
					
					if(!$stmt->prepare($query))
					{
						throw new Exception('SQL ERROR: Failed to prepare statement');
					}
					else
					{
						$stmt->bind_param("s", $row["id"]);
						$stmt->execute();
					}
					
					$query = "SELECT * FROM product_image WHERE pro_id=?";
				
					if(!$stmt->prepare($query))
					{
						throw new Exception('SQL ERROR: Failed to prepare statement');
					}
					else
					{
						$stmt->bind_param("s", $row["id"]);
						$stmt->execute();
				
						$result = $stmt->get_result();
						while ($rowInner = $result->fetch_assoc()) 
						{
							$sImage = "";
							$sImage = DecodeHTMLEntities($rowInner["image"]);
			
							unlink($sImagePath."product/image/".$sImage);
						}
					}
					
					$query = "DELETE FROM product_image WHERE pro_id=?";
					
					if(!$stmt->prepare($query))
					{
						throw new Exception('SQL ERROR: Failed to prepare statement');
					}
					else
					{
						$stmt->bind_param("s", $row["id"]);
						$stmt->execute();
					}
				}
			}
			
			$query = "DELETE FROM product WHERE cat_id=?";
			if(!$stmt->prepare($query))
			{
				throw new Exception('SQL ERROR: Failed to prepare statement');
			}
			else
			{
				$stmt->bind_param("s", $id);
				$stmt->execute();
			}
			
			$query = "SELECT * FROM $sTable WHERE id=?";
				
			if(!$stmt->prepare($query))
			{
				throw new Exception('SQL ERROR: Failed to prepare statement');
			}
			else
			{
				$stmt->bind_param("s", $id);
				$stmt->execute();
	
				$result = $stmt->get_result();
				if ($row = $result->fetch_assoc()) 
				{
					ShiftPosition($sTable, $row["position"], "");
					
					$sImage = "";
					$sImage = DecodeHTMLEntities($row["image"]);
					
					unlink($sImagePath."product/category/".$sImage);
				}
			}
			
			$query = "DELETE FROM $sTable WHERE id=?";
	
			if(!$stmt->prepare($query))
			{
				throw new Exception('SQL ERROR: Failed to prepare statement');
			}
			else
			{
				$stmt->bind_param("s", $id);
				$stmt->execute();
				
				$sRedirect = true;
				LogEntry($_SESSION["administrator_id"], $_SESSION["administrator_email"], "Deleted", "Product Category", "product_category.php", $id);
			}
		}
		catch (Exception $e) 
		{
			ErrorLog($e);
		}
	}
	else if ($action == "status")
	{
		try
		{
			$sPosID = "0";
			$query = "SELECT status,position FROM $sTable WHERE id=?";
	
			if(!$stmt->prepare($query))
			{
				throw new Exception('SQL ERROR: Failed to prepare statement');
			}
			else
			{
				$stmt->bind_param("s", $id);
				$stmt->execute();
				
				$result = $stmt->get_result();
				if ($row = $result->fetch_assoc())
				{	 
					ShiftPosition($sTable, $row["position"], "");
					if (DecodeHTMLEntities($row["status"]) == "active")
					{
						$query = "UPDATE $sTable SET status = 'inactive', position = ?, last_updated_date = now(), last_updated_by=? WHERE id=?";
						LogEntry($_SESSION["administrator_id"], $_SESSION["administrator_email"], "Deactivated", "Product Category", "product_category.php", $id);
					}
					else
					{
						$sPosID = (string)((int)GetMaxPosition($sTable."")+1);
						$query = "UPDATE $sTable SET status = 'active', position = ?, last_updated_date = now(), last_updated_by=? WHERE id=?";
						LogEntry($_SESSION["administrator_id"], $_SESSION["administrator_email"], "Activated", "Product Category", "product_category.php", $id);
					}
	
					if(!$stmt->prepare($query))
					{
						throw new Exception('SQL ERROR: Failed to prepare statement');
					}
					else
					{
						$stmt->bind_param("sss", $sPosID, $_SESSION["administrator_id"], $id);
						$stmt->execute();
						$sRedirect = true;
					}
				}
			}
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
	window.open('<?php echo $sPageName; ?>.php','_self');
</script>
<?php
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <title></title>
  <? include("header.php") ?>
</head>
<body>
<form id="form" name="form">
<div style="width:100%;">
  <div id="div-title" class="content" style="width:100%; height:42px; color:#FFFFFF; line-height:42px; font-weight:bold; font-size:16px;">&nbsp;&nbsp;&nbsp;<? echo $sTitle ?></div>
  <div class="div-horizontal-divider"></div>
  <input type="button" value="Add New <? echo $sTitle ?>" onClick="window.open('<? echo $sPageName; ?>_new.php','_self');" size="60" />
  <div class="div-horizontal-divider"></div>
  <div style="width:100%;">
  <table cellpadding="0" cellspacing="1" border="0" class="content" bgcolor="#F3F3F3" width="100%">
    <tr>
      <td width="20%" class="gridHeader">
        <a href="javascript:void(0);" onClick="sortTable('Title','Title','<? echo $_SESSION[$sPageName.'PageNos']; ?>');">
        <table cellpadding="0" cellspacing="0" border="0">
          <tr>
            <td>Title</td>
            <td width="10"></td>
            <td><div id="divTitleAsc" style="display:<? if ($_SESSION[$sPageName.'SortField'] == "Title" && $_SESSION[$sPageName.'SortDir'] == "ASC") { echo "block"; } else { echo "none"; } ?>;"><img src="images/icon_arrow_asc.gif" border="0" /></div>
			<div id="divTitleDesc" style="display:<? if ($_SESSION[$sPageName.'SortField'] == "Title" && $_SESSION[$sPageName.'SortDir'] == "DESC") { echo "block"; } else { echo "none"; } ?>;"><img src="images/icon_arrow_desc.gif" border="0" /></div></td>
          </tr>
        </table>
        </a>
      </td>
      <td width="7%" class="gridHeader">
        <a href="javascript:void(0);" onClick="sortTable('Position','Position','<?php echo $_SESSION[$sPageName.'PageNos']; ?>');">
        <table cellpadding="0" cellspacing="0" border="0">
          <tr>
            <td>Position</td>
            <td width="10"></td>
            <td><div id="divPositionAsc" style="display:<?php if ($_SESSION[$sPageName.'SortField'] == "Position" && $_SESSION[$sPageName.'SortDir'] == "ASC") { echo "block"; } else { echo "none"; } ?>;"><img src="images/icon_arrow_asc.gif" border="0" /></div>
			<div id="divPositionDesc" style="display:<?php if ($_SESSION[$sPageName.'SortField'] == "Position" && $_SESSION[$sPageName.'SortDir'] == "DESC") { echo "block"; } else { echo "none"; } ?>;"><img src="images/icon_arrow_desc.gif" border="0" /></div></td>
          </tr>
        </table>
        </a>
      </td>
      <td width="10%" class="gridHeader">Image</td>
      <td width="10%" class="gridHeader">Product</td>
      <td width="10%" class="gridHeader">
        <a href="javascript:void(0);" onClick="sortTable('Status','Status','<? echo $_SESSION[$sPageName.'PageNos']; ?>');">
        <table cellpadding="0" cellspacing="0" border="0">
          <tr>
            <td>Status</td>
            <td width="10"></td>
            <td><div id="divStatusAsc" style="display:<? if ($_SESSION[$sPageName.'SortField'] == "Status" && $_SESSION[$sPageName.'SortDir'] == "ASC") { echo "block"; } else { echo "none"; } ?>;"><img src="images/icon_arrow_asc.gif" border="0" /></div>
			<div id="divStatusDesc" style="display:<? if ($_SESSION[$sPageName.'SortField'] == "Status" && $_SESSION[$sPageName.'SortDir'] == "DESC") { echo "block"; } else { echo "none"; } ?>;"><img src="images/icon_arrow_desc.gif" border="0" /></div></td>
          </tr>
        </table>
        </a>
      </td>
      <td width="10%" class="gridHeader">
        <a href="javascript:void(0);" onClick="sortTable('CreatedBy','Created_By','<? echo $_SESSION[$sPageName.'PageNos']; ?>');">
        <table cellpadding="0" cellspacing="0" border="0">
          <tr>
            <td>Created By</td>
            <td width="10"></td>
            <td><div id="divCreatedByAsc" style="display:<? if ($_SESSION[$sPageName.'SortField'] == "Created_By" && $_SESSION[$sPageName.'SortDir'] == "ASC") { echo "block"; } else { echo "none"; } ?>;"><img src="images/icon_arrow_asc.gif" border="0" /></div>
			<div id="divCreatedByDesc" style="display:<? if ($_SESSION[$sPageName.'SortField'] == "Created_By" && $_SESSION[$sPageName.'SortDir'] == "DESC") { echo "block"; } else { echo "none"; } ?>;"><img src="images/icon_arrow_desc.gif" border="0" /></div></td>
          </tr>
        </table>
        </a>
      </td>
      <td width="15%" class="gridHeader">
        <a href="javascript:void(0);" onClick="sortTable('CreatedDate','Created_Date','<? echo $_SESSION[$sPageName.'PageNos']; ?>');">
        <table cellpadding="0" cellspacing="0" border="0">
          <tr>
            <td>Created Date</td>
            <td width="10"></td>
            <td><div id="divCreatedDateAsc" style="display:<? if ($_SESSION[$sPageName.'SortField'] == "Created_Date" && $_SESSION[$sPageName.'SortDir'] == "ASC") { echo "block"; } else { echo "none"; } ?>;"><img src="images/icon_arrow_asc.gif" border="0" /></div>
			<div id="divCreatedDateDesc" style="display:<? if ($_SESSION[$sPageName.'SortField'] == "Created_Date" && $_SESSION[$sPageName.'SortDir'] == "DESC") { echo "block"; } else { echo "none"; } ?>;"><img src="images/icon_arrow_desc.gif" border="0" /></div></td>
          </tr>
        </table>
        </a>
      </td>
      <td width="18%" class="gridHeader">Actions</td>
    </tr>
    <?
	// get and displays records from the selected table
	$intCount = 0;
	$checkCount = 0;
	$sBGColor = "#FFFFFF";
	
	try
	{
		$query = "SELECT COUNT(*) as total FROM $sTable";
		
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
				$sTotal = (int)DecodeHTMLEntities($row["total"]);
			}
		}
		
		$query = "SELECT * FROM $sTable ORDER BY ".$_SESSION[$sPageName.'SortField']." ".$_SESSION[$sPageName.'SortDir'];

		if(!$stmt->prepare($query))
		{
			throw new Exception('SQL ERROR: Failed to prepare statement');
		}
		else
		{
			$stmt->execute();

			$result = $stmt->get_result();
			while ($row = $result->fetch_assoc())
			{ 
				if ($intCount >= $minCount && $intCount < $maxCount)
				{
					if ($checkCount%2 == 0)
					{
						$sBGColor = "#FFFFFF";
					}
					else
					{
						$sBGColor = "#F5F5F5";
					} 
					
					$sCreatedBy = DecodeHTMLEntities($row["created_by"]);
					$query = "SELECT * FROM administrator WHERE id = ?";
					if(!$stmt->prepare($query))
					{
						throw new Exception('SQL ERROR: Failed to prepare statement');
					}
					else
					{
						$stmt->bind_param("s", $sCreatedBy);
						$stmt->execute();

						$resultAdmin = $stmt->get_result();
						if ($rowAdmin = $resultAdmin->fetch_assoc())
						{ 
							$sCreatedBy = DecodeHTMLEntities($rowAdmin["name"]);
						}
					}
					
					$sSubTotal = DecodeHTMLEntities($row["id"]);
					$query = "SELECT COUNT(*) AS total FROM product WHERE cat_id = ?";
					if(!$stmt->prepare($query))
					{
						throw new Exception('SQL ERROR: Failed to prepare statement');
					}
					else
					{
						$stmt->bind_param("s", $sSubTotal);
						$stmt->execute();

						$resultSub = $stmt->get_result();
						if ($rowSub = $resultSub->fetch_assoc())
						{ 
							$sSubTotal = DecodeHTMLEntities($rowSub["total"]);
						}
					}
	?>
	<tr bgcolor="<? echo $sBGColor; ?>" onMouseOver="changeColor(this,'#D0D6DA');" onMouseOut="changeColor(this,'<? echo $sBGColor; ?>')">
      <td class="gridContent"><? echo DecodeHTMLEntities($row["title"]); ?></td>
      <td class="gridContent"><?php 
	  	if (DecodeHTMLEntities($row["position"]) != "0")
		{
			echo CreateDropDown($sTable,(int)DecodeHTMLEntities($row["position"]),$row["id"]);
		} 
	  ?></td>
      <td class="gridContent"><? 
	  	if (DecodeHTMLEntities($row["image"]) != "")
		{
			echo "<a href='javascript:void(0);' onclick='window.open(\"".$sImageViewPath."product/category/".DecodeHTMLEntities($row["image"])."\",\"_blank\");'>View Image</a>"; 
		}
	  ?></td>
      <td class="gridContent"><? echo "<a href='javascript:void(0);' onclick='window.open(\"product.php?catID=".DecodeHTMLEntities($row["id"])."\",\"_self\");'>".$sSubTotal."</a>"; ?></td>
      <td class="gridContent"><? echo DecodeHTMLEntities($row["status"]); ?></td>
      <td class="gridContent"><? echo $sCreatedBy; ?></td>
      <td class="gridContent"><? echo date_format(date_create(DecodeHTMLEntities($row["created_date"])), 'j F Y g:ia'); ?></td>
      <td class="gridContent"><?  
	  				$sActionEnable = "Enable";
                	if (DecodeHTMLEntities($row["status"]) == "active")
                	{ 
                    	$sActionEnable = "Disable";
                	}
					echo "<a href='javascript:void(0);' onclick='window.open(\"".$sPageName."_update.php?action=edit&id=".DecodeHTMLEntities($row["id"])."\",\"_self\");'>Edit</a> <font color='#5ca0bc'>|</font> <a href='javascript:void(0);' onclick='if (confirm(\"Are you sure?\")) window.open(\"".$sPageName.".php?action=status&id=".DecodeHTMLEntities($row["id"])."\",\"_self\"); return false;'>".$sActionEnable."</a> <font color='#5ca0bc'>|</font> <a href='javascript:void(0);' onclick='if (confirm(\"Are you sure?\")) window.open(\"".$sPageName.".php?action=delete&id=".DecodeHTMLEntities($row["id"])."\",\"_self\"); return false;'>Delete</a>"; ?></td>			
	</tr>
	  <?			
					$checkCount = $checkCount + 1;
				}
				$intCount = $intCount + 1;
			}
			$conn->close();
		}
	} 
	catch (Exception $e) 
	{
		ErrorLog($e);
	}
	?>
    <tr>
      <td width="100%" colspan="8" align="right" class="gridBottom" height="30" valign="top">
	    <table cellpadding="0" cellspacing="0" border="0">
          <tr>
		    <td height="5px;"></td>
		  </tr>
		  <tr>
		    <? PageNum($sTotal,$noOfItem,$_SESSION[$sPageName.'PageNos'],$_SESSION[$sPageName.'SortField'],$_SESSION[$sPageName.'SortDir']) ?>
            <td width="10"></td>
          </tr>
        </table>
      </td>
	</tr>
  </table>
  </div>
</div>
</form>
</body>
</html>