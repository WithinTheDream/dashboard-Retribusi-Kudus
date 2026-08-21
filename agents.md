# Development Rules - Retribusi Sampah Kabupaten Kudus

## Architecture

- Backend menggunakan Laravel.
- Admin dashboard menggunakan Blade.
- API Laravel digunakan oleh aplikasi Flutter.
- Database menjadi source of truth.
- Flutter tidak boleh menyimpan business logic utama.
- Business logic utama harus berada di backend.

## Roles

System roles:

- super_admin
- admin_dinas
- petugas
- bendahara
- pimpinan
- user

## Permissions

- Gunakan permission untuk authorization.
- Jangan hanya mengandalkan role checks untuk fitur baru.
- Jangan membuat permission baru jika permission existing dapat digunakan.
- Route, controller action, dan menu harus mengikuti permission.

## Dynamic System

- Jangan hard-code tarif.
- Jangan hard-code jenis retribusi.
- Jangan hard-code wilayah.
- Jangan hard-code konfigurasi aplikasi.
- Data operasional harus berasal dari database.
- Perubahan konfigurasi operasional harus dapat dilakukan melalui admin.

## Existing Codebase

- Selalu periksa controller, model, migration, route, dan Blade yang sudah ada sebelum membuat file baru.
- Jangan membuat duplicate controller.
- Jangan membuat duplicate model.
- Jangan menghapus implementasi partner tanpa alasan teknis.
- Pertahankan naming convention yang sudah digunakan project.
- Jangan mengubah migration existing tanpa kebutuhan yang jelas.

## Database

- Jangan menjalankan migrate:fresh pada database yang berisi data penting.
- Gunakan migration baru untuk perubahan schema.
- Gunakan foreign key dan relationship yang sesuai.
- Jangan menggunakan nama manusia sebagai primary identifier.

## Business Rules

- Pengajuan harus melalui proses verifikasi.
- User yang mendaftar belum otomatis menjadi wajib retribusi aktif.
- Tarif harus memiliki periode.
- Tagihan harus menggunakan tarif yang berlaku.
- Pembayaran harus dapat diaudit.
- Status pembayaran tidak boleh diubah sembarangan.

## Security

- Jangan menaruh password dalam source code produksi.
- Jangan mengekspos secret atau credential.
- Validasi semua input.
- Gunakan authorization sebelum operasi sensitif.
- File upload harus divalidasi.

## Testing

After significant backend changes:

1. Run migrations if schema changed.
2. Run relevant tests.
3. Run `php artisan route:list`.
4. Check affected Blade views.
5. Verify authorization.
6. Review the Git diff before committing.

## Git

- Make focused commits.
- Do not overwrite another developer's work.
- Pull/fetch before starting work.
- Do not force push shared branches without agreement.
- Never commit `.env`.

## Flutter

- Flutter consumes Laravel API.
- Do not duplicate business rules unnecessarily in Flutter.
- API contracts must remain consistent.