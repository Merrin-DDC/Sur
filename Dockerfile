FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    python3 python3-pip python3-dev \
    curl unzip wget gnupg ca-certificates fonts-thai-tlwg \
    && rm -rf /var/lib/apt/lists/*

# ... โค้ด Dockerfile ส่วนอื่นๆ ของคุณ
COPY . /var/www/html/
WORKDIR /var/www/html

# เพิ่มบรรทัดนี้เพื่อแก้ไขสิทธิ์การเข้าถึงสำหรับโฟลเดอร์ที่ต้องเขียนไฟล์
RUN chown -R www-data:www-data /var/www/html/data && \
    chmod -R 775 /var/www/html/data

# ... (ส่วนอื่น ๆ เช่น CMD)
