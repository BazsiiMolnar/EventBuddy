<?php
    require "config.php";
    session_start();

    $format_error = null;
    $width_error = null;

    if (!isset($_SESSION['id'])) {
        header("Location: reglog.php");
        exit();
    }

    $userid = isset($_GET['userid']) ? $_GET['userid'] : $_SESSION['id'];
    if ($_SESSION['id'] != $userid) {
        header("Location: reglog.php");
        exit();
    }

    $lekerdezes = "SELECT * FROM users WHERE id='$userid'";
    $talalt_felhasznalo = $conn->query($lekerdezes);

    if ($talalt_felhasznalo) {
        $felhasznalo = $talalt_felhasznalo->fetch_assoc();
    } else {
        header("Location: reglog.php");
        exit();
    }

    if (isset($_FILES['new_img']['name'])) {
        $file_name = $_FILES['new_img']['name'];
        $tmp = $_FILES['new_img']['tmp_name'];
        $file_extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $allowed_extensions = array("jpg", "jpeg", "png", "gif");

        if (in_array($file_extension, $allowed_extensions)) {
            $unique_filename = uniqid() . '_' . $file_name;
            $destination = "felhasznalo/{$felhasznalo['username']}/$unique_filename";
            if (move_uploaded_file($tmp, getcwd() . "/$destination")) {
                $conn->query("UPDATE users SET prof_img='$unique_filename' WHERE id={$felhasznalo['id']}");
                header("Location: profile.php?userid=$userid");
                exit();
            } 
        } else {
            $format_error = "Hibás képformátum!";
        }
    }

    if (isset($_FILES['cover_img']['name'])) {
        $file_name2 = $_FILES['cover_img']['name'];
        $tmp2 = $_FILES['cover_img']['tmp_name'];
        $file_extension = strtolower(pathinfo($file_name2, PATHINFO_EXTENSION));
        $allowed_extensions = array("jpg", "jpeg", "png", "gif");

        if (in_array($file_extension, $allowed_extensions)) {
            $unique_filename2 = uniqid() . '_' . $file_name2;
            $destination = "felhasznalo/{$felhasznalo['username']}/$unique_filename2";
            if (move_uploaded_file($tmp2, getcwd() . "/$destination")) {
                $conn->query("UPDATE users SET cover_img='$unique_filename2' WHERE id={$felhasznalo['id']}");
                header("Location: profile.php?userid=$userid");
                exit();
            } 
        } else {
            $format_error = "Hibás képformátum!";
        }
    }

    require_once 'handle_friend_request.php';

    $nightModeClass = "";
    if ($felhasznalo['mode'] == "night") {
        $nightModeClass = "sotetmod";
    }

?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
<link rel="stylesheet" href="css/profile_style.css">
<link rel="stylesheet" href="css/all_style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.1/jquery-ui.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<title>EventBuddy</title>
<link rel="icon" type="image/x-icon" href="img/titlelogo.png">
</head>

<body class="<?php echo $nightModeClass; ?>">
    <div class="container">
        <header>
        <div class="kereso">
                <form method="GET" action="search.php">
                    <input type="text" id="search-input" placeholder="Ismerősök keresése..">
                    <div id="search-results"></div>
                </form>
            </div>

            <div class="ertesitesbtn">
                <?php
                    $lekerdezes="SELECT * FROM friend_requests WHERE receiver_id = $_SESSION[id] && statusz = 'pending'";
                    $talalt_ertesites = $conn->query($lekerdezes);
                    $ertesites = $talalt_ertesites->fetch_assoc();

                    $lekerdezes2="SELECT * FROM appointments WHERE receiver_id = $_SESSION[id] && statusz = 'pending'";
                    $talalt_ertesites2 = $conn->query($lekerdezes2);
                    $ertesites2 = $talalt_ertesites2->fetch_assoc();

                if($ertesites > 0 || $ertesites2 > 0){?> 
                    <button type="button" class="bell-button" onclick="toggleDropdown()">
                        <i class="fa-solid fa-bell fa-shake"></i>
                    </button>
                <?php } else { ?>
                    <button type="button" class="bell-button" onclick="toggleDropdown()">
                        <i class="fa-solid fa-bell"></i>
                    </button>
                <?php } ?>
            </div>

        <div id="ertesites-results">
            <?php
            $lekerdezes = $conn->query("SELECT * FROM friend_requests fr JOIN users u ON fr.sender_id = u.id WHERE fr.receiver_id = {$_SESSION['id']} AND fr.statusz = 'pending' ORDER BY fr.id DESC");
            if ($lekerdezes->num_rows > 0) {
                while ($row = $lekerdezes->fetch_assoc()) {
                    $senderUsername = $row['username'];
                    echo "<div class='ertesites'>
                                <a class='noti' href='talaltprofile.php?userid={$row['id']}'>
                                <img class='mini-image' src='felhasznalo/{$row['username']}/{$row['prof_img']}' alt='Profile Image'>
                                <div class='details'>
                                    <p class='request-message'><b>$senderUsername</b> bejelölt!</p>
                                    <form class='request-form' method='post' action='profile.php'>
                                    <input type='hidden' name='sender_id' value='{$row['sender_id']}'>
                                    <input class='accept-btn' type='submit' name='accept-btn' value='✔️'>
                                    <input class='reject-btn' type='submit' name='reject-btn' value='❌'>
                                </form>
                            </div>
                        </a>
                    </div>";
                }
            }
            ?>
            <?php
            $lekerdezes = $conn->query("SELECT * FROM appointments a JOIN users u ON a.sender_id = u.id WHERE a.receiver_id = {$_SESSION['id']} AND a.statusz = 'pending' ORDER BY a.id DESC");
            if ($lekerdezes->num_rows > 0) {
                while ($row = $lekerdezes->fetch_assoc()) {
                    $senderUsername = $row['username'];
                    echo "<div class='ertesites'>
                                <a class='noti' href='talaltprofile.php?userid={$row['id']}'>
                                <img class='mini-image' src='felhasznalo/{$row['username']}/{$row['prof_img']}' alt='Profile Image'>
                                <div class='details'>
                                    <p class='request-message'><b>$senderUsername</b><br>$row[date]<br>$row[topic]</p>
                                    <form class='request-form' method='post' action='profile.php'>
                                    <input type='hidden' name='sender_id2' value='{$row['sender_id']}'>
                                    <input class='accept2-btn' type='submit' name='accept-btn2' value='✔️'>
                                    <input class='reject2-btn' type='submit' name='reject-btn2' value='❌'>
                                </form>
                            </div>
                        </a>
                    </div>";
                }
            }
            ?>
        </div>
    </header>

        <div class="navdiv">
            <nav>
                <ul>
                    <li><a href="profile.php?userid=<?php echo $_SESSION['id']; ?>" class="logo">
                            <img src="img/logo2.png" alt="">
                            <span class="nav-item">EventBuddy</span>
                        </a></li>
                    <li><a href="profile.php?userid=<?php echo $_SESSION['id']; ?>">
                            <i class="fas fa-user"></i>
                            <span class="nav-item">Profilom</span>
                        </a></li>
                    <li><a href="calendar.php?userid=<?php echo $_SESSION['id']; ?>">
                            <i class="fas fa-calendar"></i>
                            <span class="nav-item">Naptáram</span>
                        </a></li>
                    <li><a href="friends.php?userid=<?php echo $_SESSION['id']; ?>">
                        <i class="fas fa-solid fa-user-group"></i>
                            <span class="nav-item">Ismerőseim</span>
                        </a></li>
                    <li><a href="#" id="nightmode" class="nightmode">
                        <span class="mode" onclick="toggleMode()"></span>
                        </a></li>
                    
                <?php if($felhasznalo['role'] == "admin"){?>
                    <li><a href="admin.php?userid=<?php echo $_SESSION['id']; ?>">
                        <i class="fas fa-solid fa-user-tie" style="color: red;"></i>
                        <span class="nav-item">Adminfelület</span>
                        </a></li>
                <?php } ?>

                    <li><a href="reglog.php" class="kijel">
                            <i class="fas fa-right-from-bracket" class="kijel"></i>
                            <span class="nav-item">Kijelentkezés</span>
                        </a></li>
                    </div>

        <div class="content">
	<h2 class="username"><?=$felhasznalo['username']?></h2>
            <div class="coverimg">
                <?php 
                        if($felhasznalo['cover_img'] == "blankcover.png"){
                            echo '<img src="img/blankcover.png" class="img-fluid">';
                        }
                        else{
                            echo '<img src="felhasznalo/'.$felhasznalo['username'].'/'.$felhasznalo['cover_img'].'" class="img-fluid" id="uploaded-image" draggable="true">';
                        }
                        ?>
                        <form method="post" id="coverimg" action="profile.php?userid=<?= $felhasznalo['id']; ?>" enctype="multipart/form-data">
                            <input type="file" name="cover_img" id="cover_img" style="cursor:pointer";>
                            <i class="fa-solid fa-camera" id="camicon"></i>
                        </form>
                    </div>

            <div class="pfpimg">
                <?php 
                    if($felhasznalo['prof_img'] == "blankpfp.png"){
                        echo '<img src="img/blankpfp.png" class="img-fluid">';
                    }
                    else{
                        echo '<img src="felhasznalo/'.$felhasznalo['username'].'/'.$felhasznalo['prof_img'].'" class="img-fluid">';
                    }
                    ?>
                        <form method="post" id="imgform" action="profile.php?userid=<?= $felhasznalo['id']; ?>" enctype="multipart/form-data">
                            <input type="file" name="new_img" id="new_img" style="cursor:pointer";>
                            <i class="fa-solid fa-camera" id="camicon"></i>
                        </form>
                    </div>

            </div>
        </div>
        <script src="js/profile_script.js"></script>
    </body>
</html>

<?php if($format_error !== null){?>
    <script>
        swal("", "<?php echo $format_error?>", "error");
    </script>
<?php } ?>

<?php if($width_error !== null){?>
    <script>
        swal("", "<?php echo $width_error?>", "error");
    </script>
<?php } ?>

<script>
    $(document).ready(function() {
        $('#nightmode').click(function(e) {
            e.preventDefault();
            $.ajax({
                url: 'update-nightmode.php',
                type: 'POST',
                data: { userid: <?php echo $userid; ?> },
                success: function(response) {
                    console.log(response);
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        });
    });

    const dark = `<i class="fas fa-solid fa-moon" style="color:orange;"></i><span class="nav-item" style="color: black;">Night Mode</span>`;
    const light = `<i class="fas fa-solid fa-sun" style="color:gold;"></i><span class="nav-item" style="color: white;">Light Mode</span>`;

    <?php if($felhasznalo['mode'] == "night"){?>
        document.querySelector('.mode').innerHTML = light;
    <?php } else { ?>
        document.querySelector('.mode').innerHTML = dark;
    <?php } ?>

    function toggleMode() {
        var modeElement = document.querySelector('.mode');
        var currentMode = modeElement.innerHTML.trim();

        if (currentMode === light.trim()) {
            modeElement.innerHTML = dark;
        }
        if (currentMode === dark.trim()) {
            modeElement.innerHTML = light;
        }
    }
</script>