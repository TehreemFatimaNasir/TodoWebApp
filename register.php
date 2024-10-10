
<?php
session_start();
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = $connection->query($sql);

    if ($result->num_rows == 0) {
        $sql = "INSERT INTO users (email, password) VALUES ('$email', '$password')";
        $connection->query($sql);
        echo "Registration successful!";
        
        // Redirect to login page
        header("Location: login.php");
        exit;
    } else {
        echo "Email already exists!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
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
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
<label>Name:</label>
    <input type="text" name="name" required>
    <br>
    <label>Email:</label>
    <input type="email" name="email" required>
    <br>
    <label>Password:</label>
    <input type="password" name="password" required>
    <br>
   
    <button type="submit">Register</button>
</form>

</body>
</html>

