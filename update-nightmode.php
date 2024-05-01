<?php
    require "config.php";
    session_start();

    if (!isset($_SESSION['id'])) {
        header("Location: reglog.php");
        exit();
    }

    $userid = isset($_POST['userid']) ? $_POST['userid'] : $_SESSION['id'];

    $get_mode_query = $conn->prepare("SELECT mode FROM users WHERE id = ?");
    $get_mode_query->bind_param("i", $userid);
    $get_mode_query->execute();
    $get_mode_result = $get_mode_query->get_result();
    $current_mode = $get_mode_result->fetch_assoc()['mode'];
    $get_mode_query->close();

    $new_mode = ($current_mode == 'light') ? 'night' : 'light';

    $update_query = $conn->prepare("UPDATE users SET mode = ? WHERE id = ?");
    $update_query->bind_param("si", $new_mode, $userid);

    if ($update_query->execute()) {
        echo "Mode updated to $new_mode successfully";
    } else {
        echo "Error updating mode: " . $conn->error;
    }

    $update_query->close();
    $conn->close();
?>