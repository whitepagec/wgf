<!-- products main page -->
<div class="content_products_main" id="products_item">
	<div class="container"><!--Container-->
<?php
// products 

$conn = ConnSQL();
$stmt = $conn->stmt_init();
$status = "active";

try
{
	$count = 0;
	$category_title = "";
	$gst = 0;
	
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
			$category_title = strtoupper(DecodeHTMLEntities($row["title"]));
		}
	}
	
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
	
	$query = "SELECT * FROM product WHERE status=? AND cat_id=? ORDER BY position ASC";
					
	if(!$stmt->prepare($query))
	{
		throw new Exception('SQL ERROR: Failed to prepare statement');
	}
	else
	{
		$stmt->bind_param("ss", $status, $id);
		$stmt->execute();
						
		$result = $stmt->get_result();
		while ($row = $result->fetch_assoc()) 
		{
			$count_inner = 0;
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
				while ($row_inner = $result_inner->fetch_assoc()) 
				{
					if ($count_inner == 0)
					{
?>
		<div id="content<?php echo $count; ?>" style="display:none;" class="row"><!--Row-->
        	<div class="col-md-12" align="center">
              <table cellpadding="0" cellspacing="0" border="0">
                <tr>
                  <td height="40"></td>
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
                  <td align="center"><img id="img<?php echo $count; ?>" name="img<?php echo $count; ?>" src="admin/product/image/<?php echo DecodeHTMLEntities($row_inner["image"]); ?>" alt="<?php echo DecodeHTMLEntities($row_inner["title"]); ?>" title="<?php echo DecodeHTMLEntities($row_inner["title"]); ?>" class="img-circle img-border-grey img-responsive"></td>
                </tr>
                <tr>
                  <td height="10"></td>
                </tr>
                <tr>
                  <td align="center">
                    <table cellpadding="0" cellspacing="0" border="0">
                      <tr>
<?php					
					}
?>	
                        <td><img onclick="document.getElementById('img<?php echo $count; ?>').src=this.src;" style="cursor:pointer;" src="admin/product/image/<?php echo DecodeHTMLEntities($row_inner["image"]); ?>" alt="<?php echo DecodeHTMLEntities($row_inner["title"]); ?>" title="<?php echo DecodeHTMLEntities($row_inner["title"]); ?>" width="70" height="70" class="img-circle img-border-grey-small img-responsive"></td>
<?php
					
					$count_inner++;
				}
				if ($count_inner != 0)
				{
?>
					  </tr>
                    </table>
                  </td>
			 	</tr>
			    <tr>
                  <td height="15"></td>
                </tr>
<?php                
                    $count_price = 0;
                	$query = "SELECT * FROM product_price WHERE status=? AND pro_id=? ORDER BY position ASC";
                        
                	if(!$stmt->prepare($query))
                	{
                    	throw new Exception('SQL ERROR: Failed to prepare statement');
                	}
                	else
                	{
                    	$stmt->bind_param("ss", $status, $product_id);
                    	$stmt->execute();
                                    
                    	$result_price = $stmt->get_result();
                    	while ($row_price = $result_price->fetch_assoc()) 
                    	{
?>
				<tr>
                  <td align="center" height="40" valign="top">
                    <table cellpadding="0" cellspacing="0" border="0">
                      <tr>
                        <td><font color="#5a1300"><?php echo DecodeHTMLEntities($row_price["specification"]); ?></font></td>
                        <td width="20"></td>
                        <td><font color="#5a1300">Quantity</font></td>
                        <td width="15"></td>
                        <td><input id="tbQuantity<?php echo $count; ?>_<?php echo $count_price; ?>" name="tbQuantity<?php echo $count; ?>_<?php echo $count_price; ?>" style="width:40px; border:0px; padding:1px;" maxlength="10" class="textfield" value="0" /></td>
                        <td width="20"></td>
                        <td><font color="#5a1300"><?php echo number_format(round(((float)DecodeHTMLEntities($row_price["price"]) * (($gst/100) + 1)),1),2); ?>&nbsp;SGD</font></td>
                        <td><font color="#5a1300"><div id="emQuantity<?php echo $count; ?>_<?php echo $count_price; ?>"></div></font><div id="hfPrice<?php echo $count; ?>_<?php echo $count_price; ?>" style="visibility:hidden;"><?php echo $row_price["id"]; ?></div></td>
                      </tr>
                    </table>
                  </td>
                </tr>
<?php
							$count_price++;
						}
					}
?>						
                <tr>
                  <td align="center"><a href="javascript:void(0);" onclick="addToCart(<?php echo $count; ?>);" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('btn_addtocart<?php echo $count; ?>','','images/products/main/btn_addtocart_over.jpg',1);"><img id="btn_addtocart<?php echo $count; ?>" name="btn_addtocart<?php echo $count; ?>" src="images/products/main/btn_addtocart.jpg" border="0" alt="Add to Cart" title="Add to Cart" /></a></td>
                </tr>
                <tr>
                  <td align="center" height="30" valign="bottom">
                    <table cellpadding="0" cellspacing="0" border="0">
                      <tr>
                        <td><img src="images/products/main/btn_back.jpg" border="0" alt="View" title="View" /></td>
                        <td width="10"></td>
                        <td class="product_next" valign="bottom"><a href="cart.php">VIEW SHOPPING CART</a></td>
                      </tr>
                    </table>
                  </td>
                </tr>   
                <tr>
                  <td align="center" height="25" valign="bottom">
                    <table cellpadding="0" cellspacing="0" border="0">
                      <tr>
                        <td><img src="images/products/main/btn_back.jpg" border="0" alt="Back" title="Back" /></td>
                        <td width="10"></td>
                        <td valign="bottom"><div class="product_next" id="productsback<?php echo $count; ?>"><a href="#products">BACK TO <?php echo $category_title ?></a></div></td>
                      </tr>
                    </table>
                  </td>
                </tr>  
                 <tr>
                  <td height="20"></td>
                </tr>        
			  </table>
			</div>
		</div><!--Row End-->
<?php				
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