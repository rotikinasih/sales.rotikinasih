<template>

    <Head>
        <title>Customer Order</title>
    </Head>
    <main>
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <button @click="buatBaru" class="btn theme-bg4 text-white f-12 float-right"
                        v-if="hasAnyPermission(['orderpenjualan.create'])">
                        <i class="fa fa-plus"></i> Tambah
                    </button>
                    <h5>Daftar Order Penjualan</h5>
                </div>
                <div class="card-block table-border-style">
                    <div class="table-responsive">
                        <!-- SEARCHING: hanya satu input -->
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" v-model="search"
                                placeholder="Cari Nama, Alamat, No Fraktur, dsb..." @input="debouncedSearch" />
                            <button class="btn theme-bg5 text-white f-12" @click="handleSearch">
                                <i class="fa fa-search me-2"></i>
                            </button>
                        </div>
                        <!-- PENCARIAN TANGGAL: beri keterangan -->
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label">Tanggal Input</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white">
                                        <i class="fa fa-calendar"></i>
                                    </span>
                                    <flat-pickr v-model="searchTanggalInput"
                                        :config="{ dateFormat: 'Y-m-d', allowInput: true }" class="form-control"
                                        @on-change="handleSearch" placeholder="Pilih tanggal input" />
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Tanggal Pembuatan</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white">
                                        <i class="fa fa-calendar"></i>
                                    </span>
                                    <flat-pickr v-model="searchTanggalPembuatan"
                                        :config="{ dateFormat: 'Y-m-d', allowInput: true }" class="form-control"
                                        @on-change="handleSearch" placeholder="Pilih tanggal pembuatan" />
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Tanggal Pengiriman</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white">
                                        <i class="fa fa-calendar"></i>
                                    </span>
                                    <flat-pickr v-model="searchTanggalPengiriman"
                                        :config="{ dateFormat: 'Y-m-d', allowInput: true }" class="form-control"
                                        @on-change="handleSearch" placeholder="Pilih tanggal pengiriman" />
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label>Status Pembayaran</label>
                                <select v-model="searchStatusPembayaran" class="form-control" @change="handleSearch">
                                    <option value="">Semua</option>
                                    <option value="1">DP</option>
                                    <option value="2">Lunas</option>
                                    <option value="3">Lunas (Riwayat DP)</option> <!-- Tambahkan ini -->
                                </select>
                            </div>
                        </div>
                        <!-- HAPUS FITUR SORTING -->
                        <table class="table table-bordered table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center">No. Fraktur</th>
                                    <th class="text-center">Nama</th>
                                    <th class="text-center">Produk</th>
                                    <th class="text-center">Metode Pembayaran</th>
                                    <th class="text-center">Jumlah Bayar</th>
                                    <th class="text-center">Total Bayar</th>
                                    <th class="text-center">Kurang Bayar</th>
                                    <th class="text-center">Tanggal DP</th>
                                    <th class="text-center">Alamat</th>
                                    <th class="text-center">Tanggal & Waktu Pengiriman</th>
                                    <th class="text-center">Keterangan</th>
                                    <th class="text-center">Catatan Admin</th>
                                    <th class="text-center">Penginput</th>
                                    <th class="text-center">Lokasi</th>
                                    <th class="text-center">Tanggal Input</th>
                                    <th class="text-center">Status Pembayaran</th>
                                    <th class="text-center" v-if="hasAnyPermission(['orderpenjualan.edit'])">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(item, index) in orderpenjualan.data" :key="item.id">
                                    <td class="text-center">{{ index + orderpenjualan.from }}</td>
                                    <td>{{ item.no_fraktur }}</td>
                                    <td>{{ item.nama }}</td>
                                    <td>
                                        <ul>
                                            <li v-for="(p, i) in item.details" :key="i">
                                                {{ p.master_produk?.nama_produk || '-' }} ({{ p.jumlah_beli }})
                                            </li>
                                        </ul>
                                    </td>
                                    <td>{{ item.metode_pembayaran }}</td>
                                    <td>{{ formatRupiah(item.jumlah_bayar) }}</td>
                                    <td>{{ formatRupiah(item.total_bayar) }}</td>
                                    <td>{{ formatRupiah(item.kurang_bayar) }}</td>
                                    <td>
                                        <span v-if="item.tanggal_dp_list && item.tanggal_dp_list.length">
                                            <span v-for="(tgl, idx) in item.tanggal_dp_list" :key="idx">
                                                {{ tgl }}<span v-if="idx < item.tanggal_dp_list.length - 1">, </span>
                                            </span>
                                        </span>
                                        <span v-else>-</span>
                                    </td>
                                    <td>{{ item.alamat_pengiriman || '-' }}</td>
                                    <td class="text-center">
                                        {{ item.tanggal_pengiriman ?? '-' }}<br>
                                        {{ item.jam_pengiriman ?? '-' }}
                                    </td>
                                    <td>{{ item.keterangan }}</td>
                                    <td>{{ item.keterangan_staf }}</td>
                                    <td>{{ item.penginput }}</td>
                                    <td>{{ item.lokasi }}</td>
                                    <td>{{ item.created_at ? item.created_at.split('T')[0] : '-' }}</td>
                                    <td class="text-center">
                                        <span v-if="item.status_pembayaran == 2"
                                            class="badge bg-success text-white px-3 py-1 rounded-pill d-inline-flex align-items-center gap-2"><i
                                                class="fas fa-check-circle"></i> Lunas</span>
                                        <span v-else-if="item.status_pembayaran == 1"
                                            class="badge bg-warning text-dark px-3 py-1 rounded-pill d-inline-flex align-items-center gap-2"><i
                                                class="fas fa-hourglass-half"></i> DP</span>
                                        <span v-else
                                            class="badge bg-secondary text-white px-3 py-1 rounded-pill d-inline-flex align-items-center gap-2"><i
                                                class="fas fa-minus-circle"></i> Belum</span>
                                    </td>

                                    <td class="text-center" v-if="hasAnyPermission(['orderpenjualan.edit'])">
    <!-- Tombol Edit -->
    <a @click="editData(item)" 
       class="label theme-bg3 text-white f-12"
       style="cursor: pointer; border-radius: 10px;">
        <i class="fa fa-pencil-alt"></i> Edit
    </a>

    <!-- Tombol Cicilan -->
    <a v-if="item.status_pembayaran == 1" 
       @click.prevent="showCicilan(item)"
       class="label bg-warning text-dark f-12 ms-2"
       style="cursor: pointer; border-radius: 10px;">
        <i class="fa fa-coins"></i> Cicilan
    </a>

    <!-- Tombol Riwayat DP -->
    <a v-if="item.status_pembayaran == 2 && item.cicilan && item.cicilan.length > 0"
       @click="showRiwayatCicilan(item)"
       class="label theme-bg2 text-white f-12 ms-2"
       style="cursor: pointer; border-radius: 10px;">
        <i class="fa fa-history"></i> Riwayat DP
    </a>

    <!-- Tombol Delete -->
    <a @click="deleteOrder(item.id)"
       class="label bg-danger text-white f-12 ms-2"
       style="cursor: pointer; border-radius: 10px;">
        <i class="fa fa-trash"></i> Delete
    </a>
</td>

                                </tr>
                                <tr v-if="orderpenjualan.data.length === 0">
                                    <td colspan="16" class="text-center">
                                        <i class="fa fa-file-excel fa-5x"></i><br /><br />
                                        No Data To Display
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="row">
                            <div class="col-md-4">
                                <label v-if="orderpenjualan.total">
                                    Showing {{ orderpenjualan.from }} to {{ orderpenjualan.to }} of {{
                                        orderpenjualan.total }} items
                                </label>
                            </div>
                            <div class="col-md-8">
                                <Pagination v-if="orderpenjualan.links" :links="orderpenjualan.links" align="end" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <Teleport to="body">
            <Modal :show="showModal" @close="showModal = false">
                <template #header>
                    <h5 class="modal-title">{{ judul }}</h5>
                </template>
                <template #body>
                    <div style="max-height: 60vh; overflow-y: auto; padding-right: 10px;">

  <div class="form-group mb-2">
    <label>Nama</label>
    <input type="text" class="form-control" v-model="nama" required />
  </div>

  <div class="form-group mb-2">
    <label>No Telp</label>
    <input type="text" class="form-control" v-model="no_telp" required />
  </div>

  <div class="form-group mb-2">
    <label>Alamat Pengiriman</label>
    <textarea class="form-control" v-model="alamat_pengiriman" required></textarea>
  </div>

  <div class="form-group mb-2">
    <label>Produk & Jumlah</label>
    <div v-for="(p, index) in produkList" :key="index"
      class="d-flex gap-2 align-items-center mb-2">
      <select class="form-control" v-model="p.master_produk_id" required style="width: 60%;">
        <option :value="null">Pilih Produk</option>
        <option v-for="prd in produk" :key="prd.id" :value="prd.id">
          {{ prd.nama_produk }}
        </option>
      </select>
      <input type="number" class="form-control" v-model="p.jumlah_beli" 
        min="1" required placeholder="Jumlah" style="width: 30%;" />
      <button type="button" class="btn btn-danger" @click="hapusProduk(index)">
        <i class="fa fa-times"></i>
      </button>
    </div>
    <button type="button" class="btn btn-sm btn-primary" @click="tambahProduk">
      <i class="fa fa-plus"></i> Tambah Produk
    </button>
  </div>

  <div class="form-group mb-2">
    <label>Tanggal Pengiriman</label>
    <flat-pickr v-model="tanggal_pengiriman" 
      :config="{ dateFormat: 'Y-m-d', allowInput: true }"
      class="form-control" placeholder="Pilih tanggal pengiriman" required />
  </div>

  <div class="form-group mb-2">
    <label>Waktu Pengiriman</label>
    <select class="form-control" v-model="jam_pengiriman" required>
      <option :value="null">Pilih Waktu</option>
      <option value="Pagi">Pagi</option>
      <option value="Siang">Siang</option>
      <option value="Sore">Sore</option>
      <option value="Malam">Malam</option>
    </select>
  </div>

  <div class="form-group mb-2">
    <label>Tanggal Pembuatan</label>
    <flat-pickr v-model="tanggal_pembuatan" 
      :config="{ dateFormat: 'Y-m-d', allowInput: true }"
      class="form-control" placeholder="Pilih tanggal pembuatan" required />
  </div>

  <div class="form-group mb-2">
    <label>Status Pembayaran</label>
    <select class="form-control" v-model="status_pembayaran" required>
      <option :value="null">Pilih</option>
      <option value="1">DP</option>
      <option value="2">Lunas</option>
    </select>
  </div>

  <div class="form-group mb-2">
    <label>Keterangan</label>
    <textarea class="form-control" v-model="keterangan" required></textarea>
  </div>

  <div class="form-group mb-2">
    <label>Metode Pembayaran</label>
    <select class="form-control" v-model="metode_pembayaran" required>
      <option value="Tunai">Tunai</option>
      <option value="Qris">Qris</option>
      <option value="Debit">Debit</option>
      <option value="Transfer">Transfer</option>
    </select>
  </div>

  <div class="form-group mb-2" v-if="status_pembayaran == 1">
    <label>Tanggal DP</label>
    <flat-pickr v-model="tanggal_dp" 
      :config="{ dateFormat: 'Y-m-d', allowInput: true }"
      class="form-control" placeholder="Pilih tanggal DP" required />
  </div>

  <div class="form-group mb-2">
    <label>Catatan Admin</label>
    <textarea class="form-control" v-model="keterangan_staf" required></textarea>
  </div>

  <div class="form-group mb-2">
    <label>Penginput</label>
    <select class="form-control" v-model="penginput" required>
      <option :value="null">Pilih Penginput</option>
      <option v-for="cs in masterCS" :key="cs.id" :value="cs.nama">
        {{ cs.nama }}
      </option>
    </select>
  </div>

  <div class="form-group mb-2">
    <label>Lokasi</label>
    <select class="form-control" v-model="lokasi" required>
      <option :value="null">Pilih Lokasi</option>
      <option v-for="out in masterOutlet" :key="out.id" :value="out.lokasi">{{ out.lokasi }}</option>
    </select>
  </div>

  <div class="form-group mb-2">
    <label>Outlet</label>
    <select class="form-control" v-model="outlet_id" required>
      <option :value="null">Pilih Outlet</option>
      <option v-for="out in masterOutlet" :key="out.id" :value="out.id">
        {{ out.lokasi }}
      </option>
    </select>
  </div>
</div>
                </template>
                <template #footer>
                    <form @submit.prevent="updateSubmit ? updateData() : storeData()">
                        <button type="submit" class="btn btn-success text-white m-2">
                            {{ updateSubmit ? "Update" : "Simpan" }}
                        </button>
                        <button type="button" class="btn btn-secondary m-2" @click="tutupModal">Keluar</button>
                    </form>
                </template>
            </Modal>
        </Teleport>

        <!-- Modal Cicilan -->
        <Teleport to="body">
            <Modal :show="showCicilanModal" @close="showCicilanModal = false">
                <template #header>
                    <h5 class="modal-title">Input Cicilan DP</h5>
                </template>
                <template #body>
                    <div class="mb-2">
                        <div><b>Jumlah Bayar:</b> Rp {{ formatRupiah(cicilanData.jumlah_bayar) }}</div>
                        <div><b>Total Bayar:</b> Rp {{ formatRupiah(cicilanData.total_bayar) }}</div>
                        <div><b>Kurang Bayar:</b> Rp {{ formatRupiah(cicilanData.total_bayar - cicilanData.jumlah_bayar)
                            }}</div>
                        <div><b>Riwayat Cicilan:</b></div>
                        <ul>
                            <li v-for="c in cicilanData.cicilan" :key="c.id">
                                Cicilan ke-{{ c.cicilan_ke }}: Rp {{ formatRupiah(c.nominal) }} ({{ c.tanggal }})
                            </li>
                        </ul>
                    </div>
                    <div v-if="cicilanData.cicilan.length < 3">
                        <label>Nominal Cicilan</label>
                        <input type="number" class="form-control" v-model="jumlah_cicilan" min="1" />
                    </div>
                    <div v-else class="text-danger">
                        Maksimal cicilan DP sudah 3x.
                    </div>
                </template>
                <template #footer>
                    <button type="button" class="btn btn-success" @click="simpanCicilan">
                        <i class="fa fa-coins"></i> Simpan Cicilan
                    </button>
                    <button type="button" class="btn btn-secondary" @click="showCicilanModal = false">Keluar</button>
                </template>
            </Modal>
        </Teleport>

        <!-- Modal Riwayat Cicilan -->
        <Teleport to="body">
            <Modal :show="showRiwayatModal" @close="showRiwayatModal = false">
                <template #header>
                    <h5 class="modal-title">Riwayat Cicilan DP</h5>
                </template>
                <template #body>
                    <div class="mb-2">
                        <div><b>Jumlah Bayar:</b> Rp {{ formatRupiah(riwayatData.jumlah_bayar) }}</div>
                        <div><b>Total Bayar:</b> Rp {{ formatRupiah(riwayatData.total_bayar) }}</div>
                        <div><b>Kurang Bayar:</b> Rp {{ formatRupiah(riwayatData.total_bayar - riwayatData.jumlah_bayar) }}</div>
                        <div><b>Riwayat Cicilan:</b></div>
                        <ul>
                            <li v-for="c in riwayatData.cicilan" :key="c.id">
                                Cicilan ke-{{ c.cicilan_ke }}: Rp {{ formatRupiah(c.nominal) }} ({{ c.tanggal }})
                            </li>
                        </ul>
                    </div>
                </template>
                <template #footer>
                    <button type="button" class="btn btn-secondary" @click="showRiwayatModal = false">Keluar</button>
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
import { ref, computed, watch } from "vue";
import { Inertia } from "@inertiajs/inertia";
import FlatPickr from "vue-flatpickr-component";
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
        orderpenjualan: Object,
        produk: Array,
        masterOutlet: Array,
        masterCS: Array, // <-- tambahkan ini
    },
    setup(props) {
        const showModal = ref(false);
        const updateSubmit = ref(false);
        const judul = ref("Tambah Order");
        const id = ref(null);
        const nama = ref(null);
        const no_telp = ref(null);
        const alamat_pengiriman = ref(null);
        const tanggal_pengiriman = ref(null);
        const jam_pengiriman = ref(null);
        const tanggal_pembuatan = ref(null);

        const status_pembayaran = ref(null);
        const keterangan = ref(null);
        const produkList = ref([{ master_produk_id: null, jumlah_beli: 1 }]);
        const search = ref(new URL(document.location).searchParams.get("search") || "");
        const searchTanggalInput = ref("");
        const searchTanggalPembuatan = ref("");
        const searchTanggalPengiriman = ref("");
        const searchStatusPembayaran = ref("");

        // Hapus searchNama dan input pencarian lain
        const handleSearch = () => {
            Inertia.get("/apps/orderpenjualan", {
                search: search.value,
                searchTanggalInput: searchTanggalInput.value,
                searchTanggalPembuatan: searchTanggalPembuatan.value,
                searchTanggalPengiriman: searchTanggalPengiriman.value,
                searchStatusPembayaran: searchStatusPembayaran.value,
            });
        };
        const debouncedSearch = debounce(handleSearch, 1000);

        const buatBaru = () => {
            resetForm();
            updateSubmit.value = false;
            judul.value = "Tambah Order";
            showModal.value = true;
        };

        const editData = (item) => {
            updateSubmit.value = true;
            judul.value = "Edit Order";
            id.value = item.id;
            nama.value = item.nama;
            no_telp.value = item.no_telp ?? null;
            alamat_pengiriman.value = item.alamat_pengiriman ?? null;
            tanggal_pengiriman.value = item.tanggal_pengiriman ?? null;
            jam_pengiriman.value = item.jam_pengiriman ?? null;
            tanggal_pembuatan.value = item.tanggal_pembuatan ?? null;
            status_pembayaran.value = item.status_pembayaran ?? null;
            keterangan.value = item.keterangan ?? null;
            produkList.value = item.details.map(p => ({
                master_produk_id: p.master_produk?.id || p.master_produk_id,
                jumlah_beli: p.jumlah_beli,
            }));
            // Pastikan field lain juga terisi
            metode_pembayaran.value = item.metode_pembayaran ?? "Tunai";
            tanggal_dp.value = item.tanggal_dp ?? null;
            keterangan_staf.value = item.keterangan_staf ?? "";
            penginput.value = item.penginput ?? "";
            lokasi.value = item.lokasi ?? null;
            jumlah_bayar.value = item.jumlah_bayar ?? 0;
            total_bayar.value = item.total_bayar ?? 0;
            outlet_id.value = item.outlet_id ?? null;
            showModal.value = true;
        };

        const tambahProduk = () => {
            produkList.value.push({ master_produk_id: null, jumlah_beli: 1 });
        };

        const hapusProduk = (index) => {
            if (produkList.value.length > 1) {
                produkList.value.splice(index, 1);
            }
        };

        const resetForm = () => {
            nama.value = null;
            no_telp.value = null;
            alamat_pengiriman.value = null;
            tanggal_pengiriman.value = null;
            jam_pengiriman.value = null;
            tanggal_pembuatan.value = null;
            status_pembayaran.value = null;
            keterangan.value = null;
            produkList.value = [{ master_produk_id: null, jumlah_beli: 1 }];
            metode_pembayaran.value = "Tunai";
            tanggal_dp.value = null;
            keterangan_staf.value = "";
            penginput.value = "";
            lokasi.value = null;
            jumlah_bayar.value = 0;
            total_bayar.value = 0;
            outlet_id.value = null;
        };

        const tutupModal = () => {
            showModal.value = false;
        };

        const peringatan = (msg = "Mohon lengkapi isian!") => {
            Swal.fire({ icon: "warning", title: "Perhatian", text: msg });
        };

        const storeData = () => {
            if (!nama.value || produkList.value.length === 0) {
                return peringatan();
            }
            for (const p of produkList.value) {
                if (!p.master_produk_id || p.jumlah_beli < 1) {
                    return peringatan("Semua produk dan jumlah beli wajib diisi.");
                }
            }
            // Jika dari order produksi kasir (misal: ada flag khusus, atau lokasi == 'INTERNAL')
            if (lokasi.value === 'INTERNAL' || status_pembayaran.value == 2) {
                jumlah_bayar.value = total_bayar.value;
                kurang_bayar.value = 0;
            }
            Inertia.post("/apps/orderpenjualan", {
                nama: nama.value,
                no_telp: no_telp.value,
                alamat_pengiriman: alamat_pengiriman.value,
                tanggal_pengiriman: tanggal_pengiriman.value,
                jam_pengiriman: jam_pengiriman.value,
                tanggal_pembuatan: tanggal_pembuatan.value,
                status_pembayaran: status_pembayaran.value,
                keterangan: keterangan.value,
                produkList: produkList.value,
                jumlah_bayar: jumlah_bayar.value,
                total_bayar: total_bayar.value,
                kurang_bayar: kurang_bayar.value,
                metode_pembayaran: metode_pembayaran.value,
                tanggal_dp: tanggal_dp.value,
                keterangan_staf: keterangan_staf.value,
                penginput: penginput.value,
                lokasi: lokasi.value,
                outlet_id: outlet_id.value,
            }, {
                onSuccess: () => {
                    tutupModal();
                    Swal.fire({ icon: "success", title: "Sukses", text: "Data berhasil ditambah!", timer: 2000 });
                },
            });
        };

        const updateData = () => {
            if (!nama.value || produkList.value.length === 0) {
                return peringatan();
            }
            for (const p of produkList.value) {
                if (!p.master_produk_id || p.jumlah_beli < 1) {
                    return peringatan("Semua produk dan jumlah beli wajib diisi.");
                }
            }
            if (lokasi.value === 'INTERNAL' || status_pembayaran.value == 2) {
                jumlah_bayar.value = total_bayar.value;
                kurang_bayar.value = 0;
            }
            Inertia.put(`/apps/orderpenjualan/${id.value}`, {
                nama: nama.value,
                no_telp: no_telp.value,
                alamat_pengiriman: alamat_pengiriman.value,
                tanggal_pengiriman: tanggal_pengiriman.value,
                jam_pengiriman: jam_pengiriman.value,
                tanggal_pembuatan: tanggal_pembuatan.value,
                status_pembayaran: status_pembayaran.value,
                keterangan: keterangan.value,
                produkList: produkList.value,
                jumlah_bayar: jumlah_bayar.value,
                total_bayar: total_bayar.value,
                kurang_bayar: kurang_bayar.value,
                metode_pembayaran: metode_pembayaran.value,
                tanggal_dp: tanggal_dp.value,
                keterangan_staf: keterangan_staf.value,
                penginput: penginput.value,
                lokasi: lokasi.value,
                outlet_id: outlet_id.value,
            }, {
                onSuccess: () => {
                    tutupModal();
                    Swal.fire({ icon: "success", title: "Sukses", text: "Data berhasil diupdate!", timer: 2000 });
                },
            });
        };

        const showCicilanModal = ref(false);
        const cicilanData = ref({});

        const showCicilan = (item) => {
            cicilanData.value = item;
            showCicilanModal.value = true;
        };

        const jumlah_cicilan = ref(0);
        const formatRupiah = (angka) => {
            if (!angka) return 'Rp 0';
            return 'Rp ' + angka.toLocaleString('id-ID');
        };

        const simpanCicilan = () => {
            Inertia.post(`/apps/orderpenjualan/${cicilanData.value.id}/cicilan`, {
                jumlah_cicilan: jumlah_cicilan.value, // Pastikan ini bukan null/0
            }, {
                onSuccess: () => {
                    showCicilanModal.value = false;
                    jumlah_cicilan.value = 0;
                    Swal.fire({ icon: "success", title: "Sukses", text: "Cicilan berhasil disimpan!", timer: 2000 });
                    handleSearch();
                },
            });
        };

        const showRiwayatModal = ref(false);
        const riwayatData = ref({ jumlah_bayar: 0, total_bayar: 0, cicilan: [] });

        const showRiwayatCicilan = (order) => {
            riwayatData.value = {
                jumlah_bayar: order.jumlah_bayar,
                total_bayar: order.total_bayar,
                cicilan: order.cicilan || [],
            };
            showRiwayatModal.value = true;
        };

        // Hapus sortField, sortOrder, sortBy, toggleSortOrder

        // Total bayar otomatis dari outlet_price master_produk
        const jumlah_bayar = ref(0);
        const total_bayar = ref(0);
        const kurang_bayar = computed(() => total_bayar.value - jumlah_bayar.value);
        const metode_pembayaran = ref("Tunai");
        const tanggal_dp = ref(null);
        const keterangan_staf = ref("");
        const penginput = ref("");
        const lokasi = ref(null);
        const outlet_id = ref(null);

        watch(produkList, (list) => {
            let total = 0;
            list.forEach(p => {
                const prod = props.produk.find(mp => mp.id === p.master_produk_id);
                if (prod) total += (prod.outlet_price || 0) * (p.jumlah_beli || 1);
            });
            total_bayar.value = total;
        }, { deep: true });

        const deleteOrder = (id) => {
            Swal.fire({
                title: "Hapus Order?",
                text: "Data order akan dihapus permanen!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Ya, hapus!",
                cancelButtonText: "Batal",
            }).then((result) => {
                if (result.isConfirmed) {
                    Inertia.delete(`/apps/orderpenjualan/${id}`, {
                        onSuccess: () => {
                            Swal.fire("Berhasil!", "Order sudah dihapus.", "success");
                        },
                        onError: () => {
                            Swal.fire("Gagal!", "Order gagal dihapus.", "error");
                        },
                    });
                }
            });
        };

        return {
            showModal,
            updateSubmit,
            judul,
            nama,
            no_telp,
            alamat_pengiriman,
            jam_pengiriman,
            tanggal_pengiriman,
            tanggal_pembuatan,
            status_pembayaran,
            keterangan,
            produkList,
            buatBaru,
            editData,
            storeData,
            updateData,
            tutupModal,
            tambahProduk,
            hapusProduk,
            search,
            debouncedSearch,
            handleSearch,
            searchTanggalInput,
            searchTanggalPembuatan,
            searchTanggalPengiriman,
            searchStatusPembayaran,
            showCicilan,
            showCicilanModal,
            cicilanData,
            simpanCicilan,
            jumlah_cicilan,
            jumlah_bayar,
            total_bayar,
            kurang_bayar,
            metode_pembayaran,
            tanggal_dp,
            keterangan_staf,
            penginput,
            lokasi,
            showRiwayatModal,
            riwayatData,
            showRiwayatCicilan,
            formatRupiah,
            outlet_id,
            deleteOrder
        };
    },
};
</script>
