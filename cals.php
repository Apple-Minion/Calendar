<?php
    $username = $title = $error = '';

    session_start();
    if(!isset($_SESSION['user']))
        header("Location: index.php");

    include('config\conn_cal.php');
    $username = mysqli_real_escape_string($conn, $_SESSION['user']);

    if(isset($_POST['action'])){
        $action = $_POST['action'];
        if($action=='+'){
            $title = $_POST['title'];
            if(empty($title))
                $error = "Password is required<br/>";
            else if(!preg_match('/^[a-zA-Z0-9\s]+$/', $title))
                $error = "Title must be letters, spaces, and numbers only<br/>";
            else{
                $title = mysqli_real_escape_string($conn, $title);
                $query = "INSERT INTO calendars(title, username) VALUES ('$title', '$username')";
                if(!mysqli_query($conn, $query))
                    echo "Query error: " . mysqli_error($conn);
            }
        }
        else{
            $id = $_POST['id'];
            if(is_numeric($id)){
                if($action=='Delete'){
                    $query = "DELETE FROM calendars WHERE id = '$id'";
                    if(!mysqli_query($conn, $query))
                        echo "Query error: " . mysqli_error($conn);
                }
                else if($action=='Open'){
                    $_SESSION['id'] = $id;
                    header("Location: cal.php");
                }
            }            
        }
    }

    $query = "SELECT c.id, c.title, COUNT(DISTINCT e.id) as numEvents, COUNT(DISTINCT g.task) as numGoals
        FROM (calendars c LEFT JOIN events e ON c.id=e.cal_id) LEFT JOIN goals g ON c.id=g.cal_id WHERE c.username='$username' GROUP BY c.id";
    $result = mysqli_query($conn, $query);
    if(!$result)
        echo "Query error: " . mysqli_error($conn);
    else{
        $cals = mysqli_fetch_all($result, MYSQLI_ASSOC);
        mysqli_free_result($result);
    }
    mysqli_close($conn);
?>

<!DOCTYPE html>
<html>

    <?php include('templates\header.php'); ?>

    <h4 class="center grey-text">Add a Calendar:</h4>
    <form class="white" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST">
        <div>
            <h5 class="left">Title:</h5>
            <input type="submit" name="action" value = "&#43;" class="btn-floating right brand">
            <input type="text" name="title" value="">
        </div>
        <div class="red-text"><?php echo $error ?></div>
    </form>

    <h4 class="center grey-text">Calendars</h4>
    <div class="container">
        <div class="row">
            <?php foreach($cals as $cal): ?>
                <div class="col s6 md3">
                    <div class="card">
                        <div class="card-content center">
                            <h4><?php echo htmlspecialchars($cal['title']) ?></h4>
                            <ul>
                                <li><?php echo 'Events: ' . $cal['numEvents'] ?></li>
                                <li><?php echo 'Goals: ' . $cal['numGoals'] ?></li>
                            </ul>
                        </div>
                        <div class="card-action">
                            <form class="white" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST">
                                <input type="submit" name="action" value="Open" class="left btn brand">
                                <input type="submit" name="action" value="Delete" class="right btn brand">
                                <input type="hidden" name="id" value="<?php echo $cal['id'] ?>">
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <?php include('templates\footer.php'); ?>

</html>