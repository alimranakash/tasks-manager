<?php

include_once "config.php";

$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME );
if( ! $conn ) {
	throw new Exception( 'Can not connection to database' );
	} else {
	$action = $_POST['action'] ?? '';
	if (!$action) {
		header('Location: index.php');
		die();
	} else {
		if ('add' == $action) {
			$task = $_POST['task'];
			$date = $_POST['date'];
			if ($task && $date) {
				$query = "INSERT INTO " . DB_TABLE . " ( task, date ) VALUES ( '{$task}', '{$date}' )";
				mysqli_query( $conn, $query );
				header('Location: index.php?added=true');
			}
		} else if ( 'complete' == $action ){
			$taskid =  $_POST['complete_task'];
			if ( $taskid ){
				$query = "UPDATE tasks SET complete = 1 WHERE id = {$taskid} LIMIT 1";
				mysqli_query( $conn, $query );
			}
			header('Location: index.php');
		} else if ( 'delete' == $action ){
			$taskid =  $_POST['delete_task'];
			if ( $taskid ){
				$query = "DELETE FROM tasks WHERE id = {$taskid} LIMIT 1";
				mysqli_query( $conn, $query );
			}
			header('Location: index.php');
		} else if ( 'incomplete' == $action ){
			$taskid =  $_POST['incomplete_task'];
			if ( $taskid ){
				$query = "UPDATE tasks SET complete = 0 WHERE id = {$taskid} LIMIT 1";
				mysqli_query( $conn, $query );
			}
			header('Location: index.php');
		} else if ( 'bulk_complete' == $action ){
			$taskids =  $_POST['taskids'];
			$_taskids = join(",", $taskids);
			if ( $taskids ){
				echo $query = "UPDATE tasks SET complete = 1 WHERE id in( $_taskids )";
				mysqli_query( $conn, $query );
			}
			header('Location: index.php');
		} else if ( 'bulk_delete' == $action ){
			$taskids =  $_POST['taskids'];
			$_taskids = join(",", $taskids);
			if ( $taskids ){
				echo $query = "DELETE FROM tasks WHERE id in( $_taskids )";
				mysqli_query( $conn, $query );
			}
			header('Location: index.php');
		}
	}
	mysqli_close( $conn );
}