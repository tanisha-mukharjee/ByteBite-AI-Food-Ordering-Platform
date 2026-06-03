<?php
session_start();

$data = json_decode(file_get_contents("php://input"), true);

if (!empty($data['address'])) {
    $_SESSION['delivery_address'] = $data['address'];
    echo json_encode(["status" => "saved"]);
} else {
    echo json_encode(["status" => "error"]);
}