<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Monitoring Stok Kasir</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        .container {
            max-width: 1000px;
            margin: auto;
            padding: 24px;
        }

        .header {
            text-align: center;
            margin-bottom: 16px;
        }

        .logo {
            float: left;
            height: 60px;
        }

        .info {
            float: right;
            text-align: left;
            font-size: 12px;
        }

        .clearfix {
            clear: both;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 16px;
        }

        th,
        td {
            border: 1px solid #333;
            padding: 6px 8px;
            vertical-align: middle;
        }

        th {
            background: #eee;
            font-weight: bold;
            text-align: center;
        }

        tfoot td {
            font-weight: bold;
            background: #f9f9f9;
        }

        .footer {
            margin-top: 32px;
            text-align: center;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Header Judul -->
        <div class="header">
            <h3>MONITORING STOK KASIR</h3>
            <div><b>Tanggal:</b> {{ $tanggal }}</div>
        </div>

        <!-- Logo + Info User -->
        <div>
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="logo">
            <div class="info">
                <div><b>Dicetak:</b> {{ now()->format('Y-m-d H:i') }}</div>
                <div><b>User:</b> {{ Auth::user()->name ?? '-' }}</div>
            </div>
            <div class="clearfix"></div>
        </div>

        <!-- Tabel Monitoring -->
        <table>
            <thead>
                <tr>
                    <th style="width: 40px;">No</th>
                    <th>Nama Produk</th>
                    <th style="width: 100px;">Harga</th>
                    <th style="width: 80px;">Stok Awal</th>
                    <th style="width: 80px;">Stok Masuk</th>
                    <th style="width: 80px;">Stok Terjual</th>
                    <th style="width: 80px;">Retur</th>
                    <th style="width: 80px;">Final Stok</th>
                    <th style="width: 140px;">Total Nominal Terjual</th>
                    <th style="width: 140px;">Total Nominal Stok Akhir</th>
                </tr>
            </thead>
            <tbody>
                @php $no=1; @endphp
                @foreach($data as $item)
                <tr>
                    <td style="text-align:center;">{{ $no++ }}</td>
                    <td>{{ $item['nama_produk'] }}</td>
                    <td style="text-align:right;">Rp {{ number_format($item['harga'],0,',','.') }}</td>
                    <td style="text-align:center;">{{ $item['stok_awal'] }}</td>
                    <td style="text-align:center;">{{ $item['stok_masuk'] }}</td>
                    <td style="text-align:center;">{{ $item['stok_terjual'] }}</td>
                    <td style="text-align:center;">{{ $item['retur'] }}</td>
                    <td style="text-align:center;">{{ $item['final_stok'] }}</td>
                    <td style="text-align:right;">Rp {{ number_format($item['harga_terjual'],0,',','.') }}</td>
                    <td style="text-align:right;">Rp {{ number_format($item['harga_final'],0,',','.') }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4" style="text-align:right;">Total</td>
                    <td style="text-align:center;">{{ $total['stok_masuk'] }}</td>
                    <td style="text-align:center;">{{ $total['stok_terjual'] }}</td>
                    <td style="text-align:center;">{{ $total['retur'] }}</td>
                    <td style="text-align:center;">{{ $total['final_stok'] }}</td>
                    <td style="text-align:right;">Rp {{ number_format($total['harga_terjual'],0,',','.') }}</td>
                    <td style="text-align:right;">Rp {{ number_format($total['harga_final'],0,',','.') }}</td>
                </tr>
            </tfoot>
        </table>

        <!-- Footer -->
        <div class="footer">
            Pastikan seluruh data sesuai
        </div>
    </div>

    <script>
        window.onload = function () {
            window.print();
        }
    </script>
</body>

</html>
