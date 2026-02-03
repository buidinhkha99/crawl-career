#!/bin/bash

# Build và khởi động container
docker compose up -d --build

# Đợi để PHP container khởi động hoàn tất
echo "Đợi 10 giây để container khởi động..."
sleep 10

# Chạy seeder TopCV
echo "Chạy TopCV Seeder..."
docker compose exec php php artisan db:seed --class=CrawlTopCVSeeder

# Chạy seeder CareerViet
echo "Chạy CareerViet Seeder..."
docker compose exec php php artisan db:seed --class=CrawlCareerVietSeeder

# Chạy seeder VietNamWorks
echo "Chạy VietNamWorks Seeder..."
docker compose exec php php artisan db:seed --class=CrawlVietNamWorksSeeder

echo "Hoàn tất!" 