<div class="row">
	<div class="large-12 columns">
		<h1>Welcome to Gifter! The family-friendly gift exchange site!</h1>

		<?php if(isset($_POST['create'])){ //if user is creating a new account, ask for the name to complete registration
		?>
		<h3>Almost there! Just need to know who you are!</h3>
		<form method="post" action="main.php?tour=1" >
			First Name :
			<input type="text" name="firstName" required placeholder="First"/>
			<br />
			Last Name :
			<input type="text" name="lastName" required placeholder="Last"/>
			<?php echo "<input type=\"hidden\" name=\"email\" value=\"" . $_POST['email'] . "\" />";
			echo "<input type=\"hidden\" name=\"password\" value=\"" . $_POST['password'] . "\" />";
			echo "<input type=\"submit\" name=\"create\" value=\"Create Account\" />";
			}

			else{ //otherwise show the regular log in
			?>

			<h3>Either log in, or create an account:</h3>
			<br />
			<form method="post" action="main.php" >

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
			<?php } ?>
	</div>
</div>