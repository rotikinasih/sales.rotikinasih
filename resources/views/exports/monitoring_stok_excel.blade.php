<table>
    <thead>
        <tr>
            <th colspan="10" style="font-size:16px; font-weight:bold;">
                Monitoring Stok Kasir - {{ $tanggal }}
            </th>
        </tr>
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
            <td>{{ $item['harga'] }}</td>
            <td>{{ $item['stok_awal'] }}</td>
            <td>{{ $item['stok_masuk'] }}</td>
            <td>{{ $item['stok_terjual'] }}</td>
            <td>{{ $item['retur'] }}</td>
            <td>{{ $item['final_stok'] }}</td>
            <td>{{ $item['harga_terjual'] }}</td>
            <td>{{ $item['harga_final'] }}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr style="font-weight: bold; background: #17a2b8; color: #fff;">
            <td>Total</td>
            <td></td>
            <td>{{ $total['stok_awal'] }}</td>
            <td>{{ $total['stok_masuk'] }}</td>
            <td>{{ $total['stok_terjual'] }}</td>
            <td>{{ $total['retur'] }}</td>
            <td>{{ $total['final_stok'] }}</td>
            <td>{{ $total['harga_terjual'] }}</td>
            <td>{{ $total['harga_final'] }}</td>
        </tr>
    </tfoot>
</table>