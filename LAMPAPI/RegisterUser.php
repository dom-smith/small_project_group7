<?php

	$inData = getRequestInfo();
	
	$firstName = $inData["firstName"];
	$lastName = $inData["lastName"];
	$login = $inData["login"];
	$password = $inData["password"];

	$conn = new mysqli("localhost", "controller", "controlpass", "COP4331"); 	
	if ($conn->connect_error) {
		returnWithError($conn->connect_error);
	} else {
		// Prepare and execute the SQL statement to insert the user's data
		$stmt = $conn->prepare("INSERT INTO Users (firstName, lastName, Login, Password) VALUES (?, ?, ?, ?)");
		$stmt->bind_param("ssss", $firstName, $lastName, $login, $password);
		$stmt->execute();

		// Get the ID of the inserted user
		$id = $stmt->insert_id;
		if ($id > 0) {
			// If registration is successful, return the user ID, first name, and last name
			returnWithInfo($id, $firstName, $lastName);
		} else {
			// If registration fails, return an error message
			returnWithError("Registration failed");
		}

		$stmt->close();
		$conn->close();
	}
	
	function getRequestInfo() {
		return json_decode(file_get_contents('php://input'), true);
	}

	function sendResultInfoAsJson($obj) {
		header('Content-type: application/json');
		echo $obj;
	}
	
	function returnWithError($err) {
		$retValue = '{"id":0,"firstName":"","lastName":"","error":"' . $err . '"}';
		sendResultInfoAsJson($retValue);
	}
	
	function returnWithInfo($id, $firstName, $lastName) {
		$retValue = '{"id":' . $id . ',"firstName":"' . $firstName . '","lastName":"' . $lastName . '","error":""}';
		sendResultInfoAsJson($retValue);
	}

?>
