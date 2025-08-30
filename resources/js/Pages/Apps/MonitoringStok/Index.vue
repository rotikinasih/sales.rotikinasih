<template>

    <Head>
        <title>Monitoring Stok Kasir</title>
    </Head>
    <main>
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header d-flex justify-between align-items-center flex-wrap">
                    <h5 class="mb-2">Monitoring Stok Kasir</h5>
                    <div class="d-flex gap-2 align-items-center">
                        <!-- Dropdown Outlet -->
                        <select v-model="outlet_id" class="form-select mr-3" style="max-width:220px;" @change="getData">
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
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Nama Produk</th>
                                    <th>Harga</th>
                                    <th>Stok Awal</th> <!-- Tambahkan ini -->
                                    <th>Stok Masuk</th>
                                    <th>Stok Terjual</th>
                                    <th>Retur</th>
                                    <th>Final Stok</th>
                                    <th>Total Nominal Terjual</th>
                                    <th>Total Nominal Stok Akhir</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="item in data" :key="item.nama_produk">
                                    <td>{{ item.nama_produk }}</td>
                                    <td>Rp {{ formatHarga(item.harga) }}</td>
                                    <td>{{ item.stok_awal }}</td> <!-- Tambahkan ini -->
                                    <td>{{ item.stok_masuk }}</td>
                                    <td>{{ item.stok_terjual }}</td>
                                    <td>{{ item.retur }}</td>
                                    <td>{{ item.final_stok }}</td>
                                    <td>Rp {{ formatHarga(item.harga_terjual) }}</td>
                                    <td>Rp {{ formatHarga(item.harga_final) }}</td>
                                </tr>
                                <tr v-if="data.length === 0">
                                    <td colspan="9" class="text-center">Data Kosong</td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr style="font-weight: bold; background-color: #17a2b8; color: #fff;">
                                    <td>Total</td>
                                    <td></td>
                                    <td>{{ total.stok_awal }}</td> <!-- Tambahkan ini -->
                                    <td>{{ total.stok_masuk }}</td>
                                    <td>{{ total.stok_terjual }}</td>
                                    <td>{{ total.retur }}</td>
                                    <td>{{ total.final_stok }}</td>
                                    <td>Rp {{ formatHarga(total.harga_terjual) }}</td>
                                    <td>Rp {{ formatHarga(total.harga_final) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <div class="mt-3 text-end">
                        <span class="fw-bold">Total Diskon Hari Ini:</span>
                        <span class="text-danger fw-bold ms-2">Rp {{ formatHarga(total.diskon) }}</span>
                    </div>
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


export default {
    layout: LayoutApp,
    props: {
        data: Array,
        total: Object,
        tanggal: String,
        outlets: Array, // <-- tambahkan ini
        outlet_id: [String, Number], // <-- tambahkan ini
    },
    components: {
        Head, FlatPickr
    },
    setup(props) {
        const tanggal = ref(props.tanggal);
        const outlet_id = ref(props.outlet_id ?? (props.outlets[0]?.id ?? null));

        const getData = () => {
            Inertia.get("/apps/monitoring-stok", {
                tanggal: tanggal.value,
                outlet_id: outlet_id.value,
            }, {
                preserveState: true,
                replace: true
            });
        };

        const downloadExcel = () => {
            window.open(`/apps/monitoring-stok/export?tanggal=${tanggal.value}&outlet_id=${outlet_id.value}&type=excel`, "_blank");
        };

        const downloadPdf = () => {
            window.open(`/apps/monitoring-stok/export?tanggal=${tanggal.value}&outlet_id=${outlet_id.value}&type=pdf`, "_blank");
        };

        const printPage = () => {
            window.open(`/apps/monitoring-stok/print?tanggal=${tanggal.value}&outlet_id=${outlet_id.value}`, "_blank");
        };

        const formatHarga = (angka) => {
            if (!angka) return "0";
            return parseInt(angka).toLocaleString("id-ID");
        };
        return { formatHarga, tanggal, outlet_id, getData, downloadPdf, printPage, downloadExcel };
    },
};
</script>

<style>
.table th,
.table td {
    vertical-align: middle;
}
</style>
