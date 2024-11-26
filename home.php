<?php
session_start();

if (!isset($_SESSION["email"])) {
    header("Location: index.php");
    exit();
}

$first_name = isset($_SESSION["first_name"]) ? $_SESSION["first_name"] : "Guest";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>
    <style>
        <?php
        echo file_get_contents("css/home.css");
        ?>
    </style>
    <link rel="shortcut icon" href="img/logo.png" type="image/x-icon">
	<link href='https://fonts.googleapis.com/css?family=Cookie' rel='stylesheet' type='text/css'>

</head>

<body>

<header class="header-fixed">

	<div class="header-limiter">

		<h1><a href="#">Company<span>logo</span></a></h1>

		<nav>
			<a href="#">Home</a>
			<a href="#" class="selected">Blog</a>
			<a href="#">Pricing</a>
			<a href="#">About</a>
			<a href="#">Faq</a>
			<a href="#">Contact</a>
		</nav>

	</div>

</header>

<div class="container">
        <h2>Hello, <?php echo htmlspecialchars($first_name); ?>!</h2>
        <p>This is the homepage you are redirected to after login. Feel free to explore the website.</p>
        <a href="logout.php" class="btn-logout">Logout</a>
    </div>





<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>





</body>

</html>
