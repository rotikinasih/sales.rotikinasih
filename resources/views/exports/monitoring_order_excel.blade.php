<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Kode Produk</th>
            <th>Nama Produk</th>
            <th>Nama Pemesan</th>
            <th>Jam Pengiriman</th>
            <th>Keterangan</th>
            <th>Keterangan Staf</th>
            <th>Jumlah</th>
        </tr>
    </thead>
    <tbody>
        @php $no=1; $grandTotal=0; @endphp
        @foreach($rekap as $row)
        <tr>
            <td>{{ $no++ }}</td>
            <td>{{ $row['kode_produk'] }}</td>
            <td>{{ $row['nama_produk'] }}</td>
            <td>{{ $row['nama_pemesan'] }}</td>
            <td>{{ $row['jam_pengiriman'] }}</td>
            <td>{{ $row['keterangan'] }}</td>
            <td>{{ $row['keterangan_staf'] }}</td>
            <td>{{ $row['jumlah'] }}</td>
        </tr>
        @php $grandTotal += $row['jumlah']; @endphp
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="7" style="text-align:right;">Total</td>
            <td>{{ $grandTotal }}</td>
        </tr>
    </tfoot>
</table>

<!-- Tabel Rekap Total Per Produk -->
<tr><td colspan="8"></td></tr>
<tr><td colspan="8" style="font-weight:bold;">Total Per Produk</td></tr>
<tr>
    <th>No</th>
    <th colspan="2">Kode & Nama Produk</th>
    <th>Total Jumlah</th>
</tr>
@php $no=1; $grandTotal=0; @endphp
@foreach($totalPerProduk as $produk => $jumlah)
<tr>
    <td>{{ $no++ }}</td>
    <td colspan="2">{{ $produk }}</td>
    <td>{{ $jumlah }}</td>
</tr>
@php $grandTotal += $jumlah; @endphp
@endforeach
<tr>
    <td colspan="3" style="text-align:right;">Grand Total</td>
    <td>{{ $grandTotal }}</td>
</tr>