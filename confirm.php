<?php
// confirm.php (styled)
$id = $_GET['id'] ?? '';
$file = "data/" . $id . ".json";
$data = [];

// ลองอ่านจาก data/ ถ้าไม่เจอไปอ่านจาก sending/
if ($id) {
    if (file_exists($file)) {
        $data = json_decode(file_get_contents($file), true);
    } else {
        $sendingFile = "sending/" . $id . ".json";
        if (file_exists($sendingFile)) {
            $data = json_decode(file_get_contents($sendingFile), true);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Confirm Quote</title>
<link rel="stylesheet" href="css/index.css">
<style>
  .confirm-card { max-width:900px; margin:24px auto; background:#fff; border-radius:12px; padding:20px; box-shadow:0 6px 18px rgba(0,0,0,0.08); }
  .confirm-grid { display:grid; grid-template-columns: 1fr 1fr; gap:12px; }
  .confirm-row { margin-bottom:8px; }
  .service-list { margin-top:12px; }
  .service-list li { padding:6px 10px; border-radius:8px; margin-bottom:6px; display:inline-block; background:#f3f4f6; }
  @media (max-width:700px) {
    .confirm-grid { grid-template-columns: 1fr; }
  }
  .btn { padding:8px 16px; background:#2563eb; color:#fff; border-radius:8px; text-decoration:none; }
  .btn-secondary { background:#6b7280; }
  .btn:hover { opacity:0.9; }
</style>
</head>
<body>
<header>
  <img src="https://i.imghippo.com/files/ziqj5305k.png" alt="SeABRA Logo">
</header>
<main>
  <div class="quote-container">
    <div class="confirm-section">
      <div class="confirm-card">
        <h2>ข้อมูลการขอใบเสนอราคา</h2>

        <?php if ($data): ?>
          <?php if (isset($_GET['success'])): ?>
            <p style="color:green; font-weight:bold;">✅ ส่งข้อมูลไปยังระบบเรียบร้อยแล้ว</p>
          <?php endif; ?>

          <div class="confirm-grid">
            <div>
              <p class="confirm-row"><strong>ID:</strong> <?= htmlspecialchars($data['id'] ?? '') ?></p>
              <p class="confirm-row"><strong>Name:</strong> <?= htmlspecialchars($data['name'] ?? '') ?></p>
              <p class="confirm-row"><strong>Company:</strong> <?= htmlspecialchars($data['company'] ?? '') ?></p>
            </div>
            <div>
              <p class="confirm-row"><strong>Tel:</strong> <?= htmlspecialchars($data['tel'] ?? '') ?></p>
              <p class="confirm-row"><strong>Email:</strong> <?= htmlspecialchars($data['email'] ?? '') ?></p>
              <p class="confirm-row"><strong>Status:</strong> <?= htmlspecialchars($data['status'] ?? '') ?></p>
            </div>
          </div>

          <h3 style="margin-top:16px;">Services Selected:</h3>
          <div class="service-list">
            <ul>
            <?php
            if (!empty($data['subService']) && is_array($data['subService'])) {
                foreach ($data['subService'] as $service) {
                    echo "<li>" . htmlspecialchars($service) . "</li>\n";
                }
            }

            foreach ($data as $key => $val) {
                if (in_array($key, ['id','name','company','tel','email','status','subService'])) continue;
                if ($val == 1 || $val === true || $val === 'on') {
                    echo "<li>" . htmlspecialchars($key) . "</li>\n";
                }
            }
            ?>
            </ul>
          </div>

          <form method="POST" action="run_confirm.php" style="margin-top:18px; display:flex; gap:12px; flex-wrap:wrap;">
            <input type="hidden" name="id" value="<?= htmlspecialchars($data['id'] ?? '') ?>">
            <button type="submit" class="btn">Confirm</button>
            <a href="index.php" class="btn btn-secondary">Cancel</a>
          </form>

        <?php else: ?>
        <?php endif; ?>

      </div>
    </div>
  </div>
</main>
</body>
</html>
