<template>
  <Head>
    <title>Monitoring Order</title>
  </Head>
  <main>
    <div class="col-xl-12">
      <div class="card">
        <div class="card-header">
          <h5>Daftar Monitoring Produksi</h5>
        </div>
        <div class="card-block table-border-style">
          <div class="table-responsive">
            <!-- Pencarian -->
            <div class="input-group mb-3">
              <input
                type="text"
                class="form-control"
                v-model="search"
                placeholder="Cari nama pemesan, nama produk, atau kode produk..."
                @input="debouncedSearch"
              />
              <button class="btn theme-bg5 text-white f-12" @click="handleSearch">
                <i class="fa fa-search me-2"></i>
              </button>
            </div>

            <!-- Filter kode produk -->
            <div class="form-group mb-3">
              <label>Pilih Kode Produk</label>
              <select class="form-control" v-model="selectedKode">
                <option :value="null">Semua Produk</option>
                <option v-for="kode in uniqueProdukKodes" :key="kode.kode" :value="kode.kode">
                  {{ kode.kode }}
                </option>
              </select>
            </div>

            <!-- Filter tanggal -->
            <div class="form-group mb-3">
              <label>Filter Tanggal Produksi</label>
              <div class="input-group">
                <span class="input-group-text bg-white">
                  <i class="fa fa-calendar"></i>
                </span>
                <flat-pickr
                  v-model="tanggalFilter"
                  :config="{ dateFormat: 'Y-m-d', allowInput: true }"
                  class="form-control"
                  placeholder="Pilih tanggal"
                  @on-change="handleSearch"
                />
              </div>
              <div class="text-end mb-3 mt-3">
                <button class="btn btn-outline-success btn-sm" @click="exportExcel">
                  <i class="fa fa-file-excel me-1"></i> Excel
                </button>
                <button class="btn btn-outline-danger btn-sm" @click="exportPdf">
                  <i class="fa fa-file-pdf me-1"></i> PDF
                </button>
                <button class="btn btn-outline-primary btn-sm" @click="printPage">
                  <i class="fa fa-print me-1"></i> Cetak
                </button>
              </div>
            </div>

            <!-- Tombol produksi -->
            <div class="text-end mb-3" v-if="selectedKode">
              <button class="btn btn-primary" @click="keOrderProduksi">Produksi Sekarang</button>
            </div>

            <!-- Ringkasan jumlah -->
            <div class="mt-2" v-if="Object.keys(totalJumlahPerProduk).length">
              <div class="card border-0">
                <div class="card-header bg-gradient-primary text-white d-flex align-items-center">
                  <i class="fas fa-box-open me-2"></i>
                  <h6 class="mb-0">Ringkasan Total Jumlah Pesanan</h6>
                </div>
                <div class="card-body p-3">
                  <div class="row g-3">
                    <div class="col-md-6 col-lg-4" v-for="(jumlah, label) in totalJumlahPerProduk" :key="label">
                      <div class="d-flex align-items-center bg-white rounded shadow-sm p-3 h-100 border border-light-subtle">
                        <div class="flex-shrink-0 bg-primary-subtle text-primary rounded-circle p-3 me-3">
                          <i class="fas fa-cube fa-lg"></i>
                        </div>
                        <div class="flex-grow-1">
                          <h6 class="mb-1">{{ label }}</h6>
                          <small class="text-muted">{{ jumlah }} item</small>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Tabel utama -->
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Kode Distribusi</th>
                  <th>Nama Pemesan</th>
                  <th>Produk</th>
                  <th>Tanggal Produksi</th>
                  <th>Jam</th>
                  <th>Keterangan</th>
                  <th>Catatan Admin</th>
                  <th>Status Produksi</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(item, index) in filteredOrders" :key="item.id">
                  <td>{{ index + monitoringOrders.from }}</td>
                  <td>{{ item.order_penjualan.kode_distribusi || '-' }}</td>
                  <td>{{ item.order_penjualan.nama || '-' }}</td>
                  <td>
                    <ul v-if="item.order_penjualan.details.length">
                      <li v-for="(p, i) in item.order_penjualan.details" :key="i">
                        {{ p.master_produk?.nama_produk || '-' }} ({{ p.jumlah_beli }})
                      </li>
                    </ul>
                    <span v-else>-</span>
                  </td>
                  <td>{{ item.tanggal_pembuatan || item.order_penjualan.tanggal_pembuatan || '-' }}</td>
                  <td>{{ item.jam_pengiriman || item.order_penjualan.jam_pengiriman || '-' }}</td>
                  <td>{{ item.keterangan || item.order_penjualan.keterangan || '-' }}</td>
                  <td>{{ item.keterangan_staf || item.order_penjualan.keterangan_staf || '-' }}</td>
                  <td>
                    <span
                      v-if="item.status_produksi == 1"
                      class="badge bg-warning text-dark px-3 py-1 rounded-pill d-inline-flex align-items-center gap-2"
                    >
                      <i class="fas fa-spinner fa-spin"></i> Sedang Diproduksi
                    </span>
                    <span
                      v-else-if="item.status_produksi == 2"
                      class="badge bg-success text-white px-3 py-1 rounded-pill d-inline-flex align-items-center gap-2"
                    >
                      <i class="fas fa-check-circle"></i> Selesai Diproduksi
                    </span>
                    <span
                      v-else
                      class="badge bg-secondary text-white px-3 py-1 rounded-pill d-inline-flex align-items-center gap-2"
                    >
                      <i class="fas fa-minus-circle"></i> Belum Diproses
                    </span>
                  </td>
                  <td>
                    <a @click="editData(item)" class="label theme-bg3 text-white f-12" style="cursor:pointer; border-radius:10px">
                      <i class="fa fa-pencil-alt"></i> set
                    </a>
                    <a
                      v-if="item.status_produksi == 1"
                      @click="editProduk(item)"
                      class="label theme-bg4 text-white f-12 ms-2"
                      style="cursor:pointer; border-radius:10px"
                    >
                      <i class="fa fa-edit"></i> Edit
                    </a>
                    <a
                      v-else
                      class="label theme-bg4 text-white f-12 ms-2 disabled"
                      style="border-radius:10px; pointer-events:none; opacity:0.5"
                    >
                      <i class="fa fa-edit"></i> Edit
                    </a>
                    <a
                      v-if="item.status_produksi == 2 && !item.distribusi_produk"
                      @click="openDistribusiModal(item)"
                      class="label theme-bg5 text-white f-12 ms-2"
                      style="cursor:pointer; border-radius:10px"
                    >
                      <i class="fa fa-truck"></i> Distribusi Sekarang
                    </a>
                  </td>
                </tr>
                <tr v-if="monitoringOrders.data.length === 0">
                  <td colspan="10" class="text-center">Data Kosong</td>
                </tr>
              </tbody>
            </table>

            <!-- Pagination -->
            <Pagination v-if="monitoringOrders.links" :links="monitoringOrders.links" @page-change="handlePageChange" />
          </div>
        </div>
      </div>
    </div>

    <!-- Modal update status -->
    <Teleport to="body">
      <Modal :show="showModal" @close="showModal = false">
        <template #header>
          <h5 class="modal-title">{{ judul }}</h5>
        </template>
        <template #body>
          <div class="form-group mb-2">
            <label>Order Penjualan</label>
            <select class="form-control" v-model="order_penjualan_id" disabled>
              <option v-for="order in orders" :key="order.id" :value="order.id">{{ order.nama }}</option>
            </select>
          </div>
          <div class="form-group mb-2">
            <label>Status Produksi</label>
            <select class="form-control" v-model="status_produksi">
              <option value="1">Sedang Diproduksi</option>
              <option value="2">Selesai Diproduksi</option>
            </select>
          </div>
        </template>
        <template #footer>
          <form @submit.prevent="updateData">
            <button type="submit" class="btn btn-success text-white m-2">Update</button>
            <button type="button" class="btn btn-secondary m-2" @click="tutupModal">Keluar</button>
          </form>
        </template>
      </Modal>
    </Teleport>

    <!-- Modal distribusi -->
    <Teleport to="body">
      <Modal :show="showDistribusiModal" @close="showDistribusiModal = false">
        <template #header>
          <h5>Pilih Kendaraan Distribusi</h5>
        </template>
        <template #body>
          <div class="form-group mb-2">
            <label>Kode Kendaraan</label>
            <select class="form-control" v-model="formDistribusi.master_kendaraan_id">
              <option v-for="kendaraan in masterKendaraan" :key="kendaraan.id" :value="kendaraan.id">
                {{ kendaraan.kode_kendaraan }} - {{ kendaraan.merk_kendaraan }} ({{ kendaraan.driver }})
              </option>
            </select>
          </div>
        </template>
        <template #footer>
          <form @submit.prevent="submitDistribusi">
            <button type="submit" class="btn btn-success text-white m-2">Distribusi</button>
            <button type="button" class="btn btn-secondary m-2" @click="showDistribusiModal = false">Batal</button>
          </form>
        </template>
      </Modal>
    </Teleport>

    <!-- Modal edit produk -->
    <Teleport to="body">
      <Modal :show="showEditProdukModal" @close="showEditProdukModal = false">
        <template #header>
          <h5>Edit Produk Order Penjualan</h5>
        </template>
        <template #body>
          <div v-if="editProdukData">
            <div class="form-group mb-2">
              <label>Produk & Jumlah</label>
              <div v-for="(p, idx) in editProdukData.details" :key="idx" class="d-flex gap-2 mb-2">
                <select class="form-control" v-model="p.master_produk_id" :disabled="editProdukStatus != 1">
                  <option value="">Pilih Produk</option>
                  <option v-for="prd in produkList" :key="prd.id" :value="prd.id">{{ prd.nama_produk }}</option>
                </select>
                <input
                  type="number"
                  class="form-control"
                  v-model="p.jumlah_beli"
                  min="1"
                  :disabled="editProdukStatus != 1"
                />
                <button type="button" class="btn btn-danger" @click="hapusProdukEdit(idx)" :disabled="editProdukStatus != 1">
                  <i class="fa fa-times"></i>
                </button>
              </div>
              <button type="button" class="btn btn-sm btn-primary" @click="tambahProdukEdit" :disabled="editProdukStatus != 1">
                <i class="fa fa-plus"></i> Tambah Produk
              </button>
            </div>
          </div>
        </template>
        <template #footer>
          <form @submit.prevent="updateProdukData">
            <button type="submit" class="btn btn-success text-white m-2" :disabled="editProdukStatus != 1">Update</button>
            <button type="button" class="btn btn-secondary m-2" @click="showEditProdukModal = false">Keluar</button>
          </form>
        </template>
      </Modal>
    </Teleport>
  </main>
</template>

<script>
import LayoutApp from "../../../Layouts/App.vue";
import { Head } from "@inertiajs/inertia-vue3";
import Pagination from "../../../Components/Pagination.vue";
import Modal from "../../../Components/Modal.vue";
import Swal from "sweetalert2";
import debounce from "lodash/debounce";
import { ref, onMounted, computed } from "vue";
import { Inertia } from "@inertiajs/inertia";
import FlatPickr from "vue-flatpickr-component";
import "flatpickr/dist/flatpickr.css";

export default {
  layout: LayoutApp,
  components: { Head, Pagination, Modal, FlatPickr },
  props: {
    monitoringOrders: { type: Object, required: true },
    orders: { type: Array, default: () => [] },
    produkKodes: { type: Array, default: () => [] },
    filters: { type: Object, default: () => ({}) },
    masterKendaraan: { type: Array, default: () => [] },
  },
  setup(props) {
    // --- State dasar ---
    const showModal = ref(false);
    const judul = ref("Edit Monitoring");
    const id = ref(null);
    const order_penjualan_id = ref(null);
    const status_produksi = ref(1);

    const search = ref(new URL(document.location).searchParams.get("search") || "");
    const selectedKode = ref(null);
    const tanggalFilter = ref(new URL(document.location).searchParams.get("tanggal") || null);

    // Distribusi
    const showDistribusiModal = ref(false);
    const distribusiItem = ref(null);
    const formDistribusi = ref({ master_kendaraan_id: null });

    // Edit Produk
    const showEditProdukModal = ref(false);
    const editProdukData = ref(null);
    const editProdukStatus = ref(1);

    // List master produk untuk select (diambil dari orders yang dikirim server)
    const produkList = ref(
      props.orders
        .flatMap((order) => order.details.map((d) => d.master_produk).filter(Boolean))
        // optional: remove duplicate by id
        .filter((v, i, arr) => (v?.id ? arr.findIndex(x => x?.id === v.id) === i : true))
    );

    // --- Search / Filter ---
    const handleSearch = () => {
      Inertia.get(
        "/apps/monitoringorder",
        {
          search: search.value || undefined,
          tanggal: tanggalFilter.value || undefined,
          // simpan juga kode di url jika ingin persist (meski backend index tidak filter kode, tidak masalah)
          kode: selectedKode.value || undefined,
        },
        {
          preserveState: true,
          preserveScroll: true,
          replace: true,
          onError: (errors) => {
            console.error("Search error:", errors);
            Swal.fire("Error", "Gagal memuat data filter.", "error");
          },
        }
      );
    };
    const debouncedSearch = debounce(handleSearch, 600);

    const filteredOrders = computed(() => {
      const rows = props.monitoringOrders?.data || [];
      if (!selectedKode.value) return rows;
      return rows
        .filter((item) =>
          item.order_penjualan?.details?.some((d) => d.master_produk?.kode === selectedKode.value)
        )
        .map((item) => {
          const filteredDetails = (item.order_penjualan?.details || []).filter(
            (d) => d.master_produk?.kode === selectedKode.value
          );
          return {
            ...item,
            order_penjualan: { ...(item.order_penjualan || {}), details: filteredDetails },
          };
        });
    });

    const totalJumlahPerProduk = computed(() => {
      const result = {};
      for (const item of filteredOrders.value) {
        for (const detail of item.order_penjualan?.details || []) {
          const kode = detail.master_produk?.kode || "-";
          const nama = detail.master_produk?.nama_produk || "-";
          const key = `${nama} (${kode})`;
          result[key] = (result[key] || 0) + Number(detail.jumlah_beli || 0);
        }
      }
      return result;
    });

    const uniqueProdukKodes = computed(() => {
      const seen = new Set();
      return (props.produkKodes || []).filter((item) => {
        if (!item?.kode) return false;
        if (seen.has(item.kode)) return false;
        seen.add(item.kode);
        return true;
      });
    });

    // --- Order Produksi (opsional, mengikuti perilaku awal) ---
    const keOrderProduksi = () => {
      if (!selectedKode.value) {
        return Swal.fire("Perhatian", "Pilih kode produk terlebih dahulu.", "warning");
      }

      const row = (props.monitoringOrders?.data || []).find((item) =>
        (item.order_penjualan?.details || []).some((d) => d.master_produk?.kode === selectedKode.value)
      );
      if (!row) return Swal.fire("Gagal", "Data monitoring tidak ditemukan!", "warning");

      const produkDetail = (row.order_penjualan?.details || []).find(
        (d) => d.master_produk?.kode === selectedKode.value
      );
      if (!produkDetail) return Swal.fire("Gagal", "Detail produk tidak ditemukan!", "warning");

      Inertia.post(
        "/apps/order-produksi",
        {
          monitoring_order_id: row.id,
          kode_produk: selectedKode.value,
          jumlah: Number(produkDetail.jumlah_beli || 0),
          tanggal_pembuatan: new Date().toISOString().slice(0, 10),
          status_produksi: 1, // 1 = sedang diproduksi
        },
        {
          onSuccess: () => Swal.fire("Sukses", "Order produksi berhasil dibuat!", "success"),
          onError: (errors) => {
            console.error(errors);
            Swal.fire("Error", "Gagal membuat order produksi.", "error");
          },
        }
      );
    };

    // --- Tambah Monitoring Otomatis untuk order yang belum termonitor ---
    const tambahMonitoringOtomatis = () => {
      const monitoredIds = (props.monitoringOrders?.data || []).map((item) => item.order_penjualan_id);
      const belumDimonitoring = (props.orders || []).filter((order) => !monitoredIds.includes(order.id));

      // Hindari spam request: kirim satu per satu, tetap sederhana sesuai kode awal
      belumDimonitoring.forEach((order) => {
        Inertia.post(
          "/apps/monitoringorder",
          {
            order_penjualan_id: order.id,
            status_produksi: 1,
          },
          {
            onError: (errors) => console.error("Autocreate monitoring error:", errors),
          }
        );
      });
    };

    // --- Edit Status Monitoring ---
    const editData = (item) => {
      id.value = item?.id ?? null;
      order_penjualan_id.value = item?.order_penjualan_id ?? null;
      status_produksi.value = Number(item?.status_produksi ?? 1);
      showModal.value = true;
    };
    const tutupModal = () => (showModal.value = false);

    const updateData = () => {
      if (!id.value) {
        return Swal.fire("Perhatian", "Data tidak valid.", "warning");
      }
      if (![1, 2].includes(Number(status_produksi.value))) {
        return Swal.fire("Perhatian", "Status produksi tidak valid.", "warning");
      }

      Inertia.put(
        `/apps/monitoringorder/${id.value}`,
        {
          status_produksi: Number(status_produksi.value),
          // Field opsional lain bisa ditambahkan jika diperlukan form-nya
          // keterangan,
          // keterangan_staf,
          // tanggal_pembuatan,
          // jam_pengiriman,
        },
        {
          preserveState: false,
          replace: true,
          onSuccess: () => {
            showModal.value = false;
            Swal.fire({ icon: "success", title: "Sukses", text: "Update berhasil!", timer: 1800 });
            // Pastikan reload agar data benar-benar fresh
            Inertia.reload({ only: ["monitoringOrders", "orders"] });
          },
          onError: (errors) => {
            console.error(errors);
            Swal.fire("Error", "Gagal memperbarui monitoring.", "error");
          },
        }
      );
    };

    // --- Distribusi ---
    const openDistribusiModal = (item) => {
      distribusiItem.value = item;
      formDistribusi.value.master_kendaraan_id = null;
      showDistribusiModal.value = true;
    };

    const submitDistribusi = () => {
      const kendaraanId = Number(formDistribusi.value.master_kendaraan_id);
      if (!kendaraanId) {
        return Swal.fire("Perhatian", "Pilih kendaraan terlebih dahulu!", "warning");
      }
      if (!distribusiItem.value?.id) {
        return Swal.fire("Perhatian", "Data monitoring tidak valid!", "warning");
      }

      Inertia.post(
        "/apps/distribusi-produk",
        {
          monitoring_order_id: distribusiItem.value.id,
          status_distribusi: 1,
          master_kendaraan_id: kendaraanId,
        },
        {
          onSuccess: () => {
            showDistribusiModal.value = false;
            Swal.fire("Sukses", "Distribusi berhasil dibuat!", "success");
            Inertia.reload({ only: ["monitoringOrders"] });
          },
          onError: (errors) => {
            console.error(errors);
            Swal.fire("Error", "Gagal membuat distribusi.", "error");
          },
        }
      );
    };

    // --- Edit Produk (Order Penjualan) ---
    const editProduk = (item) => {
      // Deep copy agar aman diedit
      editProdukData.value = JSON.parse(JSON.stringify(item.order_penjualan || {}));
      editProdukStatus.value = Number(item.status_produksi || 1);
      showEditProdukModal.value = true;
    };
    const tutupEditProdukModal = () => (showEditProdukModal.value = false);

    const updateProdukData = () => {
      if (Number(editProdukStatus.value) !== 1) return; // hanya boleh edit saat status 1
      if (!editProdukData.value?.id) {
        return Swal.fire("Perhatian", "Order penjualan tidak valid.", "warning");
      }

      // Validasi minimal
      for (const p of editProdukData.value.details || []) {
        if (!p.master_produk_id || Number(p.jumlah_beli) < 1) {
          return Swal.fire("Perhatian", "Semua produk dan jumlah beli wajib diisi.", "warning");
        }
      }

      Inertia.put(
        `/apps/orderpenjualan/${editProdukData.value.id}`,
        {
          nama: editProdukData.value.nama,
          alamat_pengiriman: editProdukData.value.alamat_pengiriman,
          tanggal_pengiriman: editProdukData.value.tanggal_pengiriman,
          jam_pengiriman: editProdukData.value.jam_pengiriman,
          tanggal_pembuatan: editProdukData.value.tanggal_pembuatan,
          status_pembayaran: editProdukData.value.status_pembayaran,
          keterangan: editProdukData.value.keterangan,
          keterangan_staf: editProdukData.value.keterangan_staf,
          produkList: (editProdukData.value.details || []).map((p) => ({
            master_produk_id: Number(p.master_produk_id),
            jumlah_beli: Number(p.jumlah_beli),
          })),
        },
        {
          preserveState: false,
          replace: true,
          onSuccess: () => {
            showEditProdukModal.value = false;
            Swal.fire("Sukses", "Update berhasil!", "success");
            Inertia.get("/apps/monitoringorder", {
              search: search.value || undefined,
              tanggal: tanggalFilter.value || undefined,
              kode: selectedKode.value || undefined,
            });
          },
          onError: (errors) => {
            console.error(errors);
            Swal.fire("Error", "Gagal mengupdate order penjualan.", "error");
          },
        }
      );
    };

    const tambahProdukEdit = () => {
      if (Number(editProdukStatus.value) !== 1) return;
      if (!editProdukData.value?.details) editProdukData.value.details = [];
      editProdukData.value.details.push({ master_produk_id: null, jumlah_beli: 1 });
    };

    const hapusProdukEdit = (idx) => {
      if (Number(editProdukStatus.value) !== 1) return;
      if ((editProdukData.value?.details || []).length > 1) {
        editProdukData.value.details.splice(idx, 1);
      }
    };

    // --- Export / Print ---
    const buildTanggalExport = () => tanggalFilter.value || new Date().toISOString().slice(0, 10);

    const exportExcel = () => {
      const tgl = buildTanggalExport();
      const kodeParam = selectedKode.value ? encodeURIComponent(selectedKode.value) : "";
      window.open(`/apps/monitoringorder/export?tanggal=${tgl}&type=excel&kode=${kodeParam}`, "_blank");
    };
    const exportPdf = () => {
      const tgl = buildTanggalExport();
      const kodeParam = selectedKode.value ? encodeURIComponent(selectedKode.value) : "";
      window.open(`/apps/monitoringorder/export?tanggal=${tgl}&type=pdf&kode=${kodeParam}`, "_blank");
    };
    const printPage = () => {
      const tgl = buildTanggalExport();
      const kodeParam = selectedKode.value ? encodeURIComponent(selectedKode.value) : "";
      window.open(`/apps/monitoringorder/print?tanggal=${tgl}&kode=${kodeParam}`, "_blank");
    };

    // --- Pagination ---
    const handlePageChange = (link) => {
      if (!link?.url) return;
      const url = new URL(link.url, window.location.origin);
      const page = url.searchParams.get("page") || 1;

      Inertia.get(
        "/apps/monitoringorder",
        {
          page,
          search: search.value || undefined,
          tanggal: tanggalFilter.value || undefined,
          kode: selectedKode.value || undefined,
        },
        {
          preserveState: true,
          preserveScroll: true,
          replace: true,
          onError: (errors) => console.error("Pagination error:", errors),
        }
      );
    };

    onMounted(() => {
      // otomatis buat monitoring untuk order yang belum ada monitoring
      tambahMonitoringOtomatis();
    });

    return {
      // state
      showModal,
      judul,
      order_penjualan_id,
      status_produksi,
      search,
      selectedKode,
      tanggalFilter,

      // computed
      filteredOrders,
      uniqueProdukKodes,
      totalJumlahPerProduk,

      // actions
      debouncedSearch,
      handleSearch,
      keOrderProduksi,
      editData,
      updateData,
      tutupModal,

      // distribusi
      showDistribusiModal,
      openDistribusiModal,
      submitDistribusi,
      formDistribusi,
      masterKendaraan: props.masterKendaraan,

      // edit produk
      editProduk,
      showEditProdukModal,
      editProdukData,
      produkList,
      editProdukStatus,
      updateProdukData,
      tambahProdukEdit,
      hapusProdukEdit,

      // export/print
      exportExcel,
      exportPdf,
      printPage,

      // pagination
      handlePageChange,
    };
  },
};
</script>

