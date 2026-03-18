# Crawl CV — Job Scraper

Crawl dữ liệu tuyển dụng từ 3 nguồn: **TopCV**, **CareerViet**, **VietnamWorks**.
Output: file Excel gồm 3 sheet (Cover, Danh sách công việc, Thống kê).

---

## Cấu trúc

```
crawl-cv/
├── src/
│   ├── topcv_scraper_api.py       # TopCV — JSON API + Chromium fallback
│   ├── careerviet_scraper.py      # CareerViet — multi-thread HTML
│   └── vietnamworks_scraper.py    # VietnamWorks — sitemap + parallel
├── data/                          # Output Excel
├── requirements.txt
└── venv/
```

---

## Cài đặt

```bash
python -m venv venv
source venv/bin/activate
pip install -r requirements.txt
```

---

## Lệnh chạy

### TopCV

```bash
# 200 trang (~10.000 jobs), có Chuyên môn
source venv/bin/activate && python src/topcv_scraper_api.py --pages 200 --output topcv_full.xlsx

# Toàn bộ dữ liệu (tự dừng khi hết)
source venv/bin/activate && python src/topcv_scraper_api.py --all --output topcv_full.xlsx

# Không lấy Chuyên môn (nhanh hơn ~5x)
source venv/bin/activate && python src/topcv_scraper_api.py --all --no-industry --output topcv_full.xlsx
```

### CareerViet

```bash
source venv/bin/activate && python src/careerviet_scraper.py --workers 20 --output careerviet_all_jobs.xlsx
```

### VietnamWorks

```bash
source venv/bin/activate && python src/vietnamworks_scraper.py --workers 20 --output vietnamworks_all_jobs.xlsx
```

---

## Tham số

### TopCV (`topcv_scraper_api.py`)

| Tham số | Mặc định | Mô tả |
|---|---|---|
| `--pages N` | 10 | Số trang cần scrape (50 jobs/trang) |
| `--all` | — | Crawl toàn bộ, tự dừng khi hết data |
| `--output FILE` | `topcv_jobs_api.xlsx` | Tên file Excel đầu ra |
| `--delay N` | 2 | Giây chờ giữa các request |
| `--no-industry` | — | Bỏ qua cột Chuyên môn (nhanh hơn) |

### CareerViet (`careerviet_scraper.py`)

| Tham số | Mặc định | Mô tả |
|---|---|---|
| `--pages N` | tất cả | Giới hạn số trang |
| `--workers N` | 15 | Số luồng song song |
| `--output FILE` | `careerviet_jobs.xlsx` | Tên file Excel đầu ra |

### VietnamWorks (`vietnamworks_scraper.py`)

| Tham số | Mặc định | Mô tả |
|---|---|---|
| `--limit N` | tất cả | Giới hạn số job |
| `--workers N` | 15 | Số luồng song song |
| `--output FILE` | `vietnamworks_jobs.xlsx` | Tên file Excel đầu ra |

---

## Ghi chú

- **TopCV**: Dùng JSON API. Khi không tìm được Chuyên môn qua HTTP, tự động fallback sang Chromium để render JS. Tỷ lệ lấy được Chuyên môn ~88%.
- **CareerViet / VietnamWorks**: Multi-thread, tốc độ phụ thuộc vào `--workers`.
- Chromium/Chrome cần được cài sẵn trên máy cho TopCV fallback.
