<?php include("admin/common/function.php"); ?>
<?php
	session_start();
	
	$count = 0;
		
	// get total items
	while (isset($_SESSION["wgf_item_".$count]))
	{	
		$count++;
	}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>WuGuFu</title>
<style>
	body {
		margin:0px;
		padding:0px;
	}
	
	.content
	{
	font-family: 'Open Sans';
	font-size:14px;
	color: #CDB289;
	}
</style>
</head>
<body>
<table cellpadding="0" cellspacing="0" border="0">
  <tr>
    <td width="20" height="15" align="center" class="content" style="padding-top:0px;"><?php echo $count; ?></td>
  </tr>
</table>
</body>
</html>
