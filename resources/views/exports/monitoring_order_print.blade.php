<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Monitoring Order - {{ $tanggal }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        .container {
            max-width: 800px;
            margin: auto;
            padding: 24px;
        }

        .header {
            text-align: center;
            margin-bottom: 16px;
        }

        .logo {
            float: left;
            height: 50px;
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
        <div class="header">
            <h3>MONITORING ORDER PRODUK</h3>
            <div><b>Tanggal Produksi :</b> {{ $tanggal }}</div>
        </div>
        <div>
            <img src="{{ asset('images/logo.png') }}" alt="Logo" style="height:60px;">
            <div class="info">
                <div><b>Dicetak:</b> {{ now()->format('Y-m-d H:i') }}</div>
                <div><b>User:</b> {{ Auth::user()->name ?? '-' }}</div>
            </div>
            <div class="clearfix"></div>
        </div>

        <table>
            <thead>
                <tr>
                    <th style="width: 40px;">No</th>
                    <th>Kode Produk</th>
                    <th>Nama Produk</th>
                    <th>Nama Pemesan</th>
                    <th>Jam Pengiriman</th>
                    <th>Keterangan</th>
                    <th>Keterangan Staf</th>
                    <th style="width: 80px;">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @php $no=1; $grandTotal=0; @endphp
                @foreach($rekap as $row)
                <tr>
                    <td style="text-align:center;">{{ $no++ }}</td>
                    <td>{{ $row['kode_produk'] }}</td>
                    <td>{{ $row['nama_produk'] }}</td>
                    <td>{{ $row['nama_pemesan'] }}</td>
                    <td>{{ $row['jam_pengiriman'] }}</td>
                    <td>{{ $row['keterangan'] }}</td>
                    <td>{{ $row['keterangan_staf'] }}</td>
                    <td style="text-align:center;">{{ $row['jumlah'] }}</td>
                </tr>
                @php $grandTotal += $row['jumlah']; @endphp
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="7" style="text-align:right;">Total</td>
                    <td style="text-align:center;">{{ $grandTotal }}</td>
                </tr>
            </tfoot>
        </table>

        <!-- Tabel Rekap Total Per Produk -->
        <h4 style="margin-top:32px;">Total Per Produk</h4>
        <table>
            <thead>
                <tr>
                    <th style="width:40px;">No</th>
                    <th>Kode & Nama Produk</th>
                    <th style="width:80px;">Total Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @php $no=1; $grandTotal=0; @endphp
                @foreach($totalPerProduk as $produk => $jumlah)
                <tr>
                    <td style="text-align:center;">{{ $no++ }}</td>
                    <td>{{ $produk }}</td>
                    <td style="text-align:center;">{{ $jumlah }}</td>
                </tr>
                @php $grandTotal += $jumlah; @endphp
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2" style="text-align:right;">Grand Total</td>
                    <td style="text-align:center;">{{ $grandTotal }}</td>
                </tr>
            </tfoot>
        </table>

        <div class="footer">
            Crosscheck Kembali
        </div>
    </div>
    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>

</html>
