# Cara Menggunakan Table Pagination & Sorting Dinamis

## Penjelasan Umum
Saya telah membuat sistem pagination dan sorting yang dinamis dan dapat digunakan kembali untuk semua table di proyek.

## File yang Dibuat:
1. **`resources/views/components/table-pagination.blade.php`** - Component Blade untuk UI pagination
2. **`public/js/table-pagination.js`** - JavaScript helper untuk logic

## Cara Penggunaan

### Step 1: Include JavaScript di Layout Utama
Tambahkan ke file `resources/views/mainlayout/layout.blade.php` di bagian `<head>` atau sebelum closing `</body>`:

```html
<script src="{{ asset('js/table-pagination.js') }}"></script>
```

### Step 2: Setup Table di View

Contoh untuk `customer.blade.php`:

```blade
<!-- Table dengan ID unik -->
<table class="table align-items-center mb-0" id="TicketTable">
  <thead>
    <tr>
      <th>...</th>
    </tr>
  </thead>
  <tbody>
    @foreach($data_ticket as $dataticket)
    <tr>
      <!-- content -->
    </tr>
    @endforeach
  </tbody>
</table>

<!-- Include Pagination Component -->
@include('components.table-pagination', [
    'tableId' => 'TicketTable',
    'totalRecords' => $data_ticket->count(),
    'sortable' => true
])
```

### Step 3: Initialize JavaScript di View

Tambahkan di bagian `<script>` di akhir view file:

```html
<script>
  $(document).ready(function() {
    initTablePagination('TicketTable', 10); // 10 adalah items per page
  });
</script>
```

## Contoh Implementasi Lengkap

File: `resources/views/asigne.blade.php` (sebelum modifikasi):
```blade
<table class="table align-items-center mb-0" id="TicketTable">
  <thead>
    <tr>
      <th>Subject</th>
      <th>Status</th>
    </tr>
  </thead>
  <tbody>
    @foreach($teknisi_data_ticket as $teknisidataticket)
    <tr>
      <td>{{ $teknisidataticket->subject }}</td>
      <td>{{ $teknisidataticket->status }}</td>
    </tr>
    @endforeach
  </tbody>
</table>
```

Setelah modifikasi:
```blade
<!-- Table dengan ID unik -->
<table class="table align-items-center mb-0" id="TechnicianTicketTable">
  <thead>
    <tr>
      <th>Subject</th>
      <th>Status</th>
    </tr>
  </thead>
  <tbody>
    @foreach($teknisi_data_ticket as $teknisidataticket)
    <tr>
      <td>{{ $teknisidataticket->subject }}</td>
      <td>{{ $teknisidataticket->status }}</td>
    </tr>
    @endforeach
  </tbody>
</table>

<!-- Include Pagination Component -->
@include('components.table-pagination', [
    'tableId' => 'TechnicianTicketTable',
    'totalRecords' => $teknisi_data_ticket->count(),
    'sortable' => true
])

<script>
  $(document).ready(function() {
    initTablePagination('TechnicianTicketTable', 10);
  });
</script>
```

## Parameter Component

| Parameter | Type | Default | Deskripsi |
|-----------|------|---------|-----------|
| `tableId` | String | 'DataTable' | ID unik dari table HTML |
| `totalRecords` | Integer | 0 | Total jumlah record dalam table |
| `sortable` | Boolean | true | Apakah menampilkan sorting controls |

## Fitur yang Tersedia

✅ **Pagination** - 10 items per page (dapat disesuaikan)
✅ **Sorting** - Terbaru/Terlama (berdasarkan format sp-xxxddmmyy)
✅ **Search** - Pencarian real-time
✅ **Dinamis** - Dapat digunakan untuk semua table
✅ **Responsive** - Styling konsisten dengan Argon Dashboard

## Catatan Penting

1. **Setiap table harus memiliki ID unik** - Jangan gunakan ID yang sama untuk multiple table
2. **Format Ticket** - Sorting otomatis berdasarkan format `sp-xxxddmmyy`
3. **Search Input** - Jika menggunakan search, pastikan ID-nya adalah `#search`
4. **DataTables Library** - Pastikan jQuery dan DataTables sudah di-include

## File yang Perlu Dimodifikasi

1. `resources/views/asigne.blade.php` - Add pagination
2. `resources/views/ListTicket.blade.php` - Add pagination
3. `resources/views/escalation.blade.php` - Add pagination
4. `resources/views/admin.blade.php` - Add pagination (jika ada table)
5. `resources/views/ManageUser.blade.php` - Add pagination (jika ada table)
6. Dan table lainnya yang belum memiliki pagination

## Tips Implementasi

- Mulai dari file satu per satu
- Test setiap implementasi untuk memastikan berjalan dengan baik
- Sesuaikan `itemsPerPage` parameter sesuai kebutuhan
- Pastikan tidak ada konflik ID table

---

Untuk pertanyaan lebih lanjut atau implementasi di file tertentu, beritahu saya!
