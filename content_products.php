<!-- products page -->
<script type="text/javascript">
$(document).ready(function() {
 
  $("#owl-demo").owlCarousel({
	  autoPlay: 10000, //Set AutoPlay to 10 seconds
	  items : 4,
	  itemsDesktop : [1199,3],
	  itemsDesktopSmall : [979,3]
  });
  
  $(".catnext").click(function(){
  	$("#owl-demo").trigger('owl.next');
  })
  
  $(".catprev").click(function(){
    $("#owl-demo").trigger('owl.prev');
  })
 
});
</script>
<div class="content_products" id="products">
    <div class="row"><!--Row-->
    	<div class="col-md-1 visible-md visible-lg"><!--col-->
            <a class="catnext"><i class="fa fa-chevron-left fa-3x"></i></a>
        </div><!--col End-->
        <div class="col-md-10"><!--col-->
            <div id="owl-demo">
<?php
//WPC. Bake Mission products category

$conn = ConnSQL();
$stmt = $conn->stmt_init();
$status = "active";

try
{
	//$query = "SELECT * FROM product_category WHERE status=? ORDER BY position ASC";
	$query = "SELECT c.*, s.counts FROM product_category AS c LEFT JOIN ( SELECT cat_id, COUNT(*) AS counts FROM product GROUP BY cat_id) AS s ON c.id = s.cat_id WHERE c.status = ? ORDER BY c.position ASC";
					
	if(!$stmt->prepare($query))
	{
		throw new Exception('SQL ERROR: Failed to prepare statement');
	}
	else
	{
		$stmt->bind_param("s", $status);
		$stmt->execute();
						
		$result = $stmt->get_result();
		while ($row = $result->fetch_assoc()) 
		{
			$count_products = DecodeHTMLEntities($row["counts"]);
			if(empty($count_products)){$count_products = '0';}
?>
			
			<div class="item">
              <table cellpadding="0" cellspacing="0" border="0">
                <tr>
                  <td><a href="products.php?id=<?php echo $row["id"]; ?>#products"><img src="admin/product/category/<?php echo DecodeHTMLEntities($row["image"]); ?>" alt="<?php echo DecodeHTMLEntities($row["title"]); ?>" class="img-circle img-border img-responsive" border="0"></a></td>
                </tr>
                <tr>
                  <td height="30"></td>
                </tr>
                <tr>
                  <td class="category_item_title"><a href="products.php?id=<?php echo $row["id"]; ?>#products"><?php echo DecodeHTMLEntities($row["title"]); ?></a></td>
                </tr>
                <tr>
                  <td height="10"></td>
                </tr>
                <tr>
                  <td height="1"><img src="images/products/img_divider.png" alt="" title="" border="0" class="img-responsive" /></td>
                </tr>
                <tr>
                  <td height="10"></td>
                </tr>
                <tr>
                  <td class="category_item_variants"><a href="products.php?id=<?php echo $row["id"]; ?>#products"><?php echo $count_products; ?> variants</a></td>
                </tr>
              </table>
            </div>
				  
<?php
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
            </div>
        </div><!--col End-->
        <div class="col-md-1 visible-md visible-lg"><!--col-->
            <a class="catprev"><i class="fa fa-chevron-right fa-3x"></i></a>
        </div><!--col End-->
    </div><!--Row End-->
</div>