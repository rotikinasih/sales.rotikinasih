<template>
    <Head>
        <title>Laporan Keuangan Harian</title>
    </Head>
    <main :key="outlet_id">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header d-flex justify-between align-items-center flex-wrap">
                    <h5 class="mb-2">Laporan Keuangan Harian</h5>
                    <div class="d-flex gap-2 align-items-center">
                        <!-- Dropdown Outlet -->
                        <select v-model.number="outlet_id" class="form-select mr-3" style="max-width:220px;" @change="getData">
                            <option :value="0">All Outlet</option> <!-- Tambahkan ini -->
                            <option v-for="o in outlets" :key="o.id" :value="o.id">
                                {{ o.lokasi }}
                            </option>
                        </select>
                        <div class="input-group" style="max-width:220px;">
                            <span class="input-group-text bg-white">
                                <i class="fa fa-calendar"></i>
                            </span>
                            <flat-pickr
                                v-model="tanggal"
                                @on-change="getData"
                                class="form-control"
                                :config="{ dateFormat: 'Y-m-d', allowInput: true }"
                                placeholder="Pilih tanggal" />
                        </div>
                        <button class="btn btn-outline-success btn-sm ml-3" @click="downloadExcel">
                            <i class="fa fa-file-excel me-1"></i> Excel
                        </button>
                        <button class="btn btn-outline-danger btn-sm" @click="downloadPdf">
                            <i class="fa fa-file-pdf me-1"></i> PDF
                        </button>
                        <button class="btn btn-outline-primary btn-sm" @click="printPage">
                            <i class="fa fa-print me-1"></i> Cetak
                        </button>
                    </div>
                </div>

                <div class="card-body">
                    <!-- BLOK 1: PENJUALAN OUTLET -->
                    <h6 class="fw-bold mb-2">Penjualan Outlet</h6>
                    <div class="table-responsive mb-4">
                        <table class="table table-bordered align-middle">
                            <tbody>
                                <tr>
                                    <td>Penjualan Outlet (Gross Sales)</td>
                                    <td class="text-end">Rp {{ formatHarga(omset) }}</td>
                                </tr>
                                <tr>
                                    <td>Total Diskon</td>
                                    <td class="text-end">Rp {{ formatHarga(total_diskon) }}</td>
                                </tr>
                                <tr>
                                    <td>Biaya Retur Produk</td>
                                    <td class="text-end">Rp {{ formatHarga(biaya_retur) }}</td>
                                </tr>
                                <tr class="fw-bold">
                                    <td>Net Sales (Penjualan Bersih)</td>
                                    <td class="text-end">
                                        Rp {{ formatHarga(omset - total_diskon - biaya_retur) }}
                                    </td>
                                </tr>
                                <tr>
                                    <td>Total Modal Produk Terjual Hari Ini</td>
                                    <td class="text-end">Rp {{ formatHarga(total_modal_terjual) }}</td>
                                </tr>
                                <tr class="fw-bold ">
    <td>Gross Profit (Laba Kotor)</td>
    <td class="text-end">
        Rp {{ formatHarga((omset - total_diskon - biaya_retur) - total_modal_terjual) }}
    </td>
</tr>

                            </tbody>
                        </table>
                    </div>

                    <!-- BLOK 2: PESANAN MASUK -->
                    <h6 class="fw-bold mb-2">Pesanan Masuk</h6>
                    <div class="table-responsive mb-4">
                        <table class="table table-bordered align-middle">
                            <tbody>
                                <tr>
                                    <td>Total Biaya Pesanan Masuk</td>
                                    <td class="text-end">Rp {{ formatHarga(total_biaya_pesanan) }}</td>
                                </tr>
                                <tr>
                                    <td>Total Modal Pesanan Masuk</td>
                                    <td class="text-end">Rp {{ formatHarga(total_modal_pesanan) }}</td>
                                </tr>
                                <tr>
                                    <td>Total DP Hari Ini</td>
                                    <td class="text-end">Rp {{ formatHarga(total_dp) }}</td>
                                </tr>
                                <tr>
                                    <td>Total Pembayaran Lunas</td>
                                    <td class="text-end">Rp {{ formatHarga(total_lunas) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- BLOK 3: BIAYA LAIN -->
                    <h6 class="fw-bold mb-2">Biaya Lain</h6>
                    <div class="table-responsive mb-4">
                        <table class="table table-bordered align-middle">
                            <tbody>
                                <tr>
                                    <td>Biaya Stok Datang</td>
                                    <td class="text-end">Rp {{ formatHarga(biaya_stok_datang) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- BLOK 4: RINGKASAN -->
                    <h6 class="fw-bold mb-2">Ringkasan</h6>
<div class="table-responsive">
    <table class="table table-bordered align-middle">
        <tbody>
            <tr style="font-weight: bold; background-color: #17a2b8; color: #fff;">
                <td>Net Income / Laba Bersih Sementara</td>
                <td class="text-end">
                    Rp {{
                        formatHarga(
                            omset
                            - total_diskon
                            - biaya_retur
                            - total_modal_terjual
                            + (total_biaya_pesanan - total_modal_pesanan)
                        )
                    }}
                </td>
            </tr>
        </tbody>
    </table>
</div>

                </div>
            </div>
        </div>
    </main>
</template>


<script>
import { Head, usePage } from "@inertiajs/inertia-vue3";
import FlatPickr from "vue-flatpickr-component";
import "flatpickr/dist/flatpickr.css";
import { ref } from "vue";
import { Inertia } from "@inertiajs/inertia";
import LayoutApp from "../../../Layouts/App.vue";

export default {
    layout: LayoutApp,
    props: {
        tanggal: String,
        outlets: Array, // <-- tambahkan ini
        outlet_id: [String, Number], // <-- tambahkan ini
        omset: Number,
        biaya_retur: Number,
        biaya_stok_datang: Number,
        total_dp: Number,
        total_lunas: Number,
        total_diskon: Number,
        total_transaksi: Number,
        total_retur: Number,
        total_pesanan: Number,
        total_modal_terjual: Number,
        total_biaya_pesanan: Number,
        total_modal_pesanan: Number,
    },
    components: { Head, FlatPickr },
    setup(props) {
        const tanggal = ref(props.tanggal);
        const outlet_id = ref(props.outlet_id ?? (props.outlets[0]?.id ?? null));

        const getData = () => {
            Inertia.get("/apps/laporan-keuangan-harian", {
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

        const downloadExcel = () => {
    window.open(`/apps/laporan-keuangan-harian/export?tanggal=${tanggal.value}&outlet_id=${outlet_id.value}&type=excel`, "_blank");
};
const downloadPdf = () => {
    window.open(`/apps/laporan-keuangan-harian/export?tanggal=${tanggal.value}&outlet_id=${outlet_id.value}&type=pdf`, "_blank");
};
const printPage = () => {
    window.open(`/apps/laporan-keuangan-harian/print?tanggal=${tanggal.value}&outlet_id=${outlet_id.value}`, "_blank");
};

     

        return {
            tanggal,
            outlet_id,
            getData,
            formatHarga,
            downloadExcel,
            downloadPdf,
            printPage,
            
        };
    },
};
</script>
