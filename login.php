
<?php 
session_start(); 
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['email']) && isset($_POST['password'])) {
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);

        $sql = "SELECT * FROM users WHERE email='$email'";
        $result = $connection->query($sql);

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                header('Location: index.php');
                exit; // Add exit after header
            } else {
                echo "<div class='alert alert-danger'>Incorrect password!</div>";
            }
        } else {
            echo "<div class='alert alert-danger'>Email not found!</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>Email and password fields are required.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="(link unavailable)">
    <style>
           body {
            font-family: 'Nunito', sans-serif;
            background-color: #1a1d23; /* Deep dark background */
        }
        
        form {
            width: 300px;
            margin: 50px auto;
            padding: 20px;
            background-color: #2c2f33; /* Dark form background */
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5); /* Deep shadow */
        }
        
        label {
            display: block;
            margin-bottom: 10px;
            color: #949494; /* Light gray label text */
        }
        
        input[type="text"], input[type="email"], input[type="password"] {
            width: 100%;
            height: 40px;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #444; /* Dark gray border */
            border-radius: 5px;
            background-color: #23262b; /* Dark input background */
            color: #fff; /* White input text */
        }
        
        input[type="text"]:focus, input[type="email"]:focus, input[type="password"]:focus {
            border: 1px solid #008000; /* Green border on focus */
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.5); /* Deep shadow on focus */
        }
        
        button[type="submit"] {
            background-color: #008000; /* Green submit button */
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        
        button[type="submit"]:hover {
            background-color: #006400; /* Darker green on hover */
        }
        
        ::placeholder {
            color: #666; /* Gray placeholder text */
        }
    </style>
</head>
<body>
    <div class="form-container">
        <div class="form-icon"><i class="fa fa-user"></i></div>
        <h3 class="title">Login</h3>
        <form method="POST" action="login.php">
            <div class="form-group">
                <label>Email</label>
                <input class="form-control" name="email" type="email" placeholder="Email Address" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input class="form-control" name="password" type="password" placeholder="Password" required>
            </div>
            <button type="submit" class="btn">Login</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
