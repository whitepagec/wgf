<?php include("admin/common/function.php"); ?>
<?php session_start(); ?>
<!DOCTYPE html>
<html>
<!-- ****************************************** HEADER ******************************************* -->
<?php include("header.php"); ?>
<!-- ****************************************** HEADER END *************************************** -->
    <body>
    
<!-- ****************************************** NAVIGATION *************************************** -->
<?php include("nav_checkout.php"); ?>
<!-- ****************************************** NAVIGATION END *********************************** -->

<!-- ****************************************** HOME ********************************************* -->
<?php include("content_products_home.php"); ?>
<!-- ****************************************** HOME END ***************************************** -->

<!-- ****************************************** CHECKOUT ***************************************** -->
<?php include("content_checkout_main.php"); ?>
<!-- ****************************************** CHECKOUT END ************************************* -->

<!-- ****************************************** CONTACT ****************************************** -->
<?php include("content_contact.php"); ?>
<!-- ****************************************** CONTACT END ************************************** -->

<!-- ****************************************** INCLUDE PLUGINS OR INDIVIDUAL FILES ************** -->
<?php include("includes.php"); ?>
<!-- ****************************************** INCLUDE PLUGINS OR INDIVIDUAL FILES END ********** -->
  </body>
</html>