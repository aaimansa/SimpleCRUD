<?php

include 'functions.php';
$pdo = pdo_connect();
session_start();

if (empty($_SESSION['key']))
	$_SESSION['key'] = bin2hex(rand(100000, 999999));

$csrf = hash_hmac('sha256', 'this is some string: index.php', $_SESSION['key']);

if (isset($_POST['submit'])) {
	if (hash_equals($csrf, $_POST['csrf'])){
	    	if (!empty($_POST)) {
        		$name = $_POST['name'];
		        $email = $_POST['email'];
		        $phone = $_POST['phone'];
		        $title = $_POST['title'];
		        // Insert new record into the contacts table
		        $stmt = $pdo->prepare('DELETE FROM contacts WHERE id = ?');
		        $stmt->execute([$_GET['id']]);
		        header("location:index.php");
    		}
	} else {
		echo 'CSRF Token Failed!';
	}
}
?>