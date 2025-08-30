<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekap Penjualan - {{ $tanggal }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #999; padding: 6px; text-align: left; }
        th { background-color: #eee; }
    </style>
</head>
<body>
    <h3>Rekap Penjualan Tanggal {{ $tanggal }}</h3>
    <table>
        <thead>
            <tr>
                <th>Kode Transaksi</th>
                <th>Waktu</th>
                <th>Pembayaran</th>
                <th>Total</th>
                <th>Detail Produk</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transaksis as $trx)
                <tr>
                    <td>{{ $trx->kode_transaksi }}</td>
                    <td>{{ $trx->created_at }}</td>
                    <td>{{ ucfirst($trx->pembayaran) }}</td>
                    <td>Rp {{ number_format($trx->total, 0, ',', '.') }}</td>
                    <td>
                        @foreach($trx->items as $item)
                            {{ $item->produk->nama_produk ?? '-' }} x {{ $item->jumlah }}
                            (Rp {{ number_format($item->harga_jual, 0, ',', '.') }})<br>
                        @endforeach
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <h4 style="text-align: right; margin-top: 20px;">Total Penjualan: Rp {{ number_format($total, 0, ',', '.') }}</h4>
</body>
</html>
