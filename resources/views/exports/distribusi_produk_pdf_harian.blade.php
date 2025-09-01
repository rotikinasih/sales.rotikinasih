<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Distribusi Produk - {{ $tanggal }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 16px; }
        th, td { border: 1px solid #333; padding: 6px 8px; vertical-align: top; }
        th { background: #eee; }
    </style>
</head>
<body>
    <h3>Distribusi Produk - {{ $tanggal }}</h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Distribusi</th>
                <th>Pelanggan</th>
                <th>Alamat</th>
                <th>Produk</th>
                <th>Kendaraan</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($monitoringOrders as $i => $item)
            <tr>
                <td>{{ $i+1 }}</td>
                <td>{{ $item->orderPenjualan->kode_distribusi ?? '-' }}</td>
                <td>{{ $item->orderPenjualan->nama ?? '-' }}</td>
                <td>{{ $item->orderPenjualan->alamat_pengiriman ?? '-' }}</td>
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
                <td>
                    @if($item->distribusiProduk?->status_distribusi == 1)
                        Sedang Distribusi
                    @elseif($item->distribusiProduk?->status_distribusi == 2)
                        Selesai Distribusi
                    @else
                        Belum Distribusi
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>