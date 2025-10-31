<?php
// run_confirm.php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? '';

    if ($id) {
        $python = "C:/Users/Satis/AppData/Local/Microsoft/WindowsApps/python3.11.exe";
        $script = __DIR__ . "/send.py";

        // run python script
        $cmd = escapeshellcmd("$python $script");
        $output = shell_exec($cmd . " 2>&1");

        // log สำหรับตรวจสอบ
        if (!is_dir("logs")) mkdir("logs");
        file_put_contents("logs/confirm.log", "[" . date("Y-m-d H:i:s") . "] ID=$id\n$output\n\n", FILE_APPEND);

        // redirect กลับ
        header("Location: confirm.php?id=" . urlencode($id) . "&success=1");
        exit;
    }
}
header("Location: index.php");
exit;
