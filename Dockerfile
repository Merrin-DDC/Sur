FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    python3 python3-pip python3-dev \
    curl unzip wget gnupg ca-certificates fonts-thai-tlwg \
    && rm -rf /var/lib/apt/lists/*
COPY . /var/www/html/
WORKDIR /var/www/html
