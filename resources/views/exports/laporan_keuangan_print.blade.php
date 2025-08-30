<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Keuangan Harian</title>
    <style>
    body { font-family: Arial, sans-serif; font-size: 12px; }
    .container { max-width: 900px; margin: auto; padding: 24px; }
    .header { text-align: center; margin-bottom: 16px; }
    .logo { float: left; height: 60px; }
    .info { float: right; text-align: left; font-size: 12px; }
    .clearfix { clear: both; }
    table { border-collapse: collapse; width: 100%; margin-top: 16px; }
    th, td { border: 1px solid #333; padding: 6px 8px; }
    th { background: #eee; font-weight: bold; text-align: center; }
    .footer { margin-top: 32px; text-align: center; font-weight: bold; }

    /* Tambahkan ini supaya background dan bold terbaca saat print */
    @media print {
        tfoot td {
            font-weight: bold !important;
            background-color: #17a2b8 !important;
            color: #fff !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        th {
            background-color: #eee !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
    }
</style>

</head>
<body>
    <div class="container">
        <!-- Header Judul -->
        <div class="header">
            <h3>LAPORAN KEUANGAN HARIAN</h3>
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

        <!-- Info Outlet -->
        <div>
            @if($outlet_id == 0)
                <div><b>Outlet:</b> Semua Outlet</div>
            @else
                <div><b>Outlet:</b> {{ \App\Models\MasterOutlet::find($outlet_id)->lokasi ?? '-' }}</div>
            @endif
        </div>

        <!-- Tabel Keuangan -->
        <table>
            <thead>
                <tr>
                    <th style="width:70%;">Keterangan</th>
                    <th style="width:30%;">Nominal</th>
                </tr>
            </thead>
            <tbody>
    <tr><td><b>Penjualan Outlet (Gross Sales)</b></td><td style="text-align:right;">Rp {{ number_format($data['omset'],0,',','.') }}</td></tr>
    <tr><td>Total Diskon</td><td style="text-align:right;">Rp {{ number_format($data['total_diskon'],0,',','.') }}</td></tr>
    <tr><td>Biaya Retur Produk</td><td style="text-align:right;">Rp {{ number_format($data['biaya_retur'],0,',','.') }}</td></tr>
    <tr><td><b>Net Sales (Penjualan Bersih)</b></td><td style="text-align:right;">Rp {{ number_format($data['omset'] - $data['total_diskon'] - $data['biaya_retur'],0,',','.') }}</td></tr>
    <tr><td>Total Modal Produk Terjual Hari Ini</td><td style="text-align:right;">Rp {{ number_format($data['total_modal_terjual'],0,',','.') }}</td></tr>
    <tr><td><b>Gross Profit (Laba Kotor)</b></td><td style="text-align:right;">Rp {{ number_format(($data['omset'] - $data['total_diskon'] - $data['biaya_retur']) - $data['total_modal_terjual'],0,',','.') }}</td></tr>
    <tr><td>Total Biaya Pesanan Masuk</td><td style="text-align:right;">Rp {{ number_format($data['total_biaya_pesanan'],0,',','.') }}</td></tr>
    <tr><td>Total Modal Pesanan Masuk</td><td style="text-align:right;">Rp {{ number_format($data['total_modal_pesanan'],0,',','.') }}</td></tr>
    <tr><td>Total DP Hari Ini</td><td style="text-align:right;">Rp {{ number_format($data['total_dp'],0,',','.') }}</td></tr>
    <tr><td>Total Pembayaran Lunas</td><td style="text-align:right;">Rp {{ number_format($data['total_lunas'],0,',','.') }}</td></tr>
    <tr><td>Biaya Stok Datang</td><td style="text-align:right;">Rp {{ number_format($data['biaya_stok_datang'],0,',','.') }}</td></tr>
</tbody>
<tfoot>
    <tr>
        <td style="font-weight:bold; background-color:#17a2b8; color:#fff;">
            Net Income / Laba Bersih Sementara
        </td>
        <td style="text-align:right; font-weight:bold; background-color:#17a2b8; color:#fff;">
            Rp {{ number_format(
                $data['omset']
                - $data['total_diskon']
                - $data['biaya_retur']
                - $data['total_modal_terjual']
                + ($data['total_biaya_pesanan'] - $data['total_modal_pesanan'])
            ,0,',','.') }}
        </td>
    </tr>
</tfoot>


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
