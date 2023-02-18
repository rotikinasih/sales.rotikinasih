<template>
    <Head>
        <title>Dashboard</title>
    </Head>
    <main>
        <!-- <div class="row">
            <div class="col-md-6 col-xl-4">
                <div class="card daily-sales">
                    <div class="card-block">
                        <h6 class="mb-4">Total Employees by PT</h6>
                        <div class="row d-flex align-items-center">
                            <div class="col-9">
                                <h3 class="f-w-300 d-flex align-items-center m-b-0"><BarChart :chartData="chartKaryawanPT" :options="options"/></h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-4">
                <div class="card daily-sales">
                    <div class="card-block">
                        <h6 class="mb-4">Total Employees by Division</h6>
                        <div class="row d-flex align-items-center">
                            <div class="col-9">
                                <h3 class="f-w-300 d-flex align-items-center m-b-0"><BarChart :chartData="chartKaryawanDivisi" :options="options"/></h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-4">
                <div class="card daily-sales">
                    <div class="card-block">
                        <h6 class="mb-4">Total Employees by Position</h6>
                        <div class="row d-flex align-items-center">
                            <div class="col-9">
                                <h3 class="f-w-300 d-flex align-items-center m-b-0"><BarChart :chartData="chartKaryawanJabatan" :options="options"/></h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> -->
        <div class="row">
            <div class="col-xl-8 col-md-6">
                <div class="card Recent-Users">
                    <div class="card-header">
                        <h5>Total Employees by Position</h5>
                    </div>
                    <div class="card-block px-0 py-3">
                        <div class="table-responsive">
                            <BarChart :chartData="chartKaryawanJabatan" :options="options"/>
                        </div>
                    </div>
                </div>
                <div class="card Recent-Users">
                    <div class="card-header">
                        <h5>New employee this month</h5>
                    </div>
                    <div class="card-block px-0 py-3">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Photo</th>
                                        <th>Employees Name</th>
                                        <th>Entry Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="unread" v-for="(baru, index) in karyawan_baru.data" :key="index">
                                        <td>{{ index + 1 }}</td>
                                        <td><img class="rounded-circle" style="width:40px;" v-if="baru.foto != null" :src="`/storage/${baru.foto}`" alt="activity-user"></td>
                                        <td>
                                            <h6 class="mb-1">{{ baru.nama_karyawan }}</h6>
                                            <p class="m-0">{{ baru.nik_penduduk }}</p>
                                        </td>
                                        <td><h6 class="text-muted"><span class="label theme-bg text-white f-12">{{ baru.tanggal_masuk }}</span></h6></td>
                                    </tr>
                                    <!-- jika data kosong -->
                                    <tr v-if="karyawan_baru.data[0] == undefined">
                                        <td colspan="4" class="text-center">
                                            <br>
                                            <i class="fa fa-file-excel fa-5x"></i><br><br>
                                                No Data To Display
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="card Recent-Users">
                    <div class="card-header">
                        <h5>The employee is approaching the end of the 2 month contract</h5>
                    </div>
                    <div class="card-block px-0 py-3">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Photo</th>
                                        <th>Employees Name</th>
                                        <th>End of Contract</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="unread" v-for="(kontrak, index) in karyawan_kontrak.data" :key="index">
                                        <td>{{ index + 1 }}</td>
                                        <td><img class="rounded-circle" style="width:40px;" v-if="kontrak.foto != null" :src="`/storage/${kontrak.foto}`" alt="activity-user"></td>
                                        <td>
                                            <h6 class="mb-1">{{ kontrak.nama_karyawan }}</h6>
                                            <p class="m-0">{{ kontrak.nik_penduduk }}</p>
                                        </td>
                                        <td><span class="label theme-bg2 text-white f-12">{{ kontrak.akhir_kontrak }}</span></td>
                                    </tr>
                                    <!-- jika data kosong -->
                                    <tr v-if="karyawan_kontrak.data[0] == undefined">
                                        <td colspan="4" class="text-center">
                                            <br>
                                            <i class="fa fa-file-excel fa-5x"></i><br><br>
                                                No Data To Display
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="card Recent-Users">
                    <div class="card-header">
                        <h5>Employees with the most violations</h5>
                    </div>
                    <div class="card-block px-0 py-3">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Photo</th>
                                        <th>Employees Name</th>
                                        <th>Amount of Violations</th>
                                        <!-- <th>Action</th> -->
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- {{ data_pelanggaran }} -->
                                    <tr class="unread" v-for="(pl, index) in data_pelanggaran" :key="index">
                                        <td>{{ index + 1 }}</td>
                                        <td><img class="rounded-circle" style="width:40px;" v-if="pl.foto != null" :src="`/storage/${pl.foto}`" alt="activity-user"></td>
                                        <td>
                                            <h6 class="mb-1">{{ pl.nama_karyawan }}</h6>
                                            <p class="m-0">{{ pl.nik_penduduk }}</p>
                                        </td>
                                        <td><h6 class="text-muted"><span class="label theme-bg3 text-white f-12">{{ pl.jumlah_pelanggaran }}</span></h6></td>
                                        <!-- <td><a href="#!" class="label theme-bg2 text-white f-12">Reject</a><a href="#!" class="label theme-bg text-white f-12">Approve</a></td> -->
                                    </tr>
                                    <!-- jika data kosong -->
                                    <tr v-if="data_pelanggaran[0] == undefined">
                                        <td colspan="4" class="text-center">
                                            <br>
                                            <i class="fa fa-file-excel fa-5x"></i><br><br>
                                                No Data To Display
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- sisi kanan -->
            <div class="col-xl-4 col-md-6">
                <div class="card card-event">
                    <div class="card-block">
                        <div class="row align-items-center justify-content-center">
                            <div class="col">
                                <h5 class="m-0">Total Employees by PT</h5>
                            </div>
                            <BarChart :chartData="chartKaryawanPT" :options="options"/>
                        </div>
                    </div>
                </div>
                <div class="card card-event">
                    <div class="card-block">
                        <div class="row align-items-center justify-content-center">
                            <div class="col">
                                <h5 class="m-0">Total Employees by Division</h5>
                            </div>
                            <BarChart :chartData="chartKaryawanDivisi" :options="options"/>
                        </div>
                    </div>
                </div>
                <div class="card card-event">
                    <div class="card-block" v-if="termuda != undefined">
                        <div class="row align-items-center justify-content-center">
                            <div class="col">
                                <h5 class="m-0">Youngest Employee</h5>
                            </div>
                            <div class="col-auto">
                                <label class="label theme-bg2 text-white f-14 f-w-400 float-right">{{ termuda.nik_karyawan }}</label>
                            </div>
                        </div>
                        <h2 class="mt-3 f-w-300">{{ termuda.umur }}<sub class="text-muted f-14"> years old</sub></h2>
                        <h6 class="text-muted mt-4 mb-0">{{ termuda.nama_karyawan }}</h6>
                        <i class="fab fa-angellist text-c-purple f-50"></i>
                    </div>
                    <div class="card-block" v-else>
                        <div class="row align-items-center justify-content-center">
                            <div class="col">
                                <h5 class="m-0">Youngest Employee</h5>
                            </div>
                        </div>
                        <span class="text-muted mt-4 mb-0">
                            <br>
                            <i class="fa fa-file-excel fa-5x"></i><br><br>
                            No Data To Display
                        </span>
                    </div>
                </div>
                <div class="card card-event">
                    <div class="card-block" v-if="tertua != undefined">
                        <div class="row align-items-center justify-content-center">
                            <div class="col">
                                <h5 class="m-0">Oldest Employee</h5>
                            </div>
                            <div class="col-auto">
                                <label class="label theme-bg2 text-white f-14 f-w-400 float-right">{{ tertua.nik_karyawan }}</label>
                            </div>
                        </div>
                        <h2 class="mt-3 f-w-300">{{ tertua.umur }}<sub class="text-muted f-14"> years old</sub></h2>
                        <h6 class="text-muted mt-4 mb-0">{{ tertua.nama_karyawan }}</h6>
                        <i class="fab fa-angellist text-c-purple f-50"></i>
                    </div>
                    <div class="card-block" v-else>
                        <div class="row align-items-center justify-content-center">
                            <div class="col">
                                <h5 class="m-0">Oldest Employee</h5>
                            </div>
                        </div>
                        <span class="text-muted mt-4 mb-0">
                            <br>
                            <i class="fa fa-file-excel fa-5x"></i><br><br>
                            No Data To Display
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </main>
</template>

<script>
    //import layout
    import LayoutApp from '../../../Layouts/App.vue';
    //import Head and useForm from Inertia
    import { Head } from '@inertiajs/inertia-vue3';
    //import ref from vue
    import { ref } from 'vue';
    //chart
    import { BarChart, DoughnutChart } from 'vue-chart-3';
    import { Chart, registerables } from "chart.js";
    //register chart
    Chart.register(...registerables);

    export default {
        //layout
        layout : LayoutApp,
        //register component
        components:{
            Head,
            BarChart,
            DoughnutChart
        },
        //props
        props:{
            karyawan_baru: Object,
            karyawan_kontrak: Object,
            data_pelanggaran: Object,
            tertua: Object,
            termuda: Object,
            //chart berdasarkan PT
            pt: Array,
            total_pt: Array,
            //chart berdasarkan Divisi
            divisi: Array,
            total_divisi: Array,
            //chart berdasarkan Jabatan
            jabatan: Array,
            total_jabatan: Array,
        },
        setup(props){
             //method random color
             function randomBackgroundColor(length) {
                var data = [];
                for (var i = 0; i < length; i++) {
                    data.push(getRandomColor());
                }
                return data;
            }

            //method generate random color
            function getRandomColor() {
                var letters = '0123456789ABCDEF'.split('');
                var color = '#';
                for (var i = 0; i < 6; i++) {
                    color += letters[Math.floor(Math.random() * 16)];
                }
                return color;
            }

            //option chart
            const options = ref({
                responsive: true,
                plugins: {
                    legend: {
                        display: false,
                    },
                    title: {
                        display: false,
                    },
                },
                beginZero: true
            });

            //chart karyawan berdasarkan PT
            const chartKaryawanPT = {
                labels: props.pt,
                datasets: [{
                    data: props.total_pt,
                    backgroundColor: randomBackgroundColor(5),
                }, ],
            };

            //chart karyawan berdasarkan Divisi
            const chartKaryawanDivisi = {
                labels: props.divisi,
                datasets: [{
                    data: props.total_divisi,
                    backgroundColor: randomBackgroundColor(5),
                }, ],
            };

            //chart karyawan berdasarkan jabatan
            const chartKaryawanJabatan = {
                labels: props.jabatan,
                datasets: [{
                    data: props.total_jabatan,
                    backgroundColor: randomBackgroundColor(5),
                }, ],
            };

            return{
                options,
                chartKaryawanPT,
                chartKaryawanDivisi,
                chartKaryawanJabatan
            }
        }
    }
</script>
