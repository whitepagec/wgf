<? include("common/function.php") ?>
<?
	$sRedirect = false;
	$sURL = "forgot_password.php";
	
	if (isset($_POST["hfRequestPassword"]) && $_POST["hfRequestPassword"] == "Request Password")
	{
		session_start();
		$sUserName = EncodeHTMLEntities($_POST["tbUserName"],1);
		$sUserName2 = Encrypt($sUserName);
		
		try
		{
			$conn = ConnSQL();
			$stmt = $conn->stmt_init();
			$query = "SELECT * FROM administrator WHERE email=? AND status='active'";
			
			if(!$stmt->prepare($query))
			{
				throw new Exception('SQL ERROR: Failed to prepare statement');
			}
			else
			{
				$stmt->bind_param("s", $sUserName2);
				$stmt->execute();

				$result = $stmt->get_result();
				if ($row = $result->fetch_assoc()) 
				{
					$sName = DecodeHTMLEntities($row["name"]);
					$sEmail = Decrypt(DecodeHTMLEntities($row["email"]));
					$pw = Decrypt(DecodeHTMLEntities($row["password"]));
					$to = $sEmail;
					$subject = "[Wu Gu Feng] Password Request";
					$from = "FROM: Wu Gu Feng <admin@wugufeng.com.sg>";
					
					$mailBody = "Hi $sName,<br><br>This is password. Please login using the following password.<br><br>Password: $pw<br><br>Note: This is an auto generated message, please do not reply.<br><br>";
					
					//Specify Email Message 
					$from = 'MIME-Version: 1.0' . "\r\n"; 
					$from .= 'Content-Type: text/html; charset=iso-8859-1'."\r\n";
					//To send HTML mail, the Content-type header must be set 
					$from .= 'FROM: Wu Gu Feng <admin@wugufeng.com.sg>'."\r\n";
			
					mail($to, $subject, $mailBody, $from);
					$stmt->close();
					
					$sURL = "default.php";
					$sRedirect = true;
?>
<script>
	alert("Password had been sent to your email!");
</script>
<? 								
				}
				else
				{
					
?>
<script>
	alert("Invalid user name!");
</script>
<? 					
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
  <form id="form" action="forgot_password.php" defaultfocus="tbUserName" defaultbutton="btnRequestPassword" method="post">
  <input type="hidden" id="hfRequestPassword" name="hfRequestPassword" value="Request Password">
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
      <td align="left"><b><font color="white">[ Administrator Access Panel Request Password ]</font></b></td>
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
      <td height="30">&nbsp;</td>
      <td align="right"><a href="default.php" target="_top" style="font-size:smaller">Login</a></td>
      <td align="left" valign="top">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="3" height="5"></td>
    </tr>
    <tr>
      <td height="30">&nbsp;</td>
      <td align="right"><input type="button" id="btnRequestPassword" name="btnRequestPassword" value="Request Password" style="width:160px;" onClick="checkInputs('EV,User Name')" /></td>
      <td align="left" valign="top">&nbsp;</td>
    </tr>
  </table>
  </div>
  <div class="copyright" align="center" style="width:100%; height:160px; background-color: #FFFFFF;">&copy; 2014 Wu Gu Feng</div>
  </form>
</body>
</html>