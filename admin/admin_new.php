<? 
	$sPageName = "admin_new";
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
			$sPassword =  EncodeHTMLEntities($_POST["tbPassword"],1);
			$sPassword2 = Encrypt($sPassword);
			$sConfirmPassword =  EncodeHTMLEntities($_POST["tbConfirmPassword"],1);
			
			$conn = ConnSQL();
			$stmt = $conn->stmt_init();
			
			$query = "SELECT * FROM $sTable WHERE email=?";
			if(!$stmt->prepare($query))
			{
				throw new Exception('SQL ERROR: Failed to prepare statement');
			}
			else
			{
				$stmt->bind_param("s", $sEmail2);
				$stmt->execute();
	
				$result = $stmt->get_result();
				if ($row = $result->fetch_assoc()) 
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
					
			if (($sPassword == $sConfirmPassword) && $sContinue == true)
			{
				if (PasswordStrength($sPassword) >= 5)
				{
					$query = "INSERT INTO $sTable (name,email,password,login,last_login,created_date,created_by,last_updated_date,last_updated_by,last_updated_password,super_admin,status) values (?,?,?,'0',now(),now(),?,now(),?,now(),'0','active')";
				
					if(!$stmt->prepare($query))
					{
						throw new Exception('SQL ERROR: Failed to prepare statement');
					}
					else
					{ 
						$stmt->bind_param("sssss", $sName, $sEmail2, $sPassword2, $_SESSION["administrator_id"], $_SESSION["administrator_id"]);
						$stmt->execute();
								
						$sRedirect = true;
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
					LogEntry($_SESSION["administrator_id"], $_SESSION["administrator_email"], "Added", "Administrator", "admin_new.php", $sTempID);
				}
				else
				{
?>
<script>
alert("Password must be at least 8 character with upper case, lower case, special character and numeric!");
</script>
<? 									
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
				checkInput('V','Name');	
				checkInput('VE','Email');	
				checkInput('V','Password');	
				checkInput('V','Confirm Password');	
				if (document.getElementById("emName").innerHTML == "" && document.getElementById("emEmail").innerHTML == "" && document.getElementById("emPassword").innerHTML == "" && document.getElementById("emConfirmPassword").innerHTML == "")
				{
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
        <td valign="middle">Password:</td>
        <td>&nbsp;</td>
        <td valign="middle"><input type="password" id="tbPassword" name="tbPassword" style="width:300px;" maxlength="150" class="textfield" /></td>
		<td valign="middle" align="left"><div id="emPassword" class="error_msg" style="width:200px;"></div></td>
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
