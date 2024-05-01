<?php
require "config.php";

if (isset($_GET['search'])) {
    $searchQuery = '%' . $_GET['search'] . '%';

    $lekerdezes = "SELECT * FROM users WHERE username LIKE ? LIMIT 3";
    $statement = $conn->prepare($lekerdezes);
    $statement->bind_param("s", $searchQuery);
    $statement->execute();
    $result = $statement->get_result();
    $statement->close();

    while ($user = $result->fetch_assoc()) {
        $searchedUserName = $user['username'];
        echo "
            <div class='wrapper'>
                <a class='link' href='talaltprofile.php?userid={$user['id']}'>
                <img class='img' src='felhasznalo/{$user['username']}/{$user['prof_img']}' alt='Profile Image' class='profile-img'>
                <p style='margin-top: 10px;'><b>$searchedUserName</p></b>
                </a>
            </div>";
        }
    }
?>

<style>
    .wrapper{
        background-color: var(--keresoszin); 
        border-radius: 2rem; 
        margin-left: 265px; 
        width: 175px; 
        height: 75px;
    }

    .link{
        border-radius: 2rem;
        width: 175px; 
        text-align: center; 
        font-size: 16px; 
        height: 75px;
        border: 2px solid var(--keresoszin);
    }

    .img{
        margin-left: 10px; 
        width: 35px; 
        height: 35px; 
        border-radius: 50%; 
        float: left; 
        margin-right: -5px;
    }

    @media screen and (max-width: 768px) {
        .wrapper{
            margin-left: 65px;
        }
    }
    @media screen and (min-width: 769px) and (max-width: 992px) {
        .wrapper{
            margin-left: 160px;
        }
    }
    @media screen and (min-width: 993px) and (max-width: 1200px) {
        .wrapper{
            margin-left: 180px;
        }
    }
</style>