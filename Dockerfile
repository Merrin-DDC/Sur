FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    python3 python3-pip python3-dev \
    curl unzip wget gnupg ca-certificates fonts-thai-tlwg \
    && rm -rf /var/lib/apt/lists/*

# ติดตั้ง Chrome และ ChromeDriver ที่ตรงกัน
RUN wget -q -O - https://dl.google.com/linux/linux_signing_key.pub | apt-key add - && \
    echo "deb [arch=amd64] http://dl.google.com/linux/chrome/deb/ stable main" > /etc/apt/sources.list.d/google-chrome.list && \
    apt-get update && apt-get install -y google-chrome-stable && \
    DRIVER_VERSION=$(curl -s "https://googlechromelabs.github.io/chrome-for-testing/last-known-good-versions-with-downloads.json" | \
      python3 -c "import sys, json; print(json.load(sys.stdin)['channels']['Stable']['version'])") && \
    wget -O /tmp/chromedriver.zip "https://edgedl.me.gvt1.com/edgedl/chrome/chrome-for-testing/${DRIVER_VERSION}/linux64/chromedriver-linux64.zip" && \
    unzip /tmp/chromedriver.zip -d /tmp/chromedriver && \
    mv /tmp/chromedriver/chromedriver-linux64/chromedriver /usr/local/bin/chromedriver && \
    chmod +x /usr/local/bin/chromedriver && \
    rm -rf /tmp/chromedriver*

# ติดตั้ง Python packages
RUN python3 -m pip install --upgrade pip --break-system-packages && \
    pip3 install selenium==4.0.0 beautifulsoup4 --break-system-packages

ENV CHROME_BIN=/usr/bin/google-chrome
ENV CHROMEDRIVER=/usr/local/bin/chromedriver

COPY . /var/www/html/
WORKDIR /var/www/html

CMD ["apache2-foreground"]
