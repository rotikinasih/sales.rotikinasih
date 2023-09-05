<template>
    <Head>
        <title>Tambah Karyawan</title>
    </Head>
    <main>
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Tambah Karyawan</h5>
                    </div>
                    <div class="card-body">
                        <!-- <h5>Tambah Karyawan</h5>
                        <hr> -->
                        <div class="row">
                            <div class="col-md-12">
                                <template id="form" v-if="activePhase == 1">
                                    <div class="row">
                                        <!-- data pribadi -->
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="fw-bold">Nama Lengkap</label>
                                                <input class="form-control" v-model="form.nama_lengkap" :class="{'is-invalid': errors.nama_lengkap}" type="text" placeholder="Masukkan Nama Lengkap">
                                            </div>
                                            <div v-if="errors.nama_lengkap" class="alert alert-danger"> {{ errors.nama_lengkap }}</div>
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
                                                    :options="jenis_kelamin"
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
                                                    :options="golongan_darah"
                                                    label="name"
                                                    track-by="value"
                                                    :allow-empty="false"
                                                    deselect-label="Can't remove this value"
                                                    placeholder="Pilih Golongan Darah"
                                                ></VueMultiselect>
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">Riwayat Penyakit</label>
                                                <input type="text" class="form-control" v-model="form.riwayat_penyakit" placeholder="Masukkan Riwayat Penyakit">
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">No. KTP</label>
                                                <input type="text" class="form-control" v-model="form.nik_penduduk" placeholder="No. KTP">
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">No. KK</label>
                                                <input type="text" class="form-control" v-model="form.no_kk" placeholder="Masukkan KK Number">
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">Kode Pos</label>
                                                <input type="text" class="form-control" v-model="form.kode_pos" placeholder="Masukkan Kode Pos">
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">Alamat KTP</label>
                                                <input type="text" class="form-control" v-model="form.alamat_ktp" placeholder="Masukkan Alamat KTP">
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">Alamat Domisili</label>
                                                <input class="form-control" v-model="form.alamat_domisili" placeholder="Masukkan Alamat Domisilis">
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">Pendidikan</label>
                                                <VueMultiselect
                                                    v-model="form.pendidikan"
                                                    :options="pendidikan"
                                                    label="name"
                                                    track-by="value"
                                                    :allow-empty="false"
                                                    deselect-label="Can't remove this value"
                                                    placeholder="Pilih Pendidikan"
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
                                                <input type="text" class="form-control" v-model="form.no_telp" :class="{'is-invalid': errors.no_telp}" placeholder="Masukkan HP Number">
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">No. WA</label>
                                                <input type="text" class="form-control" v-model="form.no_wa" :class="{'is-invalid': errors.no_wa}" placeholder="Masukkan No. WA">
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">No. Telp Keluarga</label>
                                                <input type="text" class="form-control" v-model="form.no_keluarga" placeholder="Masukkan No. Telp Keluarga">
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">Hubungan Keluarga</label>
                                                <VueMultiselect
                                                    v-model="form.hubungan_keluarga"
                                                    :options="hubungan_keluarga"
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
                                                    :options="status_pernikahan"
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
                                                    :options="status_keluarga"
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
                                                    :options="jenis_sosmed"
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
                                                    v-model="form.pt"
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
                                                    v-model="form.divisi"
                                                    :options="divisi"
                                                    label="nama_divisi"
                                                    track-by="id"
                                                    :allow-empty="false"
                                                    deselect-label="Can't remove this value"
                                                    placeholder="Pilih Divisi"
                                                ></VueMultiselect>
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">Jabatan</label>
                                                <VueMultiselect
                                                    v-model="form.jabatan"
                                                    :options="jabatan"
                                                    label="nama_jabatan"
                                                    track-by="nama_jabatan"
                                                    :allow-empty="false"
                                                    deselect-label="Can't remove this value"
                                                    placeholder="Pilih Jabatan"
                                                ></VueMultiselect>
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">Grade</label>
                                                <VueMultiselect
                                                    v-model="form.grade"
                                                    :options="grade"
                                                    label="name"
                                                    track-by="value"
                                                    :allow-empty="false"
                                                    deselect-label="Can't remove this value"
                                                    placeholder="Pilih Grade"
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
                                                <label class="fw-bold">Tanggal Karywan Tetap</label>
                                                <input type="date" class="form-control" v-model="form.tanggal_karyawan_tetap" placeholder="Masukkan Tanggal Karyawan Tetap">
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">Status Kerja</label>
                                                <VueMultiselect
                                                    v-model="form.status_kerja"
                                                    :options="status_kerja"
                                                    label="name"
                                                    track-by="value"
                                                    :allow-empty="false"
                                                    deselect-label="Can't remove this value"
                                                    placeholder="Pilih Status Kerja"
                                                ></VueMultiselect>
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">Komposisi Karyawan</label>
                                                <VueMultiselect
                                                    v-model="form.komposisi_karyawan"
                                                    :options="komposisi_karyawan"
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
                                                    :options="komposisi_peran"
                                                    label="name"
                                                    track-by="value"
                                                    :allow-empty="false"
                                                    deselect-label="Can't remove this value"
                                                    placeholder="Pilih Komposisi Peran"
                                                ></VueMultiselect>
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">Kota/Kab Rekruitmen</label>
                                                <input type="text" class="form-control" v-model="form.kota_rekruitmen" placeholder="Masukkan Kota/Kab Rekruitmen">
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">Kota/Kab Penugasan</label>
                                                <input type="text" class="form-control" v-model="form.kota_penugasan" placeholder="Masukkan Kota/Kab Penugasan">
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
                                                <input type="text" class="form-control" v-model="form.email_internal" placeholder="Masukkan Email Internal">
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">No. BPJS Kesehatan</label>
                                                <input type="text" class="form-control" v-model="form.no_bpjs_kesehatan" placeholder="Masukkan No. BPJS Kesehatan">
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">No. BPJS Ketenagakerjaan</label>
                                                <input type="text" class="form-control" v-model="form.no_bpjs_ketenagakerjaan" placeholder="Masukkan No. BPJS Ketenagakerjaant">
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">Ukuran Baju</label>
                                                <VueMultiselect
                                                    v-model="form.ukuran_baju"
                                                    :options="ukuran_baju"
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
                                                <input type="file" id="task_file" @change="selectedTaskFile">
                                            </div>
                                            <div class="mb-3">
                                                <div class="preview" v-show="preview">
                                                    <label class="form-label" name="preview">Preview</label><br>
                                                    <img :src="preview" width="200" height="150">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <Link href="/apps/karyawan" class="btn btn-success shadow-sm rounded-sm-5 mt-3">Kembali</Link>
                                            <button type="button" class="btn btn-primary mt-3" @click.prevent="storeData" style="float:right">Simpan</button>
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
    import { computed } from '@vue/runtime-core';

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
            jabatan: Array,
            errors: Object
        },
        setup() {
            //define state search
            const activePhase = ref(1);
            const foto = ref();
            //cek kelengkapan data
            const terms = ref(false);
            const golongan_darah = [
                { name: 'A', value: 1 },
                { name: 'B', value: 2 },
                { name: 'O', value: 3 },
                { name: 'AB', value: 4 }
            ];

            const jenis_kelamin = [
                { name: 'Laki-laki', value: 1 },
                { name: 'Perempuan', value: 2 }
            ];

            const komposisi_karyawan = [
                { name: 'Direktor', value: 1 },
                { name: 'Div Head', value: 2 },
                { name: 'Dept Head', value: 3 },
                { name: 'Sect Head', value: 4 },
                { name: 'Head', value: 5 },
                { name: 'Staff', value: 6 },
                { name: 'Non Staff', value: 7 }
            ];

            const komposisi_peran = [
                { name: 'Support', value: 1 },
                { name: 'Core', value: 2 }
            ];

            const status_kerja = [
                { name: 'Kontrak', value: 1 },
                { name: 'Tetap', value: 2 },
                { name: 'Training', value: 3 }
            ];

            const status_pernikahan = [
                { name: 'Belum Menikah', value: 1 },
                { name: 'Menikah', value: 2 },
                { name: 'Janda', value: 3 },
                { name: 'Duda', value: 4 },
            ];

            const status_keluarga = [
                { name: 'Kepala Keluarga', value: 1 },
                { name: 'Istri', value: 2 },
                { name: 'Anak ke 1', value: 3},
                { name: 'Anak ke 2', value: 4 },
                { name: 'Anak ke 3', value: 5 },
                { name: 'Lainnya', value: 6 },
            ];

            const hubungan_keluarga = [
                { name: 'Suami/Istri', value: 1 },
                { name: 'Ayah', value: 2 },
                { name: 'Ibu', value: 3},
                { name: 'Kakak/Adik', value: 4 },
                { name: 'Paman/Bibi', value: 5 },
                { name: 'Kakek/Nenek', value: 6 },
            ];

            const jenis_sosmed = [
                { name: 'Instagram', value: 1 },
                { name: 'Facebook', value: 2 },
                { name: 'Tiktok', value: 3},
                { name: 'Youtube', value: 4 },
                { name: 'Lainnya', value: 5 },
            ];

            const ukuran_baju = [
                { name: 'S', value: 1 },
                { name: 'M', value: 2 },
                { name: 'L', value: 3 },
                { name: 'XL', value: 4 },
                { name: 'XXL', value: 5 },
                { name: 'Jumbo', value: 6 },
            ];

            const grade = [
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

            const pendidikan = [
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

            const preview = ref();
            const form = reactive({
                //data pribadi
                nama_lengkap: '',
                nama_panggilan: '',
                tempat_lahir: '',
                tanggal_lahir: '',
                jenis_kelamin: '',
                gol_darah: '',
                riwayat_penyakit: '',
                nik_penduduk: '',
                no_kk: '',
                kode_pos: '',
                alamat_ktp:'',
                alamat_domisili:'',
                pendidikan: '',
                nama_sekolah: '',
                jurusan: '',
                email_pribadi:'',
                no_telp: '',
                no_wa: '',
                no_keluarga:'',
                hubungan_keluarga:'',
                status_pernikahan:'',
                status_keluarga:'',
                jenis_sosmed: '',
                nama_sosmed: '',

                //data di perusahaan
                nik_karyawan: '',
                pt: '',
                divisi: '',
                jabatan:'',
                grade:'',
                tanggal_masuk: '',
                tanggal_kontrak: '',
                masa_kontrak: '',
                tanggal_karyawan_tetap: '',
                status_kerja: '',
                komposisi_karyawan: '',
                komposisi_peran: '',
                kota_rekruitmen:'',
                kota_penugasan:'',
                nama_bank:'',
                rekening:'',
                no_npwp:'',
                email_internal:'',
                no_bpjs_kesehatan:'',
                no_bpjs_ketenagakerjaan:'',
                ukuran_baju:'',
                pengalaman_kerja_terakhir:'',
                jabatan_kerja_terakhir:'',
                image:'',
                file:'',
                task_file:'',

            });

            const goToStep = (step) => {
                activePhase.value = step
            }

            const finish = () => {
                console.log(form.pendidikan, form.ukuran_baju.value)
            }

            const fileImage = (event) => {
                form.task_file = event.target.files[0];
                console.log(form.task_file)
                if (form.task_file.size > 2048 * 2048) {
                    event.preventDefault();
                    alert('File too big (> 2MB)');
                    return;
                } else {
                    preview.value = URL.createObjectURL(event.target.files[0]);
                }
            }

            const selectedTaskFile = (e) => {
                form.task_file = e.target.files[0];
                if (form.task_file.size > 2048 * 2048) {
                    e.preventDefault();
                    alert('File too big (> 2MB)');
                    return;
                } else {
                    preview.value = URL.createObjectURL(e.target.files[0]);
                }
            }

            const storeData = () => {
                Inertia.post('/apps/karyawan',{
                    //data pribadi
                    nama_lengkap: form.nama_lengkap,
                    nama_panggilan: form.nama_panggilan,
                    tempat_lahir: form.tempat_lahir,
                    tanggal_lahir: form.tanggal_lahir,
                    jenis_kelamin: form.jenis_kelamin.value,
                    gol_darah: form.gol_darah.value,
                    riwayat_penyakit: form.riwayat_penyakit,
                    nik_penduduk: form.nik_penduduk,
                    no_kk: form.no_kk,
                    kode_pos: form.kode_pos,
                    alamat_ktp: form.alamat_ktp,
                    alamat_domisili: form.alamat_domisili,
                    pendidikan: form.pendidikan.value,
                    nama_sekolah: form.nama_sekolah,
                    jurusan: form.jurusan,
                    email_pribadi: form.email_pribadi,
                    no_telp: form.no_telp,
                    no_wa: form.no_wa,
                    no_keluarga:form.no_keluarga,
                    hubungan_keluarga: form.hubungan_keluarga.value,
                    status_pernikahan: form.status_pernikahan.value,
                    status_keluarga: form.status_keluarga.value,
                    jenis_sosmed: form.jenis_sosmed.value,
                    nama_sosmed: form.nama_sosmed,
                    
                    //data di perusahaan
                    nik_karyawan: form.nik_karyawan,
                    pt_id: form.pt.id,
                    divisi_id: form.divisi.id,
                    jabatan_id: form.jabatan.id,
                    grade: form.grade.name,
                    tanggal_masuk: form.tanggal_masuk,
                    tanggal_kontrak: form.tanggal_kontrak,
                    masa_kontrak: form.masa_kontrak,
                    tanggal_karyawan_tetap: form.tanggal_karyawan_tetap,
                    status_kerja: form.status_kerja.value,
                    komposisi_karyawan: form.komposisi_karyawan.value,
                    komposisi_peran: form.komposisi_peran.value,
                    kota_rekruitmen: form.kota_rekruitmen,
                    kota_penugasan: form.kota_penugasan,
                    nama_bank: form.nama_bank,
                    rekening: form.rekening,
                    no_npwp: form.no_npwp,
                    email_internal: form.email_internal,
                    no_bpjs_kesehatan: form.no_bpjs_kesehatan,
                    no_bpjs_ketenagakerjaan: form.no_bpjs_ketenagakerjaan,
                    ukuran_baju: form.ukuran_baju.value,
                    pengalaman_kerja_terakhir: form.pengalaman_kerja_terakhir,
                    jabatan_kerja_terakhir: form.jabatan_kerja_terakhir,
                    task_file: form.task_file,
                    nama_file: form.task_file.name,
                    file: form.file.value,
                },
                {
                    onSuccess: () => {
                        //show success alert
                        Swal.fire({
                            title: 'Sukses!',
                            text: 'Data Karyawan Berhasil Disimpan.',
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 2000
                        });
                    },
                });
            }

            return {
                activePhase, form,
                goToStep, finish, foto,
                fileImage, status_keluarga, hubungan_keluarga, jenis_sosmed, komposisi_peran, komposisi_karyawan,
                preview, golongan_darah, jenis_kelamin, status_pernikahan,
                ukuran_baju, status_kerja, pendidikan,
                grade, storeData,
                selectedTaskFile,
            }
        }
    }
</script>

<style>
</style>
