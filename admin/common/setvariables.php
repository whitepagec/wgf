<?
	session_start();
	
	$sImageViewPath = "/admin/";
	$sImagePath = "/home/wugufengcom/public_html/admin/";
	//$sImageViewPath = "http://bakemission.wpcdev2.com/admin/";
	//$sImagePath = "/home/bakemissionwpcde/public_html/admin/";
	
	if (!isset($_SESSION["administrator_id"]))
	{
?>
<script>
	window.open('home.php','_top');
</script>
<?		
	}
	
	if (!isset($_REQUEST["id"]))
	{
		$id = "0";
	}
	else
	{
		$id = $_REQUEST["id"];
	}
	
	if (!isset($_REQUEST["catID"]))
	{
		if (!isset($_SESSION[$sPageName.'CatID']))
		{
			$_SESSION[$sPageName.'CatID'] = "0";
		}
	}
	else
	{
		$_SESSION[$sPageName.'CatID'] = $_REQUEST["catID"];
	}
	
	if (!isset($_REQUEST["pageNos"]))
	{
		if (!isset($_SESSION[$sPageName.'PageNos']))
		{
			$_SESSION[$sPageName.'PageNos'] = "0";
		}
	}
	else
	{
		$_SESSION[$sPageName.'PageNos'] = $_REQUEST["pageNos"];
	}
	
	if (!isset($_REQUEST["sortField"]))
	{
		if (!isset($_SESSION[$sPageName.'SortField']))
		{
			$_SESSION[$sPageName.'SortField'] = "id";
		}
	}
	else
	{
		$_SESSION[$sPageName.'SortField'] = $_REQUEST["sortField"];
	}
	
	if (!isset($_REQUEST["sortDir"]))
	{
		if (!isset($_SESSION[$sPageName.'SortDir']))
		{
			$_SESSION[$sPageName.'SortDir'] = "DESC";
		}
	}
	else
	{
		$_SESSION[$sPageName.'SortDir'] = $_REQUEST["sortDir"];
	}
	
	if (!isset($_REQUEST["ddlDistrict"]))
	{
		if (!isset($_SESSION[$sPageName.'District']))
		{
			$_SESSION[$sPageName.'District'] = "0";
		}
	}
	else
	{
		$_SESSION[$sPageName.'District'] = $_REQUEST["ddlDistrict"];
	}
	$sDistrict = $_SESSION[$sPageName.'District'];
	
	if (!isset($_REQUEST["action"]))
	{
		$action = "";
	}
	else
	{
		$action = $_REQUEST["action"];
	}
	
	$noOfItem = 15;
	
	
	// set the number of row in one page
	if ((int)$_SESSION[$sPageName.'PageNos'] != 0)
	{
		$minCount = (int)$_SESSION[$sPageName.'PageNos'] * $noOfItem;
		$maxCount = ((int)$_SESSION[$sPageName.'PageNos']+1) * $noOfItem;
	}
	else
	{
		$minCount = 0;
		$maxCount = $noOfItem;
	}
?>