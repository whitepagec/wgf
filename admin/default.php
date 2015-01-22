<? include("common/function.php") ?>
<?
	$sRedirect = false;
	$sURL = "default.php";
	if (isset($_POST["hfLogin"]) && $_POST["hfLogin"] == "Login")
	{
		session_start();
		$sUserName = EncodeHTMLEntities($_POST["tbUserName"],1);
		$sPassword = EncodeHTMLEntities($_POST["tbPassword"],1);
		try
		{
			$conn = ConnSQL();
			$stmt = $conn->stmt_init();
			$query = "SELECT * FROM administrator WHERE email=? AND password=? AND status='active'";
			
			if(!$stmt->prepare($query))
			{
				throw new Exception('SQL ERROR: Failed to prepare statement');
			}
			else
			{
				$sUserName2 = Encrypt($sUserName);
				$sPassword2 = Encrypt($sPassword);
				$stmt->bind_param("ss", $sUserName2, $sPassword2);
				$stmt->execute();

				$result = $stmt->get_result();
				if ($row = $result->fetch_assoc()) 
				{
					$sID = DecodeHTMLEntities($row["id"]);
					$sName = DecodeHTMLEntities($row["name"]);
					$sEmail = Decrypt(DecodeHTMLEntities($row["email"]));
					$sCreatedDate = DecodeHTMLEntities($row["created_date"]);
					$sLastLogin = DecodeHTMLEntities($row["last_login"]);
					$sSuperAdmin = DecodeHTMLEntities($row["super_admin"]);
					$sRoleID = DecodeHTMLEntities($row["role_id"]);
					$sLogin = (int)DecodeHTMLEntities($row["login"]);
					$sFirstLogin = (int)DecodeHTMLEntities($row["first_login"]);
					$sLastPasswordChange = DecodeHTMLEntities($row["last_updated_password"]);
					
					if ($sFirstLogin == 1)
					{
						if ($sLogin < 3)
						{
							$baseArr  = explode(" ",$sLastPasswordChange);
							$dateArr = explode("-",$baseArr[0]);
							$timeArr = explode(":",$baseArr[1]);
							$sLastPasswordChangeInt = mktime($timeArr[0],$timeArr[1],$timeArr[2],$dateArr[1],$dateArr[2],$dateArr[0]) ;
							if ((time() - $sLastPasswordChangeInt) > (60 * 60 * 24 * 90))
							{
								$_SESSION["administrator_id"] = $sID;
								$_SESSION['LAST_ACTIVITY'] = time();
								$sURL = "change_password.php";
								$sRedirect = true;
?>
<script>
	alert("Your password has not been change for 90 days!");
</script>
<? 														
							}
							else 
							{					
								$pw = GenRandomString();
								$_SESSION["second_factor"] = $pw;
								$_SESSION["administrator_id"] = $sID;
								$_SESSION['LAST_ACTIVITY'] = time();
								
								$to = $sEmail;
								$subject = "[Wu Gu Feng] Second Factor Password";
								$from = "FROM: Wu Gu Feng <admin@wugufeng.com.sg>";
								
								$mailBody = "Hi $sName,<br><br>This is your second factor password. Please login using the following password.<br><br>Second Factor Password: $pw<br><br>Note: This is an auto generated message, please do not reply.<br><br>";
								
								//Specify Email Message 
								$from = 'MIME-Version: 1.0' . "\r\n"; 
								$from .= 'Content-Type: text/html; charset=iso-8859-1'."\r\n";
								//To send HTML mail, the Content-type header must be set 
								$from .= 'FROM: Wu Gu Feng <admin@wugufeng.com.sg>'."\r\n";
						
								mail($to, $subject, $mailBody, $from);
									
								$query = "UPDATE administrator SET login = 0 WHERE id=".$sID;
								if(!$stmt->prepare($query))
								{
									throw new Exception('SQL ERROR: Failed to prepare statement');
								}
								else
								{
									$stmt->execute();
								}
								$stmt->close();
								
								$sURL = "login.php";
								$sRedirect = true;
?>
<script>
	alert("Second factor password had been sent to your email!");
</script>
<? 								
							}
						}
						else
						{
?>
<script>
	alert("Maximum tries exceed, contact your administrator!");
</script>
<?						
						}
					}
					else
					{
						$_SESSION["administrator_id"] = $sID;
						$_SESSION['LAST_ACTIVITY'] = time();
						$sURL = "change_password.php";
						$sRedirect = true;
?>
<script>
	alert("First time login, please change your password!");
</script>
<?					
					}
				}
				else
				{
					$query = "SELECT * FROM administrator WHERE email=? AND status='active'";
			
					if(!$stmt->prepare($query))
					{
						throw new Exception('SQL ERROR: Failed to prepare statement');
					}
					else
					{
						$stmt->bind_param("s", $sUserName);
						$stmt->execute();
						
						$result = $stmt->get_result();
						if ($row = $result->fetch_assoc()) 
						{
							$sID = DecodeHTMLEntities($row["id"]);
							$sLogin = (int)DecodeHTMLEntities($row["login"]);
							
							if ($sLogin < 3)
							{
								$query = "UPDATE administrator SET login = login+1 WHERE id=".$sID;
								if(!$stmt->prepare($query))
								{
									throw new Exception('SQL ERROR: Failed to prepare statement');
								}
								else
								{
									$stmt->execute();
								}
?>
<script>
	alert("Wrong user name or password!");
</script>
<?							}
							else
							{
?>
<script>
	alert("Maximum tries exceed, contact your administrator!");
</script>
<?								
							}
							$stmt->close();
						}
						else
						{
?>
<script>
	alert("Wrong user name or password!");
</script>
<? 						
						}
					}
				}
			}
			$conn->close();
			
			if ($sRedirect) 
			{
?>
<script>
	location.href="<? echo $sURL; ?>";
</script>
<?
			}
		} 
		catch (Exception $e) 
		{
			ErrorLog($e);
		}
	}
	else
	{
		UnSetSession("administrator_id");
		UnSetSession("second_factor");
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
  <form id="form" action="default.php" defaultfocus="tbUserName" defaultbutton="btnLogin" method="post">
  <input type="hidden" id="hfLogin" name="hfLogin" value="Login">
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
      <td height="25"><font color="white">User Name</font></td>
      <td align="right"><input type="text" id="tbUserName" name="tbUserName" style="width:250px;" maxlength="200" value="<? echo $sUserName; ?>" class="textfield" onKeyUp="checkInput('V','User Name');" onBlur="checkInput('EV','User Name');" /></td>
      <td align="left" valign="top"><div id="emUserName" class="error_msg" style="position:absolute; width:200px; height:25px; line-height:25px;"></div></td>
    </tr>
    <tr>
      <td height="25"><font color="white">Password</font></td>
      <td align="right"><input type="password" id="tbPassword" name="tbPassword" maxlength="40" style="width:250px;" class="textfield" onKeyUp="checkInput('V','Password');" onBlur="checkInput('V','Password');" /></td>
      <td align="left" valign="top"><div id="emPassword" class="error_msg" style="position:absolute; width:200px; height:25px; line-height:25px;"></div></td>
    </tr>
    <tr>
      <td height="30">&nbsp;</td>
      <td align="right"><a href="forgot_password.php" target="_top" style="font-size:smaller">Forget Password</a></td>
      <td align="left" valign="top">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="3" height="5"></td>
    </tr>
    <tr>
      <td height="30">&nbsp;</td>
      <td align="right"><input type="button" id="btnLogin" name="btnLogin" value="Login" style="width:60px;" onClick="checkInputs('EV,User Name,V,Password')" /></td>
      <td align="left" valign="top">&nbsp;</td>
    </tr>
  </table>
  </div>
  <div class="copyright" align="center" style="width:100%; height:160px; background-color: #FFFFFF;">&copy; 2014 Wu Gu Feng</div>
  </form>
</body>
</html>