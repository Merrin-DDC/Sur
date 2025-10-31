<?php
// save.php
header("Content-Type: application/json; charset=UTF-8");

$input = file_get_contents("php://input");
$data = json_decode($input, true);

if (!$data) {
    echo json_encode(["status" => "error", "message" => "No data received"]);
    exit;
}

$file = "data/" . $data["id"] . ".json";

// โหลดข้อมูลเก่าจากไฟล์
if (file_exists($file)) {
    $jsonData = json_decode(file_get_contents($file), true);
    if (!is_array($jsonData)) {
        $jsonData = [];
    }
} else {
    $jsonData = [];
}

// เพิ่มข้อมูลใหม่
$jsonData[] = $data;

// บันทึกกลับไฟล์
if (file_put_contents($file, json_encode($jsonData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE))) {
    echo json_encode(["status" => "success"]);
} else {
    echo json_encode(["status" => "error", "message" => "Failed to write data"]);
}

