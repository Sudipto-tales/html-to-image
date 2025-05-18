
# HTML to PDF & Image Generation API (Laravel + Browsershot)

This Laravel API allows you to convert raw HTML into a **PDF** and a **PNG image** using [Spatie Browsershot](https://github.com/spatie/browsershot), powered by headless Chrome.

---

## 🚀 API Endpoint

**URL:**  
```

POST /api/generate-file

````

---

## 🔐 Authentication

The API uses a simple API key system.

### Header:

| Key        | Value               |
|------------|---------------------|
| X-API-KEY  | `your_api_key_here` |

Set your key in the `.env` file:

```env
API_ACCESS_KEY=your_secret_key
````

---

## 📝 Request Parameters

| Parameter     | Type     | Required | Description                                                                 |
| ------------- | -------- | -------- | --------------------------------------------------------------------------- |
| `html`        | `string` | ✅ Yes    | The raw HTML content to be converted.                                       |
| `format`      | `string` | ❌ No     | Page format for PDF. Options: `A4`, `A3`, `Letter`, `Legal`. Default: `A4`. |
| `orientation` | `string` | ❌ No     | PDF orientation: `portrait` or `landscape`. Default: `portrait`.            |
| `margin`      | `int`    | ❌ No     | Margin in pixels. Default: `10`.                                            |
| `width`       | `int`    | ❌ No     | Width for PNG image rendering. Default: `1200`.                             |
| `height`      | `int`    | ❌ No     | Height for PNG image rendering. Default: `700`.                             |

---

## 📤 Example cURL Request

```bash
curl -X POST http://yourdomain.com/api/generate-file \
     -H "Content-Type: application/json" \
     -H "X-API-KEY: your_api_key_here" \
     -d '{
           "html": "<h1>Hello PDF!</h1>",
           "format": "A4",
           "orientation": "portrait",
           "margin": 10,
           "width": 1200,
           "height": 700
         }'
```

---

## ✅ Successful Response

```json
{
  "message": "Files generated successfully",
  "pdf_url": "https://yourdomain.com/storage/generated_<hash>.pdf",
  "image_url": "https://yourdomain.com/storage/generated_<hash>.png"
}
```

---

## 📁 File Storage

Files are saved in Laravel’s `storage/app/public` directory and are accessible via:

```
https://yourdomain.com/storage/{filename}
```

Ensure you have run:

```bash
php artisan storage:link
```

---

## 🔄 Duplicate Requests

If the same HTML and options are passed again, the API reuses existing files (based on hash):

```json
{
  "message": "Files already exist",
  "pdf_url": "...",
  "image_url": "..."
}
```

---

## ⚙️ Requirements

* PHP 8.0+
* Laravel 9+
* [Node.js](https://nodejs.org/) & [Puppeteer](https://pptr.dev/) (Browsershot requirement)
* Google Chrome or Chromium installed

Install Browsershot dependencies:

```bash
composer require spatie/browsershot
npm install puppeteer --save
```

---

## 🛠 Troubleshooting

* Make sure Chrome/Chromium is installed.
* If Puppeteer can't find Chrome, you can specify its path:

```php
Browsershot::html($html)->setChromePath('/path/to/chrome');
```

* Ensure `storage/` folder is writable and `storage:link` is set up.

---

## 📄 License

MIT © 2025

