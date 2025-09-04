<template>
  <Head>
    <title>Distribusi Produk</title>
  </Head>
  <main>
    <div class="col-xl-12">
      <div class="card">
        <div class="card-header d-flex justify-between align-items-center flex-wrap">
          <h5 class="mb-2">Distribusi Produk</h5>
          <div class="d-flex align-items-center gap-2">
            <div class="input-group">
              <span class="input-group-text bg-white">
                <i class="fa fa-calendar"></i>
              </span>
              <flat-pickr
                v-model="filters.tanggal"
                @on-change="getData"
                class="form-control"
                :config="{ dateFormat: 'Y-m-d', allowInput: true }"
                placeholder="Pilih tanggal distribusi"
              />
            </div>
            <button class="btn btn-outline-primary btn-sm ms-2 ml-3" @click="printHarian">
              <i class="fa fa-print"></i> Print Harian
            </button>
            <button class="btn btn-outline-danger btn-sm ms-2" @click="exportPdfHarian">
              <i class="fa fa-file-pdf"></i> Export PDF Harian
            </button>
          </div>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Nama</th>
                  <th>Kode Distribusi</th>
                  <th>Alamat Pengiriman</th>
                  <th>No. Telp</th>
                  <th class="text-center">Tanggal & Waktu Pengiriman</th>
                  <th>Jumlah Produk</th>
                  <th>Status Distribusi</th>
                  <th>Kendaraan</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(item, index) in distribusiProduks.data" :key="item.id">
                  <td>{{ index + distribusiProduks.from }}</td>
                  <td>{{ item.order_penjualan?.nama || '-' }}</td>
                  <td>{{ item.order_penjualan?.kode_distribusi || '-' }}</td>
                  <td>{{ item.order_penjualan?.alamat_pengiriman || '-' }}</td>
                  <td>{{ item.order_penjualan?.no_telp || '-' }}</td>
                  <td class="text-center">
                    {{ item.order_penjualan?.tanggal_pengiriman ?? '-' }}<br>
                    {{ item.order_penjualan?.jam_pengiriman ?? '-' }}
                  </td>
                  <td>
                    <ul>
                      <li v-for="detail in item.order_penjualan?.details || []" :key="detail.id">
                        {{ detail.master_produk?.nama_produk || '-' }} ({{ detail.jumlah_beli }})
                      </li>
                    </ul>
                  </td>
                  <td class="text-center">
                    <span v-if="item.distribusi_produk?.status_distribusi == 1"
                          class="badge bg-warning text-dark px-3 py-1 rounded-pill d-inline-flex align-items-center gap-2">
                      <i class="fas fa-truck-moving"></i> Sedang Distribusi
                    </span>
                    <span v-else-if="item.distribusi_produk?.status_distribusi == 2"
                          class="badge bg-success text-white px-3 py-1 rounded-pill d-inline-flex align-items-center gap-2">
                      <i class="fas fa-check-circle"></i> Selesai Distribusi
                    </span>
                    <span v-else
                          class="badge bg-secondary text-white px-3 py-1 rounded-pill d-inline-flex align-items-center gap-2">
                      <i class="fas fa-minus-circle"></i> Belum Distribusi
                    </span>
                  </td>
                  <td>
                    {{ item.distribusi_produk?.master_kendaraan?.kode_kendaraan || '-' }}<br>
                    <small>
                      {{ item.distribusi_produk?.master_kendaraan?.merk_kendaraan || '' }} -
                      {{ item.distribusi_produk?.master_kendaraan?.driver || '' }}
                    </small>
                  </td>
                  <td>
                    <a @click="editData(item)" class="label theme-bg3 text-white f-12"
                       style="cursor:pointer; border-radius:10px">
                      <i class="fa fa-pencil-alt"></i> Edit
                    </a>
                    <a :href="`/apps/print/surat-jalan/${item.order_penjualan?.id}`" target="_blank"
                       class="label theme-bg9 text-white f-12"
                       style="cursor:pointer; border-radius:10px">
                      <i class="fa fa-print"></i> Print
                    </a>
                    <a :href="`/apps/distribusi-produk/${item.order_penjualan?.id}/export-surat-jalan`"
                       target="_blank"
                       class="label theme-bg5 text-white f-12"
                       style="cursor:pointer; border-radius:10px"
                       v-if="item.distribusi_produk?.status_distribusi == 1 || item.distribusi_produk?.status_distribusi == 2">
                      <i class="fa fa-file-pdf"></i> Export PDF
                    </a>
                  </td>
                </tr>
                <tr v-if="distribusiProduks.data.length === 0">
                  <td colspan="10" class="text-center">Data Kosong</td>
                </tr>
              </tbody>
            </table>
            <Pagination v-if="distribusiProduks.links" :links="distribusiProduks.links" align="end" />
          </div>
        </div>
      </div>
    </div>

    <!-- Modal Edit -->
    <Teleport to="body">
      <Modal :show="showModal" @close="showModal = false">
        <template #header>
          <h5>Edit Status Distribusi</h5>
        </template>
        <template #body>
          <div class="form-group">
            <label>Status Distribusi</label>
            <select class="form-control" v-model="form.status_distribusi">
              <option value="1">Sedang Distribusi</option>
              <option value="2">Selesai Distribusi</option>
            </select>
          </div>
          <div class="form-group">
            <label>Kendaraan</label>
            <select class="form-control" v-model="form.master_kendaraan_id">
              <option v-for="k in kendaraanOptions" :key="k.id" :value="k.id">
                {{ k.kode_kendaraan }} - {{ k.driver }}
              </option>
            </select>
          </div>
        </template>
        <template #footer>
          <form @submit.prevent="updateData">
            <button type="submit" class="btn btn-success">Update</button>
            <button type="button" class="btn btn-secondary" @click="showModal = false">Batal</button>
          </form>
        </template>
      </Modal>
    </Teleport>
  </main>
</template>

<script>
import { Head } from "@inertiajs/inertia-vue3";
import LayoutApp from "../../../Layouts/App.vue";
import Pagination from "../../../Components/Pagination.vue";
import Modal from "../../../Components/Modal.vue";
import FlatPickr from "vue-flatpickr-component";
import { ref } from "vue";
import { Inertia } from "@inertiajs/inertia";
import Swal from "sweetalert2";
import "flatpickr/dist/flatpickr.css";

export default {
  layout: LayoutApp,
  components: {
    Head,
    Pagination,
    Modal,
    FlatPickr,
  },
  props: {
    distribusiProduks: Object,
    filters: Object,
    kendaraanOptions: Array,
  },
  setup(props) {
    const showModal = ref(false);
    const selectedId = ref(null);
    const filters = ref({
      tanggal: props.filters?.tanggal || "",
    });

    const form = ref({
      status_distribusi: 1,
      master_kendaraan_id: "",
    });

    const getData = (page = 1) => {
      Inertia.get(
        "/apps/distribusi-produk",
        { tanggal: filters.value.tanggal, page: page },
        { preserveState: true, replace: true }
      );
    };

    const printHarian = () => {
      if (!filters.value.tanggal) {
        Swal.fire("Perhatian", "Pilih tanggal distribusi terlebih dahulu!", "warning");
        return;
      }
      window.open(
        `/apps/distribusi-produk/print-harian?tanggal=${filters.value.tanggal}`,
        "_blank"
      );
    };

    const exportPdfHarian = () => {
      window.open(
        `/apps/distribusi-produk/export-harian?tanggal=${filters.value.tanggal}`,
        "_blank"
      );
    };

    const editData = (item) => {
      selectedId.value = item.distribusi_produk?.id ?? null;
      form.value.status_distribusi = item.distribusi_produk?.status_distribusi || 1;
      form.value.master_kendaraan_id = item.distribusi_produk?.master_kendaraan_id || "";
      form.value.monitoring_order_id = item.id;
      showModal.value = true;
    };

    const updateData = () => {
      if (
        !form.value.status_distribusi ||
        form.value.master_kendaraan_id === null ||
        form.value.master_kendaraan_id === "" ||
        !selectedId.value
      ) {
        Swal.fire("Perhatian", "Status dan kendaraan wajib diisi!", "warning");
        return;
      }

      Inertia.put(
  `/apps/distribusi-produk/${selectedId.value ?? form.value.monitoring_order_id}`,
  {
    status_distribusi: form.value.status_distribusi,
    master_kendaraan_id: form.value.master_kendaraan_id,
  },
  {
    onSuccess: () => {
      showModal.value = false;
      Swal.fire("Sukses", "Distribusi berhasil diupdate!", "success");
      getData();
    },
    onError: (errors) => {
      Swal.fire("Error", "Gagal menyimpan distribusi!", "error");
      console.error(errors);
    },
  }
);
    };

    const handlePageChange = (link) => {
      if (link.url) {
        const url = new URL(link.url);
        const page = url.searchParams.get("page") || 1;
        getData(page);
      }
    };

    return {
      showModal,
      form,
      editData,
      filters,
      getData,
      printHarian,
      exportPdfHarian,
      handlePageChange,
      updateData,
    };
  },
};
</script>

<style>
.flatpickr-custom {
  max-width: 200px;
  border-radius: 8px;
  font-size: 14px;
}
</style>
