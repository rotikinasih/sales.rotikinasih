<template>

    <Head>
        <title>List Purchase Order</title>
    </Head>
    <main>
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header d-flex justify-between align-items-center flex-wrap">
                    <h5 class="mb-2">List Purchase Order</h5>
                    <div class="input-group">
                        <!-- Dropdown Outlet -->
                        <select class="form-control me-2" v-model="filters.outlet_id" @change="getData">
                            <option v-for="o in outlets" :key="o.id" :value="o.id">{{ o.lokasi }}</option>
                        </select>
                        <span class="input-group-text bg-white">
                            <i class="fa fa-calendar"></i>
                        </span>
                        <flat-pickr v-model="filters.tanggal" @on-change="getData" class="form-control"
                            :config="{ dateFormat: 'Y-m-d', allowInput: true }"
                            placeholder="Pilih tanggal distribusi" />
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Outlet</th>
                                    <th>Kode Distribusi</th>
                                    <th class="text-center">Tanggal & Waktu Pengiriman</th>
                                    <th>Jumlah Produk</th>

                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(item, index) in distribusiProduks.data" :key="item.id">
                                    <td>{{ index + distribusiProduks.from }}</td>
                                    <td>{{ item.order_penjualan?.nama || '-' }}</td>
                                    <td>{{ item.order_penjualan?.kode_distribusi || '-' }}</td>

                                    <td class="text-center">
                                        {{ item.order_penjualan?.tanggal_pengiriman ?? '-' }}<br />
                                        {{ item.order_penjualan?.jam_pengiriman ?? '-' }}
                                    </td>
                                    <td>
                                        <ul>
                                            <li v-for="detail in item.order_penjualan?.details || []" :key="detail.id">
                                                {{ detail.master_produk?.nama_produk || '-' }} ({{ detail.jumlah_beli
                                                }})
                                            </li>
                                        </ul>
                                    </td>

                                </tr>
                                <tr v-if="distribusiProduks.data.length === 0">
                                    <td colspan="9" class="text-center">Data Kosong</td>
                                </tr>
                            </tbody>
                        </table>
                        <Pagination v-if="distribusiProduks.links" :links="distribusiProduks.links" align="end" />
                    </div>
                </div>
            </div>
        </div>
    </main>
</template>

<script>
import { Head } from "@inertiajs/inertia-vue3";
import LayoutApp from "../../../Layouts/App.vue";
import Pagination from "../../../Components/Pagination.vue";
import FlatPickr from "vue-flatpickr-component";
import { ref } from "vue";
import { Inertia } from "@inertiajs/inertia";
import "flatpickr/dist/flatpickr.css";

export default {
    layout: LayoutApp,
    components: {
        Head,
        Pagination,
        FlatPickr,
    },
    props: {
        distribusiProduks: Object,
        filters: Object,
        kendaraanOptions: Array,
        outlets: Array,
        outlet_id: [String, Number],
    },
    setup(props) {
        const filters = ref({
            tanggal: props.filters?.tanggal || "",
            outlet_id: props.outlet_id || (props.outlets?.[0]?.id ?? ""),
        });

        const getData = () => {
            Inertia.get("/apps/list-purchase-order", {
                tanggal: filters.value.tanggal,
                outlet_id: filters.value.outlet_id,
            }, {
                preserveState: true,
                replace: true
            });
        };

        return {
            filters,
            getData,
            outlets: props.outlets,
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
