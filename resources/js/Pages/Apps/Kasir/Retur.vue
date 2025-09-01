<template>
    <Head>
        <title>Retur Produk</title>
    </Head>
    <main>
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header d-flex justify-between align-items-center flex-wrap">
                    <h5 class="mb-2">Retur Produk</h5>
                    <div class="d-flex gap-2 align-items-center">
                        <!-- Dropdown Outlet -->
                        <select v-model="outlet_id" class="form-select mr-3 " style="max-width:220px;" @change="getData">
                            <option v-for="o in outlets" :key="o.id" :value="o.id">
                                {{ o.lokasi }}
                            </option>
                        </select>
                        <div class="input-group" style="max-width:220px;">
                            <span class="input-group-text bg-white">
                                <i class="fa fa-calendar"></i>
                            </span>
                            <flat-pickr v-model="tanggal" @on-change="getData" class="form-control"
                                :config="{ dateFormat: 'Y-m-d', allowInput: true }" placeholder="Pilih tanggal" />
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Tabel retur produk -->
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th>Jumlah</th>
                                <th>Harga</th>
                                <th>Total</th>
                                <th>Aksi</th> <!-- Tambahkan kolom aksi -->
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="item in data" :key="item.id">
                                <td>{{ item.produk?.nama_produk }}</td>
                                <td>{{ item.jumlah }}</td>
                                <td>Rp {{ formatHarga(item.harga) }}</td>
                                <td>Rp {{ formatHarga(item.total) }}</td>
                                <td>
  <a @click="deleteRetur(item.id)"
     class="label bg-danger text-white f-12 ms-2"
     style="cursor: pointer; border-radius: 10px; padding: 5px 10px; display: inline-block;">
    <i class="fa fa-trash"></i> Delete
  </a>
</td>

                            </tr>
                            <tr v-if="data.length === 0">
                                <td colspan="5" class="text-center">Data Kosong</td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr style="font-weight: bold; background-color: #17a2b8; color: #fff;">
                                <td>Total</td>
                                <td>{{ total_jumlah }}</td>
                                <td></td>
                                <td>Rp {{ formatHarga(total_nominal) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </main>
</template>

<script>
import { Head } from "@inertiajs/inertia-vue3";
import FlatPickr from "vue-flatpickr-component";
import "flatpickr/dist/flatpickr.css";
import { ref } from "vue";
import { Inertia } from "@inertiajs/inertia";
import LayoutApp from "../../../Layouts/App.vue";
import Swal from "sweetalert2";

export default {
  layout: LayoutApp,
  props: {
    data: Array,
    total_jumlah: Number,
    total_nominal: Number,
    tanggal: String,
    outlets: Array, // <-- tambahkan ini
    outlet_id: [String, Number], // <-- tambahkan ini
  },
  components: { Head, FlatPickr },
  setup(props) {
    const tanggal = ref(props.tanggal);
    const outlet_id = ref(props.outlet_id ?? (props.outlets[0]?.id ?? null));

    const getData = () => {
      Inertia.get("/apps/returproduk", {
        tanggal: tanggal.value,
        outlet_id: outlet_id.value,
      }, {
        preserveState: true,
        replace: true
      });
    };

    const formatHarga = (angka) => {
      if (!angka) return "0";
      return parseInt(angka).toLocaleString("id-ID");
    };

    const deleteRetur = (id) => {
      Swal.fire({
        title: "Hapus Retur?",
        text: "Data retur akan dihapus permanen!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Ya, hapus!",
        cancelButtonText: "Batal",
      }).then((result) => {
        if (result.isConfirmed) {
          Inertia.delete(`/apps/returproduk/${id}`, {
            onSuccess: () => {
              Swal.fire("Berhasil!", "Retur produk sudah dihapus.", "success");
            },
            onError: () => {
              Swal.fire("Gagal!", "Retur produk gagal dihapus.", "error");
            },
          });
        }
      });
    };

    return { formatHarga, tanggal, outlet_id, getData, deleteRetur };
  },
};
</script>