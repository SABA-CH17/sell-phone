<?php
require_once("admin/include/db-connect.php");
header('Content-Type: application/json');

$response = ["success" => false, "message" => "Something went wrong."];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $model_id = (int) ($_POST['model_id'] ?? 0);
    $brand = trim($_POST['brand'] ?? '');
    $model_name = trim($_POST['model_name'] ?? '');
    $storage = trim($_POST['storage'] ?? '');
    $condition = trim($_POST['condition'] ?? '');
    $accessories = trim($_POST['accessories'] ?? '');
    $price = (float) ($_POST['price'] ?? 0);
    $name = trim($_POST['name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $address = trim($_POST['address'] ?? '');

    if (!$model_id || $storage === '' || $condition === '' || $name === '' || $phone === '') {
        $response["message"] = "Please complete the selection and provide your name and phone number.";
        echo json_encode($response);
        exit;
    }

    $model_img = '';
    $img_stmt = mysqli_prepare($conn, "SELECT image FROM models WHERE model_name = ? AND brand = ? LIMIT 1");
    if ($img_stmt === false) {
        $response["message"] = "DB error preparing image lookup: " . mysqli_error($conn);
        echo json_encode($response);
        exit;
    }
    mysqli_stmt_bind_param($img_stmt, "ss", $model_name, $brand);
    mysqli_stmt_execute($img_stmt);
    $img_result = mysqli_stmt_get_result($img_stmt);
    if ($img_row = mysqli_fetch_assoc($img_result)) {
        $model_img = $img_row['image'];
    }

    $stmt = mysqli_prepare($conn, "INSERT INTO leads (name, phone, email, address, brand, model, image, storage, price, status, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending', NOW())");
    if ($stmt === false) {
        $response["message"] = "DB error preparing insert: " . mysqli_error($conn);
        echo json_encode($response);
        exit;
    }

    mysqli_stmt_bind_param($stmt, "ssssssssd", $name, $phone, $email, $address, $brand, $model_name, $model_img, $storage, $price);

    if (mysqli_stmt_execute($stmt)) {
        $id = mysqli_insert_id($conn);
        $order_number = 'ORD-' . date('Y') . '-' . str_pad($id, 5, '0', STR_PAD_LEFT);

        $update = mysqli_prepare($conn, "UPDATE leads SET order_number = ? WHERE id = ?");
        mysqli_stmt_bind_param($update, "si", $order_number, $id);
        mysqli_stmt_execute($update);

        $response["success"] = true;
        $response["message"] = "Thanks! We'll contact you shortly with the next steps.";
        $response["price"] = $price;
        $response["order_number"] = $order_number;
    } else {
        $response["message"] = "Could not save your submission: " . mysqli_stmt_error($stmt);
    }
}

echo json_encode($response);