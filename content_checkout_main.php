<!-- checkout page -->
<form id="form" name="form" action="checkout.php" method="post">
<input type="hidden" id="action" name="action" value="checkout" />
<input type="hidden" id="final_price" name="final_price" value="" />
<div class="content_products_main" id="checkout">
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
$delivery_price = 0;
$final_price = 0;

$date = new DateTime(date('jS F Y'));
$date->add(new DateInterval('P3D'));

//get gst
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

// send email after confirmation
if (isset($_POST["action"]) && $_POST["action"] == "checkout")
{	
	// must change when domain is changed
	$domain = "http://www.wugufeng.com.sg";
	$final_price = EncodeHTMLEntities($_POST["final_price"],1);
	$payment_type = EncodeHTMLEntities($_POST["tbPaymentType"],1);
	$title = EncodeHTMLEntities($_POST["tbTitle"],1);
	$company = EncodeHTMLEntities($_POST["tbCompany"],1);
	$contact_person = EncodeHTMLEntities($_POST["tbContactPerson"],1);
	$email_address = EncodeHTMLEntities($_POST["tbEmailAddress"],1);
	$contact_number = EncodeHTMLEntities($_POST["tbContactNumber"],1);
	$country = EncodeHTMLEntities($_POST["tbCountry"],1);
	$delivery_mode = EncodeHTMLEntities($_POST["tbDeliveryMode"],1);
	$delivery_date = EncodeHTMLEntities($_POST["tbDeliveryDate"],1);
	$address = EncodeHTMLEntities($_POST["tbAddress"],1);
	
	$to = "mikki.yu@bakemission.com.sg,zhixuan.chen@bakemission.com.sg,shermaine.teo@bakemission.com.sg";

	//$to = "support@whitepagecreation.com,den@whitepagecreation.com,danny@whitepagecreation.com";
	$subject = "[WuGuFeng] Shopping Cart";
	
	$toptxt = $toptxt.
	"<table cellpadding=0 cellspacing=0 border=0 width=600 style='font-size:13px'><tr><td width=10 rowspan=4></td><td width=600 align=center><a href=".$domain."><img src=".$domain."/admin/images/logo.jpg width=300 height=190 border=0></a><br>No. 8 Senoko South Road #03-03 Singapore 758095 Tel: +65 6758 8955</td><td width=10 rowspan=4></td></tr>".
	"<tr><td height=10></td></tr>".
	"<tr><td height=1 width=580 bgcolor=#999999></td></tr>".
	"<tr><td height=10></td></tr></table>";
										
	$btmtxt = $btmtxt.
	"<table cellpadding=0 cellspacing=0 border=0 width=600 style='font-size:14px'><tr><td width=10 rowspan=7></td><td height=20></td></tr>".
	"<tr><td><b>Yours sincerely,</b></td></tr>".
	"<tr><td height=20></td></tr>".
	"<tr><td>WuGuFeng</td></tr>".
	"<tr><td><a href=mailto:ask@wugufeng.com.sg>ask@wugufeng.com.sg</a></td></tr>".
	"<tr><td><a href=http://www.wugufeng.com.sg>www.wugufeng.com.sg</a></td></tr>".
	"<tr><td height=20></td></tr></table>";
	
	$bodytxt = "<table cellpadding=0 cellspacing=0 border=0 width=600 style='font-size:14px'><tr><td height=10 colspan=3></tr><tr><td width=10></td><td width=580>".
	"<b>Title:&nbsp;".$title.
	"<br>Company:&nbsp;".$company.
	"<br>Contact Person:&nbsp;".$contact_person.
	"<br>Email Address:&nbsp;".$email_address.
	"<br>Contact Number:&nbsp;".$contact_number.
	"<br>Country:&nbsp;".$country.
	"<br>Delivery Mode:&nbsp;".$delivery_mode.
	"<br>Delivery Date:&nbsp;".$delivery_date.
	"<br>Address:&nbsp;".$address.
	"</td><td width=10></td></tr><tr><td height=10 colspan=3></tr></table>";
	
	$bodytxt2 = "<table cellpadding=0 cellspacing=0 border=0 width=600 style='font-size:14px'>".
	  "<tr>".
	    "<td width=10></td>".
		"<td height=10 colspan=3 align=left><b>Shopping Cart</b></td>".
		"<td width=10></td>".
	  "<tr>".
	  "<tr>".
  	    "<td height=10 colspan=5></td>".
	  "</tr>";
	
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
					$bodytxt2 = $bodytxt2.
					"<tr>".
					  "<td width=10></td>".
					  "<td width=100 valign='top'><img src='".$domain."/admin/product/image/".DecodeHTMLEntities($row["image"])."' alt='".DecodeHTMLEntities($row["title"])."' title='".DecodeHTMLEntities($row["title"])."' width='100' height='100' border='0'></td>".
					  "<td width='10'></td>".
					  "<td width=470>".
					    "<table cellpadding=0 cellspacing=0 border=0 width=470>".
						  "<tr>".
						    "<td width=150>Title:</td>".
							"<td width=10></td>".
							"<td width=310>".DecodeHTMLEntities($row["title"])."</td>".
						  "</tr>".
						  "<tr>".
						    "<td width=150>Specification:</td>".
							"<td width=10></td>".
							"<td width=310>".DecodeHTMLEntities($row["spec"])."</td>".
						  "</tr>".
						  "<tr>".
						    "<td width=150>Quantity:</td>".
							"<td width=10></td>".
							"<td width=310>".$_SESSION["wgf_item_quantity_".$count]."</td>".
						  "</tr>".
						  "<tr>".
						    "<td width=150>Price:</td>".
							"<td width=10></td>".
							"<td width=310>".number_format(round(((float)DecodeHTMLEntities($row["price"]) * (($gst/100) + 1)),1),2)." SGD</td>".
					  	  "</tr>".
						  "<tr>".
						    "<td width=150>Sub Total:</td>".
							"<td width=10></td>".
							"<td width=310>".number_format(round(((float)DecodeHTMLEntities($row["price"]) * (($gst/100) + 1) * (float)$_SESSION["wgf_item_quantity_".$count]),1),2)." SGD</td>".
						  "</tr>".
						"</table>".
					  "</td>".
					  "<td width=10></td>".
					"</tr>".
					"<tr>".
					  "<td height=10 colspan=5></td>".
					"</tr>";
					
					$total_price = number_format($total_price + number_format(round(((float)DecodeHTMLEntities($row["price"]) * (($gst/100) + 1) * (float)$_SESSION["wgf_item_quantity_".$count]),1),2),2);
					
					if ($total_price < 150 && $delivery_mode == "Delivery Required")
					{
						$delivery_price = number_format(32.10,2);
					}
					else
					{
						$delivery_price = number_format(0.00,2);
					}
					$final_price = number_format($total_price + $delivery_price,2);
				}
			}
			$_SESSION["wgf_item_".$count] = NULL;
			$_SESSION["wgf_item_quantity_".$count] = NULL;
			$count++;
		}
		catch (Exception $e) 
		{
			ErrorLog($e);
		}
	}
	$bodytxt2 = $bodytxt2."<tr><td height=10 colspan=5></td></tr></table>";
	
	$bodytxt3 = "<table cellpadding=0 cellspacing=0 border=0 width=600 style='font-size:14px'><tr><td height=10 colspan=3></tr><tr><td width=10></td><td width=580>".
	"<b>Total Price:&nbsp;".$total_price."&nbsp;SGD".
	"<br>Delivery Charge:&nbsp;".$delivery_price."&nbsp;SGD".
	"<br>Final Price:&nbsp;".$final_price."&nbsp;SGD".
	"</td><td width=10></td></tr><tr><td height=10 colspan=3></tr></table>";
	
	//Specify Email Message 
	$from = 'MIME-Version: 1.0' . "\r\n"; 
	$from .= 'Content-Type: text/html; charset=iso-8859-1'."\r\n";
	//To send HTML mail, the Content-type header must be set 
	$from .= 'FROM: WuGuFeng <'.$email_address.'>'."\r\n";

	if ($total_price != 0)
	{
		mail($to, $subject, $toptxt.$bodytxt.$bodytxt2.$bodytxt3.$btmtxt , $from);
	}
	
	if ($payment_type == "Bank Transfer")
	{
?>
<script>
alert("Thank you for shopping with us. You will receive an email with our Bank Transfer information.");
</script>
<?php			
	}
	else
	{
?>
<script>
alert("Thank you for shopping with us. Click 'Ok' to proceed payment via PayPal.");
window.open("https://www.paypal.com/cgi-bin/webscr?cancel_return=<?php echo $domain; ?>&cmd=_xclick&business=shermaine.teo@bakemission.com.sg&item_name=WuGuFeng - Shopping Cart&currency_code=SGD&amount=<?php echo $final_price; ?>","WuGuFeng","top=0,left=0,width=800,height=600,resizable=yes,scrollbars=yes,menubar=no,toolbar=no,location=no");
</script>
<?php
	}   
}
else
{
	$payment_type = "PayPal";
	$title = "Mr.";
	$company = "";
	$contact_person = "";
	$email_address = "";
	$contact_number = "";
	$country = "Singapore";
	$contact_person = "";
	$delivery_mode = "Delivery Required";
	$delivery_date = $date->format('Y-m-d');
	$address = "";
}

// get total number of item
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

$count = 0;
$total_price = 0;
$delivery_price = 0;
$final_price = 0;

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
			<div class="<?php echo $item_class; ?>" align="center"><!--col-->
              <table cellpadding="0" cellspacing="0" border="0">
                <tr>
                  <td align="center"><img src="admin/product/image/<?php echo DecodeHTMLEntities($row["image"]); ?>" alt="<?php echo DecodeHTMLEntities($row["title"]); ?>" title="<?php echo DecodeHTMLEntities($row["title"]); ?>" class="img-circle img-border-grey img-responsive" border="0"></td>
                </tr>
                <tr>
                  <td height="20"></td>
                </tr>
                <tr>
                  <td class="product_title" height="25" valign="top" align="center"><?php echo DecodeHTMLEntities($row["title"]); ?></td>
                </tr>
                <tr>
                  <td class="product_chinese_title" height="30" valign="top" align="center"><?php echo DecodeHTMLEntities($row["title_chinese"]); ?></td>
                </tr>
                <tr>
                  <td align="center" height="60" valign="top">
                    <table cellpadding="0" cellspacing="0" border="0">
                      <tr>
                        <td><font color="#5a1300"><?php echo DecodeHTMLEntities($row["spec"]); ?></font></td>
                        <td width="15"></td>
                        <td><font color="#5a1300">Quantity:&nbsp;<?php echo $_SESSION["wgf_item_quantity_".$count]; ?></font></td>
                        <td width="15"></td>
                        <td><font color="#5a1300"><?php echo number_format(round(((float)DecodeHTMLEntities($row["price"]) * (($gst/100) + 1)),1),2); ?>&nbsp;SGD</font></td>
                        <td><font color="#5a1300"></font></td>
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
                        <td style="font-size:33px;"><font color="#5a1300"><?php echo number_format(round(((float)DecodeHTMLEntities($row["price"]) * (($gst/100) + 1) * (float)$_SESSION["wgf_item_quantity_".$count]),1),2); ?></font></td>
                        <td width="10"></td>
                        <td style="font-size:33px;">SGD</td>
                      </tr>
                    </table>
                  </td>
                </tr>
                <tr>
                  <td align="center" height="40" valign="top"></td>
                </tr>
              </table>
            </div>
<?php
				$total_price = number_format($total_price + number_format(round(((float)DecodeHTMLEntities($row["price"]) * (($gst/100) + 1) * (float)$_SESSION["wgf_item_quantity_".$count]),1),2),2);
				
				if ($total_price < 150)
				{
					$delivery_price = number_format(32.10,2);
				}
				else
				{
					$delivery_price = number_format(0.00,2);
				}
				
				$final_price = number_format($total_price + $delivery_price,2);
?>
<script>
	document.getElementById("final_price").value = "<?php echo $final_price; ?>";
</script>
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
if ($final_price != 0.00)
{
?>
		<div class="row"><!--Row-->
			<div class="col-md-12" align="center">
            <table cellpadding="0" cellspacing="0" border="0" width="100%">
              <tr>
                <td colspan="3" width="100%" height="1" style="background-color:#5a1300;"></td>
              </tr>
              <tr>
                <td width="1px" style="background-color:#5a1300;"><img src="images/spacer.png" width="1" height="1" alt="" title="" /></td>
                <td align="center" width="100%">
                  <table cellpadding="0" cellspacing="0" border="0">
                    <tr>
                      <td height="10" colspan="7"></td>
                    </tr>
                    <tr>
                      <td width="50%"></td>
                      <td align="right" valign="middle">TOTAL<br />PRICE</td>
                      <td><img src="images/spacer.png" width="10px" height="1" alt="" title="" /></td>
                      <td style="font-size:33px;" valign="middle" align="right"><font color="#5a1300"><div id="divTotalPrice"><?php echo $total_price; ?></div></font></td>
                      <td><img src="images/spacer.png" width="10px" height="1" alt="" title="" /></td>
                      <td style="font-size:33px;" valign="middle">SGD</td>
                      <td width="50%"></td>
                    </tr>
                    <?php
					if ($delivery_price != 0)
					{
					?>
                    <tr>
                      <td colspan="7" height="5"></td>
                    </tr>
                    <tr>
                      <td width="50%"></td>
                      <td align="right" valign="middle">DELIVERY<br />CHARGE</td>
                      <td><img src="images/spacer.png" width="10px" height="1" alt="" title="" /></td>
                      <td style="font-size:33px;" valign="middle" align="right"><font color="#5a1300"><div id="divDeliveryPrice"><?php echo $delivery_price; ?></div></font></td>
                      <td><img src="images/spacer.png" width="10px" height="1" alt="" title="" /></td>
                      <td style="font-size:33px;" valign="middle">SGD</td>
                      <td width="50%"></td>
                    </tr>
                    <?php
					}
					?>
                    <tr>
                      <td colspan="7" height="5"></td>
                    </tr>
                    <tr>
                      <td colspan="7" height="10" bgcolor="#b27f35"></td>
                    </tr>
                    <tr bgcolor="#b27f35">
                      <td width="50%"></td>
                      <td align="right" valign="middle" style="color:#FFFFFF">FINAL<br />PRICE</td>
                      <td><img src="images/spacer.png" width="10px" height="1" alt="" title="" /></td>
                      <td style="font-size:33px;" valign="middle" align="right"><font color="#FFFFFF"><div id="divFinalPrice"><?php echo $final_price; ?></div></font></td>
                      <td><img src="images/spacer.png" width="10px" height="1" alt="" title="" /></td>
                      <td style="font-size:33px; color:#FFFFFF;" valign="middle">SGD</td>
                      <td width="50%"></td>
                    </tr>
                    <tr>
                      <td colspan="7" height="10" bgcolor="#b27f35"></td>
                    </tr>
                  </table>
                </td>
                <td width="1px" style="background-color:#5a1300;"><img src="images/spacer.png" width="1" height="1" alt="" title="" /></td>
              </tr>
              <tr>
                <td colspan="3" width="100%" height="1" style="background-color:#5a1300;"></td>
              </tr>
            </table>
			</div>
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
                <td height="80" valign="bottom" align="center">We are currently not running any retail outlets. However, our pineapple shortcakes and lapis cakes can be found in Cheers Convenience Stores and Esso Service Stations. You may like to place orders with us directly via phone, website or email. We provide FREE delivery service for minimum orders of S$150 and above within Singapore. A delivery charge of S$32.10 per point is applicable otherwise.</td>
              </tr>
              <tr>
                <td height="30"></td>
              </tr>
            </table>
			</div>
        </div>
        <div class="row"><!--Row-->
			<div class="col-md-4" align="center">
            <table cellpadding="0" cellspacing="0" border="0" width="100%">
              <tr>
                <td><img src="images/cart/main/border_left.jpg" width="25" height="54" alt="" title="" /></td>
                <td width="100%">
                  <table cellpadding="0" cellspacing="0" border="0" width="100%" bgcolor="#FBF6F0">
                    <tr>
                	  <td colspan="3" height="1" width="100%" bgcolor="#d9d9d9"></td>
                    </tr>
                    <tr>
                      <td valign="middle"><b>Payment&nbsp;Type</b></td>
                      <td><img src="images/spacer.png" width="10" height="1" alt="" title="" /></td>
                	  <td width="100%" height="52" valign="middle"><select id="tbPaymentType" name="tbPaymentType" style="width:100%; background-color:#FBF6F0; border:none;">
                      <option <?php if ($payment_type == "Paypal") { ?> selected="selected" <?php } ?> value="Paypal">Paypal</option>
                      <option <?php if ($payment_type == "Bank Transfer") { ?> selected="selected" <?php } ?> value="Bank Transfer">Bank Transfer</option>
                      </select></td>
              		</tr>
                    <tr>
                	  <td colspan="3" height="1" width="100%" bgcolor="#d9d9d9"></td>
                    </tr>
                  </table>
                </td>
                <td><img src="images/cart/main/border_right.jpg" width="25" height="54" alt="" title="" /></td>
              </tr>
              <tr>
                <td height="20" colspan="3"></td>
              </tr>
            </table>
            </div>
            <div class="col-md-2" align="center">
            <table cellpadding="0" cellspacing="0" border="0" width="100%">
              <tr>
                <td><img src="images/cart/main/border_left.jpg" width="25" height="54" alt="" title="" /></td>
                <td width="100%">
                  <table cellpadding="0" cellspacing="0" border="0" width="100%" bgcolor="#FBF6F0">
                    <tr>
                	  <td colspan="3" height="1" width="100%" bgcolor="#d9d9d9"></td>
                    </tr>
                    <tr>
                      <td valign="middle"><b>Title</b></td>
                      <td><img src="images/spacer.png" width="10" height="1" alt="" title="" /></td>
                	  <td width="100%" height="52" valign="middle"><select id="tbTitle" name="tbTitle" style="width:100%; background-color:#FBF6F0; border:none;">
                      <option <?php if ($title == "Mr.") { ?> selected="selected" <?php } ?> value="Mr.">Mr.</option>
                      <option <?php if ($title == "Ms.") { ?> selected="selected" <?php } ?> value="Ms.">Ms.</option>
                      <option <?php if ($title == "Mrs.") { ?> selected="selected" <?php } ?> value="Mrs.">Mrs.</option>
                      <option <?php if ($title == "Mdm.") { ?> selected="selected" <?php } ?> value="Mdm.">Mdm.</option>
                      <option <?php if ($title == "Prof.") { ?> selected="selected" <?php } ?> value="Prof.">Prof.</option>
                      <option <?php if ($title == "Dr.") { ?> selected="selected" <?php } ?> value="Dr.">Dr.</option>
                      </select></td>
              		</tr>
                    <tr>
                	  <td colspan="3" height="1" width="100%" bgcolor="#d9d9d9"></td>
                    </tr>
                  </table>
                </td>
                <td><img src="images/cart/main/border_right.jpg" width="25" height="54" alt="" title="" /></td>
              </tr>
              <tr>
                <td height="20" colspan="3"></td>
              </tr>
            </table>
            </div>
            <div class="col-md-6" align="center">
            <table cellpadding="0" cellspacing="0" border="0" width="100%">
              <tr>
                <td><img src="images/cart/main/border_left.jpg" width="25" height="54" alt="" title="" /></td>
                <td width="100%">
                  <table cellpadding="0" cellspacing="0" border="0" width="100%" bgcolor="#FBF6F0">
                    <tr>
                	  <td colspan="3" height="1" width="100%" bgcolor="#d9d9d9"></td>
                    </tr>
                    <tr>
                      <td valign="middle"><b>Company</b></td>
                      <td><img src="images/spacer.png" width="10" height="1" alt="" title="" /></td>
                	  <td width="100%" height="52" valign="middle"><input type="text" id="tbCompany" name="tbCompany" style="padding-top:1px; width:100%; background-color:#FBF6F0; border:none;" value="<?php echo $company; ?>" /></td>
              		</tr>
                    <tr>
                	  <td colspan="3" height="1" width="100%" bgcolor="#d9d9d9"></td>
                    </tr>
                  </table>
                </td>
                <td><img src="images/cart/main/border_right.jpg" width="25" height="54" alt="" title="" /></td>
              </tr>
              <tr>
                <td height="20" colspan="3"></td>
              </tr>
            </table>
            </div>
        </div>
		<div class="row"><!--Row-->
            <div class="col-md-6" align="center">
            <table cellpadding="0" cellspacing="0" border="0" width="100%">
              <tr>
                <td><img src="images/cart/main/border_left.jpg" width="25" height="54" alt="" title="" /></td>
                <td width="100%">
                  <table cellpadding="0" cellspacing="0" border="0" width="100%" bgcolor="#FBF6F0">
                    <tr>
                	  <td colspan="3" height="1" width="100%" bgcolor="#d9d9d9"></td>
                    </tr>
                    <tr>
                      <td valign="middle"><b>Contact&nbsp;Person</b></td>
                      <td><img src="images/spacer.png" width="10" height="1" alt="" title="" /></td>
                	  <td width="100%" height="52" valign="middle"><input type="text" id="tbContactPerson" name="tbContactPerson" style="padding-top:1px; width:100%; background-color:#FBF6F0; border:none;" value="<?php echo $contact_person; ?>" /></td>
              		</tr>
                    <tr>
                	  <td colspan="3" height="1" width="100%" bgcolor="#d9d9d9"></td>
                    </tr>
                  </table>
                </td>
                <td><img src="images/cart/main/border_right.jpg" width="25" height="54" alt="" title="" /></td>
              </tr>
              <tr>
                <td height="20" colspan="3"></td>
              </tr>
            </table>
            </div>
            <div class="col-md-6" align="center">
            <table cellpadding="0" cellspacing="0" border="0" width="100%">
              <tr>
                <td><img src="images/cart/main/border_left.jpg" width="25" height="54" alt="" title="" /></td>
                <td width="100%">
                  <table cellpadding="0" cellspacing="0" border="0" width="100%" bgcolor="#FBF6F0">
                    <tr>
                	  <td colspan="3" height="1" width="100%" bgcolor="#d9d9d9"></td>
                    </tr>
                    <tr>
                      <td valign="middle"><b>Email&nbsp;Address</b></td>
                      <td><img src="images/spacer.png" width="10" height="1" alt="" title="" /></td>
                	  <td width="100%" height="52" valign="middle"><input type="text" id="tbEmailAddress" name="tbEmailAddress" style="padding-top:1px; width:100%; background-color:#FBF6F0; border:none;" value="<?php echo $email_address; ?>" /></td>
              		</tr>
                    <tr>
                	  <td colspan="3" height="1" width="100%" bgcolor="#d9d9d9"></td>
                    </tr>
                  </table>
                </td>
                <td><img src="images/cart/main/border_right.jpg" width="25" height="54" alt="" title="" /></td>
              </tr>
              <tr>
                <td height="20" colspan="3"></td>
              </tr>
            </table>
            </div>
        </div>
        <div class="row"><!--Row-->
        	<div class="col-md-3" align="center">
            <table cellpadding="0" cellspacing="0" border="0" width="100%">
              <tr>
                <td><img src="images/cart/main/border_left.jpg" width="25" height="54" alt="" title="" /></td>
                <td width="100%">
                  <table cellpadding="0" cellspacing="0" border="0" width="100%" bgcolor="#FBF6F0">
                    <tr>
                	  <td colspan="3" height="1" width="100%" bgcolor="#d9d9d9"></td>
                    </tr>
                    <tr>
                      <td valign="middle"><b>Contact&nbsp;Number</b></td>
                      <td><img src="images/spacer.png" width="10" height="1" alt="" title="" /></td>
                	  <td width="100%" height="52" valign="middle"><input type="text" id="tbContactNumber" name="tbContactNumber" style="padding-top:1px; width:100%; background-color:#FBF6F0; border:none;" value="<?php echo $contact_number; ?>" /></td>
              		</tr>
                    <tr>
                	  <td colspan="3" height="1" width="100%" bgcolor="#d9d9d9"></td>
                    </tr>
                  </table>
                </td>
                <td><img src="images/cart/main/border_right.jpg" width="25" height="54" alt="" title="" /></td>
              </tr>
              <tr>
                <td height="20" colspan="3"></td>
              </tr>
            </table>
            </div>
            <div class="col-md-3" align="center">
            <table cellpadding="0" cellspacing="0" border="0" width="100%">
              <tr>
                <td><img src="images/cart/main/border_left.jpg" width="25" height="54" alt="" title="" /></td>
                <td width="100%">
                  <table cellpadding="0" cellspacing="0" border="0" width="100%" bgcolor="#FBF6F0">
                    <tr>
                	  <td colspan="3" height="1" width="100%" bgcolor="#d9d9d9"></td>
                    </tr>
                    <tr>
                      <td valign="middle"><b>Country</b></td>
                      <td><img src="images/spacer.png" width="10" height="1" alt="" title="" /></td>
                	  <td width="100%" height="52" valign="middle"><input type="text" id="tbCountry" name="tbCountry" style="padding-top:1px; width:100%; background-color:#FBF6F0; border:none;" value="<?php echo $country; ?>" /></td>
              		</tr>
                    <tr>
                	  <td colspan="3" height="1" width="100%" bgcolor="#d9d9d9"></td>
                    </tr>
                  </table>
                </td>
                <td><img src="images/cart/main/border_right.jpg" width="25" height="54" alt="" title="" /></td>
              </tr>
              <tr>
                <td height="20" colspan="3"></td>
              </tr>
            </table>
            </div>
            <div class="col-md-3" align="center">
            <table cellpadding="0" cellspacing="0" border="0" width="100%">
              <tr>
                <td><img src="images/cart/main/border_left.jpg" width="25" height="54" alt="" title="" /></td>
                <td width="100%">
                  <table cellpadding="0" cellspacing="0" border="0" width="100%" bgcolor="#FBF6F0">
                    <tr>
                	  <td colspan="3" height="1" width="100%" bgcolor="#d9d9d9"></td>
                    </tr>
                    <tr>
                      <td valign="middle"><b>Delivery&nbsp;Mode</b></td>
                      <td><img src="images/spacer.png" width="10" height="1" alt="" title="" /></td>
                	  <td width="100%" height="52" valign="middle"><select id="tbDeliveryMode" name="tbDeliveryMode" style="width:100%; background-color:#FBF6F0; border:none;" onchange="deliveryMode(this.value);">
                      <option <?php if ($delivery_mode == "Self Pick-up") { ?> selected="selected" <?php } ?> value="Self Pick-up">Self Pick-up</option>
                      <option <?php if ($delivery_mode == "Delivery Required") { ?> selected="selected" <?php } ?> value="Delivery Required">Delivery Required</option>
                      </select></td>
              		</tr>
                    <tr>
                	  <td colspan="3" height="1" width="100%" bgcolor="#d9d9d9"></td>
                    </tr>
                  </table>
                </td>
                <td><img src="images/cart/main/border_right.jpg" width="25" height="54" alt="" title="" /></td>
              </tr>
              <tr>
                <td height="20" colspan="3"></td>
              </tr>
            </table>
            </div>
            <div class="col-md-3" align="center">
            <table cellpadding="0" cellspacing="0" border="0" width="100%">
              <tr>
                <td><img src="images/cart/main/border_left.jpg" width="25" height="54" alt="" title="" /></td>
                <td width="100%">
                  <table cellpadding="0" cellspacing="0" border="0" width="100%" bgcolor="#FBF6F0">
                    <tr>
                	  <td colspan="3" height="1" width="100%" bgcolor="#d9d9d9"></td>
                    </tr>
                     <tr>
                      <td valign="middle"><b>Delivery&nbsp;Date</b></td>
                      <td><img src="images/spacer.png" width="10" height="1" alt="" title="" /></td>
                	  <td width="100%" height="52" valign="middle"><select id="tbDeliveryDate" name="tbDeliveryDate" style="width:100%; background-color:#FBF6F0; border:none;">
                      <?php
					  	$count = 0;
						while ($count < 30)
						{
					  ?>
                      <option <?php if ($delivery_date == $date->format('Y-m-d')) { ?> selected="selected" <?php } ?> value="<?php echo $date->format('Y-m-d'); ?>"><?php echo $date->format('Y-m-d'); ?></option>
                      <?php
					  		$date->add(new DateInterval('P1D'));
					  		$count++;
						}
					  ?>
                      </select></td>
              		</tr>
                    <tr>
                	  <td colspan="3" height="1" width="100%" bgcolor="#d9d9d9"></td>
                    </tr>
                  </table>
                </td>
                <td><img src="images/cart/main/border_right.jpg" width="25" height="54" alt="" title="" /></td>
              </tr>
              <tr>
                <td height="20" colspan="3"></td>
              </tr>
            </table>
            </div>
        </div>
        <div class="row"><!--Row-->
            <div class="col-md-12" align="center">
            <table cellpadding="0" cellspacing="0" border="0" width="100%">
              <tr>
                <td><img src="images/cart/main/border_left.jpg" width="25" height="54" alt="" title="" /></td>
                <td width="100%">
                  <table cellpadding="0" cellspacing="0" border="0" width="100%" bgcolor="#FBF6F0">
                    <tr>
                	  <td colspan="3" height="1" width="100%" bgcolor="#d9d9d9"></td>
                    </tr>
                    <tr>
                      <td valign="middle"><b>Address</b></td>
                      <td><img src="images/spacer.png" width="10" height="1" alt="" title="" /></td>
                	  <td width="100%" height="52" valign="middle"><input type="text" id="tbAddress" name="tbAddress" style="padding-top:1px; width:100%; background-color:#FBF6F0; border:none;" value="<?php echo $address; ?>" /></td>
              		</tr>
                    <tr>
                	  <td colspan="3" height="1" width="100%" bgcolor="#d9d9d9"></td>
                    </tr>
                  </table>
                </td>
                <td><img src="images/cart/main/border_right.jpg" width="25" height="54" alt="" title="" /></td>
              </tr>
              <tr>
                <td height="20" colspan="3"></td>
              </tr>
            </table>
            </div>
        </div>
        <div class="row"><!--Row-->
			<div class="col-md-12" align="center">
            <table cellpadding="0" cellspacing="0" border="0" width="100%">
              <tr onmouseover="this.style.backgroundColor='#b27f35';" onmouseout="this.style.backgroundColor='#470c09';" bgcolor="#470c09" style="cursor:pointer;" onclick="if (validFrm('Submit,Contact Person,V,Email Address,VE,Contact Number,V,Country,V,Address,V')){document.form.submit();}">
                <td width="50%"></td>
                <td style="font-size:33px; cursor:pointer;" valign="middle" onclick="if (validFrm('Submit,Contact Person,V,Email Address,VE,Contact Number,V,Country,V,Address,V')){document.form.submit();}" ><font color="#FFFFFF">SUBMIT</font></td>
                <td width="50%"></td>
              </tr>
              <tr>
                <td height="40" colspan="3"></td>
              </tr>
            </table>
			</div>
        </div>
<?php
}
else
{
?>
		<div class="row"><!--Row-->
			<div class="col-md-12" align="center">
            <table cellpadding="0" cellspacing="0" border="0">
              <tr>
                <td height="40" class="product_title">Thank you for shopping with us.</td>
              </tr>
              <tr>
                <td height="40"></td>
              </tr>
            </table>
			</div>
        </div>
<?php
}
?>
	</div><!--Container End--> 
</div>
</form>