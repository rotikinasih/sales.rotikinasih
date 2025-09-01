<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Struk Transaksi</title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 11px;
            width: 220px;
            margin: auto;
        }
        .header { text-align: center; margin-bottom: 8px; }
        .footer { text-align: center; margin-top: 8px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 2px 0; }
        .total { font-weight: bold; border-top: 1px dashed #333; }
        .line { border-top: 1px dashed #333; margin: 4px 0; }
    </style>
</head>
<body>
    <div class="header">
        <b>{{ config('app.name', 'TOKO') }}</b><br>
        {{ \App\Models\MasterOutlet::find($outlet_id)->lokasi ?? '-' }}<br>
        Tanggal: {{ $tanggal }}<br>
        Kasir: {{ Auth::user()->name ?? '-' }}
    </div>
    <div class="line"></div>
    <table>
        <thead>
            <tr>
                <th>Produk</th>
                <th>Qty</th>
                <th>Harga</th>
                <th>Sub</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transaksi->items as $item)
            <tr>
                <td>{{ $item->produk->nama_produk }}</td>
                <td style="text-align:center;">{{ $item->jumlah }}</td>
                <td style="text-align:right;">{{ number_format($item->harga_jual,0,',','.') }}</td>
                <td style="text-align:right;">{{ number_format($item->jumlah * $item->harga_jual,0,',','.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="line"></div>
    <table>
        <tr>
            <td>Total</td>
            <td style="text-align:right;" colspan="3">{{ number_format($transaksi->total,0,',','.') }}</td>
        </tr>
        <tr>
            <td>Diskon</td>
            <td style="text-align:right;" colspan="3">{{ number_format($transaksi->diskon,0,',','.') }}</td>
        </tr>
        <tr class="total">
            <td>Bayar</td>
            <td style="text-align:right;" colspan="3">{{ number_format($transaksi->jumlah_bayar,0,',','.') }}</td>
        </tr>
    </table>
    <div class="footer">
        --- Terima Kasih ---
    </div>
    <script>
        window.onload = function () {
            window.print();
        }
    </script>
</body>
</html>