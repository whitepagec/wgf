<? include("common/function.php") ?>
<?
	$sRedirect = false;
	$sURL = "default.php";
	
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

	if (isset($_SESSION["second_factor"]))
	{
		$sPW = $_SESSION["second_factor"];
		if (isset($_SESSION["administrator_id"]))
		{
			$sID = $_SESSION["administrator_id"];
		}
		else
		{
			$sRedirect = true;
		}
	}
	else
	{
		$sRedirect = true;
	}
	
	if (isset($_POST["hfLogin"]) && $_POST["hfLogin"] == "Login")
	{
		$sRedirect = false;
		$sURL = "login.aspx";
		$sPassword = $_POST["tbPassword"];
		try
		{
			if ($sPW == $sPassword)
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
						$sID = DecodeHTMLEntities($row["id"]);
						$sName = DecodeHTMLEntities($row["name"]);
						$sEmail = Decrypt(DecodeHTMLEntities($row["email"]));
						$sCreatedDate = DecodeHTMLEntities($row["created_date"]);
						$sLastLogin = DecodeHTMLEntities($row["last_login"]);
						$sSuperAdmin = DecodeHTMLEntities($row["super_admin"]);
						$sRoleID = DecodeHTMLEntities($row["role_id"]);
						
						$_SESSION["module"] = "admin";
						$_SESSION["administrator_id"] = $sID;
						$_SESSION["administrator_name"] = $sName;
						$_SESSION["administrator_email"] = $sEmail;
						$_SESSION["administrator_createddate"] = $sCreatedDate;
						$_SESSION["administrator_lastlogin"] = $sLastLogin;
						
						if ($sSuperAdmin == "1")
						{
							$_SESSION["administrator_superadmin"] = "true";
						}
						else
						{
							$_SESSION["administrator_superadmin"] = "false";
						}
						
						UpdateLastLoginDateTime($sID);
						LogEntry($sID, $sEmail,"Login","Login","default.php",$sID);
						
						$stmt->close();
						
						$sURL = "home.php";
						$sRedirect = true;
					}
				}
				$conn->close();
			}
			else
			{
?>
<script>
	alert("Wrong second factor password!");
</script>
<?			
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
  <form id="form" action="login.php" defaultfocus="tbUserName" defaultbutton="btnLogin" method="post">
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
      <td height="25"><font color="white">Second Factor</font></td>
      <td align="right"><input type="password" id="tbPassword" name="tbPassword" maxlength="40" style="width:250px;" class="textfield" onKeyUp="checkInput('V','Password');" onBlur="checkInput('V','Password');" /></td>
      <td align="left" valign="top"><div id="emPassword" class="error_msg" style="position:absolute; width:200px; height:25px; line-height:25px;"></div></td>
    </tr>
    <tr>
      <td colspan="3" height="5"></td>
    </tr>
    <tr>
      <td height="30">&nbsp;</td>
      <td align="right"><input type="button" id="btnLogin" name="btnLogin" value="Login" style="width:60px;" onClick="checkInputs('V,Password')" /></td>
      <td align="left" valign="top">&nbsp;</td>
    </tr>
  </table>
  </div>
  <div class="copyright" align="center" style="width:100%; height:160px; background-color: #FFFFFF;">&copy; 2014 Wu Gu Feng</div>
  </form>
</body>
</html>