<nav class="navbar navbar-default navbar-fixed-top navbar-expanded" role="navigation">
  <!-- Brand and toggle get grouped for better mobile display -->
  <div class="container"><!--Main Container-->
  <div class="navbar-header">
    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
      <span class="sr-only">Toggle navigation</span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
    </button>
    <a class="navbar-brand" href="index.php#home"><img src="images/logo.png" title="WuGuFeng" align="WuGuFeng" border="0" class="img-responsive" /></a>
  </div>

  <!-- Collect the nav links, forms, and other content for toggling -->
  <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
    <ul id="mainmenu" class="nav navbar-nav navbar-right">
      <li><a href="javascript:void(0);" onClick="location.href='index.php#home';">HOME</a></li>
      <li><a href="javascript:void(0);" onClick="location.href='index.php#whoweare';">WHO&nbsp;WE&nbsp;ARE</a></li>
      <li class="dropdown">
        <a href="#products" class="dropdown-toggle" data-toggle="dropdown">PRODUCTS <b class="caret"></b></a>
        <ul class="dropdown-menu">
<?php
//WPC. Bake Mission products category

$conn = ConnSQL();
$stmt = $conn->stmt_init();
$status = "active";

try
{
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
          <li><a class="nobar" href="products.php?id=<?php echo DecodeHTMLEntities($row["id"]); ?>#products"><?php echo strtoupper(DecodeHTMLEntities($row["title"])); ?></a></li>
<?php
		}
		
	}
}
catch (Exception $e) 
{
	ErrorLog($e);
}

?>
        </ul>
      </li>
      <li><a href="#contact">CONTACT</a></li>
      <li><a href="javascript:void(0);" onClick="location.href='cart.php#cart';">CART [<iframe id="ifNoOfItems" name="ifNoOfItems" allowtransparency="true" frameborder="0" scrolling="no" src="noofitems.php" width="20px" height="15px"></iframe>]</a></li>
      <div id="mainmenubar"><img src="images/img_divider.png" border="0" alt="" title="" class="img-responsive" /></div>
    </ul>
  </div><!-- /.navbar-collapse -->
  </div><!-- Container End-->
</nav>
