<?php
// index.php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // รับข้อมูลจากฟอร์ม
    $id = "TR-" . date("Ymd") . "-" . rand(100, 999);

    $data = $_POST;
    $data['id'] = $id;
    $data['status'] = "0";

    // เซฟ JSON ลงไฟล์
    if (!is_dir("data")) {
        mkdir("data", 0777, true);
    }
    $file = "data/" . $id . ".json";
    file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

    // redirect ไป confirm.php พร้อมส่ง id
    header("Location: confirm.php?id=" . $id);
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>BD Get Quote Form</title>
<link rel="stylesheet" href="css/index.css">
</head>
<body>

<header>
  <img src="https://i.imghippo.com/files/ziqj5305k.png" alt="SeABRA Logo">
</header>

<main>
  <div class="quote-container">
    <div class="form-section">
      <h2>Choose a Service & Get a Quote</h2>
      <div class="form-container">
        <form id="quoteForm" method="POST" action="index.php">
          <div class="form-row">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>
          </div>
          <div class="form-row">
            <label for="company">Company:</label>
            <input type="text" id="company" name="company" required>
          </div>
          <div class="form-row">
            <label for="tel">Tel:</label>
            <input type="text" id="tel" name="tel" required>
          </div>
          <div class="form-row">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
          </div>

          <div class="form-row">
            <label>Service:</label>
            <button type="button" id="serviceToggle">Select Service</button>
            <div class="subservice-container" style="display:none;">
              <label>Select Sub Services (multiple):</label>
              <div class="subservice-options"></div>
            </div>
          </div>

          <div class="button-row">
            <button type="submit">Submit</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</main>

<script>
const services = {
  "Product Service": ["Food&Beverage","Beauty&Cosmetic","Living&Lifestyle","Health&Wellness","General Cargo"],
  "Customs Brokerage": ["CustomsBrokerage", "AppLicense", "Registrationwithvariousagencies"],
  "Airfreight": ["AirFreightImport", "AirFreightExport"],
  "Seafreight": ["SeaFreightImport", "SeaFreightExport"]
};

const serviceToggle = document.getElementById("serviceToggle");
const subserviceContainer = document.querySelector(".subservice-container");
const subserviceOptionsDiv = document.querySelector(".subservice-options");

// Toggle service list
serviceToggle.addEventListener("click", (e) => {
  e.preventDefault(); 
  const isActive = serviceToggle.classList.toggle("active");
  subserviceContainer.style.display = isActive ? "block" : "none";

  if(isActive){
    subserviceOptionsDiv.innerHTML = "";
    Object.keys(services).forEach(service => {
      const groupDiv = document.createElement("div");
      groupDiv.classList.add("service-group");

      const heading = document.createElement("h4");
      heading.textContent = service;
      groupDiv.appendChild(heading);

      services[service].forEach(sub => {
        const label = document.createElement("label");
        label.classList.add("subservice-label");

        let inputValue = sub;
        if (service === "Product Service") {
            inputValue = sub.replace(/&/g, '');
        }
        label.innerHTML = `<input type="checkbox" name="subService[]" value="${inputValue}"> ${sub}`;
        groupDiv.appendChild(label);
      });

      subserviceOptionsDiv.appendChild(groupDiv);
    });
  }
});
</script>
</body>
</html>
