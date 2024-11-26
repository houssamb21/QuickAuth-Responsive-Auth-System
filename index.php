<?php
session_start();
require_once 'db.php'; 

if (isset($_SESSION['id'])) {
    header('Location: home.php');
    exit();
}

if (isset($_POST['login'])) {
    $email = strtolower(trim($_POST['email'])); 
    $password = trim($_POST['password']); 

    $query = "SELECT * FROM users WHERE email = ?";
    if ($stmt = $con->prepare($query)) {
        $stmt->bind_param('s', $email); 
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            if ($password == $user['password']) {
                $_SESSION['id'] = $user['id'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['first_name'] = $user['first_name'];
                $_SESSION['username'] = $user['username'];

                header('Location: home.php');
                exit();
            } else {
                $error = "Incorrect password.";
            }
        } else {
            $error = "User not found.";
        }

        $stmt->close();
    } else {
        $error = "Database query failed.";
    }

    $con->close();
}

if (isset($_POST['signup'])) {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (empty($first_name) || empty($last_name) || empty($email) || empty($password) || empty($confirm_password)) {
        $error = "All fields are required!";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match!";
    } else {
        $query = "SELECT * FROM users WHERE email = ?";
        if ($stmt = $con->prepare($query)) {
            $stmt->bind_param('s', $email);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                $error = "Email is already taken!";
            } else {
                $insert_query = "INSERT INTO users (first_name, last_name, email, password) VALUES (?, ?, ?, ?)";
                if ($stmt = $con->prepare($insert_query)) {
                    $stmt->bind_param('ssss', $first_name, $last_name, $email, $password);
                    if ($stmt->execute()) {
                        $_SESSION['email'] = $email;
                        $_SESSION['username'] = $first_name . " " . $last_name;
                        header('Location: home.php'); 
                        exit();
                    } else {
                        $error = "Error creating account. Please try again later.";
                    }
                }
            }

            $stmt->close();
        }
    }

    $con->close();
}
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
        echo file_get_contents("css/auth.css");
    ?>
    </style>
    <link rel="shortcut icon" href="img/logo.png" type="image/x-icon">
	<link href='https://fonts.googleapis.com/css?family=Cookie' rel='stylesheet' type='text/css'>
</head>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet" />
<script src="https://kit.fontawesome.com/64d58efce2.js"></script>
<body>
    <div class="container">
      <div class="forms-container">
        <div class="signin-signup">
          <form action="" method="POST" class="sign-in-form">
          <?php
           if (isset($error)) {
            echo "<p class='error'>$error</p>";
            }
          ?>
            <h2 class="title">Sign in</h2>
            <div class="input-field">
            <i class="fas fa-envelope"></i>
            <input type="email" name="email" placeholder="Email" id="email" required>
            </div>
            <div class="input-field">
              <i class="fas fa-lock"></i>
              <input type="password" name="password" id="password" placeholder="Password" required>
              </div>
            <a class="forgot" href="">Forgot Password?</a>
            <button type="submit" name="login" class="btn solid">Login</button>
            <p class="social-text">Or Sign in with social platforms</p>
            <div class="social-media">
              <a href="#" class="social-icon">
                <i class="fab fa-facebook-f"></i>
              </a>
              <a href="#" class="social-icon">
                <i class="fab fa-twitter"></i>
              </a>
              <a href="#" class="social-icon">
                <i class="fab fa-google"></i>
              </a>
              <a href="#" class="social-icon">
                <i class="fab fa-linkedin-in"></i>
              </a>
            </div>
          </form>
          <form action="" method="POST" class="sign-up-form">
            <h2 class="title">Sign up</h2>
            <?php
              if (isset($error)) {
            echo "<p class='error'>$error</p>";
                }
              ?>
            <div class="input-field">
                <i class="fas fa-user"></i>
                <input type="text" placeholder="First Name" name="first_name" id="first_name" required>
              </div>
            <div class="input-field">
              <i class="fas fa-user"></i>
              <input type="text" placeholder="Last Name"  name="last_name" id="last_name" required>
            </div>
            <div class="input-field">
              <i class="fas fa-envelope"></i>
              <input type="email" placeholder="Email" name="email" id="email" required>

            </div>
            <div class="input-field">
              <i class="fas fa-lock"></i>
              <input type="password" placeholder="Password"  name="password" id="password" required>

            </div>
            <div class="input-field">
                <i class="fas fa-lock"></i>
                <input type="password" placeholder="Confirm Password" name="confirm_password" id="confirm_password" required>

              </div>
            <button type="submit" class="btn" name="signup">Sign Up</button>
            <p class="social-text">Or Sign up with social platforms</p>
            <div class="social-media">
              <a href="#" class="social-icon">
                <i class="fab fa-facebook-f"></i>
              </a>
              <a href="#" class="social-icon">
                <i class="fab fa-twitter"></i>
              </a>
              <a href="#" class="social-icon">
                <i class="fab fa-google"></i>
              </a>
              <a href="#" class="social-icon">
                <i class="fab fa-linkedin-in"></i>
              </a>
            </div>
          </form>
        </div>
      </div>

      <div class="panels-container">
        <div class="panel left-panel">
          <div class="content">
          <h3>Welcome Back!</h3>
           <p>
               Don't have an account yet? Sign up today and enjoy all the benefits of our platform. It's quick and easy!
              </p>
            <button class="btn transparent" id="sign-up-btn">
              Sign up
            </button>
          </div>
        </div>
        <div class="panel right-panel">
          <div class="content">
          <h3>Already a Member?</h3>
            <p>
              Welcome back! Log in to continue where you left off and enjoy your personalized experience.
             </p>
            <button class="btn transparent" id="sign-in-btn">
              Sign in
            </button>
          </div>
          <img src="img/register.svg" class="image" alt="" />
        </div>
      </div>
    </div>
  </body>
<script src="js/script.js"></script>
