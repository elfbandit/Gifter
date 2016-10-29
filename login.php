<?php include("php/passwordReset.php"); ?>


<div class="row">
	<div class="large-12 columns">
		<h1>Welcome to Gifter! The family-friendly gift exchange site!</h1>

		<?php 
			//Put a banner at the top if there is a message to present
			if(isset($message)){
				print('<div class="warning callout">
  				<h5>'.$message.'</h5>
				</div>');
			}
			
			if(isset($_POST['create'])){ //if user is creating a new account, ask for the name to complete registration
		?>
		<h3>Almost there! Just need to know who you are!</h3>
		<form method="post" action="index.php?tour=1" >
			First Name :
			<input type="text" name="firstName" required placeholder="First"/>
			<br />
			Last Name :
			<input type="text" name="lastName" required placeholder="Last"/>
			<?php echo "<input type=\"hidden\" name=\"email\" value=\"" . $_POST['email'] . "\" />";
			echo "<input type=\"hidden\" name=\"password\" value=\"" . $_POST['password'] . "\" />";
			echo "<input type=\"submit\" name=\"create\" value=\"Create Account\" />";
			}
			
		elseif (isset($_GET['hash'])) { //user came in from a password reset link
			?>
			<h3>Please enter a new password:</h3>
			<br />
			<form method="post" action="index.php" >

				<p>
					Password :
					<input type="password" name="newpassword" required placeholder="Password"/>
					<input type="hidden" name="hash" value="<?php print $_GET['hash']; ?>" />
					<input type="submit" name="submit" value="Reset my password" />
				</p>
			</form>
			
			<?php
			}elseif (isset($_GET['reset'])) { //Clicked on password reset link
			?>
			<h3>Please enter your email address to reset your password:</h3>
			<br />
			<form method="post" action="index.php" >

				<p>
					Password :
					<input type="email" name="resetemail" required placeholder="myemail@somewhere.com"/>
					<input type="submit" name="reset" value="Send my password" />
				</p>
			</form>
			
			<?php
			}else{ //otherwise show the regular log in
			?>

			<h3>Either log in, or create an account:</h3>
			<br />
			<form method="post" action="index.php" >

				<p>
					email :
					<input type="email" name="email" required placeholder="Email"/>
					<br />
					Password :
					<input type="password" name="password" required placeholder="Password"/>
					<input type="submit" name="login" value="Log in" />
					<input type="submit" name="create" value="Create Account" />
				</p>
			</form>
			<br />
			<a href="index.php?reset=1">Forgot your password?</a>

			<?php } ?>
	</div>
</div>