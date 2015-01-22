<!-- products main page -->
<div class="content_products_main" id="products">
	<div class="container"><!--Container-->
<?php
// products 

$conn = ConnSQL();
$stmt = $conn->stmt_init();
$status = "active";
$count = 0;
$total_item = 0;
$item_class = "col-md-4";

try
{
	$query = "SELECT * FROM product_category WHERE status=? AND id=?";
					
	if(!$stmt->prepare($query))
	{
		throw new Exception('SQL ERROR: Failed to prepare statement');
	}
	else
	{
		$stmt->bind_param("ss", $status, $id);
		$stmt->execute();
		
		$result = $stmt->get_result();
		if ($row = $result->fetch_assoc()) 
		{
?>    
    	<div class="row"><!--Row-->
			<div class="col-md-12" align="center">
              <table cellpadding="0" cellspacing="0" border="0">
                <tr>
                  <td height="40" align="center"><iframe id="ifAddToCart" name="ifAddToCart" allowtransparency="true" frameborder="0" src="" width="10px" height="10px"></iframe></td>
                </tr>
                <tr>
                  <td height="35" valign="top" align="center" class="product_header"><?php echo strtoupper(DecodeHTMLEntities($row["title"])); ?></td>
                </tr>
                <tr>
                  <td align="center"><img src="images/products/main/img_divider.png" border="0" title="" alt="" class="img-responsive" /></td>
                </tr>
                <tr>
                  <td height="30" valign="bottom" align="center" class="product_chinese_header"><?php echo DecodeHTMLEntities($row["title_chinese"]); ?></td>
                </tr>
<?php
				if ($row["description"] != "")
				{
?>				
                <tr>
                  <td height="20"></td>
                </tr>
                <tr>
                  <td align="center"><?php echo DoSpace(DecodeHTMLEntities($row["description"])); ?></td>
                </tr>
<?php
				}
?>				             
                <tr>
                  <td height="20"></td>
                </tr>
                <tr>
                  <td align="center"><select id="tbPaymentType" name="tbPaymentType" onchange="location.href='products.php?id='+this.value+'#products';" style="width:200px; background-color:#FFFFFF; border:none;">
				  <?php	               
                  	$query = "SELECT * FROM product_category WHERE status=? ORDER BY position ASC";
					
					if(!$stmt->prepare($query))
					{
						throw new Exception('SQL ERROR: Failed to prepare statement');
					}
					else
					{
						$stmt->bind_param("s", $status);
						$stmt->execute();
						
						$result_inner = $stmt->get_result();
						while ($row_inner = $result_inner->fetch_assoc()) 
						{
				  ?>
                  <option <?php if ($id == $row_inner["id"]) {  ?> selected="selected" <?php } ?> value="<?php echo strtoupper(DecodeHTMLEntities($row_inner["id"])); ?>"><b><?php echo strtoupper(DecodeHTMLEntities($row_inner["title"])); ?></b></option>
                  <?php
				  		}
					}
				  ?>
                  </select></td>
                  <tr>
                  <td height="20"></td>
                </tr>
                </tr>                  
              </table>
            </div>
        </div>
<?php   		
		}
	}
	$query = "SELECT * FROM product WHERE status=? AND cat_id=? ORDER BY position ASC";
					
	if(!$stmt->prepare($query))
	{
		throw new Exception('SQL ERROR: Failed to prepare statement');
	}
	else
	{
		$count = 0;
		$stmt->bind_param("ss", $status, $id);
		$stmt->execute();
						
		$result = $stmt->get_result();
		while ($row = $result->fetch_assoc()) 
		{
			$product_id = $row["id"];
			$query = "SELECT * FROM product_image WHERE status=? AND pro_id=? ORDER BY position ASC";
					
			if(!$stmt->prepare($query))
			{
				throw new Exception('SQL ERROR: Failed to prepare statement');
			}
			else
			{
				$stmt->bind_param("ss", $status, $product_id);
				$stmt->execute();
								
				$result_inner = $stmt->get_result();
				if ($row_inner = $result_inner->fetch_assoc()) 
				{
					if ($count%3 == 0)
					{
?>
		<div class="row"><!--Row-->
<?php					
					}
?>
			<div class="col-md-4" align="center"><!--col-->
              <table cellpadding="0" cellspacing="0" border="0">
                <tr>
                  <td align="center"><img src="admin/product/image/<?php echo DecodeHTMLEntities($row_inner["image"]); ?>" alt="<?php echo DecodeHTMLEntities($row_inner["title"]); ?>" title="<?php echo DecodeHTMLEntities($row_inner["title"]); ?>" class="img-circle img-border-grey img-responsive" border="0"></td>
                </tr>
                <tr>
                  <td height="20"></td>
                </tr>
                <tr>
                  <td class="product_title" height="25" valign="top" align="center"><div id="productsmenu<?php echo $count; ?>"><a href="#products_item" onclick="displayContent('<?php echo $count; ?>');"><?php echo DecodeHTMLEntities($row["title"]); ?></a></div></td>
                </tr>
                <tr>
                  <td class="product_chinese_title" height="25" valign="top" align="center"><div id="productschinesemenu<?php echo $count; ?>"><a href="#products_item" onclick="displayContent('<?php echo $count; ?>');"><?php echo DecodeHTMLEntities($row["title_chinese"]); ?></a></div></td>
                </tr>
                <tr>
                  <td height="20"></td>
                </tr>
              </table>
            </div>
<?php
					if ($count%3 == 2)
					{
?>
		</div><!--Row End-->
<?php					
					}
					$count++;
				}
			}   
		}
		while ($count%3 != 0)
		{
?>
		</div><div class="col-md-4"></div><!--Row End-->
<?php	
			$count++;				
		}				
	}
}
catch (Exception $e) 
{
	ErrorLog($e);
}

$stmt->close();
$conn->close();
?>
		<div class="row"><!--Row-->
			<div class="col-md-12" align="center">
            <table cellpadding="0" cellspacing="0" border="0">
              <tr>
                <td height="20"></td>
              </tr>
            </table>
			</div>
        </div>
	</div><!--Container End--> 
</div>