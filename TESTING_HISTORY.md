# Panduan Testing Fitur History dan Predict

## Setup Awal

### 1. Register User Baru

```
Method: POST
URL: http://localhost:8000/api/register
Headers:
  Content-Type: application/json
Body (JSON):
{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}
```

**Response:**

```json
{
    "success": true,
    "message": "User berhasil disimpan",
    "data": "John Doe",
    "token": "1|xxxxxxxxxxxxxxxxxxxxxxxxxxx"
}
```

Simpan token ini untuk testing history!

---

## 1. Testing Predict Tanpa Login (Tidak Masuk History)

### POST /predict (Tanpa Token)

```
Method: POST
URL: http://localhost:8000/api/predict
Headers:
  Content-Type: application/json
Body (JSON):
{
  "name": "Alice",
  "age": 25,
  "email": "alice@example.com",
  "phone": "+62812345678",
  "address": "Jakarta"
}
```

**Hasil:** Prediksi berhasil dibuat TAPI tidak masuk ke history karena user tidak login.

---

## 2. Testing Predict Dengan Login (Masuk History)

### POST /predict (Dengan Token)

```
Method: POST
URL: http://localhost:8000/api/predict
Headers:
  Content-Type: application/json
  Authorization: Bearer {TOKEN_DARI_REGISTER}
Body (JSON):
{
  "name": "Bob",
  "age": 30,
  "email": "bob@example.com",
  "phone": "+62812345678",
  "address": "Surabaya"
}
```

**Hasil:** Prediksi berhasil dibuat DAN secara otomatis masuk ke history user John Doe.

---

## 3. Lihat Semua History User Login

### GET /histories

```
Method: GET
URL: http://localhost:8000/api/histories
Headers:
  Authorization: Bearer {TOKEN_DARI_REGISTER}
  Accept: application/json
```

**Response:**

```json
{
    "success": true,
    "message": "Riwayat prediksi berhasil diambil",
    "data": [
        {
            "id": 1,
            "user_id": 1,
            "name": "Bob",
            "age": 30,
            "age_classification_id": 2,
            "email": "bob@example.com",
            "phone": "+62812345678",
            "address": "Surabaya",
            "created_at": "2026-01-02T10:30:00.000000Z",
            "updated_at": "2026-01-02T10:30:00.000000Z",
            "ageClassification": {
                "id": 2,
                "name": "Dewasa",
                "min_age": 18,
                "max_age": 64,
                "description": null,
                "created_at": "2025-12-30T13:59:23.000000Z",
                "updated_at": "2025-12-30T13:59:23.000000Z"
            }
        }
    ],
    "count": 1
}
```

---

## 4. Lihat Detail Riwayat Tertentu

### GET /histories/{id}

```
Method: GET
URL: http://localhost:8000/api/histories/1
Headers:
  Authorization: Bearer {TOKEN_DARI_REGISTER}
  Accept: application/json
```

---

## 5. Update Riwayat

### PUT/PATCH /histories/{id}

```
Method: PUT
URL: http://localhost:8000/api/histories/1
Headers:
  Content-Type: application/json
  Authorization: Bearer {TOKEN_DARI_REGISTER}
Body (JSON):
{
  "age": 35,
  "name": "Bob Updated"
}
```

---

## 6. Hapus Riwayat Tertentu

### DELETE /histories/{id}

```
Method: DELETE
URL: http://localhost:8000/api/histories/1
Headers:
  Authorization: Bearer {TOKEN_DARI_REGISTER}
  Accept: application/json
```

---

## 7. Hapus Semua Riwayat User

### DELETE /histories-clear

```
Method: DELETE
URL: http://localhost:8000/api/histories-clear
Headers:
  Authorization: Bearer {TOKEN_DARI_REGISTER}
  Accept: application/json
```

---

## 8. Keamanan - User Hanya Bisa Akses History Mereka Sendiri

Ketika user John Doe (ID 1) mencoba akses history user lain:

```
Method: GET
URL: http://localhost:8000/api/histories/999
Headers:
  Authorization: Bearer {TOKEN_JOHN_DOE}
  Accept: application/json
```

**Response:**

```json
{
    "success": false,
    "message": "Riwayat tidak ditemukan"
}
```

Bahkan jika history dengan ID 999 milik user lain, user John Doe tidak bisa akses!

---

## 9. Test cURL Commands

```bash
# Register user baru
curl -X POST http://localhost:8000/api/register \
  -H "Content-Type: application/json" \
  -d '{"name":"John","email":"john@test.com","password":"password123","password_confirmation":"password123"}'

# Simpan token dari response, misalnya TOKEN=1|abc123...

# Predict dengan login (masuk history)
curl -X POST http://localhost:8000/api/predict \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer TOKEN" \
  -d '{"name":"Test User","age":28,"email":"test@test.com"}'

# Get semua history
curl -X GET http://localhost:8000/api/histories \
  -H "Authorization: Bearer TOKEN" \
  -H "Accept: application/json"

# Get history spesifik
curl -X GET http://localhost:8000/api/histories/1 \
  -H "Authorization: Bearer TOKEN" \
  -H "Accept: application/json"

# Update history
curl -X PUT http://localhost:8000/api/histories/1 \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer TOKEN" \
  -d '{"age":32}'

# Delete history
curl -X DELETE http://localhost:8000/api/histories/1 \
  -H "Authorization: Bearer TOKEN" \
  -H "Accept: application/json"

# Delete semua history
curl -X DELETE http://localhost:8000/api/histories-clear \
  -H "Authorization: Bearer TOKEN" \
  -H "Accept: application/json"
```

---

## Fitur Keamanan

✅ **User hanya bisa melihat history mereka sendiri**
✅ **User tidak bisa lihat history user lain**
✅ **Prediksi hanya masuk history jika user login**
✅ **User logout berarti tidak bisa akses history tanpa token valid**
✅ **Endpoint history dilindungi middleware auth:sanctum**

---

## Database Structure

### Tabel: histories

| Column                | Type      | Notes                                 |
| --------------------- | --------- | ------------------------------------- |
| id                    | INT       | Primary Key                           |
| user_id               | INT       | Foreign Key ke users (cascade delete) |
| name                  | VARCHAR   | Nama orang yang diprediksi            |
| age                   | INT       | Umur yang diprediksi                  |
| age_classification_id | INT       | Foreign Key ke age_classifications    |
| email                 | VARCHAR   | Email (nullable)                      |
| phone                 | VARCHAR   | Nomor telepon (nullable)              |
| address               | VARCHAR   | Alamat (nullable)                     |
| created_at            | TIMESTAMP | Waktu dibuat                          |
| updated_at            | TIMESTAMP | Waktu diupdate                        |
