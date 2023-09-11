<template>
    <Head>
        <title>Edit Karyawan</title>
    </Head>
    <main>
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Edit Karyawan</h5>
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
                                                <label class="fw-bold">Nama Lengkap</label>
                                                <input class="form-control" v-model="form.nama_lengkap" type="text" placeholder="Masukkan Nama Lengkap">
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">Nama Panggilan</label>
                                                <input class="form-control" v-model="form.nama_panggilan" type="text" placeholder="Masukkan Nama Panggilan">
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">Tempat Lahir</label>
                                                <input type="text" class="form-control" v-model="form.tempat_lahir" placeholder="Masukkan Tempat Lahir">
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">Tanggal Lahir</label>
                                                <input type="date" class="form-control" v-model="form.tanggal_lahir" placeholder="Masukkan Tanggal Lahir">
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">Jenis Kelamin</label>
                                                <VueMultiselect
                                                    v-model="form.jenis_kelamin"
                                                    :options="data_jenis_kelamin"
                                                    label="name"
                                                    track-by="value"
                                                    :allow-empty="false"
                                                    deselect-label="Can't remove this value"
                                                    placeholder="Pilih Jenis Kelamin"
                                                ></VueMultiselect>
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">Golongan Darah</label>
                                                <VueMultiselect
                                                    v-model="form.gol_darah"
                                                    :options="data_golongan_darah"
                                                    label="name"
                                                    track-by="value"
                                                    :allow-empty="false"
                                                    deselect-label="Can't remove this value"
                                                    placeholder="Pilih Golongan Darah"
                                                ></VueMultiselect>
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">Riwayat Penyakit</label>
                                                <input class="form-control" v-model="form.riwayat_penyakit" type="text" placeholder="Masukkan Riwayat Penyakit">
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">No. KK</label>
                                                <input type="text" class="form-control" v-model="form.no_kk" placeholder="Masukkan No. KK">
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">Kode Pos</label>
                                                <input type="text" class="form-control" v-model="form.kode_pos" placeholder="Masukkan Kode Pos">
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">No. KTP</label>
                                                <input type="text" class="form-control" v-model="form.nik_penduduk" placeholder="Masukkan No. KTP">
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">Alamat KTP</label>
                                                <input type="text" class="form-control" v-model="form.alamat_ktp" placeholder="Masukkan Alamat KTP">
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">Alamat Domisili</label>
                                                <input class="form-control" v-model="form.alamat_domisili" placeholder="Masukkan Alamat Domisili">
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">Pendidikan</label>
                                                <VueMultiselect
                                                    v-model="form.pendidikan"
                                                    :options="data_pendidikan"
                                                    label="name"
                                                    track-by="value"
                                                    :allow-empty="false"
                                                    deselect-label="Can't remove this value"
                                                    placeholder="Pilih pendidikan"
                                                ></VueMultiselect>
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">Nama Sekolah</label>
                                                <input type="text" class="form-control" v-model="form.nama_sekolah" placeholder="Masukkan Nama Sekolah">
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">Jurusan</label>
                                                <input type="text" class="form-control" v-model="form.jurusan" placeholder="Masukkan Jurusan">
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">Email Pribadi</label>
                                                <input type="email" class="form-control" v-model="form.email_pribadi" placeholder="Masukkan Email Pribadi">
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">No. Telp/HP</label>
                                                <input type="text" class="form-control" v-model="form.no_telp" placeholder="No. Telp/HP">
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">No. WA</label>
                                                <input type="text" class="form-control" v-model="form.no_wa" placeholder="Masukkan No. WA">
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">No. Keluarga</label>
                                                <input type="text" class="form-control" v-model="form.no_keluarga" placeholder="Masukkan No. Keluarga">
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">Hubungan Keluarga</label>
                                                <VueMultiselect
                                                    v-model="form.hubungan_keluarga"
                                                    :options="data_hubungan_keluarga"
                                                    label="name"
                                                    track-by="value"
                                                    :allow-empty="false"
                                                    deselect-label="Can't remove this value"
                                                    placeholder="Pilih Hubungan Keluarga"
                                                ></VueMultiselect>
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">Status Pernikahan</label>
                                                <VueMultiselect
                                                    v-model="form.status_pernikahan"
                                                    :options="data_status_pernikahan"
                                                    label="name"
                                                    track-by="value"
                                                    :allow-empty="false"
                                                    deselect-label="Can't remove this value"
                                                    placeholder="Pilih Status Pernikahan"
                                                ></VueMultiselect>
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">Status Keluarga</label>
                                                <VueMultiselect
                                                    v-model="form.status_keluarga"
                                                    :options="data_status_keluarga"
                                                    label="name"
                                                    track-by="value"
                                                    :allow-empty="false"
                                                    deselect-label="Can't remove this value"
                                                    placeholder="Pilih Status Keluarga"
                                                ></VueMultiselect>
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">Jenis Sosmed</label>
                                                <VueMultiselect
                                                    v-model="form.jenis_sosmed"
                                                    :options="data_jenis_sosmed"
                                                    label="name"
                                                    track-by="value"
                                                    :allow-empty="false"
                                                    deselect-label="Can't remove this value"
                                                    placeholder="Pilih Jenis Sosmed"
                                                ></VueMultiselect>
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">Nama Akun Sosmed</label>
                                                <input class="form-control" v-model="form.nama_sosmed" type="text" placeholder="Masukkan Nama Akun Sosmed">
                                            </div>
                                        </div>
                                        
                                        <!-- data di perusahaan -->
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="fw-bold">NIK (Karyawan)</label>
                                                <input class="form-control" v-model="form.nik_karyawan" type="text" placeholder="Masukkan NIK (Karyawan)">
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">Entitas</label>
                                                <VueMultiselect
                                                    v-model="form.pt_id"
                                                    :options="perusahaan"
                                                    label="nama_pt"
                                                    track-by="id"
                                                    :allow-empty="false"
                                                    deselect-label="Can't remove this value"
                                                    placeholder="Pilih Entitas"
                                                ></VueMultiselect>
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">Divisi</label>
                                                <VueMultiselect
                                                    v-model="form.divisi_id"
                                                    :options="divisi"
                                                    label="nama_divisi"
                                                    track-by="id"
                                                    :allow-empty="false"
                                                    deselect-label="Can't remove this value"
                                                    placeholder="Pilih Divisi"
                                                ></VueMultiselect>
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">Posisi</label>
                                                <VueMultiselect
                                                    v-model="form.jabatan_id"
                                                    :options="jabatan"
                                                    label="nama_jabatan"
                                                    track-by="id"
                                                    :allow-empty="false"
                                                    deselect-label="Can't remove this value"
                                                    placeholder="Pilih Posisi"
                                                ></VueMultiselect>
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">Grade</label>
                                                <VueMultiselect
                                                    v-model="form.grade"
                                                    :options="data_grade"
                                                    label="name"
                                                    track-by="value"
                                                    :allow-empty="false"
                                                    deselect-label="Can't remove this value"
                                                    placeholder="Pilih Grade"
                                                ></VueMultiselect>
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">Status Kerja</label>
                                                <VueMultiselect
                                                    v-model="form.status_kerja"
                                                    :options="data_status_kerja"
                                                    label="name"
                                                    track-by="value"
                                                    :allow-empty="false"
                                                    deselect-label="Can't remove this value"
                                                    placeholder="Pilih Status Karyawan"
                                                ></VueMultiselect>
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">Komposisi Karyawan</label>
                                                <VueMultiselect
                                                    v-model="form.komposisi_karyawan"
                                                    :options="data_komposisi_karyawan"
                                                    label="name"
                                                    track-by="value"
                                                    :allow-empty="false"
                                                    deselect-label="Can't remove this value"
                                                    placeholder="Pilih Komposisi Karyawan"
                                                ></VueMultiselect>
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">Komposisi Peran</label>
                                                <VueMultiselect
                                                    v-model="form.komposisi_peran"
                                                    :options="data_komposisi_peran"
                                                    label="name"
                                                    track-by="value"
                                                    :allow-empty="false"
                                                    deselect-label="Can't remove this value"
                                                    placeholder="Pilih Komposisi Peran"
                                                ></VueMultiselect>
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">Tanggal Masuk</label>
                                                <input type="date" class="form-control" v-model="form.tanggal_masuk" placeholder="Masukkan Tanggal Masuk">
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">Tanggal Kontrak</label>
                                                <input type="date" class="form-control" v-model="form.tanggal_kontrak" placeholder="Masukkan Tanggal Kontrak">
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">Masa Kontrak (Bulan)</label>
                                                <input type="number" class="form-control" v-model="form.masa_kontrak" placeholder="Masukkan Masa Kontrak">
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">Tanggal Karyawan Tetap</label>
                                                <input type="date" class="form-control" v-model="form.tanggal_karyawan_tetap" placeholder="Masukkan Tanggal Karywan Tetap">
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">Kota Rekruitmen</label>
                                                <input type="text" class="form-control" v-model="form.kota_rekruitmen" placeholder="Masukkan Kota Penugasan">
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">Kato Penugasan</label>
                                                <input type="text" class="form-control" v-model="form.kota_penugasan" placeholder="Masukkan Kota Penugasan">
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">Nama Bank</label>
                                                <input type="text" class="form-control" v-model="form.nama_bank" placeholder="Masukkan Nama Bank">
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">Rekening Bank</label>
                                                <input type="text" class="form-control" v-model="form.rekening" placeholder="Masukkan Rekening Bank">
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">No. NPWP</label>
                                                <input type="text" class="form-control" v-model="form.no_npwp" placeholder="Masukkan No. NPWP">
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">Email Internal</label>
                                                <input type="email" class="form-control" v-model="form.email_internal" placeholder="Masukkan Email Internal">
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">No. BPJS Kesehatan</label>
                                                <input type="text" class="form-control" v-model="form.no_bpjs_kesehatan" placeholder="Masukkan No. BPJS Kesehatan">
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">No. BPJS Ketenagakerjaan</label>
                                                <input type="text" class="form-control" v-model="form.no_bpjs_ketenagakerjaan" placeholder="Masukkan No. BPJS Ketenagakerjaan">
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">Ukuran Baju</label>
                                                <VueMultiselect
                                                    v-model="form.ukuran_baju"
                                                    :options="data_ukuran_baju"
                                                    label="name"
                                                    track-by="value"
                                                    :allow-empty="false"
                                                    deselect-label="Can't remove this value"
                                                    placeholder="Pilih Ukuran Baju"
                                                ></VueMultiselect>
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">Pengalaman Kerja Terakhir</label>
                                                <input type="text" class="form-control" v-model="form.pengalaman_kerja_terakhir" placeholder="Masukkan Pengalaman Kerja Terakhir">
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">Jabatan Kerja Terakhir</label>
                                                <input type="text" class="form-control" v-model="form.jabatan_kerja_terakhir" placeholder="Masukkan Jabatan Kerja Terakhir">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label" name="foto">Foto</label><br>
                                                <input type="file" name="foto" @change="fileImage">
                                            </div>
                                            <label class="form-label">{{ form['foto'] }}</label><br>
                                            <div class="mb-3">
                                                <div class="preview" v-show="preview">
                                                    <label class="form-label" name="preview">Preview</label><br>
                                                    <img :src="preview" width="200" height="150">
                                                </div>
                                                <img v-if="form['foto'] != null && foto == null" :src="`/storage/${form['foto']}`" alt="photo" style="width:20%"><br>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <Link href="/apps/karyawan" class="btn btn-success shadow-sm rounded-sm-5 mt-3">Kembali</Link>
                                            <button type="submit" class="btn btn-warning mt-3" @click.prevent="storeData" style="float:right">Update</button>
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
            karyawan: Object,
            jabatan: Array
        },
        setup(props) {
            //define state search
            const activePhase = ref(1);
            const foto = ref();

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
                jenis_sosmed: props.karyawan.jenis_sosmed,
                nama_sosmed: props.karyawan.nama_sosmed,
                

                nik_karyawan: props.karyawan.nik_karyawan,
                pt_id: props.karyawan.pt_id,
                divisi_id: props.karyawan.divisi_id,
                jabatan_id: props.karyawan.jabatan,
                grade: props.karyawan.grade,
                status_kerja: props.karyawan.status_kerja,
                komposisi_karyawan: props.karyawan.komposisi_karyawan,
                komposisi_peran: props.karyawan.komposisi_peran,
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

            const goToStep = (step) => {
                activePhase.value = step
            }

            const cekData1 = computed(() => {
                return form.nama_lengkap.length == 0 || form.status_kerja.length == 0 || form.nik_karyawan.length == 0 || form.pt_id.length == 0 || form.divisi_id.length == 0;
            })

            const cekData2 = computed(() => {
                return form.grade.length == 0 || form.tanggal_masuk.length == 0 || form.nik_penduduk.length == 0 || form.jabatan_id.length == 0;
            })

            const cekData3 = computed(() => {
                return form.no_hp.length == 0 || form.no_wa.length == 0 || form.gol_darah.length == 0 || form.email.length == 0;
            })

            const cekData4 = computed(() => {
                return form.tempat_lahir.length == 0 || form.tanggal_lahir.length == 0 || form.alamat_ktp.length == 0 || form.alamat_domisili.length == 0 || form.jenis_kelamin.length == 0 || form.status_pernikahan.length == 0;
            })

            const cekData5 = computed(() => {
                return form.pendidikan.length == 0 || form.nama_sekolah.length == 0 || form.kab_penugasan.length == 0 || form.ukuran_baju.length == 0 || form.no_sdr.length == 0 || form.hubungan.length == 0;
            })

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
                goToStep, foto,
                preview,
                data_golongan_darah, data_jenis_kelamin, data_status_pernikahan, data_status_keluarga, data_jenis_sosmed,
                data_ukuran_baju, data_status_kerja, data_pendidikan, data_grade, data_hubungan_keluarga, data_komposisi_peran, data_komposisi_karyawan,
                storeData, form, fileImage, cekData1, cekData2, cekData3, cekData4, cekData5
            }
        }
    }
</script>

<style>
</style>
