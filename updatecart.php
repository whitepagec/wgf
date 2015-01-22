<?php include("admin/common/function.php"); ?>
<?php
	session_start();
	
	$conn = ConnSQL();
	$stmt = $conn->stmt_init();
	
	if (isset($_REQUEST["values"]))
	{
		$values = $_REQUEST["values"];
		$valueArray = split(",",$values);
		
		$exists = false;
		$count = 0;
		$count_inner = 0;
		$values = "";
		$gst = 0;
		$sub_total = 0;
		
		// delete existing data
		while (isset($_SESSION["wgf_item_".$count]))
		{	
			$_SESSION["wgf_item_".$count] = NULL;
			$_SESSION["wgf_item_quantity_".$count] = NULL;
			
			$count = $count + 1;
		}
		
		$count = 0;
		
		// loop array and set value
		while (isset($valueArray[$count]))
		{
			$_SESSION["wgf_item_".$count_inner] = $valueArray[$count];	
			$_SESSION["wgf_item_quantity_".$count_inner] = $valueArray[$count+1];
			
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
				
				$sub_total = 0;
				
				$query = "SELECT p.title as title, p.title_chinese as title_chinese, pp.specification as spec, pp.price as price, pi.image as image, pp.id as id, pp.price as price FROM product_price as pp, product as p, product_image as pi WHERE pp.id=? AND pp.pro_id = p.id AND pi.pro_id = p.id ORDER BY pi.id asc";
								
				if(!$stmt->prepare($query))
				{
					throw new Exception('SQL ERROR: Failed to prepare statement');
				}
				else
				{
					$stmt->bind_param("s", $_SESSION["wgf_item_".$count_inner]);
					$stmt->execute();
					
					$result = $stmt->get_result();
					if ($row = $result->fetch_assoc()) 
					{
						$sub_total = number_format(round(((float)DecodeHTMLEntities($row["price"]) * (($gst/100) + 1) * (float)$_SESSION["wgf_item_quantity_".$count_inner]),1),2);				
					}
				}	
			}
			catch (Exception $e) 
			{
				ErrorLog($e);
			}	
?>
<script>
	parent.document.getElementById("divSubTotal<?php echo $count_inner; ?>").innerHTML = "<?php echo $sub_total; ?>";
</script>
<?php					
			$count_inner = $count_inner + 1;
			$count = $count + 2;
		}
	}
?>
<script>
	parent.document.getElementById("ifNoOfItems").src = parent.document.getElementById("ifNoOfItems").src;
	parent.document.getElementById("ifTotalPrice").src = parent.document.getElementById("ifTotalPrice").src;
</script>
