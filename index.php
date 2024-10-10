
<?php
session_start();
require 'db.php';

// Authentication check
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Task management
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['add_task'])) {
        $task = trim($_POST['task']);
        $sql = "INSERT INTO tasks (user_id, task) VALUES ('$user_id', '$task')";
        $connection->query($sql);
    } elseif (isset($_POST['update_task'])) {
        $task_id = $_POST['task_id'];
        $task = trim($_POST['task']);
        $sql = "UPDATE tasks SET task='$task' WHERE id='$task_id'";
        $connection->query($sql);
    } elseif (isset($_POST['delete_task'])) {
        $task_id = $_POST['task_id'];
        $sql = "DELETE FROM tasks WHERE id='$task_id'";
        $connection->query($sql);
    } elseif (isset($_POST['complete_task'])) {
        $task_id = $_POST['task_id'];
        $sql = "UPDATE tasks SET completed=1 WHERE id='$task_id'";
        $connection->query($sql);
    }
}

// Retrieve tasks
$sql = "SELECT * FROM tasks WHERE user_id='$user_id'";
$result = $connection->query($sql);

// Task completion status
$sql = "SELECT COUNT(*) as total_tasks, SUM(completed) as completed_tasks FROM tasks WHERE user_id='$user_id'";
$completion_result = $connection->query($sql);

if ($completion_result->num_rows > 0) {
    $completion_row = $completion_result->fetch_assoc();
    $total_tasks = $completion_row['total_tasks'];
    $completed_tasks = $completion_row['completed_tasks'];

    if ($total_tasks > 0) {
        $completion_percentage = ($completed_tasks / $total_tasks) * 100;
    } else {
        $completion_percentage = 0;
    }
} else {
    $total_tasks = 0;
    $completed_tasks = 0;
    $completion_percentage = 0;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Todo List</title>
<style>
body {
    font-family: 'Nunito', sans-serif;
    background-color: #456778; /* Soft dark blue background */
}

.container {
    max-width: 800px;
    margin: 40px auto;
    padding: 20px;
    background-color: #f7f7f7; /* Light gray container background */
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.2); /* Soft shadow */
}

h1 {
    color: #3498db; /* Bright blue heading */
    font-weight: 700;
    margin-bottom: 20px;
    text-align: center; /* Centered heading */
}

form {
    margin-bottom: 20px;
    background-color: #ececec; /* Light gray form background */
    padding: 10px;
    border-radius: 5px;
}

label {
    display: block;
    margin-bottom: 10px;
    color: #666; /* Dark gray label text */
}

input[type="text"] {
    width: 100%;
    height: 40px;
    padding: 10px;
    font-size: 16px;
    border: 1px solid #ccc; /* Light gray border */
    border-radius: 5px;
    background-color: #f2f2f2; /* Light gray input background */
    color: #333; /* Dark gray input text */
}

button[type="submit"] {
    background-color: #4CAF50; /* Green submit button */
    color: #fff;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

button[type="submit"]:hover {
    background-color: #3e8e41; /* Darker green on hover */
}

ul {
    list-style: none;
    padding: 0;
    margin: 0;
    background-color: #f7f7f7; /* Light gray list background */
}

li {
    padding: 10px;
    border-bottom: 1px solid #ddd; /* Light gray border */
}

li:last-child {
    border-bottom: none;
}

li strike {
    color: #999; /* Gray strike-through text */
}

.task-actions {
    float: right;
}

.task-actions button {
    background-color: #fff; /* White button background */
    border: 1px solid #ddd; /* Light gray border */
    padding: 5px 10px;
    font-size: 12px;
    cursor: pointer;
    color: #333; /* Dark gray button text */
}

.task-actions button:hover {
    background-color: #f0f0f0; /* Light gray hover background */
}

.completed-task {
    text-decoration: line-through;
    color: #999; /* Gray completed task text */
}

.task-completion-status {
    margin-top: 20px;
    background-color: #ececec; /* Light gray status background */
    padding: 10px;
    border-radius: 5px;
}

.task-completion-status p {
    font-size: 18px;
    color: #666; /* Dark gray status text */
}

.progress-bar {
    width: 100%;
    height: 10px;
    background-color: #ddd; /* Light gray progress bar */
    border-radius: 5px;
    overflow: hidden;
}

.progress-bar-fill {
    width: 0%;
    height: 100%;
    background-color: #4CAF50; /* Green progress bar fill */
    transition: width 0.5s ease-in-out;
}

/* Additional styles */

.task-actions button.delete {
    background-color: #e74c3c; /* Red delete button */
    color: #fff;
    border: none;
}

.task-actions button.complete {
    background-color: #8bc34a; /* Green complete button */
    color: #fff;
    border: none;
}

.task-actions button.update {
    background-color: #3498db; /* Blue update button */
    color: #fff;
    border: none;
}

.task-completion-status {
    text-align: center;
}

.progress-bar-fill {
    border-radius: 5px 0 0 5px;
}




</style>
</head>
<body>
    <div class="container">
        <h1>Todo List</h1>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <label>Add Task:</label>
            <input type="text" name="task" required>
            <br>
            <button type="submit" name="add_task">Add</button>
        </form>
        <ul>
            <?php while ($task = $result->fetch_assoc()) { ?>
                <li>
                    <?php if ($task['completed']) { ?>
                        <strike><?php echo $task['task']; ?></strike>
                    <?php } else { ?>
                        <?php echo $task['task']; ?>
                    <?php } ?>
                    <div class="task-actions">
                        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                            <input type="hidden" name="task_id" value="<?php echo $task['id']; ?>">
                            <button type="submit" name="delete_task">Delete</button>
                            <button type="submit" name="complete_task">Complete</button>
                            <input type="text" name="task" value="<?php echo $task['task']; ?>">
                            <button type="submit" name="update_task">Update</button>
                        </form>
                    </div>
                </li>
            <?php } ?>
        </ul>
        <div class="task-completion-status">
            <h2>Task Completion Status</h2>
            <p>Completed Tasks: <?php echo $completed_tasks; ?>/<?php echo $total_tasks; ?></p>
            <p>Completion Percentage: <?php echo $completion_percentage; ?>%</p>
            <div class="progress-bar">
                <div class="progress-bar-fill" style="width: <?php echo $completion_percentage; ?>%"></div>
            </div>
        </div>
    </div>
</body>
</html>