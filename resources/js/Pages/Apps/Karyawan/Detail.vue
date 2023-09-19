<template>
    <Head>
        <title>Detail Karyawan</title>
    </Head>
    <main>
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <!-- <a :href="`/apps/karyawan/download-pdf/${id}`" target="_blank" type="button" class="btn btn-danger" style="float: right; margin-right: 0px;"><i class='far fa-file-pdf'></i>Cetak PDF</a> -->
                        <h5>Detail Karyawan</h5>
                    </div>
                    <div class="card-body">
                        <!-- <h5>Edit Karyawan</h5>
                        <hr> -->
                        <div class="row">
                            <div class="col-md-12">
                                <template id="form" v-if="activePhase == 1">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="fw-bold">Nama Lengkap : </label>
                                                <input class="form-control" v-model="form.nama_lengkap" type="text"  disabled>
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">Nama Panggilan</label>
                                                <input disabled class="form-control" v-model="form.nama_panggilan" type="text" >
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">Tempat Lahir</label>
                                                <input disabled type="text" class="form-control" v-model="form.tempat_lahir" >
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">Tanggal Lahir</label>
                                                <input disabled type="date" class="form-control" v-model="form.tanggal_lahir" >
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">Jenis Kelamin</label>
                                                <input disabled type="text" class="form-control" v-if="form.jenis_kelamin == null" value=" " placeholder="">
                                                <input disabled type="text" class="form-control" v-else-if="form.jenis_kelamin.value == 1" value="Laki-laki" placeholder="">
                                                <input disabled type="text" class="form-control" v-else-if="form.jenis_kelamin.value == 2" value="Perempuan" placeholder="">
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">Golongan Darah</label>
                                                <input disabled type="text" class="form-control" v-if="form.gol_darah == null" value="" placeholder="">
                                                <input disabled type="text" class="form-control" v-else-if="form.gol_darah.value == 1" value="A" placeholder="">
                                                <input disabled type="text" class="form-control" v-else-if="form.gol_darah.value == 2" value="B" placeholder="">
                                                <input disabled type="text" class="form-control" v-else-if="form.gol_darah.value == 3" value="O" placeholder="">
                                                <input disabled type="text" class="form-control" v-else-if="form.gol_darah.value == 4" value="AB" placeholder="">
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">Riwayat Penyakit</label>
                                                <input disabled class="form-control" v-model="form.riwayat_penyakit" type="text" >
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">No. KK</label>
                                                <input disabled type="number" class="form-control" v-model="form.no_kk" >
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">Kode Pos</label>
                                                <input disabled type="number" class="form-control" v-model="form.kode_pos" >
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">No. KTP</label>
                                                <input disabled type="number" class="form-control" v-model="form.nik_penduduk">
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">Alamat KTP</label>
                                                <input disabled type="text" class="form-control" v-model="form.alamat_ktp">
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">Alamat Domisili</label>
                                                <input disabled class="form-control" v-model="form.alamat_domisili">
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">Pendidikan</label>
                                                <input disabled type="text" class="form-control" v-if="form.pendidikan == null" value=" " placeholder="">
                                                <input disabled type="text" class="form-control" v-else-if="form.pendidikan.value == 1" value="SD" placeholder="">
                                                <input disabled type="text" class="form-control" v-else-if="form.pendidikan.value == 2" value="SMP" placeholder="">
                                                <input disabled type="text" class="form-control" v-else-if="form.pendidikan.value == 3" value="SMA" placeholder="">
                                                <input disabled type="text" class="form-control" v-else-if="form.pendidikan.value == 4" value="D1" placeholder="">
                                                <input disabled type="text" class="form-control" v-else-if="form.pendidikan.value == 5" value="D2" placeholder="">
                                                <input disabled type="text" class="form-control" v-else-if="form.pendidikan.value == 6" value="D3" placeholder="">
                                                <input disabled type="text" class="form-control" v-else-if="form.pendidikan.value == 7" value="D4" placeholder="">
                                                <input disabled type="text" class="form-control" v-else-if="form.pendidikan.value == 8" value="S1" placeholder="">
                                                <input disabled type="text" class="form-control" v-else-if="form.pendidikan.value == 9" value="S2" placeholder="">
                                                <input disabled type="text" class="form-control" v-else-if="form.pendidikan.value == 10" value="S3" placeholder="">
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">Nama Sekolah/Universitas</label>
                                                <input disabled type="text" class="form-control" v-model="form.nama_sekolah">
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">Jurusan</label>
                                                <input disabled type="text" class="form-control" v-model="form.jurusan">
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">Email Pribadi</label>
                                                <input disabled type="email" class="form-control" v-model="form.email_pribadi">
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">No. Telp/HP</label>
                                                <input disabled type="text" class="form-control" v-model="form.no_telp">
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">No. WA</label>
                                                <input disabled type="text" class="form-control" v-model="form.no_wa">
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">No. Keluarga</label>
                                                <input disabled type="text" class="form-control" v-model="form.no_keluarga">
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">Hubungan Keluarga</label>
                                                <input disabled type="text" class="form-control" v-if="form.hubungan_keluarga == null" value="" placeholder="">
                                                <input disabled type="text" class="form-control" v-else-if="form.hubungan_keluarga.value == 1" value="Suami/Istri" placeholder="">
                                                <input disabled type="text" class="form-control" v-else-if="form.hubungan_keluarga.value == 2" value="Ayah" placeholder="">
                                                <input disabled type="text" class="form-control" v-else-if="form.hubungan_keluarga.value == 3" value="Ibu" placeholder="">
                                                <input disabled type="text" class="form-control" v-else-if="form.hubungan_keluarga.value == 4" value="Kakak/Adik" placeholder="">
                                                <input disabled type="text" class="form-control" v-else-if="form.hubungan_keluarga.value == 5" value="Paman/Bibi" placeholder="">
                                                <input disabled type="text" class="form-control" v-else-if="form.hubungan_keluarga.value == 6" value="Kakek/Nenek" placeholder="">
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">Status Pernikahan</label>
                                                <input disabled type="text" class="form-control" v-if="form.status_pernikahan == null" value=" " placeholder="">
                                                <input disabled type="text" class="form-control" v-else-if="form.status_pernikahan.value == 1" value="Belum Menikah" placeholder="">
                                                <input disabled type="text" class="form-control" v-else-if="form.status_pernikahan.value == 2" value="Menikah" placeholder="">
                                                <input disabled type="text" class="form-control" v-else-if="form.status_pernikahan.value == 3" value="Janda" placeholder="">
                                                <input disabled type="text" class="form-control" v-else-if="form.status_pernikahan.value == 4" value="Duda" placeholder="">
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">Status Keluarga</label>
                                                <input disabled type="text" class="form-control" v-if="form.status_keluarga == null" value=" " placeholder="">
                                                <input disabled type="text" class="form-control" v-else-if="form.status_keluarga.value == 1" value="Kepala Keluarga" placeholder="">
                                                <input disabled type="text" class="form-control" v-else-if="form.status_keluarga.value == 2" value="Istri" placeholder="">
                                                <input disabled type="text" class="form-control" v-else-if="form.status_keluarga.value == 3" value="Anak ke 1" placeholder="">
                                                <input disabled type="text" class="form-control" v-else-if="form.status_keluarga.value == 4" value="Anak ke 2" placeholder="">
                                                <input disabled type="text" class="form-control" v-else-if="form.status_keluarga.value == 5" value="Anak ke 3" placeholder="">
                                                <input disabled type="text" class="form-control" v-else-if="form.status_keluarga.value == 6" value="Anak ke 4" placeholder="">
                                                <input disabled type="text" class="form-control" v-else-if="form.status_keluarga.value == 7" value="Anak ke 5" placeholder="">
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">Jenis Sosmed</label>
                                                <input disabled type="text" class="form-control" v-if="form.jenis_sosmed == null" value="" placeholder="">
                                                <input disabled type="text" class="form-control" v-else-if="form.jenis_sosmed.value == 1" value="Instagram" placeholder="">
                                                <input disabled type="text" class="form-control" v-else-if="form.jenis_sosmed.value == 2" value="Facebook" placeholder="">
                                                <input disabled type="text" class="form-control" v-else-if="form.jenis_sosmed.value == 3" value="Tiktok" placeholder="">
                                                <input disabled type="text" class="form-control" v-else-if="form.jenis_sosmed.value == 4" value="Youtube" placeholder="">
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">Nama Akun Sosmed</label>
                                                <input disabled class="form-control" v-model="form.nama_sosmed" type="text" placeholder="">
                                            </div>
                                        </div>
                                        
                                        <!-- data di perusahaan -->
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="fw-bold">NIK (Karyawan)</label>
                                                <input disabled class="form-control" v-model="form.nik_karyawan" type="text">
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">Entitas</label>
                                                <input disabled class="form-control" v-model="nama_pt" type="text">
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">Divisi</label>
                                                <input disabled class="form-control" v-model="nama_divisi" type="text">    
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">Jabatan</label>
                                                <input disabled class="form-control" v-model="nama_jabatan" type="text">    
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">Posisi</label>
                                                <input disabled class="form-control" v-model="nama_posisi" type="text"> 
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">Grade</label>
                                                <input disabled type="text" class="form-control" v-model="grade" placeholder="">
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">Status Kerja</label>
                                                <input disabled type="text" class="form-control" v-if="form.status_kerja == null" value="" placeholder="">
                                                <input disabled type="text" class="form-control" v-else-if="form.status_kerja.value == 1" value="Kontrak" placeholder="">
                                                <input disabled type="text" class="form-control" v-else-if="form.status_kerja.value == 2" value="Tetap" placeholder="">
                                                <input disabled type="text" class="form-control" v-else-if="form.status_kerja.value == 3" value="Training" placeholder="">
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">Komposisi Peran</label>
                                                <input disabled type="text" class="form-control" v-if="form.komposisi_peran == null" value="" placeholder="">
                                                <input disabled type="text" class="form-control" v-else-if="form.komposisi_peran.value == 1" value="Support" placeholder="">
                                                <input disabled type="text" class="form-control" v-else-if="form.komposisi_peran.value == 2" value="Core" placeholder="">
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">Tanggal Masuk</label>
                                                <input disabled type="date" class="form-control" v-model="form.tanggal_masuk" placeholder="">
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">Tanggal Kontrak</label>
                                                <input disabled type="date" class="form-control" v-model="form.tanggal_kontrak" placeholder="">
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">Masa Kontrak (Bulan)</label>
                                                <input disabled type="number" class="form-control" v-model="form.masa_kontrak" placeholder="">
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">Tanggal Karyawan Tetap</label>
                                                <input disabled type="date" class="form-control" v-model="form.tanggal_karyawan_tetap" placeholder="">
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">Masa Kerja</label>
                                                <input disabled class="form-control" v-model="masa_kerja_tahun" type="text" placeholder="">
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">Posisi Awal Diterima</label>
                                                <input disabled type="text" class="form-control" v-model="form.posisi_awal_diterima" placeholder="">
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">Kota Rekruitmen</label>
                                                <input disabled type="text" class="form-control" v-model="form.kota_rekruitmen" placeholder="">
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">Kota Penugasan</label>
                                                <input disabled type="text" class="form-control" v-model="form.kota_penugasan" placeholder="">
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">Nama Bank</label>
                                                <input disabled type="text" class="form-control" v-model="form.nama_bank" placeholder="">
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">Rekening Bank</label>
                                                <input disabled type="text" class="form-control" v-model="form.rekening" placeholder="">
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">No. NPWP</label>
                                                <input disabled type="text" class="form-control" v-model="form.no_npwp" placeholder="">
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">Email Internal</label>
                                                <input disabled type="email" class="form-control" v-model="form.email_internal" placeholder="">
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">No. BPJS Kesehatan</label>
                                                <input disabled type="text" class="form-control" v-model="form.no_bpjs_kesehatan" placeholder="">
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">No. BPJS Ketenagakerjaan</label>
                                                <input disabled type="text" class="form-control" v-model="form.no_bpjs_ketenagakerjaan" placeholder="">
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">Ukuran Baju</label>
                                                <input disabled type="text" class="form-control" v-if="form.ukuran_baju == null" value=" " placeholder="">
                                                <input disabled type="text" class="form-control" v-else-if="form.ukuran_baju.value == 1" value="S" placeholder="">
                                                <input disabled type="text" class="form-control" v-else-if="form.ukuran_baju.value == 2" value="M" placeholder="">
                                                <input disabled type="text" class="form-control" v-else-if="form.ukuran_baju.value == 3" value="L" placeholder="">
                                                <input disabled type="text" class="form-control" v-else-if="form.ukuran_baju.value == 4" value="XL" placeholder="">
                                                <input disabled type="text" class="form-control" v-else-if="form.ukuran_baju.value == 5" value="XXL" placeholder="">
                                                <input disabled type="text" class="form-control" v-else-if="form.ukuran_baju.value == 6" value="Jumbo" placeholder="">
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">Pengalaman Kerja Terakhir</label>
                                                <input disabled type="text" class="form-control" v-model="form.pengalaman_kerja_terakhir" placeholder="">
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">Jabatan Kerja Terakhir</label>
                                                <input disabled type="text" class="form-control" v-model="form.jabatan_kerja_terakhir" placeholder="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <!-- <button type="submit" class="btn btn-warning mt-3" @click.prevent="storeData" style="float:right">Update</button> -->
                                            <Link href="/apps/karyawan" class="btn btn-secondary shadow-sm rounded-sm-5 mt-3" style="float:right">Kembali</Link>
                                            <!-- <button type="button" class="btn btn-primary mt-3" @click.prevent="goToStep(4)" style="float:right">Previous</button> -->
                                        </div>
                                    </div>
                                </template>
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
    //import component pagination
    import Pagination from '../../../Components/Pagination.vue';
    //import Heade and Link from Inertia
    import { Head, Link  } from '@inertiajs/inertia-vue3';
    //import ref from vue
    import { ref, reactive } from 'vue';
    //import inertia adapter
    import { Inertia } from '@inertiajs/inertia';
    //import sweet alert2
    import Swal from 'sweetalert2';
    //import vuemultiselect
    import VueMultiselect from 'vue-multiselect';
    import 'vue-multiselect/dist/vue-multiselect.css';
    import { onMounted, computed } from '@vue/runtime-core';

    export default {
        //layout
        layout: LayoutApp,
        //register component
        components: {
            Head,
            Link,
            Pagination,
            VueMultiselect
        },
        //props
        props: {
            divisi: Array,
            perusahaan: Array,
            karyawan: Array,
            jabatan: Array,
            posisi: Array,
        },

        setup(props) {
            //define state search
            const activePhase = ref(1);
            const foto = ref();
            // console.log(props.karyawan.perusahaan.nama_pt);

            const data_status_kerja = [
                { name: 'Kontrak', value: 1 },
                { name: 'Tetap', value: 2 },
                { name: 'Training', value: 3 }
            ];

            const data_golongan_darah = [
                { name: 'A', value: 1 },
                { name: 'B', value: 2 },
                { name: 'O', value: 3 },
                { name: 'AB', value: 4 }
            ];

            const data_jenis_kelamin = [
                { name: 'Laki-laki', value: 1 },
                { name: 'Perempuan', value: 2 }
            ];

            const data_status_pernikahan = [
                { name: 'Belum Menikah', value: 1 },
                { name: 'Menikah', value: 2 },
                { name: 'Janda', value: 3 },
                { name: 'Duda', value: 4 },
            ];

            const data_ukuran_baju = [
                { name: 'S', value: 1 },
                { name: 'M', value: 2 },
                { name: 'L', value: 3 },
                { name: 'XL', value: 4 },
                { name: 'XXL', value: 5 },
                { name: 'Jumbo', value: 6 },
            ];

            const data_grade = [
                { name: '1A' },
                { name: '1B' },
                { name: '1C' },
                { name: '2A' },
                { name: '2B' },
                { name: '2C' },
                { name: '2D' },
                { name: '2E' },
                { name: '2F' },
                { name: '3A' },
                { name: '3B' },
                { name: '3C' },
                { name: '3D' },
                { name: '3E' },
                { name: '4A' },
                { name: '4B' },
                { name: '4C' },
                { name: '4D' },
                { name: '5A' },
                { name: '5B' },
                { name: '5C' },
                { name: '6A' },
                { name: '6B' },
                { name: '6C' },
            ];

            const data_pendidikan = [
                { name: 'SD', value: 1 },
                { name: 'SMP', value: 2 },
                { name: 'SMA', value: 3 },
                { name: 'D1', value: 4 },
                { name: 'D2', value: 5 },
                { name: 'D3', value: 6 },
                { name: 'D4', value: 7 },
                { name: 'S1', value: 8 },
                { name: 'S2', value: 9 },
                { name: 'S3', value: 10 },
            ];

            const data_hubungan_keluarga = [
                { name: 'Suami/Istri', value: 1 },
                { name: 'Ayah', value: 2 },
                { name: 'Ibu', value: 3},
                { name: 'Kakak/Adik', value: 4 },
                { name: 'Paman/Bibi', value: 5 },
                { name: 'Kakek/Nenek', value: 6 },
            ];

            const data_status_keluarga = [
                { name: 'Kepala Keluarga', value: 1 },
                { name: 'Istri', value: 2 },
                { name: 'Anak ke 1', value: 3},
                { name: 'Anak ke 2', value: 4 },
                { name: 'Anak ke 3', value: 5 },
                { name: 'Anak ke 4', value: 6 },
                { name: 'Anak ke 5', value: 7 },
            ];

            const data_jenis_sosmed = [
                { name: 'Instagram', value: 1 },
                { name: 'Facebook', value: 2 },
                { name: 'Tiktok', value: 3},
                { name: 'Youtube', value: 4 },
                { name: 'Lainnya', value: 5 },
            ];

            const data_komposisi_peran = [
                { name: 'Support', value: 1 },
                { name: 'Core', value: 2 }
            ];

            const data_komposisi_karyawan = [
                { name: 'Direktor', value: 1 },
                { name: 'Div Head', value: 2 },
                { name: 'Dept Head', value: 3 },
                { name: 'Sect Head', value: 4 },
                { name: 'Head', value: 5 },
                { name: 'Staff', value: 6 },
                { name: 'Non Staff', value: 7 }
            ];

            onMounted(() => {
                getData()
            })

            //mendapatkan object dari masing2 FK
            const getData = () => {

                console.log(props.karyawan.jenis_kelamin)
                //pt
                let datapt_nya = props.perusahaan
                datapt_nya.forEach(data => {
                    if(props.karyawan.pt_id == data.id){
                        props.karyawan.pt_id = data
                        form.pt_id = data
                    }
                })
                //divisi
                let datadiv_nya = props.divisi
                datadiv_nya.forEach(data => {
                    if(props.karyawan.divisi_id == data.id){
                        props.karyawan.divisi_id = data
                        form.divisi_id = data
                    }
                })

                //status kerja
                data_status_kerja.forEach(function (data) {
                    if(data.value == props.karyawan.status_kerja){
                        form.status_kerja = data
                    }
                })

                //jabatan
                let datajb_nya = props.jabatan
                datajb_nya.forEach(data => {
                    if(props.karyawan.jabatan_id == data.id){
                        props.karyawan.jabatan_id = data
                        form.jabatan_id = data
                    }
                })

                 //posisi
                let datapss_nya = props.posisi
                datapss_nya.forEach(data => {
                    if(props.karyawan.posisi_id == data.id){
                        props.karyawan.posisi_id = data
                        form.posisi_id = data
                    }
                })

                //grade
                data_grade.forEach(function (data) {
                    if(data.name == props.karyawan.grade){
                        form.grade = data
                    }
                })

                //golongan darah
                data_golongan_darah.forEach(function (data) {
                    if(data.value == props.karyawan.gol_darah){
                        form.gol_darah = data
                    }
                })

                //jenis kelamin
                data_jenis_kelamin.forEach(function (data) {
                    if(data.value == props.karyawan.jenis_kelamin){
                        form.jenis_kelamin = data
                    }
                })

                //status pernikahan
                data_status_pernikahan.forEach(function (data) {
                    if(data.value == props.karyawan.status_pernikahan){
                        form.status_pernikahan = data
                    }
                })

                //pendidikan
                data_pendidikan.forEach(function (data) {
                    if(data.value == props.karyawan.pendidikan){
                        form.pendidikan = data
                    }
                })

                //ukuran baju
                data_ukuran_baju.forEach(function (data) {
                    if(data.value == props.karyawan.ukuran_baju){
                        form.ukuran_baju = data
                    }
                })

                //hubngan keluarga
                data_hubungan_keluarga.forEach(function (data) {
                    if(data.value == props.karyawan.hubungan_keluarga){
                        form.hubungan_keluarga = data
                    }
                })

                //status keluarga
                data_status_keluarga.forEach(function (data) {
                    if(data.value == props.karyawan.status_keluarga){
                        form.status_keluarga = data
                    }
                })

                //jenis sosmed
                data_jenis_sosmed.forEach(function (data) {
                    if(data.value == props.karyawan.jenis_sosmed){
                        form.jenis_sosmed = data
                    }
                })

                //komposisi peran
                data_komposisi_peran.forEach(function (data) {
                    if(data.value == props.karyawan.komposisi_peran){
                        form.komposisi_peran = data
                    }
                })

                //komposisi karyawan
                data_komposisi_karyawan.forEach(function (data) {
                    if(data.value == props.karyawan.komposisi_karyawan){
                        form.komposisi_karyawan = data
                    }
                })
            }

            const preview = ref();

            const nama_pt = props.karyawan.pt_id == null ? '' : props.karyawan.perusahaan.nama_pt;
            const nama_divisi = props.karyawan.divisi_id == null ? '' : props.karyawan.divisi.nama_divisi;
            const nama_jabatan = props.karyawan.jabatan_id == null ? '' : props.karyawan.jabatan.nama_jabatan;
            const nama_posisi = props.karyawan.posisi_id == null ? '' : props.karyawan.posisi.nama_posisi;
            const masa_kerja_tahun = props.karyawan.masa_kerja_tahun == null ? '' : props.karyawan.masa_kerja_tahun;
            const grade = props.karyawan.grade == null ? '' : props.karyawan.grade;

            //define form with reactive
            const form = reactive({
                nama_lengkap: props.karyawan.nama_lengkap,
                nama_panggilan: props.karyawan.nama_panggilan,
                tempat_lahir: props.karyawan.tempat_lahir,
                tanggal_lahir: props.karyawan.tanggal_lahir,
                jenis_kelamin: props.karyawan.jenis_kelamin,
                gol_darah: props.karyawan.gol_darah,
                riwayat_penyakit: props.karyawan.riwayat_penyakit,
                nik_penduduk: props.karyawan.nik_penduduk,
                no_kk: props.karyawan.no_kk,
                kode_pos: props.karyawan.kode_pos,
                alamat_ktp: props.karyawan.alamat_ktp,
                alamat_domisili: props.karyawan.alamat_domisili,
                pendidikan: props.karyawan.pendidikan,
                nama_sekolah: props.karyawan.nama_sekolah,
                jurusan: props.karyawan.jurusan,
                email_pribadi: props.karyawan.email_pribadi,
                no_telp: props.karyawan.no_telp,
                no_wa: props.karyawan.no_wa,
                no_keluarga: props.karyawan.no_keluarga,
                hubungan_keluarga: props.karyawan.hubungan_keluarga,
                status_pernikahan: props.karyawan.status_pernikahan,
                status_keluarga: props.karyawan.status_keluarga,
                jenis_sosmed: props.karyawan.jenis_sosme,
                nama_sosmed: props.karyawan.nama_sosmed,
                

                nik_karyawan: props.karyawan.nik_karyawan,
                pt_id: props.karyawan.perusahaan.nama_pt,
                divisi_id: props.karyawan.divisi_id,
                jabatan_id: props.karyawan.jabatan_id,
                posisi_id: props.karyawan.posisi_id,
                grade: props.karyawan.grade,
                status_kerja: props.karyawan.status_kerja,
                komposisi_karyawan: props.karyawan.komposisi_karyawan,
                komposisi_peran: props.karyawan.komposisi_peran,
                posisi_awal_diterima: props.karyawan.posisi_awal_diterima,
                tanggal_masuk: props.karyawan.tanggal_masuk,
                tanggal_kontrak: props.karyawan.tanggal_kontrak,
                tanggal_karyawan_tetap: props.karyawan.tanggal_karyawan_tetap,
                masa_kontrak: props.karyawan.masa_kontrak,
                kota_rekruitmen: props.karyawan.kota_rekruitmen,
                kota_penugasan: props.karyawan.kota_penugasan,
                no_npwp: props.karyawan.no_npwp,
                no_bpjs_kesehatan: props.karyawan.no_bpjs_kesehatan,
                no_bpjs_ketenagakerjaan: props.karyawan.no_bpjs_ketenagakerjaan,
                email_internal: props.karyawan.email_internal,
                nama_bank: props.karyawan.nama_bank,
                rekening: props.karyawan.rekening,
                ukuran_baju: props.karyawan.ukuran_baju,
                pengalaman_kerja_terakhir: props.karyawan.pengalaman_kerja_terakhir,
                jabatan_kerja_terakhir: props.karyawan.jabatan_kerja_terakhir,
                foto: props.karyawan.foto,
            });


            const fileImage = (event) => {
                foto.value = event.target.files[0];
                if (foto.size > 2048 * 2048) {
                    event.preventDefault();
                    alert('File too big (> 2MB)');
                    return;
                } else {
                    preview.value = URL.createObjectURL(event.target.files[0]);
                }
            }

            const storeData = () => {
                if(foto.value == undefined){
                    Inertia.post(`/apps/karyawan/${props.karyawan.id}`, {
                        _method: 'put',
                        nama_lengkap: form.nama_lengkap,
                        nama_panggilan: form.nama_panggilan,
                        tempat_lahir: form.tempat_lahir,
                        tanggal_lahir: form.tanggal_lahir,
                        jenis_kelamin: form.jenis_kelamin ? form.jenis_kelamin.value : '',
                        gol_darah: form.gol_darah ? form.gol_darah.value : '',
                        riwayat_penyakit: form.riwayat_penyakit,
                        no_kk: form.no_kk,
                        kode_pos: form.kode_pos,
                        nik_penduduk: form.nik_penduduk,
                        alamat_ktp: form.alamat_ktp,
                        alamat_domisili: form.alamat_domisili,
                        pendidikan: form.pendidikan ? form.pendidikan.value : '',
                        nama_sekolah: form.nama_sekolah,
                        jurusan: form.jurusan,
                        email_pribadi: form.email_pribadi,
                        no_telp: form.no_telp,
                        no_wa: form.no_wa,
                        no_keluarga:form.no_keluarga,
                        hubungan_keluarga: form.hubungan_keluarga ? form.hubungan_keluarga.value : '',
                        status_pernikahan: form.status_pernikahan ? form.status_pernikahan.value : '',
                        status_keluarga: form.status_keluarga ? form.status_keluarga.value : '',
                        jenis_sosmed: form.jenis_sosmed ? form.jenis_sosmed.value : '',
                        nama_sosmed: form.nama_sosmed,
                        
                        //data di perusahaan
                        nik_karyawan: form.nik_karyawan,
                        pt_id: form.pt_id ? form.pt_id.id : '',
                        divisi_id: form.divisi_id ? form.divisi_id.id : '',
                        jabatan_id: form.jabatan_id ? form.jabatan_id.id : '',
                        posisi_id: form.posisi_id ? form.posisi_id.id : '',
                        grade: form.grade ? form.grade.name : '',
                        tanggal_masuk: form.tanggal_masuk,
                        status_kerja: form.status_kerja ? form.status_kerja.value : '',
                        komposisi_karyawan: form.komposisi_karyawan ? form.komposisi_karyawan.value : '',
                        komposisi_peran: form.komposisi_peran ? form.komposisi_peran.value : '',
                        tanggal_kontrak: form.tanggal_kontrak,
                        tanggal_karyawan_tetap: form.tanggal_karyawan_tetap,
                        masa_kontrak: form.masa_kontrak,
                        kota_rekruitmen: form.kota_rekruitmen,
                        kota_penugasan: form.kota_penugasan,
                        nama_bank: form.nama_bank,
                        rekening: form.rekening,
                        no_npwp: form.no_npwp,
                        email_internal: form.email_internal,
                        no_bpjs_kesehatan: form.no_bpjs_kesehatan,
                        no_bpjs_ketenagakerjaan: form.no_bpjs_ketenagakerjaan,
                        ukuran_baju: form.ukuran_baju ? form.ukuran_baju.value : '',
                        pengalaman_kerja_terakhir: form.pengalaman_kerja_terakhir,
                        jabatan_kerja_terakhir: form.jabatan_kerja_terakhir,
                    },{
                        onSuccess: () => {
                            //show success alert
                            Swal.fire({
                                title: 'Sukses!',
                                text: 'Edit Data Karyawan Berhasil.',
                                icon: 'success',
                                showConfirmButton: false,
                                timer: 2000
                            });
                        },
                    });
                }else{
                    Inertia.post(`/apps/karyawan/${props.karyawan.id}`, {
                        _method: 'put',
                        nama_lengkap: form.nama_lengkap,
                        nama_panggilan: form.nama_panggilan,
                        tempat_lahir: form.tempat_lahir,
                        tanggal_lahir: form.tanggal_lahir,
                        jenis_kelamin: form.jenis_kelamin ? form.jenis_kelamin.value : '',
                        gol_darah: form.gol_darah ? form.gol_darah.value : '',
                        riwayat_penyakit: form.riwayat_penyakit,
                        no_kk: form.no_kk,
                        kode_pos: form.kode_pos,
                        nik_penduduk: form.nik_penduduk,
                        alamat_ktp: form.alamat_ktp,
                        alamat_domisili: form.alamat_domisili,
                        pendidikan: form.pendidikan ? form.pendidikan.value : '',
                        nama_sekolah: form.nama_sekolah,
                        jurusan: form.jurusan,
                        email_pribadi: form.email_pribadi,
                        no_telp: form.no_telp,
                        no_wa: form.no_wa,
                        no_keluarga:form.no_keluarga,
                        hubungan_keluarga: form.hubungan_keluarga ? form.hubungan_keluarga.value : '',
                        status_pernikahan: form.status_pernikahan ? form.status_pernikahan.value : '',
                        status_keluarga: form.status_keluarga ? form.status_keluarga.value : '',
                        jenis_sosmed: form.jenis_sosmed ? form.jenis_sosmed.value : '',
                        nama_sosmed: form.nama_sosmed,
                        
                        //data di perusahaan
                        nik_karyawan: form.nik_karyawan,
                        pt_id: form.pt_id ? form.pt_id.id : '',
                        divisi_id: form.divisi_id ? form.divisi_id.id : '',
                        jabatan_id: form.jabatan_id ? form.jabatan_id.id : '',
                        posisi_id: form.posisi_id ? form.posisi_id.id : '',
                        grade: form.grade ? form.grade.name : '',
                        tanggal_masuk: form.tanggal_masuk,
                        status_kerja: form.status_kerja ? form.status_kerja.value : '',
                        komposisi_karyawan: form.komposisi_karyawan ? form.komposisi_karyawan.value : '',
                        komposisi_peran: form.komposisi_peran ? form.komposisi_peran.value : '',
                        tanggal_kontrak: form.tanggal_kontrak,
                        tanggal_karyawan_tetap: form.tanggal_karyawan_tetap,
                        masa_kontrak: form.masa_kontrak,
                        kota_rekruitmen: form.kota_rekruitmen,
                        kota_penugasan: form.kota_penugasan,
                        nama_bank: form.nama_bank,
                        rekening: form.rekening,
                        no_npwp: form.no_npwp,
                        email_internal: form.email_internal,
                        no_bpjs_kesehatan: form.no_bpjs_kesehatan,
                        no_bpjs_ketenagakerjaan: form.no_bpjs_ketenagakerjaan,
                        ukuran_baju: form.ukuran_baju ? form.ukuran_baju.value : '',
                        pengalaman_kerja_terakhir: form.pengalaman_kerja_terakhir,
                        jabatan_kerja_terakhir: form.jabatan_kerja_terakhir,
                        task_file: foto.value,
                        nama_file: foto.value.name,
                    },{
                        onSuccess: () => {
                            //show success alert
                            Swal.fire({
                                title: 'Sukses!',
                                text: 'Edit Data Karyawan Berhasil.',
                                icon: 'success',
                                showConfirmButton: false,
                                timer: 2000
                            });
                        },
                    });
                }
            }

            return {
                activePhase,
                foto,
                preview, nama_pt, nama_divisi, nama_jabatan, nama_posisi, masa_kerja_tahun, grade,
                data_golongan_darah, data_jenis_kelamin, data_status_pernikahan, data_status_keluarga, data_jenis_sosmed,
                data_ukuran_baju, data_status_kerja, data_pendidikan, data_grade, data_hubungan_keluarga, data_komposisi_peran, data_komposisi_karyawan,
                storeData, form, fileImage
            }
        }
    }
</script>

<style>
</style>
