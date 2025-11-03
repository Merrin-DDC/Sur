FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    python3 python3-pip python3-dev \
    curl unzip wget gnupg ca-certificates fonts-thai-tlwg \
    && rm -rf /var/lib/apt/lists/*

# ติดตั้ง Python packages
RUN python3 -m pip install --upgrade pip --break-system-packages && \
    pip3 install selenium==4.0.0 beautifulsoup4 --break-system-packages

CMD ["apache2-foreground"]
