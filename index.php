
<?php
include_once "config.php";

$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME );
if( ! $conn ) {
	throw new Exception( 'Can not connection to database' );
}
$upcomingTasksQuery = "SELECT * FROM tasks where complete = 0 order by date";
$upcomingTasksResults = mysqli_query( $conn, $upcomingTasksQuery );

$completeTasksQuery = "SELECT * FROM tasks where complete = 1 order by date";
$completeTasksResults = mysqli_query( $conn, $completeTasksQuery );
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Todo/Tasks</title>
	<link rel="stylesheet" href="//fonts.googleapis.com/css?family=Roboto:300,300italic,700,700italic">
	<link rel="stylesheet" href="//cdn.rawgit.com/necolas/normalize.css/master/normalize.css">
	<link rel="stylesheet" href="//cdn.rawgit.com/milligram/milligram/master/dist/milligram.min.css">
	<style>
		body {
			margin-top: 30px;
		}
		#main {
			padding: 0 150px;
		}
		#action {
			width: 150px;
		}
	</style>
</head>
<body>
<div class="container" id="main">
	<h1>Tasks Manager</h1>
	<p>This is a simple project for manage our daily tasks</p>


	<?php
	if ( mysqli_num_rows( $completeTasksResults ) > 0 ) {
		?>
        <h4>Complete Tasks</h4>
        <table>
            <thead>
            <tr>
                <th>Id</th>
                <th>Task</th>
                <th>Date</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
			<?php
			while( $data = mysqli_fetch_assoc( $completeTasksResults ) ) {
				$timestamp = strtotime( $data['date'] );
				$date = date( "jS M, Y", $timestamp );
				?>
                <tr>
                    <td><?php echo $data['id']; ?></td>
                    <td><?php echo $data['task']; ?></td>
                    <td><?php echo $date; ?></td>
                    <td><a href="#" class="delete" data-taskid="<?php echo $data['id']; ?>">Delete</a> | <a href="#" class="incomplete" data-taskid="<?php echo $data['id']; ?>">Mark Incomplete</a></td>
                </tr>
				<?php
			}

			?>
            </tbody>
        </table>
		<?php
	}
	?>

	<?php
		if ( mysqli_num_rows( $upcomingTasksResults ) == 0 ) {
			echo "Task Not Found";
		}else {
			?>
            <h4>Upcoming Tasks</h4>
            <form method="post" action="tasks.php">
                <table>
                    <thead>
                    <tr>
                        <th></th>
                        <th>Id</th>
                        <th>Task</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    while( $data = mysqli_fetch_assoc( $upcomingTasksResults ) ) {
                        $timestamp = strtotime( $data['date'] );
                        $date = date( "jS M, Y", $timestamp );
                        ?>
                        <tr>
                            <td><input name="taskids[]" class="label-inline" type="checkbox" value="<?php echo $data['id']; ?>"></td>
                            <td><?php echo $data['id']; ?></td>
                            <td><?php echo $data['task']; ?></td>
                            <td><?php echo $date; ?></td>
                            <td><a href="#" class="delete" data-taskid="<?php echo $data['id']; ?>">Delete</a> | <a class="complete" data-taskid="<?php echo $data['id']; ?>" href="#">Complete</a></td>
                        </tr>
                        <?php
                    }

                    ?>
                    </tbody>
                </table>
                <select id="action" name="action">
                    <option value="0">Width Select</option>
                    <option value="bulk_delete">Delete</option>
                    <option value="bulk_complete">Mark as complete</option>
                </select>
                <input class="button-primary" type="submit" value="Submit">
            </form>
			<?php
		}
	?>

	<h4>Add Tasks</h4>

	<form method="post" action="tasks.php">
		<fieldset>
			<?php
			$added = $_GET['added'] ?? '';
			if ( $added ) {
				echo 'Task Successfully added';
			}
			?>
			<label for="Task">Task</label>
			<input type="text" placeholder="Task Name" id="" name="task">
			<label for="Task">Date</label>
			<input type="text" placeholder="Task Date" id="" name="date">
		</fieldset>
		<input class="button-primary" type="submit" value="Add Task">
		<input type="hidden" value="add" name="action">
	</form>

    <form method="post" action="tasks.php" id="task-complete-form">
        <input type="hidden" id="complete-action" name="action" value="complete">
        <input type="hidden" id="complete-task" name="complete_task">
    </form>

    <form method="post" action="tasks.php" id="task-delete-form">
        <input type="hidden" id="delete-action" name="action" value="delete">
        <input type="hidden" id="delete-task" name="delete_task">
    </form>

    <form method="post" action="tasks.php" id="task-incomplete-form">
        <input type="hidden" id="incomplete-action" name="action" value="incomplete">
        <input type="hidden" id="incomplete-task" name="incomplete_task">
    </form>

</div>
</body>
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script>
    ;(function ($) {
        $(document).ready(function(){
            $(".complete").on('click', function(e){
                e.preventDefault();
                var id = $(this).data("taskid");
                $("#complete-task").val( id );
                $("#task-complete-form").submit();
            })

            $(".delete").on('click', function(e){
                e.preventDefault();
                var id = $(this).data("taskid");
                $("#delete-task").val( id );
                $("#task-delete-form").submit();
            })

            $(".incomplete").on('click', function(e){
                e.preventDefault();
                var id = $(this).data("taskid");
                $("#incomplete-task").val( id );
                $("#task-incomplete-form").submit();
            })
        })
    })(jQuery);
</script>
</html>