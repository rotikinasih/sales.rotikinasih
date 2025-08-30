<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Monitoring Stok Kasir - {{ $tanggal }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #333; padding: 6px 8px; }
        th { background: #eee; }
        tfoot td { font-weight: bold; background: #17a2b8; color: #fff; }
    </style>
</head>
<body>
    <h3>Monitoring Stok Kasir<br><small>Tanggal: {{ $tanggal }}</small></h3>
    <table>
        <thead>
            <tr>
                <th>Nama Produk</th>
                <th>Harga</th>
                <th>Stok Awal</th>
                <th>Stok Masuk</th>
                <th>Stok Terjual</th>
                <th>Retur</th>
                <th>Final Stok</th>
                <th>Total Nominal Terjual</th>
                <th>Total Nominal Stok Akhir</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $item)
            <tr>
                <td>{{ $item['nama_produk'] }}</td>
                <td>Rp {{ number_format($item['harga'],0,',','.') }}</td>
                <td>{{ $item['stok_awal'] }}</td>
                <td>{{ $item['stok_masuk'] }}</td>
                <td>{{ $item['stok_terjual'] }}</td>
                <td>{{ $item['retur'] }}</td>
                <td>{{ $item['final_stok'] }}</td>
                <td>Rp {{ number_format($item['harga_terjual'],0,',','.') }}</td>
                <td>Rp {{ number_format($item['harga_final'],0,',','.') }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td>Total</td>
                <td></td>
                <td>{{ $total['stok_awal'] }}</td>
                <td>{{ $total['stok_masuk'] }}</td>
                <td>{{ $total['stok_terjual'] }}</td>
                <td>{{ $total['retur'] }}</td>
                <td>{{ $total['final_stok'] }}</td>
                <td>Rp {{ number_format($total['harga_terjual'],0,',','.') }}</td>
                <td>Rp {{ number_format($total['harga_final'],0,',','.') }}</td>
            </tr>
        </tfoot>
    </table>
</body>
</html>