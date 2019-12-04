<?php
    session_start();
    date_default_timezone_set("America/New_York");
    $error = $notifications = '';
    if(!isset($_SESSION['user']) || !isset($_SESSION['id']))
        header("Location: index.php");
    
    include('config\conn_cal.php');
    $username = mysqli_real_escape_string($conn, $_SESSION['user']);
    $cal_id = mysqli_real_escape_string($conn, $_SESSION['id']);

    include('config\calendar.php');
    $cal = new Calendar($cal_id);
    
    if(isset($_POST['add_goal'])){
        $task = mysqli_real_escape_string($conn, $_POST['task']);
        $end = $_POST['end'];
        $total = $_POST['total'];
        if($end < date('Y-m-d'))
            $error = "Error: end date must be after today's date";
        else{
            $query = "INSERT INTO goals(task, endDate, total, cal_id) VALUES ('$task', '$end', '$total', '$cal_id')";
            if(!mysqli_query($conn, $query))
                echo "Query error: " . mysqli_error($conn);
        }
    }
    else if(isset($_POST['add_progress'])){
        $task = $_POST['task'];
        $add = $_POST['added'];
        $query = "UPDATE goals SET progress = progress + '$add' WHERE id = (SELECT MIN(id) FROM goals WHERE task = '$task' GROUP BY task HAVING total = MAX(total))";
        if(!mysqli_query($conn, $query))
            echo "Query error: " . mysqli_error($conn);

        $query = "SELECT DISTINCT SUM(progress) as progress, MAX(total) as total FROM goals WHERE task = '$task'";
        $result = mysqli_query($conn, $query);
        if(!$result)
            echo "Query error: " . mysqli_error($conn);
        else
            $result = mysqli_fetch_all($result, MYSQLI_ASSOC);

        foreach($result as $prog){
            if($prog['progress'] >= $prog['total']){
                $query = "DELETE FROM goals WHERE task = '$task'";
                if(!mysqli_query($conn, $query))
                    echo "Query error: " . mysqli_error($conn);
                else{
                    $notifications .=
                    '<div class="alert success">'.
                        '<span class="closebtn" onclick="this.parentElement.style.display=\'none\';">&times;</span>'.
                        'You completed a goal!'.
                    '</div>';
                }
            }
        }
    }
    else if(isset($_POST['delete'])){
        $task = $_POST['task'];
        $query = "DELETE FROM goals WHERE task = '$task'";
        if(!mysqli_query($conn, $query))
            echo "Query error: " . mysqli_error($conn);
    }

    $date = date('Y-m-d');
    $query = "SELECT DISTINCT msg FROM reminders, events, goals WHERE '$date' = remindDate AND ((reminders.goal_id = goals.id AND goals.cal_id = '$cal_id') OR (reminders.event_id = events.id AND events.cal_id = '$cal_id')) ORDER BY remindDate ASC";
    $result = mysqli_query($conn, $query);
    if(!$result)
        echo "Query error: " . mysqli_error($conn);
    else{
        $result = mysqli_fetch_all($result, MYSQLI_ASSOC);
        foreach($result as $message){
            $notifications .= 
            '<div class="alert warning">'.
                '<span class="closebtn" onclick="this.parentElement.style.display=\'none\';">&times;</span>'.
                'Reminder to '.$message['msg'].
            '</div>';
        }
            
    }

    $query = "SELECT DISTINCT task, MAX(startDate) as startDate, MAX(endDate) as endDate, SUM(progress) as progress, MAX(total) as total FROM goals WHERE '$date' <= endDate AND cal_id = '$cal_id' GROUP BY task ORDER BY MAX(endDate) ASC";
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
    <?php include('config\style.php'); ?>
    <?php $cal->print(); ?>
    <br>
    <div style="text-align:center"><a href="cals.php" class="btn brand">Back</a></div>
    <br>
    <style>
    table, th, td{
        border: 1px solid black;
        border-collapse: collapse;
    }
    th, td{
        padding: 5px;
        text-align: center;
    }
    .alert.warning{
        background-color: #ff9800;
    }
    .alert.success{
        background-color: #4CAF50;
    }
    </style>
    <?php echo $notifications ?>
    <h1>Goals:</h1>
    <table style="width:100%">
        <tr>
            <th>Task</th>
            <th>Start</th>
            <th>End</th>
            <th colspan="2">Progress</th>
            <th>Add Progress</th>
            <th>Delete</th>
        </tr>
        <?php foreach($result as $goal): ?>
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST">
                <tr>
                    <td><?php echo htmlspecialchars($goal['task']) ?></td>
                    <td><?php echo $goal['startDate'] ?></td>
                    <td><?php echo $goal['endDate'] ?></td>
                    <td><?php echo $goal['progress'].'/'.$goal['total'] ?></td>
                    <td><?php echo (floor(((float)$goal['progress']/$goal['total'])*10000)/100).'%' ?></td>
                    <td>
                        <input style="width: 50%" class="left" type="number" name="added" max="<?php echo $goal['total'] - $goal['progress'] ?>" value="1">
                        <input type="submit" name="add_progress" value="&#43;" class="btn brand right">
                    </td>
                    <td><input type="submit" name="delete" value="-" class="btn brand"></td>
                </tr>
                <input type="hidden" name="task" value="<?php echo $goal['task'] ?>">
            </form>
        <?php endforeach; ?>
    </table>
    <h4 class="center">Add a Goal:</h4>
    <form class="white" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST">
        <label>Task:</label>
        <input type="text" name="task">
        <label>End date:</label>
        <input type="date" name="end">
        <div class="red-text"><?php echo $error ?></div>
        <label>Total:</label>
        <input type="number" name="total" min="1" value="1">
        <div class="center"><input type="submit" name="add_goal" value="submit" class="btn brand"></div>
    </form>

    <?php include('templates\footer.php'); ?>

</html>
