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
                                                    placeholder="Select Employment Status"
                                                ></VueMultiselect>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label" name="foto">Photo</label><br>
                                                <input type="file" id="task_file" @change="selectedTaskFile">
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
                                    <div class="row">
                                        <div class="col-md-12">
                                            <Link href="/apps/karyawan" class="btn btn-success shadow-sm rounded-sm-5 mt-3">BACK</Link>
                                            <button type="button" class="btn btn-primary mt-3" @click.prevent="goToStep(2)" style="float:right" :disabled="cekData1">Next</button>
                                        </div>
                                    </div>
                                </template>

                                <template id="user_detail2" v-if="activePhase == 2">
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
                                                <label class="fw-bold">Entry Date</label>
                                                <input type="date" class="form-control" v-model="user_detail2.tanggal_masuk" placeholder="Entry Date">
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">Resident Number (NIK)</label>
                                                <input type="text" class="form-control" v-model="user_detail2.nik_penduduk" placeholder="Resident Number (NIK)">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="fw-bold">Position</label>
                                                <VueMultiselect
                                                    v-model="user_detail2.jabatan"
                                                    :options="jabatan"
                                                    label="nama_jabatan"
                                                    track-by="nama_jabatan"
                                                    :allow-empty="false"
                                                    deselect-label="Can't remove this value"
                                                    placeholder="Select Position"
                                                ></VueMultiselect>
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">Contract Date</label>
                                                <input type="date" class="form-control" v-model="user_detail2.tanggal_kontrak" placeholder="Contract Date">
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">KK Number</label>
                                                <input type="text" class="form-control" v-model="user_detail2.no_kk" placeholder="KK Number">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <Link href="/apps/karyawan" class="btn btn-success shadow-sm rounded-sm-5 mt-3">BACK</Link>
                                            <button type="button" class="btn btn-primary mt-3" @click.prevent="goToStep(3)" style="float:right" :disabled="cekData2">Next</button>
                                            <button type="button" class="btn btn-primary mt-3" @click.prevent="goToStep(1)" style="float:right">Previous</button>
                                        </div>
                                    </div>
                                </template>

                                <template id="user_detail3" v-if="activePhase == 3">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="fw-bold">HP Number</label>
                                                <input type="text" class="form-control" v-model="user_detail3.no_hp" placeholder="HP Number">
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">Health BPJS</label>
                                                <input type="text" class="form-control" v-model="user_detail3.no_bpjs_kesehatan" placeholder="Health BPJS">
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">Blood Group</label>
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
                                                <label class="fw-bold">WA Number</label>
                                                <input type="text" class="form-control" v-model="user_detail3.no_wa" placeholder="WA Number">
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">BPJS of Employment</label>
                                                <input type="text" class="form-control" v-model="user_detail3.no_bpjs_ketenagakerjaan" placeholder="BPJS of Employment">
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">Email</label>
                                                <input type="email" class="form-control" v-model="user_detail3.email" placeholder="Email">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <Link href="/apps/karyawan" class="btn btn-success shadow-sm rounded-sm-5 mt-3">BACK</Link>
                                            <button type="button" class="btn btn-primary mt-3" @click.prevent="goToStep(4)" style="float:right" :disabled="cekData3">Next</button>
                                            <button type="button" class="btn btn-primary mt-3" @click.prevent="goToStep(2)" style="float:right">Previous</button>
                                        </div>
                                    </div>
                                </template>

                                <template id="user_detail4" v-if="activePhase == 4">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="fw-bold">Place of Birth</label>
                                                <input type="text" class="form-control" v-model="user_detail4.tempat_lahir" placeholder="Place of Birth">
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">KTP Address</label>
                                                <textarea type="text" class="form-control" v-model="user_detail4.alamat_ktp" placeholder="KTP Address"></textarea>
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">Gender</label>
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
                                                <label class="fw-bold">Date of Birth</label>
                                                <input type="date" class="form-control" v-model="user_detail4.tanggal_lahir" placeholder="Date of Birth">
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">Residence Address</label>
                                                <textarea class="form-control" v-model="user_detail4.alamat_domisili" placeholder="Residence Address"></textarea>
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">Married Status</label>
                                                <VueMultiselect
                                                    v-model="user_detail4.status_pernikahan"
                                                    :options="status_pernikahan"
                                                    label="name"
                                                    track-by="value"
                                                    :allow-empty="false"
                                                    deselect-label="Can't remove this value"
                                                    placeholder="Select Married Status"
                                                ></VueMultiselect>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <Link href="/apps/karyawan" class="btn btn-success shadow-sm rounded-sm-5 mt-3">BACK</Link>
                                            <button type="button" class="btn btn-primary mt-3" @click.prevent="goToStep(5)" style="float:right" :disabled="cekData4">Next</button>
                                            <button type="button" class="btn btn-primary mt-3" @click.prevent="goToStep(3)" style="float:right">Previous</button>
                                        </div>
                                    </div>
                                </template>

                                <template id="user_detail4" v-if="activePhase == 5">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="fw-bold">Education</label>
                                                <VueMultiselect
                                                    v-model="user_detail5.pendidikan"
                                                    :options="pendidikan"
                                                    label="name"
                                                    track-by="value"
                                                    :allow-empty="false"
                                                    deselect-label="Can't remove this value"
                                                    placeholder="Select Education"
                                                ></VueMultiselect>
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">School Name</label>
                                                <input type="text" class="form-control" v-model="user_detail5.nama_sekolah" placeholder="School Name">
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">Contact Number Family</label>
                                                <input type="text" class="form-control" v-model="user_detail5.no_sdr" placeholder="Contact Number Family">
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">Family Relationship</label>
                                                <input type="text" class="form-control" v-model="user_detail5.hubungan" placeholder="Family Relationship">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="fw-bold">Rekening Bank</label>
                                                <input type="text" class="form-control" v-model="user_detail5.rekening" placeholder="Rekening Bank">
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">Clothes Size</label>
                                                <VueMultiselect
                                                    v-model="user_detail5.ukuran_baju"
                                                    :options="ukuran_baju"
                                                    label="name"
                                                    track-by="value"
                                                    :allow-empty="false"
                                                    deselect-label="Can't remove this value"
                                                    placeholder="Select Clothes Size"
                                                ></VueMultiselect>
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">Assignment District</label>
                                                <input type="text" class="form-control" v-model="user_detail5.kab_penugasan" placeholder="Assignment District">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <Link href="/apps/karyawan" class="btn btn-success shadow-sm rounded-sm-5 mt-3">BACK</Link>
                                            <button type="button" class="btn btn-primary mt-3" @click.prevent="storeData" style="float:right" :disabled="cekData5">Finish</button>
                                            <button type="button" class="btn btn-primary mt-3" @click.prevent="goToStep(4)" style="float:right">Previous</button>
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
            jabatan: Array
        },
        setup() {
            //define state search
            const activePhase = ref(1);
            const foto = ref();
            //cek kelengkapan data
            const terms = ref(false);
            const golongan_darah = [
                { name: 'A', value: 0 },
                { name: 'B', value: 1 },
                { name: 'O', value: 2 },
                { name: 'AB', value: 3 }
            ];

            const jenis_kelamin = [
                { name: 'Male', value: 0 },
                { name: 'Female', value: 1 }
            ];

            const status_kerja = [
                { name: 'Kontrak', value: 0 },
                { name: 'Tetap', value: 1 }
            ];

            const status_pernikahan = [
                { name: 'Single', value: 0 },
                { name: 'Married', value: 1 }
            ];

            const ukuran_baju = [
                { name: 'S', value: 0 },
                { name: 'M', value: 1 },
                { name: 'L', value: 2 },
                { name: 'XL', value: 3 },
                { name: 'XXL', value: 4 },
                { name: 'Jumbo', value: 5 },
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

            const cekData1 = computed(() => {
                return user_detail1.nama_karyawan.length == 0 || user_detail1.status_kerja.length == 0 || user_detail1.nik_karyawan.length == 0 || user_detail1.pt.length == 0 || user_detail1.divisi.length == 0;
            })

            const cekData2 = computed(() => {
                return user_detail2.grade.length == 0 || user_detail2.tanggal_masuk.length == 0 || user_detail2.nik_penduduk.length == 0 || user_detail2.jabatan.length == 0 || user_detail2.no_kk.length == 0;
            })

            const cekData3 = computed(() => {
                return user_detail3.no_hp.length == 0 || user_detail3.no_wa.length == 0 || user_detail3.gol_darah.length == 0 || user_detail3.email.length == 0;
            })

            const cekData4 = computed(() => {
                return user_detail4.tempat_lahir.length == 0 || user_detail4.tanggal_lahir.length == 0 || user_detail4.alamat_ktp.length == 0 || user_detail4.alamat_domisili.length == 0 || user_detail4.jenis_kelamin.length == 0 || user_detail4.status_pernikahan.length == 0;
            })

            const cekData5 = computed(() => {
                return user_detail5.pendidikan.length == 0 || user_detail5.nama_sekolah.length == 0 || user_detail5.kab_penugasan.length == 0 || user_detail5.ukuran_baju.length == 0 || user_detail5.no_sdr.length == 0 || user_detail5.hubungan.length == 0;
            })

            const preview = ref();
            const user_detail1 = reactive({
                nama_karyawan: '',
                nik_karyawan: '',
                status_kerja: '',
                divisi: '',
                pt: '',
                image:'',
                file:'',
                task_file:'',
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

            // const fileImage = (event) => {
            //     user_detail1.task_file = event.target.files[0];
            //     console.log(user_detail1.task_file)
            //     if (user_detail1.task_file.size > 2048 * 2048) {
            //         event.preventDefault();
            //         alert('File too big (> 2MB)');
            //         return;
            //     } else {
            //         preview.value = URL.createObjectURL(event.target.files[0]);
            //     }
            // }

            const selectedTaskFile = (e) => {
                user_detail1.task_file = e.target.files[0];
                if (user_detail1.task_file.size > 2048 * 2048) {
                    e.preventDefault();
                    alert('File too big (> 2MB)');
                    return;
                } else {
                    preview.value = URL.createObjectURL(e.target.files[0]);
                }
            }

            const storeData = () => {
                Inertia.post('/apps/karyawan',{
                    nama_karyawan: user_detail1.nama_karyawan,
                    nik_karyawan: user_detail1.nik_karyawan,
                    status_kerja: user_detail1.status_kerja.value,
                    divisi_id: user_detail1.divisi.id,
                    pt_id: user_detail1.pt.id,
                    task_file: user_detail1.task_file,
                    nama_file: user_detail1.task_file.name,
                    file: user_detail1.file.value,
                    tanggal_masuk: user_detail2.tanggal_masuk,
                    tanggal_kontrak: user_detail2.tanggal_kontrak,
                    no_kk: user_detail2.no_kk,
                    nik_penduduk: user_detail2.nik_penduduk,
                    grade: user_detail2.grade.name,
                    jabatan_id: user_detail2.jabatan.id,
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
                // fileImage,
                preview, golongan_darah, jenis_kelamin, status_pernikahan,
                ukuran_baju, status_kerja, pendidikan,
                grade, storeData,
                selectedTaskFile, terms, cekData1, cekData2, cekData3, cekData4, cekData5
            }
        }
    }
</script>

<style>
</style>
