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
            max-width: 900px;
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
            padding: 10px 12px;
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
                <div><b>Pelanggan:</b> {{ $order->nama }}</div>
                <div><b>Alamat:</b> {{ $order->alamat_pengiriman }}</div>
                <div><b>Telepon:</b> {{ $order->no_telp }}</div>
                <div><b>Outlet:</b> {{ $order->outlet->lokasi ?? '-' }}</div>
                <div>
                    <b>Tanggal Pengiriman:</b>
                    @php
                        use Carbon\Carbon;
                        $hari = Carbon::parse($order->tanggal_pengiriman)->locale('id')->isoFormat('dddd');
                    @endphp
                    {{ $hari }}, {{ $order->tanggal_pengiriman ?? '-' }}
                    @if($order->jam_pengiriman)
                        ({{ $order->jam_pengiriman }})
                    @endif
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
        <table>
            <thead>
                <tr>
                    <th style="width:40px;">No.</th>
                    <th style="width:110px;">Nama</th>
                    <th style="width:180px;">Alamat</th>
                    <th style="width:220px;">Produk</th>
                    <th style="width:90px;">Status</th>
                    <th style="width:140px;">Pengiriman</th>
                    <th style="width:120px;">Catatan Admin</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>{{ $order->nama }}</td>
                    <td>{{ $order->alamat_pengiriman }}</td>
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
                        @elseif($order->jumlah_bayar == 0)
                            <span style="color:red;font-weight:bold;">Belum Bayar</span>
                        @else
                            <span style="color:orange;font-weight:bold;">DP</span>
                        @endif
                    </td>
                    <td>
                        @php
                            $hari = Carbon::parse($order->tanggal_pengiriman)->locale('id')->isoFormat('dddd');
                        @endphp
                        {{ $hari }}, {{ $order->tanggal_pengiriman ?? '-' }}
                        @if($order->jam_pengiriman)
                        ({{ $order->jam_pengiriman }})
                        @endif
                    </td>
                    <td>
                        {{ $order->keterangan_staf ?? '-' }}
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