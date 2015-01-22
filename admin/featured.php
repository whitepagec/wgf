<? 
	$sPageName = "featured";
	$sPrevPageName = "featured";
	
	include("common/function.php"); 
	include("common/setvariables.php"); 
	
	$curPage = "featured";
	$adminModule = "featured";
	$pageName = "Featured";
	$sTable = "featured";
	$sRedirect = false;
	$id = "1";
	
	if ($action == "Save")
	{
		try
		{
			$sYouTube = EncodeHTMLEntities($_POST["tbYouTube"],1);
			
			$conn = ConnSQL();
			$stmt = $conn->stmt_init();
			
			$query = "UPDATE $sTable SET youtube = ?, last_updated_date = now(), last_updated_by = ? WHERE id=?";
		
			if(!$stmt->prepare($query))
			{
				throw new Exception('SQL ERROR: Failed to prepare statement');
			}
			else
			{ 
				$stmt->bind_param("sss", $sYouTube, $_SESSION["administrator_id"], $id);
				$stmt->execute();
			
				LogEntry($_SESSION["administrator_id"], $_SESSION["administrator_email"], "Updated", "Featured", "featured.php", $id);
				
				$sRedirect = true;
			}
			
			$stmt->close();
			$conn->close();
		} 
		catch (Exception $e) 
		{
			ErrorLog($e);
		}
	}
	else
	{
		try
		{
			$conn = ConnSQL();
			$stmt = $conn->stmt_init();
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
					$sYouTube = DecodeHTMLEntities($row["youtube"]);
				}
				$stmt->close();
				$conn->close();
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
				checkInput('V','YouTube');			
				if (document.getElementById("emYouTube").innerHTML == "")
				{
					document.getElementById("btnSave").disabled = true;
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
    <div id="div-title" class="content" style="width:100%; height:42px; color:#FFFFFF; line-height:42px; font-weight:bold; font-size:16px;">&nbsp;&nbsp;&nbsp;<? echo $pageName; ?></div>
	<div class="div-horizontal-divider"></div>
    <input type="button" id="btnSave" name="btnSave" onClick="SaveClick();" value="Save" width="80" />
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
        <td valign="middle">YouTube: [Please exclude<br />
        "http://www.youtube.com/watch?v="]</td>
        <td>&nbsp;</td>
        <td valign="middle"><input type="text" id="tbYouTube" name="tbYouTube" value="<? echo $sYouTube; ?>" style="width:300px;" maxlength="150" class="textfield" /></td>
		<td valign="middle" align="left"><div id="emYouTube" class="error_msg" style="width:200px;"></div></td>
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
