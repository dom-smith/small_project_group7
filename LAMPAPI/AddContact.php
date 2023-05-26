<?php
	$inData = getRequestInfo();
	
	$name = $inData["name"];
	$phone = $inData["phone"];
	$email = $inData["email"];
	$userId = $inData["userId"];

	$conn = new mysqli("localhost", "controller", "controlpass", "COP4331");
	if ($conn->connect_error) {
		returnWithError($conn->connect_error);
		
	} else {
		$stmt = $conn->prepare("INSERT INTO Contacts (Name, Phone, Email, UserID) VALUES (?, ?, ?, ?)");
		$stmt->bind_param("sssi", $name, $phone, $email, $userId);
		if ($stmt->execute()) {
			$stmt->close();
			$conn->close();
			returnWithSuccess("Contact added successfully.");
		} else {
			$stmt->close();
			$conn->close();
			returnWithError("Failed to add contact.");
		}
	}
	
	function getRequestInfo()
	{
		return json_decode(file_get_contents('php://input'), true);
	}

	function sendResultInfoAsJson( $obj )
	{
		header('Content-type: application/json');
		echo $obj;
	}
	
	function returnWithError( $err )
	{
		$retValue = '{"error":"' . $err . '"}';
		sendResultInfoAsJson( $retValue );
	}

	function returnwithSuccess($message) {
		$retValue ='{"success":"' . $message . '"}';
		sendResultInfoAsJson($retValue);
	}
	
?>