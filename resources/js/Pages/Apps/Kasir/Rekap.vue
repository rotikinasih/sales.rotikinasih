<template>

  <Head>
    <title>Rekap Penjualan</title>
  </Head>
  <main>
    <div class="col-xl-12">
      <div class="card">
        <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
          <h5 class="mb-0">Rekap Penjualan</h5>

          <div class="d-flex align-items-center gap-3 flex-nowrap">
            <!-- Dropdown Outlet -->
            <select v-model="outlet_id" class="form-select" style="max-width:220px;" @change="filterData">
              <option v-for="o in outlets" :key="o.id" :value="o.id">
                {{ o.lokasi }}
              </option>
            </select>

            <!-- Input Tanggal -->
            <div class="input-group ml-4" style="min-width: 230px; max-width: 230px;">
              <span class="input-group-text bg-white px-3">
                <i class="fa fa-calendar"></i>
              </span>
              <flat-pickr v-model="tanggal" :config="{ dateFormat: 'Y-m-d', allowInput: true }"
                class="form-control form-control-sm" @on-change="filterData" placeholder="Pilih tanggal" />
            </div>

            <!-- Tombol Export -->
            <button class="btn btn-outline-success btn-sm d-flex align-items-center justify-content-center ml-3 mr-2"
              style="min-width: 100px; height: 38px;" @click="exportExcel">
              <i class="fa fa-file-excel me-2"></i> Excel
            </button>

            <button class="btn btn-outline-danger btn-sm d-flex align-items-center justify-content-center"
              style="min-width: 100px; height: 38px;" @click="exportPdf">
              <i class="fa fa-file-pdf me-2"></i> PDF
            </button>
          </div>


        </div>

        <div class="card-block table-border-style">
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead class="thead-light">
                <tr>
                  <th>Kode Transaksi</th>
                  <th>Waktu</th>
                  <th>Pembayaran</th>
                  <th>Total</th>
                  <th>Diskon</th>
                  <th>Jumlah Bayar</th> <!-- Tambahkan kolom ini -->
                  <th>Detail</th>
                  <th>Jenis Penjualan</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="trx in transaksis" :key="trx.kode_transaksi">
                  <td>{{ trx.kode_transaksi }}</td>
                  <td>{{ formatTanggal(trx.created_at) }}</td>
                  <td>{{ trx.pembayaran }}</td>
                  <td class="text-success fw-semibold">
                    Rp {{ formatHarga(trx.total) }}
                  </td>
                  <td class="text-danger fw-semibold">
                    <span v-if="trx.diskon && trx.diskon > 0">Rp {{ formatHarga(trx.diskon) }}</span>
                    <span v-else>-</span>
                  </td>
                  <td class="text-primary fw-semibold">
                    <span v-if="trx.jumlah_bayar && trx.jumlah_bayar > 0">Rp {{ formatHarga(trx.jumlah_bayar) }}</span>
                    <span v-else>-</span>
                  </td>
                  <td>
                    <ul class="mb-0">
                      <li v-for="item in trx.items" :key="item.produk?.id">
                        {{ item.produk?.nama_produk }} x {{ item.jumlah }}
                        (Rp {{ formatHarga(item.harga_jual) }})
                      </li>
                    </ul>
                  </td>
                  <td>
                    <span v-if="trx.jenis_penjualan">{{ trx.jenis_penjualan }}</span>
                    <span v-else>-</span>
                  </td>
                  <td>
  <a
    v-if="trx.jenis_penjualan === 'Reguler'"
    @click="deleteTransaksi(trx.id)"
    class="label bg-danger text-white f-12 ms-2"
    style="cursor: pointer; border-radius: 10px;"
  >
    <i class="fa fa-trash"></i> Delete
  </a>
</td>

                </tr>
                <tr v-if="transaksis.length === 0">
                  <td colspan="9" class="text-center">
                    <br>
                    <i class="fa fa-receipt fa-3x text-muted"></i>
                    <p class="mt-2 mb-0 text-muted">Tidak ada transaksi untuk tanggal ini.</p>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <div class="text-end mt-3 me-2">
            <span class="fw-bold fs-6">Total Penjualan:</span>
            <span class="fs-5 text-success fw-bold ms-2">Rp {{ formatHarga(total) }}</span>
          </div>

          
        </div>
      </div>
    </div>
  </main>
</template>

<script>
import LayoutApp from "../../../Layouts/App.vue";
import { Head } from "@inertiajs/inertia-vue3";
import { Inertia } from "@inertiajs/inertia";
import { ref } from "vue";
import FlatPickr from "vue-flatpickr-component";
import "flatpickr/dist/flatpickr.css";
import Swal from "sweetalert2";

export default {
  layout: LayoutApp,
  components: { Head, FlatPickr },
  props: {
    transaksis: Array,
    tanggal: String,
    total: Number,
    outlets: Array,
    outlet_id: [String, Number],
  },
  setup(props) {
    const tanggal = ref(props.tanggal);
    const outlet_id = ref(props.outlet_id ?? 0);

    const formatHarga = (angka) =>
      angka == null ? "0" : parseInt(angka).toLocaleString("id-ID");

    const formatTanggal = (val) => {
      const tgl = new Date(val);
      return tgl.toLocaleString("id-ID", {
        day: "2-digit",
        month: "short",
        year: "numeric",
        hour: "2-digit",
        minute: "2-digit",
      });
    };

    const filterData = () => {
      Inertia.get("/apps/kasir/rekap", {
        tanggal: tanggal.value,
        outlet_id: outlet_id.value,
      });
    };

    const exportExcel = () => {
      window.open(`/apps/kasir/export?tanggal=${tanggal.value}&outlet_id=${outlet_id.value}&type=excel`, "_blank");
    };

    const exportPdf = () => {
      window.open(`/apps/kasir/export?tanggal=${tanggal.value}&outlet_id=${outlet_id.value}&type=pdf`, "_blank");
    };

    const deleteTransaksi = (id) => {
      Swal.fire({
        title: "Hapus Transaksi?",
        text: "Data transaksi akan dihapus permanen!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Ya, hapus!",
        cancelButtonText: "Batal",
      }).then((result) => {
        if (result.isConfirmed) {
          Inertia.delete(`/apps/kasir/rekap/${id}`, {
            onSuccess: () => {
              Swal.fire("Berhasil!", "Transaksi sudah dihapus.", "success");
            },
            onError: () => {
              Swal.fire("Gagal!", "Transaksi gagal dihapus.", "error");
            },
          });
        }
      });
    };

    return {
      tanggal,
      outlet_id,
      formatHarga,
      formatTanggal,
      filterData,
      exportExcel,
      exportPdf,
      deleteTransaksi,
    };
  },
};
</script>

<style scoped>
.table td,
.table th {
  vertical-align: middle;
}
</style>
