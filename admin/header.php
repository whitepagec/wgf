<?    
    $sRootPath = "//";
    $bIsIE8OrLower = false;
    
	if ($_SESSION["administrator_id"] == "" && $adminModule == "home")
	{
?>
<script>
	location.href = "login_err.htm";
</script>
<?
	}
	else
	{
		//check if normal admin has the proper privilege to access the module
		if ($adminModule != "")
		{
			if ($adminModule == "administrator")
			{
				if ($_SESSION["administrator_superadmin"] != "true")
				{						
?>
<script>
	parent.location.href = "login_err.htm";
</script>
<?						
				}
			}
		}
		else
		{
?>
<script>
	location.href = "login_err.htm";
</script>
<?		
		}
	}
?>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="cache-control" content="no-cache">
<meta http-equiv="pragma" content="no-cache">
<meta name="robots" content="noindex">
<link href="common/style.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="common/jscripts.js"></script>
