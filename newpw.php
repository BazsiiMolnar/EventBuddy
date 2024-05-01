<?php

require "config.php";
session_start();

$email_error = null;
$newpw_success = null;

if(isset($_POST['newpw-btn'])){
    $email = $_POST['email'];
    $newpassword = $_POST['newpassword'];

    $lekerdezes = "SELECT * FROM users WHERE email='$email'";
    $talalt_email = $conn->query($lekerdezes);

    if ($talalt_email->num_rows == 1) {
        $lekerdezes = "UPDATE users SET password = '$newpassword' WHERE email='$email'";
        if ($conn->query($lekerdezes) === TRUE) {
            $newpw_success = "Sikeres jelszó változtatás!";
        } 
	} else {
		$email_error = "Nincs ilyen e-mail cím regisztrálva!";
	}
}

?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
<link rel="stylesheet" href="css/style.css">
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<title>EventBuddy</title>
<link rel="icon" type="image/x-icon" href="img/titlelogo.png">
</head>

<body>
	<div class="site-introduction">
		<div class="logo">
			<img src="img/logo2.png" alt="Your Logo">
		</div>
		<div class="description">
			<h2>EventBuddy</h2>
			<p><h6>Oldalunk által egyszerű és hatékony módon egyeztetheted az időpontokat ismerőseiddel.</h6></p> 
			<p><h6>Így könnyen megtalálhatjátok a legmegfelelőbb időpontot, ami mindenki számára ideális.</h6></p>
		</div>
	</div>

	<div class="container" style="margin-top: -45px; height: 560px;">
		<div class="form-newpw">
			<form method="post" action="newpw.php">
				<h1 class="szoveg">Új jelszó</h1>
				<div class="inputok">
                    <input type="email" name="email" placeholder="Regisztrált e-mail-cím" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Regisztrált e-mail-cím'" required="required" autocomplete="off">
					<i class='bx bxl-gmail'></i>
				</div>
				<div class="inputok">
					<input type="password" name="newpassword" placeholder="Új jelszó" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Új jelszó'" required="required">
					<i class='bx bxs-lock' ></i>
				</div>
				<p style="text-align:left;"><a href="reglog.php" onclick="pwform()">Vissza a bejelentkezéshez</a></p>
				<input type="submit" name="newpw-btn" value="Megerősítés" style="color: white">
			</form>
		</div>
	</div>
			
<script src="js/script.js"></script>

</body>
</html>

<?php if($email_error !== null){?>
	<script>
		swal("", "<?php echo $email_error?>", "error");
	</script>
<?php } ?>

<?php if($newpw_success !== null){?>
	<script>
		swal("", "<?php echo $newpw_success?>", "success");
	</script>
<?php } ?>