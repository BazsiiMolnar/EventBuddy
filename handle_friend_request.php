<?php
    require "config.php";

    if (isset($_POST['accept-btn']) || isset($_POST['reject-btn'])) {
            $senderId = $_POST['sender_id'];

            if (isset($_POST['accept-btn'])) {
                $conn->query("UPDATE friend_requests SET statusz = 'accepted' WHERE sender_id = $senderId AND receiver_id = {$_SESSION['id']} AND statusz = 'pending'");

            } elseif (isset($_POST['reject-btn'])) {
                $conn->query("UPDATE friend_requests SET statusz = 'rejected' WHERE sender_id = $senderId AND receiver_id = {$_SESSION['id']} AND statusz = 'pending'");
            }
        }

    if (isset($_POST['accept-btn2']) || isset($_POST['reject-btn2'])) {
        $senderId2 = $_POST['sender_id2'];
    
        if (isset($_POST['accept-btn2'])) {
            $conn->query("UPDATE appointments SET statusz = 'accepted' WHERE sender_id = $senderId2 AND receiver_id = {$_SESSION['id']} AND statusz = 'pending'");
    
        } elseif (isset($_POST['reject-btn2'])) {
            $conn->query("UPDATE appointments SET statusz = 'rejected' WHERE sender_id = $senderId2 AND receiver_id = {$_SESSION['id']} AND statusz = 'pending'");
        }
        
    }
?>