<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Keuangan Harian</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #333; padding: 6px 8px; }
        th { background: #eee; }
        tfoot td { font-weight: bold; background: #17a2b8; color: #fff; }
    </style>
</head>
<body>
    <h3>Laporan Keuangan Harian<br><small>Tanggal: {{ $tanggal }}</small></h3>
    
    @if($outlet_id == 0)
        <div><b>Outlet:</b> Semua Outlet</div>
    @else
        <div><b>Outlet:</b> {{ \App\Models\MasterOutlet::find($outlet_id)->lokasi ?? '-' }}</div>
    @endif
    <table>
        <thead>
            <tr>
                <th>Keterangan</th>
                <th>Nominal</th>
            </tr>
        </thead>
        <tbody>
            <tr><td>Penjualan Outlet (Gross Sales)</td><td>Rp {{ number_format($data['omset'],0,',','.') }}</td></tr>
            <tr><td>Total Diskon</td><td>Rp {{ number_format($data['total_diskon'],0,',','.') }}</td></tr>
            <tr><td>Biaya Retur Produk</td><td>Rp {{ number_format($data['biaya_retur'],0,',','.') }}</td></tr>
            <tr><td>Net Sales (Penjualan Bersih)</td><td>Rp {{ number_format($data['omset'] - $data['total_diskon'] - $data['biaya_retur'],0,',','.') }}</td></tr>
            <tr><td>Total Modal Produk Terjual Hari Ini</td><td>Rp {{ number_format($data['total_modal_terjual'],0,',','.') }}</td></tr>
            <tr><td>Gross Profit (Laba Kotor)</td><td>Rp {{ number_format(($data['omset'] - $data['total_diskon'] - $data['biaya_retur']) - $data['total_modal_terjual'],0,',','.') }}</td></tr>
            <tr><td>Total Biaya Pesanan Masuk</td><td>Rp {{ number_format($data['total_biaya_pesanan'],0,',','.') }}</td></tr>
            <tr><td>Total Modal Pesanan Masuk</td><td>Rp {{ number_format($data['total_modal_pesanan'],0,',','.') }}</td></tr>
            <tr><td>Total DP Hari Ini</td><td>Rp {{ number_format($data['total_dp'],0,',','.') }}</td></tr>
            <tr><td>Total Pembayaran Lunas</td><td>Rp {{ number_format($data['total_lunas'],0,',','.') }}</td></tr>
            <tr><td>Biaya Stok Datang</td><td>Rp {{ number_format($data['biaya_stok_datang'],0,',','.') }}</td></tr>
        </tbody>
        <tfoot>
            <tr>
                <td>Net Income / Laba Bersih Sementara</td>
                <td>
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
    </table>
</body>
</html>