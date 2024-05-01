<?php
require "config.php";
session_start();

$format_error = null;
$friendreqsent = null;
$friendreqwithdraw = null;
$friendRequestExists = null;
$deletedate = null;

if (!isset($_SESSION['id'])) {
    header("Location: reglog.php");
    exit;
}

$userid = isset($_GET['userid']) ? $_GET['userid'] : $_SESSION['id'];
$lekerdezes = "SELECT * FROM users WHERE id='$userid'";
$talalt_felhasznalo = $conn->query($lekerdezes);

if ($talalt_felhasznalo) {
    $felhasznalo = $talalt_felhasznalo->fetch_assoc();
} else {
    header("Location: reglog.php");
    exit();
}

$mode_lekerdezes = "SELECT * FROM users WHERE id='$_SESSION[id]'";
$talalt_mode = $conn->query($mode_lekerdezes);
$mode = $talalt_mode->fetch_assoc();

$nightModeClass = "";
if ($mode['mode'] == "night") {
    $nightModeClass = "sotetmod";
}

require_once 'handle_friend_request.php';

if (isset($_POST['friendreq-btn'])) {
    $friendRequestExists = isset($_SESSION['friend_request'][$userid]) ? $_SESSION['friend_request'][$userid] : false;
    $friendshipStatus = $conn->query("SELECT statusz FROM friend_requests WHERE (sender_id = {$_SESSION['id']} AND receiver_id = $userid) OR (sender_id = $userid AND receiver_id = {$_SESSION['id']})")->fetch_assoc();
        
    if ($friendshipStatus && $friendshipStatus['statusz'] === 'accepted') {
        
        $conn->query("DELETE FROM friend_requests WHERE (sender_id = {$_SESSION['id']} AND receiver_id = $userid) OR (sender_id = $userid AND receiver_id = {$_SESSION['id']})");
        $friendreqwithdraw = "Ismerős eltávolítva!";
        $_SESSION['friend_request'][$userid] = false;
        
    } elseif ($friendRequestExists) {
        
        $conn->query("DELETE FROM friend_requests WHERE sender_id = {$_SESSION['id']} AND receiver_id = $userid AND statusz = 'pending'");
        $friendreqwithdraw = "Jelölés visszavonva!";
        $_SESSION['friend_request'][$userid] = false;
                
    } else {
        
        $conn->query("INSERT INTO friend_requests (sender_id, receiver_id, statusz) VALUES ({$_SESSION['id']}, $userid, 'pending')");
        $friendreqsent = "Jelölés elküldve!";
        $_SESSION['friend_request'][$userid] = true;
    }
}

if(isset($_POST['sub-date-btn'])){
    $date = $_POST['date'];
    $topic = $_POST['topic'];
    $conn->query("INSERT INTO appointments VALUES(id, '$_SESSION[id]', '$userid', '$date', '$topic', 'pending')");
}

if(isset($_POST['del-row-btn'])) {
    $dateid = $_POST['dateid'];
    $conn->query("DELETE FROM appointments WHERE id = '$dateid'");
    $deletedate = "Időpont törölve!";
}
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="css/talaltprofile.css">
    <link rel="stylesheet" href="css/all_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" type="text/css" href="https://npmcdn.com/flatpickr/dist/themes/material_red.css">
    <script src="https://npmcdn.com/flatpickr/dist/flatpickr.min.js"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/hu.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
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
                    echo '<img src="felhasznalo/'.$felhasznalo['username'].'/'.$felhasznalo['cover_img'].'" class="img-fluid" id="uploaded-image">';
                }
                ?>
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
            </div>
        
        <form method="post" action="">
            <button type="submit" name="friendreq-btn" class="friendreq">
                <?php
                    $friendRequestExists = isset($_SESSION['friend_request'][$userid]) ? $_SESSION['friend_request'][$userid] : false;
                    $friendshipStatus = $conn->query("SELECT statusz FROM friend_requests WHERE (sender_id = {$_SESSION['id']} AND receiver_id = $userid) OR (sender_id = $userid AND receiver_id = {$_SESSION['id']})")->fetch_assoc();
            
                    if ($friendshipStatus && $friendshipStatus['statusz'] === 'accepted') {
                        echo '<i class="fa-solid fa-user-minus"></i> Eltávolítás';
                    } elseif (!$friendRequestExists) { 
                        echo '<i class="fa-solid fa-user-plus"></i> Jelölés';
                    } else {
                        echo '<i class="fa-solid fa-user-minus"></i> Visszavon';
                    }
                    ?>
            </button>
        </form>

            <?php
            if ($friendshipStatus && $friendshipStatus['statusz'] === 'accepted') {?>
                <form class="kozosidopontform" method="post">
                <h2 style="color: var(--betuszin);">Közös időpontok</h2>
                    <div class="header-row">
                        <span></span>
                    </div>
                    <div class="scroll-container">
                        <?php
                            $lekerdezes = "SELECT * FROM appointments WHERE (sender_id = '$_SESSION[id]' AND receiver_id = '$userid' AND statusz = 'accepted') OR (sender_id = '$userid' AND receiver_id = '$_SESSION[id]' AND statusz = 'accepted')";
                            $talalt_idopontok = $conn->query($lekerdezes);
                            echo "<ul>";
                            $count = 0; 
                            while($idopont = $talalt_idopontok->fetch_assoc()) {
                                echo "<li>" .
                                        "<span>" . $idopont['date'] . "</span><br>" .
                                        "<span>" . $idopont['topic'] . "</span>" .
                                        "<form method='post' style='display: inline; margin-left: 10px;'>" .
                                            "<input type='hidden' name='dateid' value='" . $idopont['id'] . "'>" .
                                            "<button type='submit' class='del-row-btn' name='del-row-btn' title='Időpont törlése'>X</button>" .
                                        "</form>" .
                                    "</li>";
                                $count++; 
                            }
                            for ($i = $count; $i < 3; $i++) {
                                echo "<li><span>Üres<br>időpont</span></li>";
                            }
                            echo "</ul>";
                        ?>
                    </form>
                </div>

                <form class="idopontform" method="post" action="">
                    <h2>Időpont küldése</h2>
                    <input type="text" id="flatpickr" class="flatpickr" name="date" placeholder="Dátum és időpont" required><br>
                    <input type="text" class="topic" name="topic" placeholder="Találkozás célja" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Találkozás célja'" maxlength="20" required>
                    <input type="submit" class="sub-date-btn" name="sub-date-btn" value="Küldés" title="Időpont küldése">
                <?php } ?>
            </form>
        </div> 
    </div>
              
<script src="js/talaltprofile_script.js"></script>
</body>
</html>

<?php if($friendreqsent !== null){?>
    <script>
        swal("", "<?php echo $friendreqsent?>", "success");
    </script>
<?php } ?>
    <?php if($friendreqwithdraw !== null){?>
        <script>
            swal("", "<?php echo $friendreqwithdraw?>", "error");
        </script>
<?php } ?>
    <?php if($deletedate !== null){?>
        <script>
            swal("", "<?php echo $deletedate?>", "success");
        </script>
	<?php } ?>

<script>
    $(document).ready(function() {
        $('#nightmode').click(function(e) {
            e.preventDefault(); 
            $.ajax({
                url: 'update-nightmode.php',
                type: 'POST',
                data: { userid: <?php echo $_SESSION['id']; ?> }, 
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

    <?php if($mode['mode'] == "night"){?>
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

    flatpickr(".flatpickr", {
        enableTime: true,
        dateFormat: "Y-m-d H:i",
        altInput: true,
        altFormat: "Y. F j. H:i",
        dateFormat: "Y-m-d",
        time_24hr: true,
        minDate: "today",
        locale: "hu",
        inline: true,
    });
    
</script>
