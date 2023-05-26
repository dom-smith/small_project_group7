<?php

$inData = getRequestInfo();

$contactId = $inData["contactId"];
$userId = $inData["userId"];
$firstName = $inData["firstName"];
$lastName = $inData["lastName"];
$phone = $inData["phone"];
$email = $inData["email"];

$conn = new mysqli("localhost", "controller", "controlpass", "COP4331");
if ($conn->connect_error) {
	returnWithError($conn->connect_error);
} else {
	$stmt = $conn->prepare("UPDATE Contacts SET FirstName = ?, LastName = ?, Phone = ?, Email = ? WHERE ID = ? AND UserID = ?");
	$stmt->bind_param("ssssii", $firstName, $lastName, $phone, $email, $contactId, $userId);
	if ($stmt->execute()) {
		$stmt->close();
		$conn->close();
		returnWithSuccess("Contact updated successfully.");
	} else {
		$stmt->close();
		$conn->close();
		returnWithError("Failed to update contact.");
	}
}

function getRequestInfo() {
	return json_decode(file_get_contents('php://input'), true);
}

function sendResultInfoAsJson($obj) {
	header('Content-type: application/json');
	echo $obj;
}

function returnWithError($err) {
	$retValue = '{"error":"' . $err . '"}';
	sendResultInfoAsJson($retValue);
}

function returnWithSuccess($message) {
	$retValue = '{"success":"' . $message . '"}';
	sendResultInfoAsJson($retValue);
}

?>
