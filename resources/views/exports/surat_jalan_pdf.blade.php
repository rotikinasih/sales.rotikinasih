<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Surat Jalan</title>
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
            vertical-align: top;
        }

        th {
            background: #eee;
        }

        ul {
            margin: 0;
            padding-left: 18px;
        }

        .footer {
            margin-top: 32px;
        }

        .ttd-row {
            display: flex;
            justify-content: space-between;
            margin-top: 40px;
        }

        .ttd-col {
            width: 40%;
            text-align: center;
        }

        .ttd-line {
            border-bottom: 1px solid #333;
            height: 40px;
            margin: 16px 0 4px 0;
            width: 120px;
            margin-left: auto;
            margin-right: auto;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h3>PESANAN PENJUALAN</h3>
        </div>
        <div>
            <img src="{{ public_path('images/logo.png') }}" class="logo">
            <div class="info">
                <div><b>Tanggal Pemesanan:</b> {{ \Carbon\Carbon::parse($order->created_at)->format('Y-m-d') }}</div>
                <div><b>Pelanggan:</b> {{ $order->nama }}</div>
                <div><b>Alamat:</b> {{ $order->alamat_pengiriman }}</div>
                <div><b>Telepon:</b> {{ $order->no_telp }}</div>
            </div>
            <div class="clearfix"></div>
        </div>
        <table>
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Tanggal</th>
                    <th>Nama</th>
                    <th>Produk</th>
                    <th>Status</th>
                    <th>Pengiriman</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>{{ \Carbon\Carbon::parse($order->created_at)->format('Y-m-d') }}</td>
                    <td>{{ $order->nama }}</td>
                    <td>
                        <ul>
                            @foreach($order->details as $detail)
                            <li>{{ $detail->master_produk->nama_produk ?? '-' }} ({{ $detail->jumlah_beli }})</li>
                            @endforeach
                        </ul>
                    </td>
                    <td>
                        @if($order->status_pembayaran == 2)
                        <span style="color:green;font-weight:bold;">Lunas</span>
                        @else
                        <span style="color:orange;font-weight:bold;">DP</span>
                        @endif
                    </td>
                    <td>
                        {{ $order->tanggal_pengiriman ?? '-' }}
                        @if($order->jam_pengiriman)
                        ({{ $order->jam_pengiriman }})
                        @endif
                    </td>
                </tr>
            </tbody>
        </table>
        <div class="footer">
            <div style="text-align:center; margin-top:24px; font-weight:bold;">
                Terima kasih telah berbelanja dan sampai jumpa
            </div>
            <div style="margin-top: 50px;">
                <table style="width: 100%; text-align: center;">
                   <tr>
                        <td>
                            <div style="margin-bottom: 75px;">
                                Penerima,
                            </div>

                            <div>
                                ( {{ $distribusi->nama_penerima ?? '___________________' }} )
                            </div>
                        </td>

                        <td>
                            <div style="margin-bottom: 60px;">
                                Hormat Kami, <br>
                                Driver
                            </div>
                            <div>
                                <span>( {{ $kendaraan->driver ?? '___________________' }} )</span>

                            </div>
                        </td>
                    </tr>
                </table>
            </div>

        </div>
    </div>
</body>

</html>