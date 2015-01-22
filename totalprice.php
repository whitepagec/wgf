<?php include("admin/common/function.php"); ?>
<?php
	session_start();
	
	$conn = ConnSQL();
	$stmt = $conn->stmt_init();
	
	$count = 0;
	$gst = 0;
	$total_price = 0;
	
	// get total items
	while (isset($_SESSION["wgf_item_".$count]))
	{	
		try
		{
			$query = "SELECT * FROM gst";
					
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
					$gst = DecodeHTMLEntities($row["gst"]);
				}
			}
			
			$query = "SELECT p.title as title, p.title_chinese as title_chinese, pp.specification as spec, pp.price as price, pi.image as image, pp.id as id, pp.price as price FROM product_price as pp, product as p, product_image as pi WHERE pp.id=? AND pp.pro_id = p.id AND pi.pro_id = p.id ORDER BY pi.id asc";
							
			if(!$stmt->prepare($query))
			{
				throw new Exception('SQL ERROR: Failed to prepare statement');
			}
			else
			{
				$stmt->bind_param("s", $_SESSION["wgf_item_".$count]);
				$stmt->execute();
				
				$result = $stmt->get_result();
				if ($row = $result->fetch_assoc()) 
				{
					$total_price = $total_price + number_format(round(((float)DecodeHTMLEntities($row["price"]) * (($gst/100) + 1) * (float)$_SESSION["wgf_item_quantity_".$count]),1),2);				
				}
			}	
		}
		catch (Exception $e) 
		{
			ErrorLog($e);
		}	
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
    <td height="47" valign="middle" align="center" class="content" style="font-size:33px; color:#5a1300" width="180"><?php echo number_format($total_price,2); ?></td>
  </tr>
</table>
</body>
</html>
