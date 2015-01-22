<?php
if(!isset($_SESSION['status'])){
	$_SESSION['status'] = 'visited';
	
	
	$conn = ConnSQL();
	$stmt = $conn->stmt_init();
	
	try
	{
		$query2 = "SELECT youtube FROM featured WHERE status='active'";
		
		if(!$stmt->prepare($query2))
		{
			throw new Exception('SQL ERROR: Failed to prepare statement');
		}
		else
		{
			$stmt->execute();
			
			$result2 = $stmt->get_result();
			if ($row = $result2->fetch_assoc()) 
			{
				$youtube = DecodeHTMLEntities($row["youtube"]);
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
	<script>
	
	 // lock scroll position, but retain settings for later
      var scrollPosition = [
        self.pageXOffset || document.documentElement.scrollLeft || document.body.scrollLeft,
        self.pageYOffset || document.documentElement.scrollTop  || document.body.scrollTop
      ];
      var html = jQuery('html'); // it would make more sense to apply this to body, but IE7 won't have that
      html.data('scroll-position', scrollPosition);
      html.data('previous-overflow', html.css('overflow'));
      html.css('overflow', 'hidden');
      window.scrollTo(scrollPosition[0], scrollPosition[1]);
	
	function overlaybtn(){
		// un-lock scroll position
      var html = jQuery('html');
      var scrollPosition = html.data('scroll-position');
      html.css('overflow', html.data('previous-overflow'));
      window.scrollTo(scrollPosition[0], scrollPosition[1])
	  
		document.getElementById("overlay").style.display = "none";
		document.getElementById("ifVideo").src = "";
	}
	</script>
	<div id="overlay" onclick="overlaybtn();">
        <div class="flex-video">
        <iframe id="ifVideo" width="560" height="315" src="//www.youtube.com/embed/<?php echo $youtube; ?>" frameborder="0" allowfullscreen></iframe>
        </div>
	</div>
<?php
	}
?>
