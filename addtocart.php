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
		
		// loop array
		while (isset($valueArray[$count]))
		{
			$count_inner = 0;
			$exists = false;
			
			// loop existing item to check if item already exist
			while (isset($_SESSION["wgf_item_".$count_inner]) && $exists == false)
			{	
				// update existing item record
				if ((int)$_SESSION["wgf_item_".$count_inner] == (int)$valueArray[$count])
				{
					$_SESSION["wgf_item_quantity_".$count_inner] = (int)$_SESSION["wgf_item_quantity_".$count_inner] + (int)$valueArray[$count+1];
					
					try
					{
						$query = "SELECT p.title as title, pp.specification as spec FROM product_price as pp, product as p WHERE pp.id=? AND pp.pro_id = p.id";
										
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
?>
<script>
	alert("<?php echo DecodeHTMLEntities($row["title"]); ?> (<?php echo DecodeHTMLEntities($row["spec"]); ?>) - <?php echo $_SESSION["wgf_item_quantity_".$count_inner]; ?> Item/Items");
</script>
<?php								
							}
						}	
					}
					catch (Exception $e) 
					{
						ErrorLog($e);
					}				
					$exists = true;
				}
				$count_inner++;
			}
			if ($exists == false)
			{
				if ($values == "")
				{
					$values = $valueArray[$count].",".$valueArray[$count+1];
				}
				else
				{
					$values = $values.",".$valueArray[$count].",".$valueArray[$count+1];
				}
			}
			$count = $count + 2;
		}
		
		$valueArray = split(",",$values);
		$count = 0;
		$count_inner = 0;
		$added = false;
		
		// if no existing item found
		if ($values != "")
		{
			// loop array
			while (isset($valueArray[$count]))
			{
				$count_inner = 0;
				$added = false;
				
				while ($added == false)
				{	
					// add new item
					if (!isset($_SESSION["wgf_item_".$count_inner]))
					{
						$_SESSION["wgf_item_".$count_inner] = $valueArray[$count];
						$_SESSION["wgf_item_quantity_".$count_inner] = $valueArray[$count+1];
						
						try
						{
							$query = "SELECT p.title as title, pp.specification as spec FROM product_price as pp, product as p WHERE pp.id=? AND pp.pro_id = p.id";
											
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
?>
<script>
	alert("<?php echo DecodeHTMLEntities($row["title"]); ?> (<?php echo DecodeHTMLEntities($row["spec"]); ?>) - <?php echo $_SESSION["wgf_item_quantity_".$count_inner]; ?> Item/Items");
</script>
<?php								
								}
							}	
						}
						catch (Exception $e) 
						{
							ErrorLog($e);
						}	
						$added = true;
					}
				
					$count_inner++;
				}
				$count = $count + 2;
			}
		}
	}
?>
<script>
	parent.document.getElementById("ifNoOfItems").src = parent.document.getElementById("ifNoOfItems").src;
</script>
