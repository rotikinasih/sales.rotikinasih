<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Struk Transaksi</title>
    <style>
        @page {
            size: auto;
            margin: 0mm;
        }
        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 11px;
            max-width: 58mm;
            width: 100%;
            margin: auto;
        }
        .header { text-align: center; margin-bottom: 8px; }
        .footer { text-align: center; margin-top: 8px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 2px 0; }
        .total { font-weight: bold; border-top: 1px dashed #333; }
        .line { border-top: 1px dashed #333; margin: 4px 0; }
        .logo { height: 32px; margin-bottom: 4px; }
        .info { margin-bottom: 6px; }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ asset('images/logo.png') }}" class="logo"><br><br>
        
        {{ \App\Models\MasterOutlet::find($outlet_id)->lokasi ?? '-' }}<br>
        <div class="info">
            Tanggal Pembelian: {{ $transaksi->created_at->format('Y-m-d H:i') }}<br>
            Kasir: {{ Auth::user()->name ?? '-' }}<br>
            @php
                // Coba ambil nama pelanggan dari field transaksi->pelanggan jika ada
                $namaPelanggan = $transaksi->pelanggan ?? '-';
            @endphp
            Pelanggan: {{ $namaPelanggan }}
        </div>
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
            @php $total = 0; @endphp
            @foreach($transaksi->items as $item)
                @php
                    $sub = $item->jumlah * $item->harga_jual;
                    $total += $sub;
                @endphp
                <tr>
                    <td>{{ $item->produk->nama_produk }}</td>
                    <td style="text-align:center;">{{ $item->jumlah }}</td>
                    <td style="text-align:right;">{{ number_format($item->harga_jual,0,',','.') }}</td>
                    <td style="text-align:right;">{{ number_format($sub,0,',','.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="line"></div>
    <table>
        <tr>
            <td>Total</td>
            <td colspan="3" style="text-align:right;">
                {{ number_format($total,0,',','.') }}
            </td>
        </tr>
        <tr>
            <td>Diskon</td>
            <td colspan="3" style="text-align:right;">
                {{ number_format($transaksi->diskon,0,',','.') }}
            </td>
        </tr>
        <tr class="total">
            <td>Bayar</td>
            <td colspan="3" style="text-align:right;">
                {{ number_format($total - $transaksi->diskon,0,',','.') }}
            </td>
        </tr>
        <tr>
            <td>Metode</td>
            <td colspan="3" style="text-align:right;">
                {{ ucfirst($transaksi->pembayaran) }}
            </td>
        </tr>
        <tr>
            <td>Jumlah Bayar</td>
            <td colspan="3" style="text-align:right;">
                {{ number_format($transaksi->jumlah_bayar,0,',','.') }}
            </td>
        </tr>
        <tr>
            <td>Kembali</td>
            <td colspan="3" style="text-align:right;">
                {{ number_format(max(0, $transaksi->jumlah_bayar - ($total - $transaksi->diskon)),0,',','.') }}
            </td>
        </tr>
    </table>
    <div class="footer">
        --- Terima Kasih ---<br>
        Simpan struk ini sebagai bukti pembayaran.
    </div>
    <script>
        window.onload = function () {
            window.print();
            setTimeout(() => window.close(), 500);
        }
    </script>
</body>
</html>
