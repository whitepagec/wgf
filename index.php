<?php include("admin/common/function.php"); ?>
<?php session_start(); ?>
<!DOCTYPE html>
<html>
<!-- ****************************************** HEADER ******************************************* -->
<?php include("header.php"); ?>
<!-- ****************************************** HEADER END *************************************** -->
    <body onLoad="autoDisplay();">
<?php	
	if (!isset($_SESSION["wgf_index"]))
	{    
?>    
<!-- ****************************************** INTRO ******************************************** -->
<?php include("intro.php"); ?>
<!-- ****************************************** INTRO END **************************************** -->
<?php
		$_SESSION["wgf_index"] = "true";
	}
?>
<!-- ****************************************** NAVIGATION *************************************** -->
<?php include("nav_index.php"); ?>
<!-- ****************************************** NAVIGATION END *********************************** -->
  
<!-- ****************************************** OVERLAY ****************************************** -->
<?php include("overlay.php"); ?>
<!-- ****************************************** OVERLAY END ************************************** -->

<!-- ****************************************** HOME ********************************************* -->
<?php include("content_home.php"); ?>
<!-- ****************************************** HOME END ***************************************** -->

<!-- ****************************************** WHO WE ARE *************************************** -->
<?php include("content_whoweare.php"); ?>
<!-- ****************************************** WHO WE ARE END *********************************** -->

<!-- ****************************************** HIGHLIGHT **************************************** -->
<?php include("content_highlight.php"); ?>
<!-- ****************************************** HIGHLIGHT END ************************************ -->

<!-- ****************************************** FLOW ********************************************* -->
<?php include("content_flow.php"); ?>
<!-- ****************************************** FLOW END ***************************************** -->

<!-- ****************************************** PRODUCTS ***************************************** -->
<?php include("content_products.php"); ?>
<!-- ****************************************** PRODUCTS END ************************************* -->

<!-- ****************************************** PERANAKAN **************************************** -->
<?php include("content_peranakan.php"); ?>
<!-- ****************************************** PERANAKAN END ************************************ -->

<!-- ****************************************** CONTACT ****************************************** -->
<?php include("content_contact.php"); ?>
<!-- ****************************************** CONTACT END ************************************** -->

<!-- ****************************************** INCLUDE PLUGINS OR INDIVIDUAL FILES ************** -->
<?php include("includes.php"); ?>
<!-- ****************************************** INCLUDE PLUGINS OR INDIVIDUAL FILES END ********** -->
  </body>
</html>