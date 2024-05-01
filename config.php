<?php 

	$conn = new mysqli("localhost", "team03", "kfvvat4mVal6o2v", "team03");
	
	if($conn->connect_error){
		die("Connection failed! ".$conn->connect_error);
	}
	
?>