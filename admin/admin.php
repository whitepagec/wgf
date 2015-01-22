<? 
	$sPageName = "admin";
	
	include("common/function.php"); 
	include("common/setvariables.php"); 

	$sRedirect = false;
	$sContinue = false;
	$curPage = "admin";
	$adminModule = "administrator";
	
	$sTable = "administrator";
	$sTitle = "Administrator";
	$sTotal = 0;
	
	$conn = ConnSQL();
	$stmt = $conn->stmt_init();
	
	if ($action == "delete")
	{
		try
		{
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
				LogEntry($_SESSION["administrator_id"], $_SESSION["administrator_email"], "Administrator Deleted", "Administrator", "admin.php", $id);
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
			$query = "SELECT status FROM $sTable WHERE id=?";
	
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
					if (DecodeHTMLEntities($row["status"]) == "active")
					{
						$query = "UPDATE $sTable SET status = 'inactive', last_updated_date = now(), last_updated_by=? WHERE id=?";
						LogEntry($_SESSION["administrator_id"], $_SESSION["administrator_email"], "Deactivated", "Administrator", "admin.php", $id);
					}
					else
					{
						$query = "UPDATE $sTable SET status = 'active', last_updated_date = now(), last_updated_by=? WHERE id=?";
						LogEntry($_SESSION["administrator_id"], $_SESSION["administrator_email"], "Activated", "Administrator", "admin.php", $id);
					}
	
					if(!$stmt->prepare($query))
					{
						throw new Exception('SQL ERROR: Failed to prepare statement');
					}
					else
					{
						$stmt->bind_param("ss", $_SESSION["administrator_id"], $id);
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
  <div id="div-title" class="content" style="width:100%; height:42px; color:#FFFFFF; line-height:42px; font-weight:bold; font-size:16px;">&nbsp;&nbsp;&nbsp;<? echo $sTitle ?>s</div>
  <div class="div-horizontal-divider"></div>
  <input type="button" value="Add New <? echo $sTitle ?>" onclick="window.open('<? echo $sPageName; ?>_new.php','_self');" size="60" />
  <div class="div-horizontal-divider"></div>
  <div style="width:100%;">
  <table cellpadding="0" cellspacing="1" border="0" class="content" bgcolor="#F3F3F3" width="100%">
    <tr>
      <td width="30%" class="gridHeader">
        <a href="javascript:void(0);" onclick="sortTable('Name','Name','<? echo $_SESSION[$sPageName.'PageNos']; ?>');">
        <table cellpadding="0" cellspacing="0" border="0">
          <tr>
            <td>Name</td>
            <td width="10"></td>
            <td><div id="divNameAsc" style="display:<? if ($_SESSION[$sPageName.'SortField'] == "Name" && $_SESSION[$sPageName.'SortDir'] == "ASC") { echo "block"; } else { echo "none"; } ?>;"><img src="images/icon_arrow_asc.gif" border="0" /></div>
			<div id="divNameDesc" style="display:<? if ($_SESSION[$sPageName.'SortField'] == "Name" && $_SESSION[$sPageName.'SortDir'] == "DESC") { echo "block"; } else { echo "none"; } ?>;"><img src="images/icon_arrow_desc.gif" border="0" /></div></td>
          </tr>
        </table>
        </a>
      </td>
      <td width="20%" class="gridHeader">
        <a href="javascript:void(0);" onclick="sortTable('Email','Email','<? echo $_SESSION[$sPageName.'PageNos']; ?>');">
        <table cellpadding="0" cellspacing="0" border="0">
          <tr>
            <td>Email</td>
            <td width="10"></td>
            <td><div id="divEmailAsc" style="display:<? if ($_SESSION[$sPageName.'SortField'] == "Email" && $_SESSION[$sPageName.'SortDir'] == "ASC") { echo "block"; } else { echo "none"; } ?>;"><img src="images/icon_arrow_asc.gif" border="0" /></div>
			<div id="divEmailDesc" style="display:<? if ($_SESSION[$sPageName.'SortField'] == "Email" && $_SESSION[$sPageName.'SortDir'] == "DESC") { echo "block"; } else { echo "none"; } ?>;"><img src="images/icon_arrow_desc.gif" border="0" /></div></td>
          </tr>
        </table>
        </a>
      </td>
      <td width="10%" class="gridHeader">
        <a href="javascript:void(0);" onclick="sortTable('Status','Status','<? echo $_SESSION[$sPageName.'PageNos']; ?>');">
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
      <td width="20%" class="gridHeader">
        <a href="javascript:void(0);" onclick="sortTable('LastLogin','Last_Login','<? echo $_SESSION[$sPageName.'PageNos']; ?>');">
        <table cellpadding="0" cellspacing="0" border="0">
          <tr>
            <td>Last Login Date/Time</td>
            <td width="10"></td>
            <td><div id="divLastLoginAsc" style="display:<? if ($_SESSION[$sPageName.'SortField'] == "Last_Login" && $_SESSION[$sPageName.'SortDir'] == "ASC") { echo "block"; } else { echo "none"; } ?>;"><img src="images/icon_arrow_asc.gif" border="0" /></div>
			<div id="divLastLoginDesc" style="display:<? if ($_SESSION[$sPageName.'SortField'] == "Last_Login" && $_SESSION[$sPageName.'SortDir'] == "DESC") { echo "block"; } else { echo "none"; } ?>;"><img src="images/icon_arrow_desc.gif" border="0" /></div></td>
          </tr>
        </table>
        </a>
      </td>
      <td width="20%" class="gridHeader">Actions</td>
    </tr>
    <?
	// get and displays records from the selected table
	$intCount = 0;
	$checkCount = 0;
	$sBGColor = "#FFFFFF";
	
	try
	{
		$query = "SELECT Count(*) as total FROM $sTable";
		
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
	}
	catch (Exception $e) 
	{
		ErrorLog($e);
	}
	
	try
	{
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
	?>
	<tr bgcolor="<? echo $sBGColor; ?>" onMouseOver="changeColor(this,'#D0D6DA');" onMouseOut="changeColor(this,'<? echo $sBGColor; ?>')">
      <td class="gridContent"><? echo DecodeHTMLEntities($row["name"]); ?></td>
      <td class="gridContent"><a href="mailto:<? echo Decrypt(DecodeHTMLEntities($row["email"])); ?>"><? echo Decrypt(DecodeHTMLEntities($row["email"])); ?></a></td>
      <td class="gridContent"><? echo DecodeHTMLEntities($row["status"]); ?></td>
      <td class="gridContent"><? echo date_format(date_create(DecodeHTMLEntities($row["last_login"])), 'j F Y g:ia'); ?></td>
      <td class="gridContent">
      <?
	  				if (DecodeHTMLEntities($row["super_admin"]) == "1")
					{
						//echo "<a href='javascript:void(0);' onclick='window.open(\"".$curPage."_update.php?action=edit&id=".$row["id"]."\",\"_self\");'>Edit</a>";
					}
					else
					{
						$sActionEnable = "Enable";
                		if (DecodeHTMLEntities($row["status"]) == "active")
                		{ 
                    		$sActionEnable = "Disable";
                		}
                		echo "<a href='javascript:void(0);' onclick='window.open(\"".$sPageName."_update.php?action=edit&id=".DecodeHTMLEntities($row["id"])."\",\"_self\");'>Edit</a> <font color='#5ca0bc'>|</font> <a href='javascript:void(0);' onclick='if (confirm(\"Are you sure?\")) window.open(\"".$sPageName.".php?action=status&id=".DecodeHTMLEntities($row["id"])."\",\"_self\"); return false;'>".$sActionEnable."</a> <font color='#5ca0bc'>";
					}
	  ?></td>			
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
      <td width="100%" colspan="5" align="right" class="gridBottom" height="30" valign="top">
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