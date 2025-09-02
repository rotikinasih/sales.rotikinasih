<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Distribusi Produk - {{ $tanggal }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }

        table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    border: 1px solid #333; /* Semua sisi */
}

th, td {
    border: 1px solid #333;
    padding: 6px 8px;
    vertical-align: top;
    word-break: break-word;
}

th {
    background: #eee;
}

th.catatan-admin, td.catatan-admin {
    max-width: 120px;
    word-break: break-word;
}

/* Pastikan kolom terakhir tetap punya border */
td:last-child, th:last-child {
    border-right: 1px solid #333 !important;
}


        @media print {
            @page { size: A4 landscape; }
            body { width: 297mm !important; }
        }
    </style>
</head>
<body>
    @php
        $hariIndo = [
            'Sunday' => 'Minggu',
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
        ];
        $carbon = \Carbon\Carbon::parse($tanggal);
        $hari = $hariIndo[$carbon->format('l')] ?? $carbon->format('l');
    @endphp

    <h3>Distribusi Produk - {{ $tanggal }} <br>
        <span style="font-size:14px;font-weight:normal;">Hari: {{ $hari }}</span>
    </h3>

    <table>
        <thead>
            <tr>
                <th style="width:28px;">No</th>
                <th style="width:80px;">Kode Distribusi</th>
                <th style="width:80px;">Pelanggan</th>
                <th style="width:90px;">Alamat</th>
                <th style="width:80px;">No. Telp</th>
                <th style="width:80px;">Outlet</th>
                <th>Produk</th>
                <th style="width:90px;">Kendaraan</th>
                <th style="width:90px;">Jam Pengiriman</th>
                <th class="catatan-admin" style="width:120px;">Catatan Admin</th>
            </tr>
        </thead>
        <tbody>
            @foreach($monitoringOrders as $i => $item)
            <tr>
                <td>{{ $i+1 }}</td>
                <td>{{ $item->orderPenjualan->kode_distribusi ?? '-' }}</td>
                <td>{{ $item->orderPenjualan->nama ?? '-' }}</td>
                <td style="max-width:90px;">{{ $item->orderPenjualan->alamat_pengiriman ?? '-' }}</td>
                <td>{{ $item->orderPenjualan->no_telp ?? '-' }}</td>
                <td>{{ $item->orderPenjualan->outlet->lokasi ?? '-' }}</td>
                <td>
                    <ul>
                        @foreach($item->orderPenjualan->details as $detail)
                        <li>{{ $detail->master_produk->nama_produk ?? '-' }} ({{ $detail->jumlah_beli }})</li>
                        @endforeach
                    </ul>
                </td>
                <td>
                    {{ $item->distribusiProduk->master_kendaraan->kode_kendaraan ?? '-' }}<br>
                    <small>
                        {{ $item->distribusiProduk->master_kendaraan->merk_kendaraan ?? '-' }}
                        -
                        {{ $item->distribusiProduk->master_kendaraan->driver ?? '-' }}
                    </small>
                </td>
                <td>{{ $item->orderPenjualan->jam_pengiriman ?? '-' }}</td>
                <td class="catatan-admin">{{ $item->orderPenjualan->keterangan_staf ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>
