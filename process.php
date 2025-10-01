<? php
session_start();
//initializing variables
$username="";
$errors=array();
//connect to database
$db=mysqli_connect('localhost','root','','cafe');
//register user
if (isset($_POST['reg_user'])){
//receive all input from the form
$username=mysqli_real_escape_string($db,$_POST['username']);
$password=mysqli_real_escape_string($db, $_POST['password']);
$confirm_password=mysqli_real_escape_string($db, $_POST['confirm_password']);

//form validation
if (empty($username)){array_push($errors, "Username is required");}
if (empty($password)){array_push($errors, "Password is required");}
if ($password!=$confirm_password){
array_push($errors, "the two passwords do not match");}

//check db to make sure user does not exist with same username
$user_check_query="select * from user where username='$username' limit 1";
$result=mysqli_query($db, $user_check_query);
$user=mysqli_fetch_assoc($result);
if ($user){ //user exists
if ($user['username']===$username){
array_push($errors, "Username already exists");
} 
}

//register user
if (count($errors)==0){
$password=md5($password);
$query="insert into user (username, password) values ('$username','$password')";
mysqli_query($db, $query);
$_SESSION['username']=$username;
$_SESSION['success']="you have signed up successfully";
header('locatio:index.php');
}
}

//Login user
if (isset($_POST['login_user'])){
$username=mysqli_real_escape_string($db, $_POST['username']);
$password=mysqli_real_escape_string($db, $_POST['password']);

if (empty($username)){
array_push($errors, "username is required");
}
if (empty($password)){
array_push($errors, "password is required");
}
if (count($errors)==0) {
$password=md5($password);
$query="select * from user where username='$username' and password='$password'";
$results=mysqli_query($db, $query);
if (mysqli_num_rows($results)==1) {
$_SESSION['username']=$username;
$_SESSION['success']="you are now logged in";
header('location:index.php');
} else {
array_push($errors, "Wrong username/password");
}
}
}
?>
