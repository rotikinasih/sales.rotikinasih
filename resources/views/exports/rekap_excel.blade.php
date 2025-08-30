<table>
    <thead>
        <tr>
            <th>Kode Transaksi</th>
            <th>Waktu</th>
            <th>Pembayaran</th>
            <th>Total</th>
            <th>Detail Produk</th>
        </tr>
    </thead>
    <tbody>
        @foreach($transaksis as $trx)
            <tr>
                <td>{{ $trx->kode_transaksi }}</td>
                <td>{{ $trx->created_at }}</td>
                <td>{{ ucfirst($trx->pembayaran) }}</td>
                <td>{{ $trx->total }}</td>
                <td>
                    @foreach($trx->items as $item)
                        {{ $item->produk->nama_produk ?? '-' }} x {{ $item->jumlah }}
                        (Rp {{ $item->harga_jual }})<br>
                    @endforeach
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
