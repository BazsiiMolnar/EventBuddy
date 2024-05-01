<?php

	require "config.php";
	session_start();

	$username_error = null;
	$password_error = null;
	$username_regerror = null;
	$email_regerror = null;
	$folder_error = null;

	if(isset($_POST['reg-btn'])){
		$usern = $_POST['username'];
		$pass = $_POST['password'];
		$email = $_POST['email'];
		$lekerdezes = "SELECT * FROM users WHERE email='$email'";
   		$talalt_email = $conn->query($lekerdezes);
    
    if(mysqli_num_rows($talalt_email) == 0){
        $lekerdezes = "SELECT * FROM users WHERE username='$usern'";
        $talalt_username = $conn->query($lekerdezes);
        
        if(mysqli_num_rows($talalt_username) == 0){
            $conn->query("INSERT INTO users VALUES (id, '$usern', '$pass', '$email', 'blankpfp.png', 'blankcover.png', 'light', 'member')");
            
    	    $curdir = getcwd();
            
            if(mkdir($curdir."/felhasznalo/".$usern, 0777)){
                copy($curdir."/img/blankpfp.png", $curdir."/felhasznalo/".$usern."/blankpfp.png");
                copy($curdir."/img/blankcover.png", $curdir."/felhasznalo/".$usern."/blankcover.png");
                
                $lekerdezes = "SELECT * FROM users WHERE username='$usern' AND password='$pass'";
                $talalt_sor = $conn->query($lekerdezes);
                $felhasznalo = $talalt_sor->fetch_assoc();
                $_SESSION['id'] = $felhasznalo['id'];
                $_SESSION['username'] = $felhasznalo['username'];
                header("Location: profile.php?userid=$felhasznalo[id]");
            } else {
                $folder_error = "Nem sikerült a mappát létrehozni!";
            }
        } else {
            $username_regerror = "Ezzel a névvel már regisztráltak!";
        }
    } else {
        $email_regerror = "Ezzel az email címmel már regisztráltak!";
    }
}

	if(isset($_POST['bej-btn'])){
		$usern = $_POST['username'];
		$pass = $_POST['password'];
		$lekerdezes = "SELECT * FROM users WHERE username='$usern'";
		$talalt_sor = $conn->query($lekerdezes);

		if(mysqli_num_rows($talalt_sor) == 1){
			$lekerdezes = "SELECT * FROM users WHERE username='$usern' AND password='$pass'";
			$talalt_sor = $conn->query($lekerdezes);

		if(mysqli_num_rows($talalt_sor) == 1){		
				$felhasznalo = $talalt_sor->fetch_assoc();
				$_SESSION['id'] = $felhasznalo['id'];
				$_SESSION['username'] = $felhasznalo['username'];
				header("Location: profile.php?userid=$felhasznalo[id]");	
			}
			else{
				$password_error = "Helytelen jelszó!";
			}
		}
		else{
			$username_error = "Nincs ilyen felhasználónév!";
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
		<div class="form-login">
			<form method="post" action="reglog.php">
				<h1 class="szoveg">Bejelentkezés</h1>
				<div class="inputok">
					<input for="" type="text" name="username" placeholder="Felhasználónév" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Felhasználónév'" required="required" autocomplete="off">
					<i class='bx bxs-user'></i>
				</div>
				<div class="inputok">
					<input type="password" name="password" placeholder="Jelszó" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Jelszó'" required="required">
					<i class='bx bxs-lock' ></i>
				</div>
				<p style="text-align:left;"><a href="#" onclick="pwform()">Elfelejtett jelszó</a></p>
				<input type="submit" name="bej-btn" value="Bejelentkezés" style="color: white">
			<p>Nincs fiókja? <a href="#" class="RegLink-btn">Regisztráció</a></p>
		</form>
	</div>

	<div class="form-register" style="margin-top:250px;">
		<form method="post" action="reglog.php">
		<h1 class="szoveg">Regisztráció</h1>
			<div class="inputok">
				<input type="email" name="email" placeholder="E-mail-cím" onfocus="this.placeholder = ''" onblur="this.placeholder = 'E-mail-cím'" required="required" autocomplete="off">
				<i class='bx bxl-gmail' ></i>
			</div>
			<div class="inputok">
				<input type="text" name="username" placeholder="Felhasználónév (max. 15 kar.)" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Felhasználónév (max. 15 kar.)'" required="required" autocomplete="off" maxlength="15">
				<i class='bx bxs-user'></i>
			</div>
			<div class="inputok">
				<input type="text" name="password" placeholder="Jelszó" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Jelszó'" required="required">
				<i class='bx bxs-lock' ></i>
			</div>
			<input class="regbtn" type="submit" value="Regisztrálás" name="reg-btn" style="color: white">
			<p>Van már fiókja? <a href="#" class="LoginLink-btn">Bejelentkezés</a></p>
		</form>
    </div>
</div>

<script src="js/script.js"></script>

</body>
</html>

<?php if($username_error !== null){?>
	<script>
		swal("", "<?php echo $username_error?>", "error");
	</script>
<?php } ?>

<?php if($password_error !== null){?>
	<script>
		swal("", "<?php echo $password_error?>", "error");
	</script>
<?php } ?>

<?php if($folder_error !== null){?>
	<script>
		swal("", "<?php echo $folder_error?>", "error");
	</script>
<?php } ?>

<?php if($username_regerror !== null){?>
	<script>
		swal("", "<?php echo $username_regerror?>", "error");
	</script>
<?php } ?>

<?php if($email_regerror !== null){?>
	<script>
		swal("", "<?php echo $email_regerror?>", "error");
	</script>
<?php } ?>

<style>
	.container.active .form-register {
		transform: translateY(-650px);
	}
</style>

