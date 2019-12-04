<?php

    session_start();

    $error = $notifications = '';
    if(!isset($_SESSION['user']) || !isset($_SESSION['id']))
        header("Location: index.php");
    
    include('config\conn_cal.php');
    $username = mysqli_real_escape_string($conn, $_SESSION['user']);
    $cal_id = mysqli_real_escape_string($conn, $_SESSION['id']);
    if(isset($_POST['year']) && isset($_POST['month']) && isset($_POST['day'])){
        $_SESSION['year'] = $year = $_POST['year'];
        $_SESSION['month'] = $month = $_POST['month'];
        $_SESSION['day'] = $day = $_POST['day'];
    }
    else if(isset($_SESSION['year']) && isset($_SESSION['month']) && isset($_SESSION['day'])){
        $year = $_SESSION['year'];
        $month = $_SESSION['month'];
        $day = $_SESSION['day'];
    }
    else
        header("Location: index.php");
    
    $date = $year.'-'.$month.'-'.$day;


    if(isset($_POST['add'])){
        $title = mysqli_real_escape_string($conn, $_POST['title']);

        if(!preg_match('/^[a-zA-Z0-9\s]+$/', $title))
            $error = "Title must be letters, spaces, and numbers only<br/>";
        else{
            $text = mysqli_real_escape_string($conn, $_POST['text']);
            $start = date('H:i:s', strtotime($_POST['start']));
            $end = date('H:i:s', strtotime($_POST['end']));
            
            $query = "INSERT INTO events(title, descrip, day, startTime, endTime, cal_id) VALUES ('$title', '$text', '$date', '$start', '$end', '$cal_id')";
            $result = mysqli_query($conn, $query);
            if(!$result)
                echo "Query error: " . mysqli_error($conn);
        }
    }
    else if(isset($_POST['delete'])){
        $id = $_POST['event_id'];

        $query = "DELETE FROM events WHERE id = '$id'";
        $result = mysqli_query($conn, $query);
        if(!$result)
            echo "Query error: " . mysqli_error($conn);
    }

    $query = "SELECT id, title, descrip, startTime, endTime FROM events WHERE '$date' = day AND cal_id = '$cal_id' ORDER BY startTime ASC";
    $result = mysqli_query($conn, $query);
    if(!$result)
        echo "Query error: " . mysqli_error($conn);
    else
        $result = mysqli_fetch_all($result, MYSQLI_ASSOC);
    
    mysqli_close($conn);
?>

<!DOCTYPE html>
<html>

    <?php include('templates\header.php'); ?>

    <h1>Events:</h1>
    <table style="width:100%">
        <tr>
            <th>Title</th>
            <th>Description</th>
            <th>Start</th>
            <th>End</th>
            <th>Delete</th>
        </tr>
        <?php foreach($result as $event): ?>
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST">
                <tr>
                    <td><?php echo htmlspecialchars($event['title']) ?></td>
                    <td><?php echo htmlspecialchars($event['descrip']) ?></td>
                    <td><?php echo date('h:i A', strtotime($event['startTime'])) ?></td>
                    <td><?php echo date('h:i A', strtotime($event['endTime'])) ?></td>
                    <td><input type="submit" name="delete" value="-" class="btn brand"></td>
                </tr>
                <input type="hidden" name="event_id" value="<?php echo $event['id'] ?>">
            </form>
        <?php endforeach; ?>
    </table>
    <h4 class="center">Add an Event:</h4>
    <form class="white" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST">
        <label>Title:</label>
        <input type="text" name="title">
        <div class="red-text"><?php echo $error ?></div>
        <label>Description:</label>
        <input type="text" name="text">
        <label>Start:</label>
        <input type="time" name="start">
        <label>End:</label>
        <input type="time" name="end">
        <div class="center"><input type="submit" name="add" value="submit" class="btn brand"></div>
    </form>

    <div style="text-align:center"><a href="cal.php" class="btn brand">Back</a></div>

    <?php include('templates\footer.php'); ?>

</html>