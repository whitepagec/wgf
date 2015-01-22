<?php include("admin/common/function.php"); ?>
<?php
	session_start();
	
	if (isset($_REQUEST["value"]))
	{
		$value = $_REQUEST["value"];
		$count = $value;
		
		// loop existing item
		while (isset($_SESSION["wgf_item_".$count]))
		{	
			// update existing item record
			if (isset($_SESSION["wgf_item_".($count+1)]))
			{
				$_SESSION["wgf_item_".$count] = $_SESSION["wgf_item_".($count+1)];
				$_SESSION["wgf_item_quantity_".$count] = $_SESSION["wgf_item_quantity_".($count+1)];
			}
			else
			{
				$_SESSION["wgf_item_".$count] = NULL;
				$_SESSION["wgf_item_quantity_".$count] = NULL;
			}
			$count = $count + 1;
		}
	}
?>
<script>
	parent.document.getElementById("ifNoOfItems").src = parent.document.getElementById("ifNoOfItems").src;
	parent.document.getElementById("ifTotalPrice").src = parent.document.getElementById("ifTotalPrice").src;
</script>
