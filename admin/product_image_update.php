<? 
	$sPageName = "product_image_new";
	$sPrevPageName = "product_image";
	$sPrevPrevPageName = "product_category";
	
	include("common/function.php"); 
	include("common/setvariables.php"); 
	
	$curPage = "product_image";
	$adminModule = "productcategory";
	$pageName = " - Image";
	$sTable = "product_image";
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
			$sTitle = EncodeHTMLEntities($_POST["tbTitle"],1);
			
			$conn = ConnSQL();
			$stmt = $conn->stmt_init();
			
			if ($_FILES["tbImage"]["name"] == "")
			{
				$query = "UPDATE $sTable SET title = ?, last_updated_date = now(), last_updated_by = ? WHERE id=?";
			
				if(!$stmt->prepare($query))
				{
					throw new Exception('SQL ERROR: Failed to prepare statement');
				}
				else
				{ 
					$stmt->bind_param("sss", $sTitle, $_SESSION["administrator_id"], $id);
					$stmt->execute();
				
					LogEntry($_SESSION["administrator_id"], $_SESSION["administrator_email"], "Updated", "Product Image", "product_image_update.php", $id);
					
					$sRedirect = true;
				}
			}
			else
			{
				if (($_FILES["tbImage"]["type"] == "image/jpg" || $_FILES["tbImage"]["type"] == "image/jpeg" || $_FILES["tbImage"]["type"] == "image/gif" || $_FILES["tbImage"]["type"] == "image/png") && $_FILES["tbImage"]["size"] <= 2000000)
				{
					if ($_FILES["tbImage"]["error"] > 0)
					{	
						throw new Exception($_FILES["tbImage"]["error"]);
					}
					else
					{
						$extension = end(explode(".", $_FILES["tbImage"]["name"]));
						if(move_uploaded_file($_FILES['tbImage']['tmp_name'], $sImagePath."product/image/".$id.".".$extension)) 
						{
							$query = "UPDATE $sTable SET title = ?, image = ?, last_updated_date = now(), last_updated_by = ? WHERE id=?";
							
							if(!$stmt->prepare($query))
							{
								throw new Exception('SQL ERROR: Failed to prepare statement');
							}
							else
							{ 
								$sImage = $id.".".$extension;
								$stmt->bind_param("ssss", $sTitle, $sImage, $_SESSION["administrator_id"], $id);
								$stmt->execute();
								
								LogEntry($_SESSION["administrator_id"], $_SESSION["administrator_email"], "Updated", "Product Image", "product_image_update.php", $id);
								
								$sRedirect = true;
							}
						}
						else
						{
							throw new Exception("Problem uploading your file.");
						}
					}
				}
				else 
				{	
					if ($_FILES["tbImage"]["type"] != "image/jpg" && $_FILES["tbImage"]["type"] != "image/jpeg" && $_FILES["tbImage"]["type"] != "image/gif" && $_FILES["tbImage"]["type"] != "image/png")
					{
		?>
		<script>
		alert("Only .jpg, .jpeg, .gif and .png file allow.");
		</script>
		<?					
					}
					else if ($_FILES["tbImage"]["size"] > 2000000)
					{
		?>
		<script>
		alert("Max 2M file size allow.");
		</script>
		<?										
					}
				}	
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
					$sImage = DecodeHTMLEntities($row["image"]);
					$sTitle = DecodeHTMLEntities($row["title"]);
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
				document.getElementById("btnSave").disabled = true;
				document.getElementById("btnBack").disabled = true;
				document.getElementById("form").submit();
            }
            catch (ex) {
            }
        }
    </script>
</head>
<body>
  <form enctype="multipart/form-data" id="form" name="form" method="post">
  <input type="hidden" id="action" name="action" value="Save">
  <div style="width:100%;">
    <div id="div-title" class="content" style="width:100%; height:42px; color:#FFFFFF; line-height:42px; font-weight:bold; font-size:16px;">&nbsp;&nbsp;&nbsp;<? echo $pageName; ?> / Update</div>
	<div class="div-horizontal-divider"></div>
    <input type="button" id="btnSave" name="btnSave" onClick="SaveClick();" value="Save" width="80" />&nbsp;&nbsp;<input id="btnBack" name="btnBack" type="button" value="Go Back" onClick="window.open('<? echo $sPrevPageName; ?>.php','_self');" width="80" /><?
	if ($sImage != "")
	{
	?>&nbsp;&nbsp;<input type="button" id="btnViewImage" name="btnViewImage" onClick="window.open('<? echo $sImageViewPath; ?>product/image/<? echo $sImage; ?>','_blank')" value="View Image" width="80" /><?
	}
	?>
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
        <td valign="middle">Title:</td>
        <td>&nbsp;</td>
        <td valign="middle"><input type="text" id="tbTitle" name="tbTitle" value="<? echo $sTitle; ?>" style="width:300px;" maxlength="150" class="textfield" /></td>
		<td valign="middle" align="left"><div id="emTitle" class="error_msg" style="width:200px;"></div></td>
      </tr>
      <tr>
        <td colspan="5">&nbsp;</td>
      </tr>
      <tr>
        <td></td>
        <td valign="middle">Image: [300x300]</td>
        <td>&nbsp;</td>
        <td valign="middle"><input id="tbImage" name="tbImage" type="file" class="textfield" /></td>
		<td valign="middle" align="left"><div id="emImage" class="error_msg" style="width:200px;"></div></td>
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
