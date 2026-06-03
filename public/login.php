<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once dirname(__DIR__) . '/includes/db_connect.php';

$error = "";
$success = "";

/* LOGIN */
if(isset($_POST['login'])){
    $email = $_POST['email'];
    $password = $_POST['password'];

    $user = $usersCollection->findOne(['email'=>$email]);

    if($user && password_verify($password, $user['password'])){
        $_SESSION['user_id'] = (string)$user['_id'];
        $_SESSION['user_name'] = $user['name'];
        header("Location: home.php");
        exit();
    } else {
        $error = "Invalid Email or Password!";
    }
}

/* REGISTER */
if(isset($_POST['register'])){
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $existingUser = $usersCollection->findOne(['email'=>$email]);

    if($existingUser){
        $error = "Email already registered!";
    } else {
        $usersCollection->insertOne([
            'name' => $name,
            'email' => $email,
            'password' => $password
        ]);
        $success = "Registration Successful! Please login.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Bytebite - Login</title>

<style>
body{
    margin:0;
    font-family:Arial, sans-serif;
    display:flex;
    height:100vh;
}

/* LEFT SIDE */
.left{
    width:50%;
    background:#fc8019;
    color:white;
    display:flex;
    justify-content:center;
    align-items:center;
    flex-direction:column;
}

.left h1{
    font-size:48px;
    margin:0;
}

.left p{
    font-size:18px;
    margin-top:10px;
}

/* RIGHT SIDE */
.right{
    width:50%;
    display:flex;
    justify-content:center;
    align-items:center;
    background:#f5f5f5;
}

.form-box{
    width:360px;
    background:white;
    padding:30px;
    border-radius:10px;
    box-shadow:0 8px 25px rgba(0,0,0,0.08);
}

/* INPUT */
input{
    width:100%;
    padding:12px;
    margin:8px 0;
    border:1px solid #ccc;
    border-radius:5px;
    box-sizing:border-box;
}

/* LOGIN BUTTON */
button{
    width:100%;
    height:45px;
    border:none;
    background:#fc8019;
    color:white;
    font-weight:bold;
    cursor:pointer;
    margin-top:10px;
    border-radius:5px;
}

button:hover{
    background:#e67300;
}

/* OR */
.divider{
    text-align:center;
    margin:15px 0;
    font-size:13px;
    color:#777;
}

/* GOOGLE BUTTON */
.google-login{
    display:flex;
    align-items:center;
    justify-content:center;
    gap:10px;

    width:100%;
    height:45px;

    background:#ffffff;
    border:1px solid #dadce0;
    border-radius:5px;

    text-decoration:none;
    color:#444;
    font-weight:500;
    font-size:14px;

    box-sizing:border-box;
}

.google-login img{
    width:18px;
    height:18px;
}

.google-login:hover{
    background:#f5f5f5;
}

.toggle{
    margin-top:15px;
    text-align:center;
    cursor:pointer;
    color:#fc8019;
    font-size:14px;
}

.error{
    color:red;
    margin-bottom:10px;
}

.success{
    color:green;
    margin-bottom:10px;
}
</style>

<script>
function showRegister(){
    document.getElementById('loginForm').style.display='none';
    document.getElementById('registerForm').style.display='block';
}
function showLogin(){
    document.getElementById('registerForm').style.display='none';
    document.getElementById('loginForm').style.display='block';
}
</script>

</head>
<body>

<div class="left">
    <h1>Bytebite</h1>
    <p>Order food faster & smarter 🍔</p>
</div>

<div class="right">
<div class="form-box">

<?php if(!empty($error)) echo "<div class='error'>$error</div>"; ?>
<?php if(!empty($success)) echo "<div class='success'>$success</div>"; ?>

<!-- LOGIN FORM -->
<form method="POST" id="loginForm">
    <h2>Login</h2>

    <input type="email" name="email" placeholder="Enter Email" required>
    <input type="password" name="password" placeholder="Enter Password" required>

    <button type="submit" name="login">Login</button>

    <div class="divider">OR</div>

    <a href="/AI_Food_Order_System/auth/google-connect.php" class="google-login">
        <img src="/AI_Food_Order_System/assets/images/google.svg" alt="Google">
        Continue with Google
    </a>

    <div class="toggle" onclick="showRegister()">
        New User? Register Here
    </div>
</form>

<!-- REGISTER FORM -->
<form method="POST" id="registerForm" style="display:none;">
    <h2>Register</h2>
    <input type="text" name="name" placeholder="Enter Name" required>
    <input type="email" name="email" placeholder="Enter Email" required>
    <input type="password" name="password" placeholder="Enter Password" required>
    <button type="submit" name="register">Register</button>
    <div class="toggle" onclick="showLogin()">Already have account? Login</div>
</form>

</div>
</div>

</body>
</html>