<?php include_once("php/check_if_logged.php"); ?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link rel="stylesheet" href="css/normalize.css">
        <link rel="stylesheet" href="css/foundation.css">
        <link rel="stylesheet" href="js/jquery-ui/css/ui-lightness/jquery-ui-1.10.3.custom.min.css" />
        <link rel="stylesheet" type="text/css" href="js/jtable/themes/metro/blue/jtable.min.css" />
        <script src="js/jquery-1.10.2.min.js"></script>
        <script src="js/jquery-ui/js/jquery-ui-1.10.3.custom.min.js"></script>
		<script src="js/vendor/modernizr.js"></script>
		<script src="js/prettydate.min.js"></script>
        <title>Gifter exchange</title>
    </head>
    <body>
       <div class="row">

	<?php
	if (isset($_SESSION['userInfo'])) {//show main table
		include ("mainPage.html");
		print "<script>var context = ".json_encode($_SESSION['context'])."</script>"; //print context for JS table
		print "<script>var userInfo = ".json_encode($_SESSION['userInfo'])."</script>"; //print context for JS table
		print "<script> var headerMessage = 'Welcome ".$_SESSION['userInfo']['firstName']."';</script>";
		print "<script> var server_path = '".$server_path."';</script>";
	} else { // show login page
		include ("login.php");
	}
	?>

    <!-- Footer -->

      <footer class="row">
        <div class="large-10 small-6 columns"><hr />
          <div class="row">

            <div class="large-5 small-3 columns">
              <p>&copy; Alex Louie 2013</p>
            </div>

            <div class="large-5 small-5 columns" id="footer-links">
              <ul class="inline-list right">
              	<li><p> Need help?</p></li>
                <li><a href="http://www.amazon.com">Amazon.com</a></li>
                <li><a href="http://www.shopping.yahoo.com">Yahoo Shopping</a></li>
                <li><a href="http://www.google.com/shopping">Google Shopping</a></li>
                <li><a href="http://www.consumerreports.org">Consumer Reports</a></li>
              </ul>
            </div>

          </div>
        </div>
      </footer>

    <!-- End Footer -->

    </div>
  </div>
  <!-- Check for Zepto support, load jQuery if necessary -->
<script>
  document.write('<script src=js/vendor/'
    + ('__proto__' in {} ? 'zepto' : 'jquery')
    + '.js><\/script>');
</script>

  <script src="js/jtable/jquery.jtable.js"></script>
  <script src="js/vendor/custom.modernizr.js"></script>
  <script src="js/foundation.min.js"></script>
  <script src="js/foundation/foundation.joyride.js"></script>
  <script src="js/foundation/foundation.cookie.js"></script>
  <script src="js/foundation/foundation.offcanvas.js"></script>

<script>
  //<!--initialize foundation library -->

	$(document).ready(function($) {
		$(document).foundation({
			offcanvas : {
				// Sets method in which offcanvas opens.
				// [ move | overlap_single | overlap ]
				open_method : 'move',
				// Should the menu close when a menu link is clicked?
				// [ true | false ]
				close_on_click : true
			}
		});
		$('#headerMessage').text('Welcome ' + userInfo.firstName);
	});

</script>
<?php //put things here that should run after login
	if (isset($_SESSION['userInfo'])) {//include logged-in js info
		include("giftTable.php");
		include("modals.html");
		?>
		
		<script src='js/menuLoad.js'></script>
		<script src='js/exchangeLoad.js'></script>
		<script src='js/tour.js'></script>
		
	
<?php }//end login items?>

    </body>
</html>