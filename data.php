<?php
include_once "config.php";

$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME );
if( ! $conn ) {
	throw new Exception( 'Can not connection to database' );
}else{
	echo 'Connected';
//	echo mysqli_query( $conn, "INSERT INTO tasks ( task, date ) VALUES ( 'First Task', '2019-3-4' )" );

//	$results = mysqli_query( $conn, "SELECT * FROM tasks" );
//	while ( $data = mysqli_fetch_object( $results ) ){
//		echo "<pre>";
//		print_r( $data );
//		echo "</pre>";
//	}

//	$delete = mysqli_query( $conn, "DELETE FROM tasks" );
//	$TRUNCATE = mysqli_query( $conn, "TRUNCATE TABLE tasks" );
	mysqli_close( $conn );
}