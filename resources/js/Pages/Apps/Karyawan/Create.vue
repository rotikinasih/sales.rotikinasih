<template>
    <Head>
       <title>Create Employees</title>
   </Head>
   <main>
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Employees</h5>
                    </div>
                    <div class="card-body">
                        <h5>Create Employees</h5>
                        <hr>
                        <div class="row">
                            <div class="col-md-12">
                                <template id="user_detail1" v-if="activePhase == 1">
                                    <!-- <h1>Step 1</h1>{{ divisi }} {{ perusahaan }} -->
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="fw-bold">Employee Name</label>
                                                <input class="form-control" v-model="user_detail1.nama_karyawan" type="text" placeholder="Employee Name">
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">Employment Status</label>
                                                <VueMultiselect
                                                    v-model="user_detail1.status_kerja"
                                                    :options="status_kerja"
                                                    label="name"
                                                    track-by="value"
                                                    :allow-empty="false"
                                                    deselect-label="Can't remove this value"
                                                    placeholder="Select Status kerja"
                                                ></VueMultiselect>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label" name="foto">Photo</label><br>
                                                <input type="file" name="foto" @change="fileImage" >
                                            </div>
                                            <div class="mb-3">
                                                <div class="preview" v-show="preview">
                                                    <label class="form-label" name="preview">Preview</label><br>
                                                    <img :src="preview" width="200" height="150">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="fw-bold">NIK (Employee)</label>
                                                <input class="form-control" v-model="user_detail1.nik_karyawan" type="text" placeholder="NIK">
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">PT</label>
                                                <VueMultiselect
                                                    v-model="user_detail1.pt"
                                                    :options="perusahaan"
                                                    label="nama_pt"
                                                    track-by="id"
                                                    :allow-empty="false"
                                                    deselect-label="Can't remove this value"
                                                    placeholder="Select PT"
                                                ></VueMultiselect>
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">Division</label>
                                                <VueMultiselect
                                                    v-model="user_detail1.divisi"
                                                    :options="divisi"
                                                    label="nama_divisi"
                                                    track-by="id"
                                                    :allow-empty="false"
                                                    deselect-label="Can't remove this value"
                                                    placeholder="Select Division"
                                                ></VueMultiselect>
                                            </div>
                                        </div>
                                    </div>
                                    {{ user_detail1 }}
                                    <div style="float:right">
                                        <button type="button" class="btn btn-primary mt-3" @click.prevent="goToStep(2)">Next</button>
                                    </div>
                                </template>

                                <template id="user_detail2" v-if="activePhase == 2">
                                    <!-- <h1>Step 2</h1> -->
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="fw-bold">Grade</label>
                                                <VueMultiselect
                                                    v-model="user_detail2.grade"
                                                    :options="grade"
                                                    label="name"
                                                    track-by="value"
                                                    :allow-empty="false"
                                                    deselect-label="Can't remove this value"
                                                    placeholder="Select Grade"
                                                ></VueMultiselect>
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">Tanggal Masuk</label>
                                                <input type="date" class="form-control" v-model="user_detail2.tanggal_masuk" placeholder="Entry Date">
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">NIK Penduduk</label>
                                                <input type="text" class="form-control" v-model="user_detail2.nik_penduduk" placeholder="NIK (Population)">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="fw-bold">Position</label>
                                                <VueMultiselect
                                                    v-model="user_detail2.jabatan"
                                                    :options="jabatan"
                                                    label="name"
                                                    track-by="value"
                                                    :allow-empty="false"
                                                    deselect-label="Can't remove this value"
                                                    placeholder="Select Jabatan"
                                                ></VueMultiselect>
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">Tanggal Contrak</label>
                                                <input type="date" class="form-control" v-model="user_detail2.tanggal_kontrak" placeholder="Contract Date">
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">No KK</label>
                                                <input type="text" class="form-control" v-model="user_detail2.no_kk" placeholder="No KK">
                                            </div>
                                        </div>
                                    </div>{{ user_detail2 }}
                                    <div style="float:right">
                                        <button type="button" class="btn btn-primary mt-3" @click.prevent="goToStep(1)">Previous</button>
                                        <button type="button" class="btn btn-primary mt-3" @click.prevent="goToStep(3)">Next</button>
                                    </div>
                                </template>

                                <template id="user_detail3" v-if="activePhase == 3">
                                    <!-- <h1>Step 3</h1> -->
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="fw-bold">No HP</label>
                                                <input type="text" class="form-control" v-model="user_detail3.no_hp" placeholder="No HP">
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">No BPJS Kesehatan</label>
                                                <input type="text" class="form-control" v-model="user_detail3.no_bpjs_kesehatan" placeholder="Healthy BPJS">
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">Golongan Darah</label>
                                                <VueMultiselect
                                                    v-model="user_detail3.gol_darah"
                                                    :options="golongan_darah"
                                                    label="name"
                                                    track-by="value"
                                                    :allow-empty="false"
                                                    deselect-label="Can't remove this value"
                                                    placeholder="Select Blood Group"
                                                ></VueMultiselect>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="fw-bold">No WA</label>
                                                <input type="text" class="form-control" v-model="user_detail3.no_wa" placeholder="WA Number">
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">No BPJS Ketenagakerjaan</label>
                                                <input type="text" class="form-control" v-model="user_detail3.no_bpjs_ketenagakerjaan" placeholder="BPJS of Employment">
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">Email</label>
                                                <input type="email" class="form-control" v-model="user_detail3.email" placeholder="Email">
                                            </div>
                                        </div>
                                    </div>{{ user_detail3 }}
                                    <div style="float:right">
                                        <button type="button" class="btn btn-primary mt-3" @click.prevent="goToStep(2)">Previous</button>
                                        <button type="button" class="btn btn-primary mt-3" @click.prevent="goToStep(4)">Next</button>
                                    </div>
                                </template>

                                <template id="user_detail4" v-if="activePhase == 4">
                                    <!-- <h1>Step 3</h1> -->
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="fw-bold">Tempat Lahir</label>
                                                <input type="text" class="form-control" v-model="user_detail4.tempat_lahir" placeholder="Tempat Lahir">
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">Alamat KTP</label>
                                                <textarea type="text" class="form-control" v-model="user_detail4.alamat_ktp" placeholder="Alamat KTP"></textarea>
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">Jenis Kelamin</label>
                                                <VueMultiselect
                                                    v-model="user_detail4.jenis_kelamin"
                                                    :options="jenis_kelamin"
                                                    label="name"
                                                    track-by="value"
                                                    :allow-empty="false"
                                                    deselect-label="Can't remove this value"
                                                    placeholder="Select Gender"
                                                ></VueMultiselect>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="fw-bold">Tanggal Lahir</label>
                                                <input type="date" class="form-control" v-model="user_detail4.tanggal_lahir" placeholder="Tanggal Lahir">
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">Alamat Domisili</label>
                                                <textarea class="form-control" v-model="user_detail4.alamat_domisili" placeholder="Alamat Domisili"></textarea>
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">Status Pernikahan</label>
                                                <VueMultiselect
                                                    v-model="user_detail4.status_pernikahan"
                                                    :options="status_pernikahan"
                                                    label="name"
                                                    track-by="value"
                                                    :allow-empty="false"
                                                    deselect-label="Can't remove this value"
                                                    placeholder="Select Gender"
                                                ></VueMultiselect>
                                            </div>
                                        </div>
                                    </div>{{ user_detail4 }}
                                    <div style="float:right">
                                        <button type="button" class="btn btn-primary mt-3" @click.prevent="goToStep(3)">Previous</button>
                                        <button type="button" class="btn btn-primary mt-3" @click.prevent="goToStep(5)">Next</button>
                                    </div>
                                </template>

                                <template id="user_detail4" v-if="activePhase == 5">
                                    <!-- <h1>Step 3</h1> -->
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="fw-bold">Pendidikan</label>
                                                <VueMultiselect
                                                    v-model="user_detail5.pendidikan"
                                                    :options="pendidikan"
                                                    label="name"
                                                    track-by="value"
                                                    :allow-empty="false"
                                                    deselect-label="Can't remove this value"
                                                    placeholder="Select pendidikan"
                                                ></VueMultiselect>
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">Sekolah</label>
                                                <input type="text" class="form-control" v-model="user_detail5.nama_sekolah" placeholder="Sekolah">
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">No SDR</label>
                                                <input type="text" class="form-control" v-model="user_detail5.no_sdr" placeholder="NO SDR">
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">Hubungan</label>
                                                <input type="text" class="form-control" v-model="user_detail5.hubungan" placeholder="Hubungan">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="fw-bold">Rekening</label>
                                                <input type="text" class="form-control" v-model="user_detail5.rekening" placeholder="Rekening">
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">Ukuran Baju</label>
                                                <VueMultiselect
                                                    v-model="user_detail5.ukuran_baju"
                                                    :options="ukuran_baju"
                                                    label="name"
                                                    track-by="value"
                                                    :allow-empty="false"
                                                    deselect-label="Can't remove this value"
                                                    placeholder="Select Ukuran Baju"
                                                ></VueMultiselect>
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">Kab Penugasan</label>
                                                <input type="text" class="form-control" v-model="user_detail5.kab_penugasan" placeholder="Kab Penugasan">
                                            </div>
                                        </div>
                                    </div>{{ user_detail5 }}
                                    <div style="float:right">
                                        <button type="button" class="btn btn-primary mt-3" @click.prevent="goToStep(4)">Previous</button>
                                        <button type="button" class="btn btn-primary mt-3" @click.prevent="storeData">Finish</button>
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
            perusahaan: Array
        },
        setup() {
            //define state search
            const activePhase = ref(1);
            const foto = ref();
            // const image = ref();
            const golongan_darah = [
                { name: 'A', value: 0 },
                { name: 'B', value: 1 },
                { name: 'O', value: 2 },
                { name: 'AB', value: 3 }
            ];

            const jenis_kelamin = [
                { name: 'Laki-laki', value: 0 },
                { name: 'Perempuan', value: 1 }
            ];

            const status_kerja = [
                { name: 'Kontrak', value: 0 },
                { name: 'Tetap', value: 1 }
            ];

            const status_pernikahan = [
                { name: 'Belum Menikah', value: 0 },
                { name: 'Sudah Menikah', value: 1 }
            ];

            const ukuran_baju = [
                { name: 'S', value: 0 },
                { name: 'M', value: 1 },
                { name: 'L', value: 2 },
                { name: 'XL', value: 3 },
                { name: 'XXL', value: 4 },
                { name: 'Jumbo', value: 5 },
            ];

            const jabatan = [
                { name: 'Director' },
                { name: 'Div Head' },
                { name: 'Dept Head' },
                { name: 'Sect Head' },
                { name: 'Head' },
                { name: 'Staff' },
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
                { name: 'SD', value: 0 },
                { name: 'SMP', value: 1 },
                { name: 'SMA', value: 2 },
                { name: 'S1', value: 3 },
                { name: 'S2', value: 4 },
                { name: 'S3', value: 5 },
            ];

            const preview = ref();
            const user_detail1 = reactive({
                nama_karyawan: null,
                nik_karyawan: '',
                status_kerja: '',
                divisi: '',
                pt: '',
                image:''
            });

            const user_detail2 = reactive({
                tanggal_masuk: '',
                tanggal_kontrak: '',
                no_kk: '',
                nik_penduduk: '',
                grade:'',
                jabatan:''
            });

            const user_detail3 = reactive({
                no_hp: '',
                no_wa: '',
                no_bpjs_kesehatan:'',
                no_bpjs_ketenagakerjaan:'',
                gol_darah:'',
                email:''
            });

            const user_detail4 = reactive({
                tempat_lahir: '',
                tanggal_lahir: '',
                alamat_ktp:'',
                alamat_domisili:'',
                jenis_kelamin:'',
                status_pernikahan:''
            });

            const user_detail5 = reactive({
                pendidikan: '',
                nama_sekolah: '',
                kab_penugasan:'',
                rekening:'',
                ukuran_baju:'',
                no_sdr:'',
                hubungan:''
            });

            const goToStep = (step) => {
                activePhase.value = step
            }

            const finish = () => {
                console.log(user_detail5.pendidikan, user_detail5.ukuran_baju.value)
            }

            const fileImage = (event) => {
                user_detail1.image = event.target.files[0];
                console.log(user_detail1.image.name)
                if (user_detail1.image.size > 2048 * 2048) {
                    event.preventDefault();
                    alert('File too big (> 2MB)');
                    return;
                } else {
                    preview.value = URL.createObjectURL(event.target.files[0]);
                }
            }

            const storeData = () => {
                // console.log(user_detail1.status_kerja.value);
                Inertia.post('/apps/karyawan',{
                    nama_karyawan: user_detail1.nama_karyawan,
                    nik_karyawan: user_detail1.nik_karyawan,
                    status_kerja: user_detail1.status_kerja.value,
                    divisi_id: user_detail1.divisi.id,
                    pt_id: user_detail1.pt.id,
                    foto: user_detail1.image.name,
                    tanggal_masuk: user_detail2.tanggal_masuk,
                    tanggal_kontrak: user_detail2.tanggal_kontrak,
                    no_kk: user_detail2.no_kk,
                    nik_penduduk: user_detail2.nik_penduduk,
                    grade: user_detail2.grade.name,
                    jabatan: user_detail2.jabatan.name,
                    no_hp: user_detail3.no_hp,
                    no_wa: user_detail3.no_wa,
                    no_bpjs_kesehatan: user_detail3.no_bpjs_kesehatan,
                    no_bpjs_ketenagakerjaan: user_detail3.no_bpjs_ketenagakerjaan,
                    gol_darah: user_detail3.gol_darah.value,
                    email: user_detail3.email,
                    tempat_lahir: user_detail4.tempat_lahir,
                    tanggal_lahir: user_detail4.tanggal_lahir,
                    alamat_ktp: user_detail4.alamat_ktp,
                    alamat_domisili: user_detail4.alamat_domisili,
                    jenis_kelamin: user_detail4.jenis_kelamin.value,
                    status_pernikahan: user_detail4.status_pernikahan.value,
                    pendidikan: user_detail5.pendidikan.value,
                    nama_sekolah: user_detail5.nama_sekolah,
                    kab_penugasan: user_detail5.kab_penugasan,
                    rekening: user_detail5.rekening,
                    ukuran_baju: user_detail5.ukuran_baju.value,
                    no_sdr:user_detail5.no_sdr,
                    hubungan: user_detail5.hubungan
                },{
                    onSuccess: () => {
                        //show success alert
                        Swal.fire({
                            title: 'Success!',
                            text: 'Employee Saved Successfully.',
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 2000
                        });
                    },
                });
            }

            return {
                activePhase, user_detail1, user_detail2, user_detail3, user_detail4, user_detail5,
                goToStep, finish, foto,
                fileImage, preview, golongan_darah, jenis_kelamin, status_pernikahan,
                ukuran_baju, status_kerja, pendidikan, jabatan, grade, storeData
            }
        }
    }
</script>

<style>
</style>
