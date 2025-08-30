<template>
  <div id="surat-jalan-print" class="bg-white surat-jalan-container">
    <!-- Header -->
    <div class="text-center mb-2">
      <h4 class="fw-bold mb-0">PESANAN PENJUALAN</h4>
    </div>
    <div class="row mb-3 align-items-center">
      <div class="col-auto d-flex align-items-center">
        <img src="/images/logo.png" alt="Logo" style="height: 50px; max-width:120px;">
      </div>
      <div class="col ps-3">
        <div><b>Tanggal Pemesanan:</b> {{ formatTanggal(order.created_at) }}</div>
        <div><b>Pelanggan:</b> {{ order.nama }}</div>
        <div><b>Alamat:</b> {{ order.alamat_pengiriman }}</div>
        <div><b>Telepon:</b> {{ order.no_telp }}</div>
      </div>
    </div>
    <!-- Tabel Isi -->
    <div class="surat-jalan-table">
      <table class="table table-bordered align-middle mb-0" style="width:100%;table-layout:fixed;">
        <thead>
          <tr class="bg-light">
            <th style="width:40px;">No.</th>
            <th style="width:90px;">Tanggal</th>
            <th style="width:110px;">Nama</th>
            <th>Produk</th>
            <th style="width:90px;">Status</th>
            <th style="width:110px;">Pengiriman</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>1</td>
            <td>{{ formatTanggal(order.created_at) }}</td>
            <td>{{ order.nama }}</td>
            <td>
              <ul class="mb-0 ps-3">
                <li v-for="detail in order.details" :key="detail.id" class="text-truncate">
                  {{ detail.master_produk?.nama_produk || '-' }} ({{ detail.jumlah_beli }})
                </li>
              </ul>
            </td>
            <td>
              <span v-if="order.status_pembayaran == 2" class="badge bg-success">Lunas</span>
              <span v-else class="badge bg-warning text-dark">DP</span>
            </td>
            <td>
              {{ order.tanggal_pengiriman || '-' }}
              <span v-if="order.jam_pengiriman">({{ order.jam_pengiriman }})</span>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <!-- Ucapan Terima Kasih -->
    <div class="text-center mt-4 mb-2">
      <div class="fw-semibold">Terima kasih telah berbelanja dan sampai jumpa</div>
    </div>
    <!-- Footer TTD -->
    <div class="row gx-4 surat-jalan-ttd">
      <div class="col-6 d-flex flex-column align-items-start">
        <div class="ttd-box">
          <b>Pengirim</b>
          <div class="ttd-line"></div>
          <span>{{ kendaraan.driver }}</span>
        </div>
      </div>
      <div class="col-6 d-flex flex-column align-items-end">
        <div class="ttd-box">
          <b>Penerima</b>
          <div class="ttd-line"></div>
          <span>&nbsp;</span>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    order: Object,
    kendaraan: Object
  },
  methods: {
    formatTanggal(tgl) {
      if (!tgl) return "-";
      return tgl.split("T")[0];
    }
  }
};
</script>

<style scoped>
.surat-jalan-container {
  max-width: 800px;
  min-width: 600px;
  margin: auto;
  padding: 32px 24px;
  /* Ukuran pas A4 */
  box-sizing: border-box;
}
.surat-jalan-table {
  width: 100%;
  overflow-x: visible;
}
.table {
  width: 100%;
  margin-bottom: 0;
}
.table th, .table td {
  vertical-align: middle !important;
  padding: 0.5rem 0.5rem !important;
  word-break: break-word;
  font-size: 1rem;
}
ul {
  padding-left: 1rem;
  margin-bottom: 0;
}
.ttd-box {
  width: 100%;
  min-width: 80px;
  margin-bottom: 8px;
}
.ttd-line {
  height: 28px;
  border-bottom: 1px solid #ccc;
  margin-bottom: 4px;
  margin-top: 8px;
  width: 120px;
}
.surat-jalan-ttd {
  margin-bottom: 30px;
}
@media (max-width: 900px) {
  .surat-jalan-container {
    max-width: 100vw;
    min-width: 0;
    padding: 16px 4px;
  }
  .table th, .table td {
    font-size: 0.95rem;
    padding: 0.4rem 0.3rem !important;
  }
  .ttd-line {
    width: 80px;
  }
}
@media print {
  body, html, #surat-jalan-print, .surat-jalan-container {
    width: 210mm !important;
    min-width: 0 !important;
    max-width: 210mm !important;
    margin: 0 !important;
    padding: 0 !important;
    box-shadow: none !important;
    background: #fff !important;
  }
  .table th, .table td {
    font-size: 0.95rem !important;
    padding: 0.4rem 0.3rem !important;
  }
  .ttd-line {
    width: 80px !important;
  }
}
</style>