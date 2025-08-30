<template>

    <Head>
        <title>Kasir</title>
    </Head>
    <main>
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header d-flex justify-between align-items-center flex-wrap">
                    <h5 class="mb-2">Kasir</h5>
                    <div class="d-flex gap-2 align-items-center">
                        <!-- Dropdown Outlet -->
                        <select v-model="outlet_id" class="form-select" style="max-width:220px;" @change="getData">
                            <option v-for="o in outlets" :key="o.id" :value="o.id">
                                {{ o.lokasi }}
                            </option>
                        </select>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-4 mt-3">

                        <!-- Kolom Produk -->
                        <div class="col-lg-8 col-12">
                            <div class="card border-0 shadow-sm rounded-4">
                                <div
                                    class="card-header bg-white border-0 px-4 py-3 d-flex flex-wrap align-items-center justify-content-between">
                                    <h5 class="fw-semibold text-dark mb-2 mb-lg-0">Daftar Produk</h5>

                                    <div class="d-flex gap-2 flex-wrap align-items-center ms-auto">
                                        <input type="text" v-model="searchProduk"
                                            class="form-control form-control-sm rounded-pill border-0 shadow-sm px-3"
                                            placeholder="Cari produk/barcode..." style="min-width: 170px" />

                                        <select v-model="selectedKategori"
                                            class="form-select form-select-sm rounded-pill border-0 shadow-sm px-3"
                                            style="min-width: 130px">
                                            <option value="">Kategori</option>
                                            <option v-for="kat in kategoriList" :key="kat.kode" :value="kat.kode">
                                                {{ kat.kode }}
                                            </option>
                                        </select>

                                        <button class="btn btn-sm btn-outline-warning rounded-pill px-3 shadow-sm mt-3 ml-3"
                                            @click="showModal = true">
                                            <i class="fa fa-boxes-stacked me-1"></i> Stok
                                        </button>
                                        <button class="btn btn-sm btn-outline-info rounded-pill px-3 shadow-sm mt-3"
                                            @click="gotoRekap">
                                            <i class="fa fa-chart-line me-1"></i> Rekap
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger rounded-pill px-3 shadow-sm mt-3"
                                            @click="showReturModal = true">
                                            <i class="fa fa-undo me-1"></i> Retur Produk
                                        </button>
                                    </div>
                                </div>

                                <div class="card-body px-4 pt-2 pb-4">
                                    <div class="row g-3">
                                        <div v-for="prd in filteredProduk" :key="prd.id" class="col-6 col-md-4 col-lg-3 mb-5">
                                            <div class="card border-0 h-100 shadow-sm rounded-4 product-card hover-scale"
                                                @click="tambahProduk(prd)" style="cursor: pointer; transition: all 0.2s">
                                                <div
                                                    class="card-body text-center d-flex flex-column justify-content-between px-2 py-3">
                                                    <h6 class="fw-semibold text-truncate text-dark mb-1">
                                                        {{ prd.nama_produk }}
                                                    </h6>
                                                    <span class="badge bg-light text-dark  rounded-pill small mb-2">
                                                        Stok: {{ prd.inventory_stok?.stok ?? prd.stok ?? 0 }}
                                                    </span>
                                                    <div class="fw-bold text-success fs-6">
                                                        Rp {{ formatHarga(prd.outlet_price) }}
                                                    </div>
                                                </div>
                                                <div class="card-footer bg-white border-0 text-center py-2 small text-muted">
                                                    {{ prd.kode }}
                                                </div>
                                            </div>
                                        </div>

                                        <div v-if="filteredProduk.length === 0" class="col-12 text-center text-muted py-5">
                                            <i class="fa fa-box-open fa-2x mb-2"></i><br />
                                            Produk tidak ditemukan.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Kolom Keranjang -->
                        <div class="col-lg-4 col-12">
                            <div class="card shadow border-0 h-100">
                                <div class="card-header text-white d-flex align-items-center justify-content-between"
                                    style="background-color: #3c8dbc;">
                                    <h5 class="mb-0 text-white">Keranjang</h5>
                                </div>
                                <div class="card-body px-3 py-4" style="max-height: 70vh; overflow-y: auto;">
                                    <ul class="list-group mb-3">
                                        <li v-for="(item, idx) in keranjang" :key="idx"
                                            class="list-group-item d-flex align-items-center justify-content-between">
                                            <div class="flex-grow-1">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <strong>{{ item.nama_produk }}</strong>
                                                        <br />
                                                        <small class="text-muted">
                                                            {{ item.jumlah }} x Rp {{ formatHarga(item.harga_jual) }} =
                                                            Rp {{ formatHarga(item.jumlah * item.harga_jual) }}
                                                        </small>
                                                    </div>
                                                    <button @click="hapusProduk(idx)"
                                                        class="btn btn-sm btn-outline-danger ms-2">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </div>
                                                <input type="number" v-model="item.jumlah" min="1"
                                                    class="form-control form-control-sm mt-2 w-50" />
                                            </div>
                                        </li>
                                        <li v-if="keranjang.length === 0" class="list-group-item text-center text-muted">
                                            <i class="fa fa-shopping-cart fa-lg mb-2"></i><br>
                                            Tidak ada item dalam keranjang.
                                        </li>
                                    </ul>
                                    <div class="mb-3 text-end fw-bold fs-5">
                                        Total: Rp {{ formatHarga(totalHarga) }}
                                    </div>
                                    <!-- Kolom Diskon dan Jumlah Uang Dibayar -->
                                    <!-- Kolom Jumlah Uang Dibayar (hanya untuk cash) -->
                                    <div class="form-group mb-3" v-if="pembayaran === 'cash'">
                                        <label class="fw-semibold mb-2 d-block">
                                            <i class="fa fa-calculator me-1"></i> Jumlah Uang Dibayar
                                        </label>
                                        <input
                                            type="number"
                                            class="form-control mb-2"
                                            v-model.number="jumlahBayar"
                                            min="0"
                                            placeholder="Masukkan nominal cash"
                                            @focus="inputAktif = 'jumlah'"
                                        /><button v-if="pembayaran === 'cash'" class="btn btn-outline-success px-4 py-2 fs-5" @click="setPas">
                                            Pas
                                        </button>
                                    </div>

                                    <!-- Kolom Diskon -->
                                    <div class="form-group mb-3">
                                        <label class="fw-semibold mb-2 d-block">
                                            <i class="fa fa-percent me-1"></i> Diskon
                                        </label>
                                        <input
                                            type="number"
                                            class="form-control"
                                            v-model.number="diskon"
                                            min="0"
                                            placeholder="Masukkan nominal diskon"
                                            @input="updateDiskon"
                                            @focus="inputAktif = 'diskon'"
                                        />
                                        <div v-if="diskon > 0" class="text-danger small mt-1">
                                            -Rp {{ formatHarga(diskon) }} (diskon)
                                        </div>
                                    </div>

                                    <!-- Tombol Angka -->
                                    <div class="mt-2 d-flex flex-wrap gap-2 justify-content-center">
                                        <button v-for="n in 10" :key="n" class="btn btn-light border px-4 py-2 fs-5"
                                            @click="appendAngka(n % 10)">
                                            {{ n % 10 }}
                                        </button>
                                        <button class="btn btn-light border px-4 py-2 fs-5" @click="hapusAngka">
                                            <i class="fas fa-arrow-left"></i>
                                        </button>
                                        <button class="btn btn-outline-secondary px-4 py-2 fs-5" @click="resetInput">
                                            C
                                        </button>
                                        
                                    </div>

                                    <!-- Pilihan Metode Pembayaran -->
                                    <div class="form-group mb-3">
                                        <label class="fw-semibold mb-2 d-block">
                                            <i class="fa fa-credit-card me-1"></i> Metode Pembayaran
                                        </label>
                                        <div class="d-flex gap-2 justify-content-between">
                                            <label class="btn btn-outline-success rounded-pill flex-fill mb-0"
                                                :class="{ active: pembayaran === 'cash' }">
                                                <input type="radio" class="d-none" value="cash" v-model="pembayaran" />
                                                <i class="fa fa-money-bill-wave me-1"></i> Cash
                                            </label>
                                            <label class="btn btn-outline-primary rounded-pill flex-fill mb-0"
                                                :class="{ active: pembayaran === 'bank' }">
                                                <input type="radio" class="d-none" value="bank" v-model="pembayaran" />
                                                <i class="fa fa-university me-1"></i> Bank
                                            </label>
                                        </div>
                                    </div>

                                    <!-- Total yang Perlu Dibayar -->
                                    <div class="form-group mb-3">
                                        <label class="fw-semibold mb-2 d-block">
                                            <i class="fa fa-money-bill me-1"></i> Jumlah yang Perlu Dibayar
                                        </label>
                                        <div class="form-control-plaintext fs-5 fw-bold text-success">
                                            Rp {{ formatHarga(totalSetelahDiskon) }}
                                            <span v-if="diskon > 0" class="text-danger small ms-2">
                                                (Diskon: -Rp {{ formatHarga(diskon) }})
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Kembalian (hanya cash) -->
                                    <div class="form-group mb-3" v-if="pembayaran === 'cash'">
                                        <label class="fw-semibold mb-2 d-block">
                                            <i class="fa fa-coins me-1"></i> Kembalian
                                        </label>
                                        <div class="form-control-plaintext fs-5 fw-bold"
                                            :class="jumlahBayar >= totalSetelahDiskon ? 'text-success' : 'text-danger'">
                                            Rp {{ formatHarga(jumlahBayar - totalSetelahDiskon) }}
                                        </div>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label class="fw-semibold mb-2 d-block">
                                            <i class="fa fa-user me-1"></i> Nama Pelanggan
                                        </label>
                                        <input type="text" class="form-control" v-model="pelanggan"
                                            placeholder="Nama pelanggan (opsional)" />
                                    </div>
                                    <button class="btn btn-success w-100 py-2" @click="checkout"
                                        :disabled="keranjang.length === 0">
                                        <i class="fa fa-cash-register me-1"></i> Checkout
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Penyesuaian Stok -->
        <Teleport to="body">
            <Modal :show="showModal" @close="showModal = false">
                <template #header>
                    <h5 class="modal-title">Penyesuaian Stok Produk</h5>
                </template>
                <template #body>
                    <div class="form-group mb-2">
                        <label>Produk</label>
                        <select class="form-select" v-model="form.master_produk_id">
                            <option value="">Pilih Produk</option>
                            <option v-for="prd in produk" :key="prd.id" :value="prd.id">
                                {{ prd.nama_produk }}
                            </option>
                        </select>
                    </div>
                    <div class="form-group mb-2">
                        <label>Stok Terakhir: <span class="fw-bold">{{produk.find(p => p.id ===
                            form.master_produk_id)?.inventory_stok?.stok ?? 0 }}</span></label>
                    </div>
                    <div class="form-group mb-2">
                        <label>Penyesuaian Stok (+/-)</label>
                        <input type="number" class="form-control" v-model="form.penyesuaian" />
                    </div>
                </template>
                <template #footer>
                    <form @submit.prevent="submitPenyesuaian">
                        <button type="submit" class="btn btn-success text-white m-2">Simpan</button>
                        <button type="button" class="btn btn-secondary m-2" @click="showModal = false">
                            Batal
                        </button>
                    </form>
                </template>
            </Modal>
        </Teleport>

        <!-- Modal Retur Produk -->
        <Teleport to="body">
            <Modal :show="showReturModal" @close="showReturModal = false">
                <template #header>
                    <h5 class="modal-title text-danger">
                        <i class="fa fa-undo me-2"></i> Retur Produk
                    </h5>
                </template>
                <template #body>
                    <div style="max-height:60vh; overflow-y:auto; padding-right:8px;">
                        <div v-for="(row, idx) in returRows" :key="idx" class="mb-3 pb-2 border-bottom">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <label class="form-label fw-semibold mb-0">Nama Produk</label>
                                <button v-if="returRows.length > 1" class="btn btn-sm btn-outline-danger ms-auto ml-4" @click="removeReturRow(idx)">
                                    <i class="fa fa-times"></i>
                                </button>
                            </div>
                            <div class="input-group mb-1">
                                <span class="input-group-text bg-light">
                                    <i class="fa fa-cube"></i>
                                </span>
                                <select class="form-select border-primary rounded-pill" v-model="row.produkId" @change="updateReturStok(idx)" style="font-weight:500;">
                                    <option value="" disabled>-- Pilih Produk --</option>
                                    <option v-for="prd in produk" :key="prd.id" :value="prd.id">
                                        {{ prd.nama_produk }} &mdash; Stok: {{ prd.inventory_stok?.stok ?? prd.stok ?? 0 }} &mdash; Rp {{ formatHarga(prd.outlet_price) }}
                                    </option>
                                </select>
                            </div>
                            <small v-if="!row.produkId" class="text-muted ms-2">Pilih produk yang ingin diretur</small>
                            <div v-if="row.produkId" class="mb-2">
                                <label class="form-label fw-semibold">Stok Saat Ini</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="fa fa-box"></i>
                                    </span>
                                    <input type="text" class="form-control" :value="row.stok" readonly>
                                </div>
                            </div>
                            <div v-if="row.produkId" class="mb-2">
                                <label class="form-label fw-semibold">Jumlah Retur</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="fa fa-sort-numeric-up"></i>
                                    </span>
                                    <input type="number" class="form-control" v-model.number="row.jumlah" :max="row.stok" min="1" placeholder="Masukkan jumlah retur" />
                                </div>
                                <small v-if="row.jumlah > row.stok" class="text-danger">Jumlah retur melebihi stok!</small>
                            </div>
                        </div>
                        <button class="btn btn-outline-primary w-100 mb-2" @click="addReturRow">
                            <i class="fa fa-plus"></i> Tambah Retur
                        </button>
                    </div>
                </template>
                <template #footer>
                    <button class="btn btn-danger px-4"
                        :disabled="returRows.some(row => !row.produkId || !row.jumlah || row.jumlah < 1 || row.jumlah > row.stok)"
                        @click="submitReturProdukMulti">
                        <i class="fa fa-undo me-1"></i> Retur
                    </button>
                    <button class="btn btn-secondary px-4" @click="showReturModal = false">
                        <i class="fa fa-times me-1"></i> Batal
                    </button>
                </template>
            </Modal>
        </Teleport>
    </main>
</template>

<script>
import LayoutApp from "../../../Layouts/App.vue";
import { Head } from "@inertiajs/inertia-vue3";
import Modal from "../../../Components/Modal.vue";
import Swal from "sweetalert2";
import { Inertia } from "@inertiajs/inertia";
import { ref, computed, watch, onMounted } from "vue";
import Multiselect from 'vue-multiselect'
import 'vue-multiselect/dist/vue-multiselect.min.css'

export default {
    layout: LayoutApp,
    components: { Head, Modal },
    props: { produk: Array, kategoriList: Array, outlets: Array, outlet_id: [String, Number] },
    setup(props) {
        const keranjang = ref([]);
        const showModal = ref(false);
        const form = ref({ master_produk_id: null, penyesuaian: 0 });
        const selectedKategori = ref("");
        const searchProduk = ref("");
        const pembayaran = ref("cash");
        const jumlahBayar = ref(0);
        const diskon = ref(0);
        const showDiskon = ref(false);
        const pelanggan = ref(""); // Tambahkan ini
        const inputAktif = ref('jumlah');
        const showReturModal = ref(false);
        const returProdukId = ref("");
        const returJumlah = ref(1);
        const returStok = ref(0);
        const outlet_id = ref(props.outlet_id ?? (props.outlets[0]?.id ?? null));

        // Multi-row retur
        const returRows = ref([
            { produkId: "", jumlah: 1, stok: 0 }
        ]);

        const formatHarga = (angka) => {
            if (angka == null || isNaN(angka)) return "0";
            return parseInt(angka).toLocaleString("id-ID");
        };

        const totalHarga = computed(() =>
            keranjang.value.reduce(
                (total, item) => total + item.jumlah * item.harga_jual,
                0
            )
        );

        // Total setelah diskon
        const totalSetelahDiskon = computed(() => {
            let hasil = totalHarga.value - diskon.value;
            return hasil < 0 ? 0 : hasil;
        });

        const filteredProduk = computed(() => {
            let produkFiltered = props.produk;
            if (selectedKategori.value) {
                produkFiltered = produkFiltered.filter(p => p.kode === selectedKategori.value);
            }
            if (searchProduk.value) {
                produkFiltered = produkFiltered.filter(p => p.nama_produk.toLowerCase().includes(searchProduk.value.toLowerCase()));
            }
            return produkFiltered;
        });

        const tambahProduk = (prd) => {
            // Jika user hanya punya satu outlet, misal outletId = 1 (ganti sesuai kebutuhan)
            const outletId = 1; // TODO: Ganti dengan id outlet aktif user jika dinamis
            const stok = prd.inventory_stok?.stok ?? 0;
            
            if (stok < 1) {
                Swal.fire("Stok habis", "Stok produk ini sudah habis!", "warning");
                return;
            }
            const idx = keranjang.value.findIndex((x) => x.id === prd.id);
            if (idx === -1) {
                keranjang.value.push({
                    id: prd.id,
                    nama_produk: prd.nama_produk,
                    jumlah: 1,
                    harga_jual: prd.outlet_price ?? 0,
                });
            } else {
                if (keranjang.value[idx].jumlah < stok) {
                    keranjang.value[idx].jumlah += 1;
                } else {
                    Swal.fire("Stok tidak cukup", "Jumlah melebihi stok!", "warning");
                }
            }
        };

        const hapusProduk = (idx) => {
            keranjang.value.splice(idx, 1);
        };

        const submitPenyesuaian = () => {
            if (!form.value.master_produk_id || form.value.penyesuaian === null) {
                return Swal.fire("Perhatian", "Produk dan penyesuaian stok wajib diisi!", "warning");
            }
            const stokAkhir = (props.produk.find(p => p.id === form.value.master_produk_id)?.inventory_stok?.stok ?? 0) + parseInt(form.value.penyesuaian);
            Inertia.post("/apps/kasir/edit-stok", {
                master_produk_id: form.value.master_produk_id,
                stok: stokAkhir,
            }, {
                onSuccess: () => {
                    showModal.value = false;
                    form.value = { master_produk_id: null, penyesuaian: 0 };
                    Swal.fire("Sukses", "Stok berhasil disesuaikan!", "success");
                }
            });
        };

        const checkout = () => {
            let jumlahBayarFinal = jumlahBayar.value;
            // Jika metode bank, isi otomatis
            if (pembayaran.value === 'bank') {
                jumlahBayarFinal = totalHarga.value; // atau totalHarga.value - diskon.value jika diskon sudah dikurangi
            }
            Inertia.post('/apps/kasir/checkout', {
                keranjang: keranjang.value,
                pembayaran: pembayaran.value,
                jumlah_bayar: jumlahBayarFinal,
                pelanggan: pelanggan.value,
                diskon: diskon.value,
                outlet_id: outlet_id.value,
            }, {
                onSuccess: () => {
                    keranjang.value = [];
                    jumlahBayar.value = 0;
                    diskon.value = 0;
                    Swal.fire('Sukses', 'Transaksi berhasil disimpan!', 'success');
                },
            });
        };

        const cetakStruk = () => {
            window.print();
        };

        const scanBarcode = () => {
            const found = props.produk.find(p => p.barcode === searchProduk.value);
            if (found) tambahProduk(found);
            searchProduk.value = "";
        };

        const gotoRekap = () => {
            Inertia.get("/apps/kasir/rekap");
        };

        const gotoRetur = () => {
            Inertia.get('/apps/kasir/retur');
        };

        const getData = () => {
            Inertia.get("/apps/kasir", {
                outlet_id: outlet_id.value,
            }, {
                preserveState: true,
                replace: true
            });
        };

        watch(keranjang, (val) => {
            localStorage.setItem('kasirKeranjang', JSON.stringify(val));
        }, { deep: true });

        onMounted(() => {
            const draft = localStorage.getItem('kasirKeranjang');
            if (draft) keranjang.value = JSON.parse(draft);
        });

        const toggleDiskon = () => {
            showDiskon.value = !showDiskon.value;
            if (!showDiskon.value) diskon.value = 0;
        };

        const updateDiskon = () => {
            // Jika diskon melebihi total, batasi
            if (diskon.value > totalHarga.value) diskon.value = totalHarga.value;
            if (diskon.value < 0) diskon.value = 0;
        };

        const appendAngka = (angka) => {
            if (inputAktif.value === 'diskon') {
                if (diskon.value === 0) diskon.value = angka;
                else diskon.value = parseInt(diskon.value.toString() + angka.toString());
                updateDiskon();
            } else {
                if (jumlahBayar.value === 0) jumlahBayar.value = angka;
                else jumlahBayar.value = parseInt(jumlahBayar.value.toString() + angka.toString());
            }
        };
        const hapusAngka = () => {
            if (inputAktif.value === 'diskon') {
                let str = diskon.value.toString();
                str = str.slice(0, -1);
                diskon.value = str ? parseInt(str) : 0;
            } else {
                let str = jumlahBayar.value.toString();
                str = str.slice(0, -1);
                jumlahBayar.value = str ? parseInt(str) : 0;
            }
        };
        const resetInput = () => {
            if (inputAktif.value === 'diskon') diskon.value = 0;
            else jumlahBayar.value = 0;
        };

        const setPas = () => {
            jumlahBayar.value = totalSetelahDiskon.value;
            inputAktif.value = 'jumlah';
        };

        // Multi-row retur
        const addReturRow = () => {
            returRows.value.push({ produkId: "", jumlah: 1, stok: 0 });
        };

        const removeReturRow = (idx) => {
            returRows.value.splice(idx, 1);
        };

        const updateReturStok = (idx) => {
            const row = returRows.value[idx];
            const prd = props.produk.find(p => p.id === row.produkId);
            row.stok = prd?.inventory_stok?.stok ?? 0;
            row.jumlah = 1;
        };

        const submitReturProdukMulti = () => {
            // Validasi
            if (returRows.value.some(row => !row.produkId || !row.jumlah || row.jumlah < 1 || row.jumlah > row.stok)) {
                Swal.fire("Perhatian", "Pilih produk dan jumlah retur yang valid!", "warning");
                return;
            }
            // Kirim array retur ke backend
            Inertia.post("/apps/retur-produk/store-multi", {
                items: returRows.value.map(row => ({
                    produk_id: row.produkId,
                    jumlah: row.jumlah,
                })),
            }, {
                onSuccess: () => {
                    showReturModal.value = false;
                    returRows.value = [{ produkId: "", jumlah: 1, stok: 0 }];
                    Swal.fire("Sukses", "Retur produk berhasil!", "success");
                    Inertia.reload({ only: ['produk'] });
                },
                onError: (err) => {
                    Swal.fire("Gagal", err.msg || "Gagal retur produk!", "error");
                }
            });
        };

        return {
            keranjang,
            tambahProduk,
            hapusProduk,
            formatHarga,
            totalHarga,
            totalSetelahDiskon,
            showModal,
            form,
            submitPenyesuaian,
            checkout,
            selectedKategori,
            filteredProduk,
            cetakStruk,
            searchProduk,
            scanBarcode,
            pembayaran,
            gotoRekap,
            gotoRetur,
            jumlahBayar,
            pelanggan,
            diskon,
            showDiskon,
            appendAngka,
            hapusAngka,
            resetInput,
            inputAktif,
            setPas,
            showReturModal,
            returProdukId,
            returJumlah,
            returStok,
            updateReturStok,
            addReturRow,
            removeReturRow,
            returRows,
            submitReturProdukMulti,
            outlet_id,
            getData,
        };
    },
};
</script>

<style scoped>
.product-card {
    transition: transform 0.2s;
    border-radius: 0.75rem;
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.07);
    background: linear-gradient(135deg, #f8fafc 80%, #e3e8ee 100%);
}

.product-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.05);
}

.hover-scale {
    transition: all 0.3s ease-in-out;
}

.hover-scale:hover {
    transform: scale(1.03);
}

option {
    font-size: 0.85rem;
}

.card-header.bg-gradient-primary {
    background: linear-gradient(90deg, #2563eb 60%, #1e40af 100%) !important;
}

.card-header.bg-gradient-success {
    background: linear-gradient(90deg, #22c55e 60%, #16a34a 100%) !important;
}

.btn.active, .btn:active {
    background-color: #198754 !important;
    color: #fff !important;
    border-color: #198754 !important;
}
.btn-outline-primary.active, .btn-outline-primary:active {
    background-color: #0d6efd !important;
    color: #fff !important;
    border-color: #0d6efd !important;
}

::-webkit-scrollbar {
    width: 8px;
    background: #e3e8ee;
}

::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 4px;
}
</style>