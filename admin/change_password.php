<? include("common/function.php") ?>
<?
	$sRedirect = false;
	$sURL = "change_password.php";
	
	session_start();
	
	if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > (60 * 10))) 
	{
    	// last request was more than 10 minutes ago
    	session_unset();     // unset $_SESSION variable for the run-time 
   	 	session_destroy();   // destroy session data in storage
?>
<script>
	location.href="default.php";
</script>
<?
	}

	if (isset($_SESSION["administrator_id"]))
	{
		$sID = $_SESSION["administrator_id"];
	}
	else
	{
		$sRedirect = true;
	}
	
	if (isset($_POST["hfChangePassword"]) && $_POST["hfChangePassword"] == "Change Password")
	{
		$sRedirect = false;
		$sURL = "change_password.aspx";
		$sOldPassword = EncodeHTMLEntities($_POST["tbOldPassword"],1);
		$sNewPassword = EncodeHTMLEntities($_POST["tbNewPassword"],1);
		$sNewPassword2 = Encrypt($sNewPassword);
		$sConfirmPassword = EncodeHTMLEntities($_POST["tbConfirmPassword"],1);
		try
		{
			$conn = ConnSQL();
			$stmt = $conn->stmt_init();
			$query = "SELECT * FROM administrator WHERE id=?";
			
			if(!$stmt->prepare($query))
			{
				throw new Exception('SQL ERROR: Failed to prepare statement');
			}
			else
			{
				$stmt->bind_param("s", $sID);
				$stmt->execute();

				$result = $stmt->get_result();
				if ($row = $result->fetch_assoc()) 
				{
					$sPassword = Decrypt(DecodeHTMLEntities($row["password"]));
					if ($sOldPassword == $sPassword)
					{
						if ($sNewPassword == $sConfirmPassword)
						{
							if ($sNewPassword == $sOldPassword)
							{
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
									$query = "UPDATE administrator SET last_updated_password=now(), login=0, first_login=1, password = ? WHERE id=".$sID;
									if(!$stmt->prepare($query))
									{
										throw new Exception('SQL ERROR: Failed to prepare statement');
									}
									else
									{	
										$stmt->bind_param("s", $sNewPassword2);
										$stmt->execute();
										
										$sRedirect = true;
										$sURL = "default.php";
?>
<script>
	alert("Password changed!");
</script>
<? 											
									}
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
						}
						else
						{
?>
<script>
	alert("New and confirm password must be the same!");
</script>
<? 												
						}
					}
					else
					{
?>
<script>
	alert("Wrong old password!");
</script>
<? 						
					}
					$stmt->close();
				}
			}
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
	location.href="<? echo $sURL; ?>";
</script>
<?
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Administrator Access Panel - Login</title>
    <link href="common/style.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="common/jscripts.js"></script>
    <style type="text/css">
        body
        {
            margin: 0px 0px 0px 0px;
            padding: 0px 0px 0px 0px;
        }
        
        a, a:link, a:visited {
	        color:#ffffff;
        }
        
        a:hover 
        {
            color:#ffff00;
        }
        
        #divCenter
        {
            width: 100%;
            height: 270px;
            background-image: url('images/login_administrator_bg2.jpg');
            background-position: left top;
            background-repeat: repeat-x;
            background-color: #FFFFFF;
        }
    </style>
</head>
<body id="body">
  <form id="form" action="change_password.php" defaultfocus="tbOldPassword" defaultbutton="btnChangePassword" method="post">
  <input type="hidden" id="hfChangePassword" name="hfChangePassword" value="Change Password">
  <div style="width:100%; height:60px; background-color: #FFFFFF;"></div>
  <div style="width:100%; height:200px; background-color: #FFFFFF;"><center><img src="images/logo.jpg" alt="Wu Gu Feng" title="Wu Gu Feng"/></center></div>
  <div align="center" id="divCenter">
  <table border="0" cellpadding="0" cellspacing="0" width="400" class="content">
    <tr>
      <td width="90" height="70">&nbsp;</td>
      <td width="200">&nbsp;</td>
      <td width="10">&nbsp;</td>
    </tr>
    <tr>
      <td height="25">&nbsp;</td>
      <td align="left"><b><font color="white">[ Administrator Access Panel Login ]</font></b></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td colspan="3" height="5"></td>
    </tr>
    <tr>
      <td height="25"><font color="white">Old Password</font></td>
      <td align="right"><input type="password" id="tbOldPassword" name="tbOldPassword" maxlength="20" style="width:250px;" class="textfield" onKeyUp="checkInput('V','Old Password');" onBlur="checkInput('V','Old Password');" /></td>
      <td align="left" valign="top"><div id="emOldPassword" class="error_msg" style="position:absolute; width:200px; height:25px; line-height:25px;"></div></td>
    </tr>
    <tr>
      <td colspan="3" height="5"></td>
    </tr>
    <tr>
      <td height="25"><font color="white">New Password</font></td>
      <td align="right"><input type="password" id="tbNewPassword" name="tbNewPassword" maxlength="20" style="width:250px;" class="textfield" onKeyUp="checkInput('V','New Password');" onBlur="checkInput('V','New Password');" /></td>
      <td align="left" valign="top"><div id="emNewPassword" class="error_msg" style="position:absolute; width:200px; height:25px; line-height:25px;"></div></td>
    </tr>
    <tr>
      <td colspan="3" height="5"></td>
    </tr>
    <tr>
      <td height="25"><font color="white">Confirm Password</font></td>
      <td align="right"><input type="password" id="tbConfirmPassword" name="tbConfirmPassword" maxlength="20" style="width:250px;" class="textfield" onKeyUp="checkInput('V','Confirm Password');" onBlur="checkInput('V','Confirm Password');" /></td>
      <td align="left" valign="top"><div id="emConfirmPassword" class="error_msg" style="position:absolute; width:200px; height:25px; line-height:25px;"></div></td>
    </tr>
    <tr>
      <td colspan="3" height="5"></td>
    </tr>
    <tr>
      <td height="30">&nbsp;</td>
      <td align="right"><input type="button" id="btnChangePassword" name="btnChangePassword" value="Change Password" style="width:160px;" onClick="checkInputs('V,Old Password,V,New Password,V,Confirm Password')" /></td>
      <td align="left" valign="top">&nbsp;</td>
    </tr>
  </table>
  </div>
  <div class="copyright" align="center" style="width:100%; height:160px; background-color: #FFFFFF;">&copy; 2014 Wu Gu Feng</div>
  </form>
</body>
</html>