<? 
	$sPageName = "admin_update";
	$sPrevPageName = "admin";
	
	include("common/function.php"); 
	include("common/setvariables.php"); 
	
	$curPage = "admin";
	$adminModule = "admin";
	$pageName = "Administrator";
	$sTable = "administrator";
	$sRedirect = false;
	$sContinue = false;
	
	if ($action == "Save")
	{
		try
		{
			$sName =  EncodeHTMLEntities($_POST["tbName"],1);
			$sEmail =  EncodeHTMLEntities($_POST["tbEmail"],1);
			$sEmail2 = Encrypt($sEmail);
			$sOldPassword =  EncodeHTMLEntities($_POST["tbOldPassword"],1);
			$sNewPassword =  EncodeHTMLEntities($_POST["tbNewPassword"],1);
			$sNewPassword2 = Encrypt($sNewPassword);
			$sConfirmPassword =  EncodeHTMLEntities($_POST["tbConfirmPassword"],1);
			
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
					if (DecodeHTMLEntities($row["name"]) == $sName && DecodeHTMLEntities($row["email"]) == $sEmail2)
					{
						$sContinue = true;
					}
					else
					{
						$query = "SELECT * FROM $sTable WHERE id!=? AND email=?";
						if(!$stmt->prepare($query))
						{
							throw new Exception('SQL ERROR: Failed to prepare statement');
						}
						else
						{
							$stmt->bind_param("ss", $id, $sEmail2);
							$stmt->execute();
				
							$result2 = $stmt->get_result();
							if ($row2 = $result2->fetch_assoc()) 
							{
								$sContinue = false;
?>
<script>
	alert("Email already exist!");
</script>
<? 					
							}
							else
							{
								$sContinue = true;					
							}
						}
					}
					if ($sContinue == true && $sOldPassword != "" && $sNewPassword != "" && $sConfirmPassword != "")
					{
						$sPassword = Decrypt(DecodeHTMLEntities($row["password"]));
						if ($sOldPassword == $sPassword)
						{
							if ($sNewPassword == $sConfirmPassword)
							{
								if ($sNewPassword == $sOldPassword)
								{
									$sContinue = false;
?>
<script>
	alert("Old and new password must not be the same!");
</script>
<? 								
								}
								else
								{
									if (PasswordStrength($sNewPassword) >= 5)
									{
										$query = "UPDATE $sTable SET last_updated_password=now(), login=0, password = ?, last_updated_date = now(), last_updated_by=? WHERE id=?";
										if(!$stmt->prepare($query))
										{
											throw new Exception('SQL ERROR: Failed to prepare statement');
										}
										else
										{	
											$stmt->bind_param("sss", $sNewPassword2, $_SESSION["administrator_id"], $id);
											$stmt->execute();
											
											$sRedirect = true;
											$sContinue = true;
											
											LogEntry($_SESSION["administrator_id"], $_SESSION["administrator_email"], "Password Changed", "Administrator", "admin_update.php", $id);
?>
<script>
	alert("Password changed!");
</script>
<? 											
										}
									}
									else
									{
										$sContinue = false;
?>
<script>
	alert("Password must be at least 8 character with upper case, lower case, special character and numeric!");
</script>
<? 									
									}
								}
							}
							else
							{
								$sContinue = false;
?>
<script>
	alert("New and confirm password must be the same!");
</script>
<? 												
							}
						}
						else
						{
							$sContinue = false;
?>
<script>
	alert("Wrong old password!");
</script>
<? 						
						}
					}
					else
					{
						$sRedirect = true;
					}
					if ($sContinue == true)
					{
						$query = "UPDATE $sTable SET name=?, email=?, last_updated_date = now(), last_updated_by=? WHERE id=?";
						if(!$stmt->prepare($query))
						{
							throw new Exception('SQL ERROR: Failed to prepare statement');
						}
						else
						{	
							$stmt->bind_param("ssss", $sName, $sEmail2, $_SESSION["administrator_id"], $id);
							$stmt->execute();	
							
							LogEntry($_SESSION["administrator_id"], $_SESSION["administrator_email"], "Updated", "Administrator", "admin_update.php", $id);														
						}
					}
				}
				else
				{
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
					$sName = DecodeHTMLEntities($row["name"]);
					$sEmail = Decrypt(DecodeHTMLEntities($row["email"]));
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
				var sName = trim(document.getElementById('tbName').value);
                var sEmail = trim(document.getElementById('tbEmail').value);
                var sOldPassword = trim(document.getElementById('tbOldPassword').value);
                var sNewPassword = trim(document.getElementById('tbNewPassword').value);
                var sConfirmPassword = trim(document.getElementById('tbConfirmPassword').value);
				
				if ((sOldPassword != "") || (sNewPassword != "") || (sConfirmPassword != "")) {
					checkInputs('V,Name,VE,Email,V,Old Password,V,New Password,V,Confirm Password');
                }
				else
				{
                    checkInputs('V,Name,VE,Email');
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
    <div id="div-title" class="content" style="width:100%; height:42px; color:#FFFFFF; line-height:42px; font-weight:bold; font-size:16px;">&nbsp;&nbsp;&nbsp;<? echo $pageName; ?> / Update</div>
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
      <tr><td colspan="5" style="line-height:15px;">&nbsp;</td></tr>
      <tr>
        <td></td>
        <td valign="middle">Name:</td>
        <td>&nbsp;</td>
        <td valign="middle"><input type="text" id="tbName" name="tbName" value="<? echo $sName; ?>" style="width:300px;" maxlength="150" class="textfield" /></td>
		<td valign="middle" align="left"><div id="emName" class="error_msg" style="width:200px;"></div></td>
      </tr>
      <tr>
        <td colspan="5" style="line-height:15px;">&nbsp;</td>
      </tr>
      <tr>
        <td></td>
        <td valign="middle">Email:</td>
        <td>&nbsp;</td>
        <td valign="middle"><input type="text" id="tbEmail" name="tbEmail" value="<? echo $sEmail; ?>" style="width:300px;" maxlength="150" class="textfield" /></td>
		<td valign="middle" align="left"><div id="emEmail" class="error_msg" style="width:200px;"></div></td>
      </tr>
      <tr>
        <td colspan="5" style="line-height:15px;">&nbsp;</td>
      </tr>
      <tr>
        <td></td>
        <td valign="middle">Old Password:</td>
        <td>&nbsp;</td>
        <td valign="middle"><input type="password" id="tbOldPassword" name="tbOldPassword" style="width:300px;" maxlength="150" class="textfield" /></td>
		<td valign="middle" align="left"><div id="emOldPassword" class="error_msg" style="width:200px;"></div></td>
      </tr>
      <tr>
        <td colspan="5" style="line-height:15px;">&nbsp;</td>
      </tr>
      <tr>
        <td></td>
        <td valign="middle">Password:</td>
        <td>&nbsp;</td>
        <td valign="middle"><input type="password" id="tbNewPassword" name="tbNewPassword" style="width:300px;" maxlength="150" class="textfield" /></td>
		<td valign="middle" align="left"><div id="emNewPassword" class="error_msg" style="width:200px;"></div></td>
      </tr>
      <tr>
        <td colspan="5" style="line-height:15px;">&nbsp;</td>
      </tr>
      <tr>
        <td></td>
        <td valign="middle">Confirm Password:</td>
        <td>&nbsp;</td>
        <td valign="middle"><input type="password" id="tbConfirmPassword" name="tbConfirmPassword" style="width:300px;" maxlength="150" class="textfield" /></td>
		<td valign="middle" align="left"><div id="emConfirmPassword" class="error_msg" style="width:200px;"></div></td>
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
