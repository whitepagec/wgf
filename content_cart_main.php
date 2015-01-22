<!-- cart main page -->
<div class="content_products_main" id="cart">
	<div class="container"><!--Container-->
    	<div class="row"><!--Row-->
			<div class="col-md-12" align="center">
              <table cellpadding="0" cellspacing="0" border="0">
                <tr>
                  <td height="40" align="center"><iframe id="ifUpdate" name="ifUpdate" allowtransparency="true" frameborder="0" src="" width="10px" height="10px"></iframe></td>
                </tr>
                <tr>
                  <td height="35" valign="top" align="center" class="product_header">SHOPPING CART</td>
                </tr>
                <tr>
                  <td align="center"><img src="images/products/main/img_divider.png" border="0" title="" alt="" class="img-responsive" /></td>
                </tr>    
                <tr>
                  <td height="30"></td>
                </tr>
              </table>
            </div>
        </div>
<?php
// cart 

$conn = ConnSQL();
$stmt = $conn->stmt_init();
$status = "active";
$count = 0;
$total_item = 0;
$item_class = "col-md-4";
$total_price = 0;

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

// loop items
while (isset($_SESSION["wgf_item_".$total_item]))
{
	$total_item++;
}

if ($total_item == 1)
{
	$item_class = "col-md-12";
}
elseif ($total_item == 2)
{
	$item_class = "col-md-6";
}

// loop items
while (isset($_SESSION["wgf_item_".$count]))
{
	try
	{
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
				if ($count%3 == 0)
				{
?>
		<div class="row"><!--Row-->
<?php					
				}
?>
			<div id="divItem<?php echo $count; ?>" class="<?php echo $item_class; ?>" align="center"><!--col-->
              <table cellpadding="0" cellspacing="0" border="0">
                <tr>
                  <td align="center"><img id="divImage<?php echo $count; ?>" name="divImage<?php echo $count; ?>" src="admin/product/image/<?php echo DecodeHTMLEntities($row["image"]); ?>" alt="<?php echo DecodeHTMLEntities($row["title"]); ?>" title="<?php echo DecodeHTMLEntities($row_inner["title"]); ?>" class="img-circle img-border-grey img-responsive" border="0"></td>
                </tr>
                <tr>
                  <td height="20"></td>
                </tr>
                <tr>
                  <td class="product_title" height="25" valign="top" align="center"><div id="divTitle<?php echo $count; ?>"><?php echo DecodeHTMLEntities($row["title"]); ?></div></td>
                </tr>
                <tr>
                  <td class="product_chinese_title" height="30" valign="top" align="center"><div id="divTitleChinese<?php echo $count; ?>"><?php echo DecodeHTMLEntities($row["title_chinese"]); ?></div></td>
                </tr>
                <tr>
                  <td align="center" height="60" valign="top">
                    <table cellpadding="0" cellspacing="0" border="0">
                      <tr>
                        <td><font color="#5a1300"><div id="divSpec<?php echo $count; ?>"><?php echo DecodeHTMLEntities($row["spec"]); ?></div></font></td>
                        <td width="15"></td>
                        <td><font color="#5a1300">Quantity</font></td>
                        <td width="15"></td>
                        <td><input id="tbQuantity<?php echo $count; ?>" name="tbQuantity<?php echo $count; ?>" style="width:30px; border:0px; padding:1px;" maxlength="5" class="textfield" value="<?php echo $_SESSION["wgf_item_quantity_".$count]; ?>" /></td>
                        <td width="15"></td>
                        <td><font color="#5a1300"><div id="divPrice<?php echo $count; ?>"><?php echo number_format(round(((float)DecodeHTMLEntities($row["price"]) * (($gst/100) + 1)),1),2); ?></div>&nbsp;SGD</font></td>
                        <td><font color="#5a1300"><div id="emQuantity<?php echo $count; ?>"></div></font><div id="hfPrice<?php echo $count; ?>" style="visibility:hidden;"><?php echo $row["id"]; ?></div></td>
                      </tr>
                    </table>
                  </td>
                </tr>
                <tr>
                  <td align="center" height="45" valign="top">
                    <table cellpadding="0" cellspacing="0" border="0">
                      <tr>
                        <td>SUB TOTAL</td>
                        <td width="10"></td>
                        <td style="font-size:33px;"><font color="#5a1300"><div id="divSubTotal<?php echo $count; ?>"><?php echo number_format(round(((float)DecodeHTMLEntities($row["price"]) * (($gst/100) + 1) * (float)$_SESSION["wgf_item_quantity_".$count]),1),2); ?></div></font></td>
                        <td width="10"></td>
                        <td style="font-size:33px;">SGD</td>
                      </tr>
                    </table>
                  </td>
                </tr>
                <tr>
                  <td align="center" height="60" valign="top"><input type="button" style="font-size:12px;" value="delete" onclick="if (confirm('Confirm delete item?')) { deleteItem(<?php echo $count; ?>); }" /></td>
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
	catch (Exception $e) 
	{
		ErrorLog($e);
	}
}
if ($count%3 != 0)
{
?>
		</div><!--Row End-->
<?php	
	$count++;				
}	

?>
		<div class="row"><!--Row-->
        	<div class="col-md-4" align="center"><a href="javascript:void(0);" onclick="updateCart();" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('btn_update','','images/cart/main/btn_update_over.jpg',1);"><img id="btn_update" name="btn_update" src="images/cart/main/btn_update.jpg" border="0" alt="Update" title="Update" /></a></div>
			<div class="col-md-4" align="center">
            <table cellpadding="0" cellspacing="0" border="0">
              <tr>
                <td width="15"></td>
                <td width="1px" style="background-color:#5a1300;"></td>
                <td>
                  <table cellpadding="0" cellspacing="0" border="0">
                    <tr>
                      <td height="1px" colspan="5" style="background-color:#5a1300;"></td>
                    </tr>
                    <tr>
                      <td width="20"></td>
                      <td align="right" height="47" valign="middle">TOTAL<br />PRICE</td>
                      <td style="font-size:33px;" height="47" valign="middle"><font color="#5a1300"><iframe id="ifTotalPrice" name="ifTotalPrice" allowtransparency="true" scrolling="no" frameborder="0" src="totalprice.php" width="180px" height="47px"></iframe></font></td>
                      <td style="font-size:33px;" height="47" valign="middle">SGD</td>
                      <td width="20"></td>
                    </tr>
                    <tr>
                      <td height="1px" colspan="5" style="background-color:#5a1300;"></td>
                    </tr>
                  </table>
                </td>
                <td width="1px" style="background-color:#5a1300;"></td>
                <td width="15"></td>
              </tr>
            </table>
			</div>
            <div class="col-md-4" align="center"><a href="javascript:void(0);" onclick="checkOut();" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('btn_checkout','','images/cart/main/btn_checkout_over.jpg',1);"><img id="btn_checkout" name="btn_checkout" src="images/cart/main/btn_checkout.jpg" border="0" alt="Check Out" title="Check Out" /></a></div>
        </div>
		<div class="row"><!--Row-->
			<div class="col-md-12" align="center">
            <table cellpadding="0" cellspacing="0" border="0">
              <tr>
                <td height="20" style="font-size:12px;">Price includes <?php echo $gst ?>% GST</td>
              </tr>
            </table>
			</div>
        </div>
        <div class="row"><!--Row-->
			<div class="col-md-12" align="center">
            <table cellpadding="0" cellspacing="0" border="0">
              <tr>
                <td height="40"></td>
              </tr>
            </table>
			</div>
        </div>
	</div><!--Container End--> 
</div>