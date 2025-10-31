import json
import smtplib
import shutil
import os
from email.mime.text import MIMEText
from email.mime.multipart import MIMEMultipart

# -------------------------------
# ฟังก์ชันส่งอีเมล
# -------------------------------
def send_email(sender_email, sender_password, to_email, subject, body,
               smtp_server="smtp.gmail.com", smtp_port=587):
    try:
        msg = MIMEMultipart()
        msg["From"] = sender_email
        msg["To"] = to_email
        msg["Subject"] = subject
        msg.attach(MIMEText(body, "plain", "utf-8"))

        with smtplib.SMTP(smtp_server, smtp_port) as server:
            server.starttls()
            server.login(sender_email, sender_password)
            server.sendmail(sender_email, to_email, msg.as_string())
        print(f"✅ ส่งเมลไปยัง {to_email} แล้ว")
        return True
    except Exception as e:
        print(f"❌ ส่งเมลไม่สำเร็จไปยัง {to_email}: {e}")
        return False

# -------------------------------
# ฟังก์ชันประมวลผลไฟล์ JSON
# -------------------------------
def process_file(file_path, sender_email, sender_password):
    try:
        with open(file_path, "r", encoding="utf-8") as f:
            record = json.load(f)
    except Exception as e:
        print(f"❌ อ่านไฟล์ {file_path} ไม่ได้: {e}")
        # ย้ายไปโฟลเดอร์ Jaea (เก็บ error)
        os.makedirs("Jaea", exist_ok=True)
        shutil.move(file_path, os.path.join("Jaea", os.path.basename(file_path)))
        return

    # รองรับกรณี record เป็น list
    if isinstance(record, list):
        for rec in record:
            send_record(rec, file_path, sender_email, sender_password)
    else:
        send_record(record, file_path, sender_email, sender_password)

    # ย้ายไฟล์เสร็จแล้ว
    sending_dir = "sending"
    os.makedirs(sending_dir, exist_ok=True)
    shutil.move(file_path, os.path.join(sending_dir, os.path.basename(file_path)))
    print(f"📂 ย้ายไฟล์ {file_path} ไปยัง {sending_dir} แล้ว")

# -------------------------------
# ฟังก์ชันส่งข้อมูลแต่ละ record
# -------------------------------
def send_record(record, file_path, sender_email, sender_password):
    name = record.get("name")
    company = record.get("company")
    email = record.get("email")
    tel = record.get("tel")
    status = record.get("status")

    # รวม service ที่เลือก
    services = []
    if isinstance(record.get("subService"), list):
        services.extend(record["subService"])
    for key, val in record.items():
        if key not in ["id", "name", "company", "tel", "email", "status", "subService"]:
            if val in [1, True, "on"]:
                services.append(key)

    subject = f"[Request] From {company}"
    body = f"""เรียน ddc@seabratrans.com

ID: {record.get('id')}
Name: {name}
Company: {company}
Tel: {tel}
Email: {email}
Status: {status}

บริการที่ลูกค้าต้องการ:
    """ + "\n    ".join(services)

    # ส่งอีเมลครั้งเดียว ไปที่ ddc
    send_email(sender_email, sender_password, "ddc@seabratrans.com", subject, body)

# -------------------------------
# Main process loop
# -------------------------------
def process_all_json():
    sender_email = "msending11@gmail.com"       # อีเมลผู้ส่ง
    sender_password = "leye vpxm gpmy mxap"     # App Password

    data_dir = "data"
    sending_dir = "sending"
    os.makedirs(data_dir, exist_ok=True)
    os.makedirs(sending_dir, exist_ok=True)

    files = [f for f in os.listdir(data_dir) if f.endswith(".json")]
    while files:
        for file_name in files:
            file_path = os.path.join(data_dir, file_name)
            process_file(file_path, sender_email, sender_password)
        files = [f for f in os.listdir(data_dir) if f.endswith(".json")]

    print("✅ ไม่มีไฟล์ .json เหลือใน data/ แล้ว")

# -------------------------------
# Run
# -------------------------------
if __name__ == "__main__":
    process_all_json()
