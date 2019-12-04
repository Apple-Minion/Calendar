
<?php
    $username = $password = '';
    $errors = ['user' => '', 'pass' => '', 'login'=> '', 'signup' => ''];

    if(isset($_POST['login']) || isset($_POST['signup'])){
        $username = $_POST['user'];
        $password = $_POST['pass'];
        if(empty($username))
            $errors['user'] = "Username is required<br/>";
        else if(!preg_match('/^[a-zA-Z0-9]+$/', $username))
            $errors['user'] = "Username must be letters and numbers only<br/>";
        if(empty($password))
            $errors['pass'] = "Password is required<br/>";
        else if(!preg_match('/^[a-zA-Z0-9]+$/', $password))
            $errors['pass'] = "Password must be letters and numbers only<br/>";

        if(!array_filter($errors)){
            
            include('config\conn_cal.php');
            session_start();
            session_unset();
            $_SESSION['user'] = $username;
            $username = mysqli_real_escape_string($conn, $username);
            $password = mysqli_real_escape_string($conn, $password);

            if(isset($_POST['login'])){
                $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
                $result = mysqli_query($conn, $query);
                if(!$result)
                    echo "Query error: " . mysqli_error($conn);
                else{
                    $result = mysqli_fetch_all($result, MYSQLI_ASSOC);
                    if(count($result) == 0)
                        $errors['login'] = "Login Error: Invalid username or password";
                    else
                        header("Location: cals.php");
                }
                
            }
            else{
                $query = "SELECT * from users WHERE username = '$username'";
                $result = mysqli_query($conn, $query);
                if(!$result)
                    echo "Query error: " . mysqli_error($conn);
                else{
                    $result = mysqli_fetch_all($result, MYSQLI_ASSOC);
                    if(count($result) > 0)
                        $errors['signup'] = "Signup Error: Username taken";
                    else{
                        $query = "INSERT INTO users VALUES ('$username', '$password')";
                        if(mysqli_query($conn, $query))
                            header("Location: cals.php");
                        else
                            echo "Query error: " . mysqli_error($conn);
                    }
                }
            }
            mysqli_close($conn);
        }
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Calendar</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
        <style type="text/css">
            .brand{
                background: #800404 !important;
            }
            .brand-text{
                color: #800404 !important;
            }
            form{
                max-width: 460px;
                margin: 20px auto;
                padding: 20px;
            }
        </style>
    </head>
    <body class="grey lighten-4">
        <nav class="white"><a class="center brand-logo brand-text">Risi's Calendar App</a></nav>
        <section class="container grey-text">
            <h4 class="center">Account Information</h4>
            <form class="white" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST">
                <label>Username:</label>
                <input type="text" name="user" value="<?php echo htmlspecialchars($username) ?>">
                <div class="red-text"><?php echo $errors['user'] ?></div>
                <label>Password:</label>
                <input type="password" name="pass" value="<?php echo htmlspecialchars($password) ?>">
                <div class="red-text"><?php echo $errors['pass'] ?></div>
                <div class="center">
                    <input type="submit" name="login" value = "log in" class="btn brand">
                    <input type="submit" name="signup" value = "sign up" class="btn brand">
                </div>
                <div class="red-text"><?php echo $errors['login'] . $errors['signup'] ?></div>
            </form>
        </section>
    
    <?php include('templates\footer.php'); ?>

</html>