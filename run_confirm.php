<?php
// run_confirm.php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? '';

    if ($id) {
        $python = "python3 ";
        $script = __DIR__ . "/send.py";

        // run python script
        $cmd = escapeshellcmd("$python $script");
        $output = shell_exec($cmd . " 2>&1");

        // redirect กลับ
        header("Location: confirm.php?id=" . urlencode($id) . "&success=1");
        exit;
    }
}
header("Location: index.php");
exit;


