<?php
include("include/db-connect.php");
include("include/auth-check.php");
header('Content-Type: application/json');

$response = ["success" => false, "message" => "Something went wrong."];

$allowed_statuses = ['pending', 'contacted', 'completed'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int) ($_POST['id'] ?? 0);
    $status = trim($_POST['status'] ?? '');

    if (!$id || !in_array($status, $allowed_statuses, true)) {
        $response["message"] = "Invalid lead or status.";
        echo json_encode($response);
        exit;
    }

    $stmt = mysqli_prepare($conn, "UPDATE leads SET status = ? WHERE id = ?");
    if ($stmt === false) {
        $response["message"] = "DB error: " . mysqli_error($conn);
        echo json_encode($response);
        exit;
    }

    mysqli_stmt_bind_param($stmt, "si", $status, $id);

    if (mysqli_stmt_execute($stmt)) {
        $response["success"] = true;
        $response["message"] = "Status updated.";
    } else {
        $response["message"] = "Could not update status: " . mysqli_stmt_error($stmt);
    }
}

echo json_encode($response);