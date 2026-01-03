# Summary: Fitur History untuk Prediksi Umur

## Apa yang Telah Dibuat

### 1. Migration: `2026_01_02_000000_create_histories_table.php`

-   Membuat tabel `histories` dengan fields:
    -   user_id (Foreign Key ke users)
    -   name, age, email, phone, address
    -   age_classification_id (Foreign Key ke age_classifications)
    -   timestamps (created_at, updated_at)

### 2. Model: `app/Models/History.php`

-   Model untuk tabel histories
-   Relasi `belongsTo` dengan User
-   Relasi `belongsTo` dengan AgeClassification

### 3. Controller: `app/Http/Controllers/API/HistoryController.php`

-   **index()**: GET /histories - Tampilkan semua history user login
-   **show()**: GET /histories/{id} - Tampilkan history spesifik (hanya milik user)
-   **update()**: PUT/PATCH /histories/{id} - Update history
-   **destroy()**: DELETE /histories/{id} - Hapus history spesifik
-   **clearAll()**: DELETE /histories-clear - Hapus semua history user
-   Semua method dilindungi middleware `auth:sanctum`

### 4. Update: `app/Models/User.php`

-   Menambah relasi `hasMany` dengan History
-   User bisa akses semua history mereka via `$user->histories()`

### 5. Update: `app/Http/Controllers/API/PredictController.php`

-   Method `store()` sekarang otomatis simpan ke history jika user login
-   Jika user tidak login, prediksi tetap dibuat tapi tidak masuk history

### 6. Update: `routes/api.php`

-   Menambah route `apiResource('histories', HistoryController::class)`
-   Menambah route `DELETE /histories-clear` untuk clear all

---

## Cara Kerja

### Skenario 1: User Tidak Login

```
1. POST /predict dengan data
2. Prediksi berhasil dibuat di tabel persons
3. TIDAK MASUK ke tabel histories
4. User bisa lihat prediksi tapi tidak tercatat di riwayat mereka
```

### Skenario 2: User Login

```
1. POST /predict (dengan Authorization: Bearer {token})
2. Prediksi berhasil dibuat di tabel persons
3. SECARA OTOMATIS disimpan ke tabel histories dengan user_id = user yang login
4. User bisa akses riwayat via GET /histories
```

---

## Endpoints yang Tersedia

### Authentication Required ✅ (auth:sanctum)

```
GET     /api/histories              - Ambil semua history user
GET     /api/histories/{id}         - Ambil history spesifik
PUT     /api/histories/{id}         - Update history
DELETE  /api/histories/{id}         - Hapus history spesifik
DELETE  /api/histories-clear        - Hapus semua history
```

### Authentication Optional (Tapi Diperlukan untuk Entry ke History)

```
POST    /api/predict                - Create prediction (masuk history jika login)
GET     /api/predict                - Lihat semua prediction
GET     /api/predict/{id}           - Lihat prediction spesifik
PUT     /api/predict/{id}           - Update prediction
DELETE  /api/predict/{id}           - Hapus prediction
POST    /api/predict/classify       - Prediksi klasifikasi (tanpa simpan)
```

### Auth Endpoints

```
POST    /api/register               - Daftar user baru
POST    /api/login                  - Login (dapatkan token)
```

---

## Fitur Keamanan

✅ **Isolasi User**: Setiap user hanya bisa akses history mereka sendiri
✅ **Protected Endpoints**: Semua history endpoints memerlukan token valid
✅ **Cascade Delete**: Jika user dihapus, history mereka otomatis terhapus
✅ **Transparent Saving**: Prediksi otomatis tersimpan jika user login

---

## Testing

Lihat file [TESTING_HISTORY.md](./TESTING_HISTORY.md) untuk panduan lengkap testing dengan:

-   cURL commands
-   Postman requests
-   Request/response examples
-   Security verification

---

## Database Relations

```
User (1) -----> (Many) History
  ↓
  └── Histories memiliki user_id yang reference ke User.id

History (Many) -----> (1) AgeClassification
  └── Setiap history memiliki age_classification_id
      yang reference ke AgeClassification.id
```

---

## Next Steps (Opsional)

Jika ingin menambah fitur lanjutan:

1. **Pagination** untuk GET /histories
2. **Filter & Search** untuk history (by date, age range, classification)
3. **Statistics** endpoint (avg age, total predictions, etc)
4. **Export** history ke CSV/PDF
5. **Sharing** history antar user dengan permission controls

---
