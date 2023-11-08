<template>
    <Head>
        <title>Dashboard</title>
    </Head>
    <main>
        <div class="row">
            <!-- <div class="col-md-3">
                <div class="card card-event">
                    <div class="card-block" v-if="total_karyawan_aktif != undefined">
                        <div class="row align-items-center justify-content-center">
                            <div class="col">
                                <h4 class="m-0">Total Karyawan</h4>
                            </div>
                            <div class="col-auto">
                                <label class="label theme-bg2 text-white f-14 f-w-400 float-right"></label>
                            </div>
                        </div>
                        <h3 class="mt-3 f-w-300">{{ total_karyawan_aktif }}<sub class="text-muted f-14"> </sub></h3>
                        <h4 class="text-muted mt-4 mb-0"></h4>
                        <i class="fab fa-angellist text-c-purple f-50"></i>
                    </div>
                </div>
            </div> -->
            <div class="col-md-4">
                <div class="card card-event">
                    <div class="card-block" v-if="termuda != undefined">
                        <div class="row align-items-center justify-content-center">
                            <div class="col">
                                <h5 class="m-0">Karyawan Termuda</h5>
                            </div>
                            <div class="col-auto">
                                <label class="label theme-bg2 text-white f-14 f-w-400 float-right">{{ termuda.nik_karyawan }}</label>
                            </div>
                        </div>
                        <h4 class="mt-3 f-w-300">{{ termuda.umur }}<sub class="text-muted f-14"> Tahun</sub></h4>
                        <h6 class="text-muted mt-4 mb-0">{{ termuda.nama_lengkap }}</h6>
                        <!-- <i class="fab fa-angellist text-c-purple f-50"></i> -->
                    </div>
                    <div class="card-block" v-else>
                        <div class="row align-items-center justify-content-center">
                            <div class="col">
                                <h5 class="m-0">Karyawan Termuda</h5>
                            </div>
                        </div>
                        <span class="text-muted mt-4 mb-0">
                            <br>
                            <i class="fa fa-file-excel fa-5x"></i><br><br>
                            Data Kosong
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-event">
                    <div class="card-block" v-if="tertua != undefined">
                        <div class="row align-items-center justify-content-center">
                            <div class="col">
                                <h5 class="m-0">Karyawan Tertua</h5>
                            </div>
                            <div class="col-auto">
                                <label class="label theme-bg2 text-white f-14 f-w-400 float-right">{{ tertua.nik_karyawan }}</label>
                            </div>
                        </div>
                        <h4 class="mt-3 f-w-300">{{ tertua.umur }}<sub class="text-muted f-14"> Tahun</sub></h4>
                        <h6 class="text-muted mt-4 mb-0">{{ tertua.nama_lengkap }}</h6>
                        <!-- <i class="fab fa-angellist text-c-purple f-50"></i> -->
                    </div>
                    <div class="card-block" v-else>
                        <div class="row align-items-center justify-content-center">
                            <div class="col">
                                <h5 class="m-0">Karyawan Tertua</h5>
                            </div>
                        </div>
                        <span class="text-muted mt-4 mb-0">
                            <br>
                            <i class="fa fa-file-excel fa-5x"></i><br><br>
                            Data Kosong 
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-event">
                    <div class="card-block" v-if="terlama != undefined">
                        <div class="row align-items-center justify-content-center">
                            <div class="col">
                                <h5 class="m-0">Karyawan Terlama</h5>
                            </div>
                            <div class="col-auto">
                                <label class="label theme-bg2 text-white f-14 f-w-400 float-right">{{ terlama.nik_karyawan }}</label>
                            </div>
                        </div>
                        <h4 class="mt-3 f-w-300">{{ terlama.masa_kerja_tahun }}<sub class="text-muted f-14"></sub></h4>
                        <h6 class="text-muted mt-4 mb-0">{{ terlama.nama_lengkap }}</h6>
                        <!-- <i class="fab fa-angellist text-c-purple f-50"></i> -->
                    </div>
                    <div class="card-block" v-else>
                        <div class="row align-items-center justify-content-center">
                            <div class="col">
                                <h5 class="m-0">Karyawan Terlama</h5>
                            </div>
                        </div>
                        <span class="text-muted mt-4 mb-0">
                            <br>
                            <i class="fa fa-file-excel fa-5x"></i><br><br>
                            Data Kosong 
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- grafik -->
        <div class="row">
            <div class="col-md-12">
                <div class="card Recent-Users">
                    <div class="card-header">
                        <h5>Total Karyawan berdasarkan Entitas</h5>
                    </div>
                    <div class="card-block px-0 py-3">
                        <div class="table-responsive">
                            <BarChart :chartData="chartKaryawanPT" :options="options" style="height: 250px;"/>
                        </div>
                    </div>
                </div>
                <div class="card Recent-Users">
                    <div class="card-header">
                        <h5>Total Karyawan berdasarkan Divisi</h5>
                    </div>
                    <div class="card-block px-0 py-3">
                        <div class="table-responsive">
                            <BarChart :chartData="chartKaryawanDivisi" :options="options" style="height: 250px;"/>
                        </div>
                    </div>
                </div>
                <div class="card Recent-Users">
                    <div class="card-header">
                        <h5>Total Karyawan Berdasarkan Jabatan</h5>
                    </div>
                    <div class="card-block px-0 py-3">
                        <div class="table-responsive">
                            <BarChart :chartData="chartKaryawanJabatan" :options="options" style="height: 250px;"/>
                        </div>
                    </div>
                </div>
                <!-- <div class="card Recent-Users">
                    <div class="card-header">
                        <h5>Total Karyawan Berdasarkan Posisi</h5>
                    </div>
                    <div class="card-block px-0 py-3">
                        <div class="table-responsive">
                            <BarChart :chartData="chartPosisi" :options="options" style="height: 250px;"/>
                        </div>
                    </div>
                </div> -->
                <div class="card Recent-Users">
                    <div class="card-header">
                        <h5>Total Karyawan Berdasarkan Kota Penugasan</h5>
                    </div>
                    <div class="card-block px-0 py-3">
                        <div class="table-responsive">
                            <BarChart :chartData="chartKotaPenugasan" :options="options" style="height: 250px;"/>
                        </div>
                    </div>
                </div>
                <div class="card Recent-Users">
                    <div class="card-header">
                        <h5>Total Karyawan Berdasarkan Komposisi Generasi</h5>
                    </div>
                    <div class="card-block px-0 py-3">
                        <div class="table-responsive">
                            <BarChart :chartData="chartKomposisiGenerasi" :options="options" style="height: 250px;"/>
                        </div>
                    </div>
                </div>
                <div class="card Recent-Users">
                    <div class="card-header">
                        <h5>Total Karyawan Berdasarkan Pendidikan</h5>
                    </div>
                    <div class="card-block px-0 py-3">
                        <div class="table-responsive">
                            <BarChart :chartData="chartPendidikan" :options="options" style="height: 250px;"/>
                        </div>
                    </div>
                </div>
                <div class="card Recent-Users">
                    <div class="card-header">
                        <h5>Total Karyawan Berdasarkan Rentang Umur</h5>
                    </div>
                    <div class="card-block px-0 py-3">
                        <div class="table-responsive">
                            <BarChart :chartData="chartUmur" :options="options" style="height: 250px;"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="card Recent-Users">
                    <div class="card-header">
                        <h5>Total Karyawan Berdasarkan Jenis Kelamin</h5>
                    </div>
                    <div class="card-block px-0 py-3">
                        <div class="table-responsive">
                            <BarChart :chartData="chartJenisKelamin" :options="options" style="height: 250px;"/>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card Recent-Users">
                    <div class="card-header">
                        <h5>Total Karyawan Berdasarkan Komposisi Peran</h5>
                    </div>
                    <div class="card-block px-0 py-3">
                        <div class="table-responsive">
                            <BarChart :chartData="chartKomposisiPeran" :options="options" style="height: 250px;"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="card Recent-Users">
                    <div class="card-header">
                        <h5>Total Karyawan Berdasarkan Status Pernikahan</h5>
                    </div>
                    <div class="card-block px-0 py-3">
                        <div class="table-responsive">
                            <BarChart :chartData="chartStatusPernikahan" :options="options" style="height: 250px;"/>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card Recent-Users">
                    <div class="card-header">
                        <h5>Total Karyawan Berdasarkan Status Kerja</h5>
                    </div>
                    <div class="card-block px-0 py-3">
                        <div class="table-responsive">
                            <BarChart :chartData="chartStatusKerja" :options="options" style="height: 250px;"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card Recent-Users">
                    <div class="card-header">
                        <h5>Total Karyawan PHK</h5>
                    </div>
                    <div class="card-block px-0 py-3">
                        <div class="table-responsive">
                            <BarChart :chartData="chartKaryawanPHK" :options="options" style="height: 250px;"/>
                        </div>
                    </div>
                </div>
                <div class="card Recent-Users">
                    <div class="card-header">
                        <h5>Total Karyawan Resign</h5>
                    </div>
                    <div class="card-block px-0 py-3">
                        <div class="table-responsive">
                            <BarChart :chartData="chartKaryawanResign" :options="options" style="height: 250px;"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card Recent-Users">
                    <div class="card-header">
                        <h5>Karyawan Baru Kurang Lebih 1 bulan</h5>
                    </div>
                    <div class="card-block px-0 py-3">
                        <div class="container">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <!-- <th>Foto</th> -->
                                            <th>Nama Lengkap</th>
                                            <th>Entitas</th>
                                            <!-- <th>Divisi</th>
                                            <th>Jabatan</th>
                                            <th>Posisi</th> -->
                                            <th>Tanggal Masuk</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="unread" v-for="(baru, index) in karyawan_baru" :key="index">
                                            <td>{{ index + 1}}</td>
                                            <!-- <td><img class="rounded-circle" style="width:40px;" v-if="baru.foto != null" :src="`/storage/${baru.foto}`" alt="activity-user"></td> -->
                                            <td>
                                                <h6 class="mb-1">{{ baru.nama_lengkap }}</h6>
                                                <p class="m-0">{{ baru.nik_penduduk }}</p>
                                            </td>
                                            <td>{{ baru.perusahaan.nama_pt }}</td>
                                            <!-- <td>{{ baru.divisi.nama_divisi }}</td>
                                            <td>{{ baru.jabatan.nama_jabatan }}</td>
                                            <td>{{ baru.posisi.nama_posisi }}</td> -->
                                            <td><h6 class="text-muted"><span class="label theme-bg text-white f-12">{{ baru.tanggal_masuk }}</span></h6></td>
                                        </tr>
                                        <!-- jika data kosong -->
                                        <tr v-if="karyawan_baru[0] == undefined">
                                            <td colspan="8" class="text-center">
                                                <br>
                                                <i class="fa fa-file-excel fa-5x"></i><br><br>
                                                    Data Kosong
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <!-- <div class="row" style="max-width:100%; overflow-x:hidden">
                                    <div class="col-md-4">
                                        <label v-if="karyawan_baru[0] != undefined" align="start">Showing {{ karyawan_baru.from }} to {{ karyawan_baru.to }} of {{ karyawan_baru.total }} items</label>
                                    </div>
                                    <div class="col-md-8">
                                        <Pagination v-if="karyawan_baru[0] != undefined" :links="karyawan_baru.links" align="end"/>
                                    </div>
                                </div> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="card Recent-Users">
                    <div class="card-header">
                        <!-- <h5>The employee is approaching the end of the 2 month contract</h5> -->
                        <h5>Karyawan Mendekati Akhir Kontrak 2 Bulan</h5>
                    </div>
                    <div class="card-block px-0 py-3">
                        <div class="container">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <!-- <th>Foto</th> -->
                                            <th>Nama Lengkap</th>
                                            <th>NIK (Karyawan)</th>
                                            <th>Entitas</th>
                                            <!-- <th>Divisi</th>
                                            <th>Jabatan</th>
                                            <th>Posisi</th> -->
                                            <th>Akhir Kontrak</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="unread" v-for="(kontrak, index) in karyawan_kontrak" :key="index">
                                            <td>{{ index + 1 }}</td>
                                            <!-- <td><img class="rounded-circle" style="width:40px;" v-if="kontrak.foto != null" :src="`/storage/${kontrak.foto}`" alt="activity-user"></td> -->
                                            <td>
                                                <h6 class="mb-1">{{ kontrak.nama_lengkap }}</h6>
                                                <p class="m-0">{{ kontrak.nik_penduduk }}</p>
                                            </td>
                                            <td>{{ kontrak.nik_karyawan }}</td>
                                            <td>{{ kontrak.perusahaan.nama_pt }}</td>
                                            <!-- <td>{{ kontrak.divisi.nama_divisi }}</td>
                                            <td>{{ kontrak.jabatan.nama_jabatan }}</td>
                                            <td>{{ kontrak.posisi.nama_posisi }}</td> -->
                                            <td><span class="label theme-bg2 text-white f-12">{{ kontrak.akhir_kontrak }}</span></td>
                                        </tr>
                                        <!-- jika data kosong -->
                                        <tr v-if="karyawan_kontrak[0] == undefined">
                                            <td colspan="8" class="text-center">
                                                <br>
                                                <i class="fa fa-file-excel fa-5x"></i><br><br>
                                                    Data Kosong
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <!-- <div class="row" style="max-width:100%; overflow-x:hidden">
                                    <div class="col-md-4">
                                        <label v-if="karyawan_kontrak[0] != undefined" align="start">Showing {{ karyawan_kontrak.from }} to {{ karyawan_kontrak.to }} of {{ karyawan_kontrak.total }} items</label>
                                    </div>
                                    <div class="col-md-8">
                                        <Pagination v-if="karyawan_kontrak[0] != undefined" :links="karyawan_kontrak.links" align="end"/>
                                    </div>
                                </div> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="card Recent-Users">
                    <div class="card-header">
                        <!-- <h5>Employees that has 3 violations or more</h5> -->
                        <h5>Karyawan yang melakukan 3 kali pelanggaran atau lebih</h5>
                    </div>
                    <div class="card-block px-0 py-3">
                        <div class="container">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr class="">
                                            <th>#</th>
                                            <!-- <th>Foto</th> -->
                                            <th>Nama Lengkap</th>
                                            <th>NIK (Karyawan)</th>
                                            <th>Entitas</th>
                                            <!-- <th>Divisi</th>
                                            <th>Jabatan</th>
                                            <th>Posisi</th> -->
                                            <th class="text-center">Jumlah Pelanggaran</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="unread" v-for="(pl, index) in data_pelanggaran" :key="index">
                                            <td class="text-center">{{ index + 1 }}</td>
                                            <!-- <td><img class="rounded-circle" style="width:40px;" v-if="pl.foto != null" :src="`/storage/${pl.foto}`" alt="activity-user"></td> -->
                                            <td>
                                                <h6 class="mb-1">{{ pl.nama_lengkap }}</h6>
                                                <p class="m-0">{{ pl.nik_penduduk }}</p>
                                            </td>
                                            <td>{{ pl.nik_karyawan }}</td>
                                            <td>{{ pl.nama_pt }}</td>
                                            <!-- <td class="text-center">{{ pl.nama_divisi }}</td> -->
                                            <!-- <td class="text-center">{{ pl.nama_jabatan }}</td> -->
                                            <!-- <td class="text-center">{{ pl.nama_posisi }}</td> -->
                                            <td class="text-center"><h6 class="text-muted"><span class="label theme-bg3 text-white f-12">{{ pl.jumlah_pelanggaran }}</span></h6></td>
                                        </tr>
                                        <!-- jika data kosong -->
                                        <tr v-if="data_pelanggaran[0] == undefined">
                                            <td colspan="8" class="text-center">
                                                <br>
                                                <i class="fa fa-file-excel fa-5x"></i><br><br>
                                                    Data Kosong
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <!-- <div class="row" style="max-width:100%; overflow-x:hidden">
                                    <div class="col-md-4">
                                        <label v-if="data_pelanggaran[0] != undefined" align="start">Showing {{ data_pelanggaran.from }} to {{ data_pelanggaran.to }} of {{ data_pelanggaran.total }} items</label>
                                    </div>
                                    <div class="col-md-8">
                                        <Pagination v-if="data_pelanggaran[0] != undefined" :links="data_pelanggaran.links" align="end"/>
                                    </div>
                                </div> -->
                            </div>
                        </div>
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
    import { BarChart, DoughnutChart, PieChart } from 'vue-chart-3';
    import { Chart, registerables } from "chart.js";
    //register chart
    Chart.register(...registerables);
    // Impor plugin Datalabels
    import 'chartjs-plugin-datalabels'; 
    // Impor plugin tooltip

    import Pagination from '../../../Components/Pagination.vue';

    export default {
        //layout
        layout : LayoutApp,
        //register component
        components:{
            Head,
            BarChart,
            DoughnutChart,
            PieChart,
            Pagination
        },
        //props
        props:{
            karyawan_baru: Object,
            karyawan_kontrak: Object,
            data_pelanggaran: Object,
            tertua: Object,
            termuda: Object,
            terlama: Object,
            total_karyawan_aktif: Object,
            //chart berdasarkan PT
            pt: Array,
            total_pt: Array,
            //chart berdasarkan Divisi
            divisi: Array,
            total_divisi: Array,
            //chart berdasarkan Jabatan
            jabatan: Array,
            total_jabatan: Array,

             //chart berdasarkan kota penugasan
            kota_penugasan: Array,
            total_kota_penugasan: Array,

            //chart berdasarkan jenis kelamin
            jenis_kelamin: Array,
            total_jenis_kelamin: Array,

            //chart berdasarkan status kerja
            status_kerja: Array,
            total_status_kerja: Array,

            //chart berdasarkan komposisi gen
            komposisi_generasi: Array,
            total_komposisi_generasi: Array,

            
            //chart berdasarkan komposisi peran
            komposisi_peran: Array,
            total_komposisi_peran: Array,

            //chart berdasarkan pendidikan
            pendidikan: Array,
            total_pendidikan: Array,

            //chart berdasarkan status pernikahan
            status_pernikahan: Array,
            total_status_pernikahan: Array,

            //chart berdasarkan komposisi karyawan
            posisi: Array,
            total_posisi: Array,

            //chart berdasarkan penyebab phk
            penyebab_phk: Array,
            total_penyebab_phk: Array,

             //chart berdasarkan rentang umur
            umur: Array,
            total_umur: Array,

            
            //chart berdasarkan alasan resign
            alasan_resign: Array,
            total_alasan_resign: Array,
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
                }, 
                
                ],
            };

            //chart karyawan berdasarkan jabatan
            const chartKaryawanJabatan = {
                labels: props.jabatan,
                datasets: [{
                    data: props.total_jabatan,
                    backgroundColor: randomBackgroundColor(5),
                }, ],
            };

            //chart karyawan berdasarkan kota penugasan
            const chartKotaPenugasan = {
                labels: props.kota_penugasan,
                datasets: [{
                    data: props.total_kota_penugasan,
                    backgroundColor: randomBackgroundColor(5),
                }, ],
            };

            //chart karyawan berdasarkan jenis kelamin
            const chartJenisKelamin = {
                labels: props.jenis_kelamin,
                datasets: [{
                    data: props.total_jenis_kelamin,
                    backgroundColor: randomBackgroundColor(5),
                }, ],
            };

            
            //chart karyawan berdasarkan jenis kelamin
            const chartStatusKerja = {
                labels: props.status_kerja,
                datasets: [{
                    data: props.total_status_kerja,
                    backgroundColor: randomBackgroundColor(5),
                }, ],
            };

        
            //chart karyawan berdasarkan komposisi generasi
            const chartKomposisiGenerasi = {
                labels: props.komposisi_generasi,
                datasets: [{
                    data: props.total_komposisi_generasi,
                    backgroundColor: randomBackgroundColor(5),
                }, ],
            };

            //chart karyawan berdasarkan komposisi peran
            const chartKomposisiPeran = {
                labels: props.komposisi_peran,
                datasets: [{
                    data: props.total_komposisi_peran,
                    backgroundColor: randomBackgroundColor(5),
                }, ],
            };

            //chart karyawan berdasarkan pendidikan
            const chartPendidikan = {
                labels: props.pendidikan,
                datasets: [{
                    data: props.total_pendidikan,
                    backgroundColor: randomBackgroundColor(5),
                }, ],
            };

            //chart karyawan berdasarkan komposisi peran
            const chartStatusPernikahan = {
                labels: props.status_pernikahan,
                datasets: [{
                    data: props.total_status_pernikahan,
                    backgroundColor: randomBackgroundColor(5),
                }, ],
            };

            // chart karyawan berdasarkan komposisi karyawan
            const chartPosisi = {
                labels: props.posisi,
                datasets: [{
                    data: props.total_posisi,
                    backgroundColor: randomBackgroundColor(5),
                }, ],
            };

            //chart karyawan berdasarkan komposisi karyawan
            const chartKaryawanPHK = {
                labels: props.penyebab_phk,
                datasets: [{
                    data: props.total_penyebab_phk,
                    backgroundColor: randomBackgroundColor(5),
                }, ],
            };

             //chart karyawan berdasarkan komposisi karyawan
            const chartUmur = {
                labels: props.umur,
                datasets: [{
                    data: props.total_umur,
                    backgroundColor: randomBackgroundColor(5),
                }, ],
            };

             //chart karyawan berdasarkan komposisi karyawan
            const chartKaryawanResign = {
                labels: props.alasan_resign,
                datasets: [{
                    data: props.total_alasan_resign,
                    backgroundColor: randomBackgroundColor(5),
                }, ],
            };


            return{
                options,
                chartKaryawanPT,
                chartKaryawanDivisi,
                chartKaryawanJabatan,
                chartKotaPenugasan,
                chartJenisKelamin,
                chartStatusKerja,
                chartKomposisiGenerasi,
                chartKomposisiPeran,
                chartPendidikan,
                chartStatusPernikahan,
                chartPosisi,
                chartKaryawanPHK,
                chartUmur,
                chartKaryawanResign,
            }
        }
    }
</script>
