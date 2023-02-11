<template>
    <Head>
       <title>Edit Employees</title>
   </Head>
   <main>
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Employees</h5>
                    </div>
                    <div class="card-body">
                        <h5>Edit Employees</h5>
                        <hr>
                        <div class="row">
                            <div class="col-md-12">
                                <template id="user_detail1" v-if="activePhase == 1">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="fw-bold">Employee Name</label>
                                                <input class="form-control" v-model="form.nama_karyawan" type="text" placeholder="Employee Name">
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">Employment Status</label>
                                                <VueMultiselect
                                                    v-model="form.status_kerja"
                                                    :options="data_status_kerja"
                                                    label="name"
                                                    track-by="value"
                                                    :allow-empty="false"
                                                    deselect-label="Can't remove this value"
                                                    placeholder="Select Status kerja"
                                                ></VueMultiselect>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label" name="foto">Photo</label><br>
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
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="fw-bold">NIK (Employee)</label>
                                                <input class="form-control" v-model="form.nik_karyawan" type="text" placeholder="NIK">
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">PT</label>
                                                <VueMultiselect
                                                    v-model="form.pt_id"
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
                                                    v-model="form.divisi_id"
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
                                            <button type="button" class="btn btn-primary mt-3" @click.prevent="goToStep(2)" style="float:right">Next</button>
                                        </div>
                                    </div>
                                </template>

                                <template id="user_detail2" v-if="activePhase == 2">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="fw-bold">Grade</label>
                                                <VueMultiselect
                                                    v-model="form.grade"
                                                    :options="data_grade"
                                                    label="name"
                                                    track-by="value"
                                                    :allow-empty="false"
                                                    deselect-label="Can't remove this value"
                                                    placeholder="Select Grade"
                                                ></VueMultiselect>
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">Entry Date</label>
                                                <input type="date" class="form-control" v-model="form.tanggal_masuk" placeholder="Entry Date">
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">Resident Number (NIK)</label>
                                                <input type="text" class="form-control" v-model="form.nik_penduduk" placeholder="NIK (Population)">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="fw-bold">Position</label>
                                                <VueMultiselect
                                                    v-model="form.jabatan"
                                                    :options="data_jabatan"
                                                    label="name"
                                                    track-by="name"
                                                    :allow-empty="false"
                                                    deselect-label="Can't remove this value"
                                                    placeholder="Select Jabatan"
                                                ></VueMultiselect>
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">Contract Date</label>
                                                <input type="date" class="form-control" v-model="form.tanggal_kontrak" placeholder="Contract Date">
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">KK Number</label>
                                                <input type="text" class="form-control" v-model="form.no_kk" placeholder="No KK">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <Link href="/apps/karyawan" class="btn btn-success shadow-sm rounded-sm-5 mt-3">BACK</Link>
                                            <button type="button" class="btn btn-primary mt-3" @click.prevent="goToStep(3)" style="float:right">Next</button>
                                            <button type="button" class="btn btn-primary mt-3" @click.prevent="goToStep(1)" style="float:right">Previous</button>
                                        </div>
                                    </div>
                                </template>

                                <template id="user_detail3" v-if="activePhase == 3">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="fw-bold">HP Number</label>
                                                <input type="text" class="form-control" v-model="form.no_hp" placeholder="No HP">
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">Health BPJS</label>
                                                <input type="text" class="form-control" v-model="form.no_bpjs_kesehatan" placeholder="Healthy BPJS">
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">Blood Group</label>
                                                <VueMultiselect
                                                    v-model="form.gol_darah"
                                                    :options="data_golongan_darah"
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
                                                <input type="text" class="form-control" v-model="form.no_wa" placeholder="WA Number">
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">BPJS of Employment</label>
                                                <input type="text" class="form-control" v-model="form.no_bpjs_ketenagakerjaan" placeholder="BPJS of Employment">
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">Email</label>
                                                <input type="email" class="form-control" v-model="form.email" placeholder="Email">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <Link href="/apps/karyawan" class="btn btn-success shadow-sm rounded-sm-5 mt-3">BACK</Link>
                                            <button type="button" class="btn btn-primary mt-3" @click.prevent="goToStep(4)" style="float:right">Next</button>
                                            <button type="button" class="btn btn-primary mt-3" @click.prevent="goToStep(2)" style="float:right">Previous</button>
                                        </div>
                                    </div>
                                </template>

                                <template id="user_detail4" v-if="activePhase == 4">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="fw-bold">Place of Birth</label>
                                                <input type="text" class="form-control" v-model="form.tempat_lahir" placeholder="Tempat Lahir">
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">KTP Address</label>
                                                <textarea type="text" class="form-control" v-model="form.alamat_ktp" placeholder="Alamat KTP"></textarea>
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">Gender</label>
                                                <VueMultiselect
                                                    v-model="form.jenis_kelamin"
                                                    :options="data_jenis_kelamin"
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
                                                <input type="date" class="form-control" v-model="form.tanggal_lahir" placeholder="Tanggal Lahir">
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">Residence Address</label>
                                                <textarea class="form-control" v-model="form.alamat_domisili" placeholder="Alamat Domisili"></textarea>
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">Married Status</label>
                                                <VueMultiselect
                                                    v-model="form.status_pernikahan"
                                                    :options="data_status_pernikahan"
                                                    label="name"
                                                    track-by="value"
                                                    :allow-empty="false"
                                                    deselect-label="Can't remove this value"
                                                    placeholder="Select Gender"
                                                ></VueMultiselect>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <Link href="/apps/karyawan" class="btn btn-success shadow-sm rounded-sm-5 mt-3">BACK</Link>
                                            <button type="button" class="btn btn-primary mt-3" @click.prevent="goToStep(5)" style="float:right">Next</button>
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
                                                    v-model="form.pendidikan"
                                                    :options="data_pendidikan"
                                                    label="name"
                                                    track-by="value"
                                                    :allow-empty="false"
                                                    deselect-label="Can't remove this value"
                                                    placeholder="Select pendidikan"
                                                ></VueMultiselect>
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">School Name</label>
                                                <input type="text" class="form-control" v-model="form.nama_sekolah" placeholder="Sekolah">
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">Contact Number Family</label>
                                                <input type="text" class="form-control" v-model="form.no_sdr" placeholder="NO SDR">
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">Family Relationship</label>
                                                <input type="text" class="form-control" v-model="form.hubungan" placeholder="Hubungan">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="fw-bold">Rekening Bank</label>
                                                <input type="text" class="form-control" v-model="form.rekening" placeholder="Rekening">
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">Clothes Size</label>
                                                <VueMultiselect
                                                    v-model="form.ukuran_baju"
                                                    :options="data_ukuran_baju"
                                                    label="name"
                                                    track-by="value"
                                                    :allow-empty="false"
                                                    deselect-label="Can't remove this value"
                                                    placeholder="Select Ukuran Baju"
                                                ></VueMultiselect>
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">Assignment District</label>
                                                <input type="text" class="form-control" v-model="form.kab_penugasan" placeholder="Kab Penugasan">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <Link href="/apps/karyawan" class="btn btn-success shadow-sm rounded-sm-5 mt-3">BACK</Link>
                                            <button type="submit" class="btn btn-primary mt-3" @click.prevent="storeData" style="float:right">Finish</button>
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
    import { onMounted } from '@vue/runtime-core';

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
            karyawan: Object
        },
        setup(props) {
            //define state search
            const activePhase = ref(1);
            const foto = ref();

            const data_status_kerja = [
                { name: 'Kontrak', value: 0 },
                { name: 'Tetap', value: 1 }
            ];

            const data_golongan_darah = [
                { name: 'A', value: 0 },
                { name: 'B', value: 1 },
                { name: 'O', value: 2 },
                { name: 'AB', value: 3 }
            ];

            const data_jenis_kelamin = [
                { name: 'Male', value: 0 },
                { name: 'Female', value: 1 }
            ];

            const data_status_pernikahan = [
                { name: 'Single', value: 0 },
                { name: 'Married', value: 1 }
            ];

            const data_ukuran_baju = [
                { name: 'S', value: 0 },
                { name: 'M', value: 1 },
                { name: 'L', value: 2 },
                { name: 'XL', value: 3 },
                { name: 'XXL', value: 4 },
                { name: 'Jumbo', value: 5 },
            ];

            const data_jabatan = [
                { name: 'Director' },
                { name: 'Div Head' },
                { name: 'Dept Head' },
                { name: 'Sect Head' },
                { name: 'Head' },
                { name: 'Staff' },
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
                { name: 'SD', value: 0 },
                { name: 'SMP', value: 1 },
                { name: 'SMA', value: 2 },
                { name: 'S1', value: 3 },
                { name: 'S2', value: 4 },
                { name: 'S3', value: 5 },
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
                        // console.log(data)
                    }
                })

                //status kerja
                data_status_kerja.forEach(function (data) {
                    if(data.value == props.karyawan.status_kerja){
                        form.status_kerja = data
                    }
                })


                //jabatan
                data_jabatan.forEach(function (data) {
                    if(data.name == props.karyawan.jabatan){
                        form.jabatan = data
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
            }

            const image = ref();


            const preview = ref();

            //define form with reactive
            const form = reactive({
                alamat_domisili: props.karyawan.alamat_domisili,
                alamat_ktp: props.karyawan.alamat_ktp,
                divisi_id: props.karyawan.divisi_id,
                email: props.karyawan.email,
                foto: props.karyawan.foto,
                gol_darah: props.karyawan.golongan_darah,
                grade: props.karyawan.grade,
                hubungan: props.karyawan.hubungan,
                jabatan: props.karyawan.jabatan,
                jenis_kelamin: props.karyawan.jenis_kelamin,
                kab_penugasan: props.karyawan.kab_penugasan,
                nama_karyawan: props.karyawan.nama_karyawan,
                nama_sekolah: props.karyawan.nama_sekolah,
                nik_karyawan: props.karyawan.nik_karyawan,
                nik_penduduk: props.karyawan.nik_penduduk,
                no_bpjs_kesehatan: props.karyawan.no_bpjs_kesehatan,
                no_bpjs_ketenagakerjaan: props.karyawan.no_bpjs_ketenagakerjaan,
                no_hp: props.karyawan.no_hp,
                no_wa: props.karyawan.no_wa,
                no_kk: props.karyawan.no_kk,
                no_sdr: props.karyawan.no_sdr,
                pendidikan: props.karyawan.pendidikan,
                pt_id: props.karyawan.pt_id,
                rekening: props.karyawan.rekening,
                status_kerja: props.karyawan.status_kerja,
                status_pernikahan: props.karyawan.status_pernikahan,
                tanggal_kontrak: props.karyawan.tanggal_kontrak,
                tanggal_lahir: props.karyawan.tanggal_lahir,
                tanggal_masuk: props.karyawan.tanggal_masuk,
                tempat_lahir: props.karyawan.tempat_lahir,
                ukuran_baju: props.karyawan.ukuran_baju
            });

            const goToStep = (step) => {
                activePhase.value = step
            }

            const fileImage = (event) => {
                foto.value = event.target.files[0];
                // console.log(user_detail1.image.name)
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
                        nama_karyawan: form.nama_karyawan,
                        nik_karyawan: form.nik_karyawan,
                        status_kerja: form.status_kerja.value,
                        divisi_id: form.divisi_id.id,
                        pt_id: form.pt_id.id,
                        tanggal_masuk: form.tanggal_masuk,
                        tanggal_kontrak: form.tanggal_kontrak,
                        no_kk: form.no_kk,
                        nik_penduduk: form.nik_penduduk,
                        grade: form.grade.name,
                        jabatan: form.jabatan.name,
                        no_hp: form.no_hp,
                        no_wa: form.no_wa,
                        no_bpjs_kesehatan: form.no_bpjs_kesehatan,
                        no_bpjs_ketenagakerjaan: form.no_bpjs_ketenagakerjaan,
                        gol_darah: form.gol_darah.value,
                        email: form.email,
                        tempat_lahir: form.tempat_lahir,
                        tanggal_lahir: form.tanggal_lahir,
                        alamat_ktp: form.alamat_ktp,
                        alamat_domisili: form.alamat_domisili,
                        jenis_kelamin: form.jenis_kelamin.value,
                        status_pernikahan: form.status_pernikahan.value,
                        pendidikan: form.pendidikan.value,
                        nama_sekolah: form.nama_sekolah,
                        kab_penugasan: form.kab_penugasan,
                        rekening: form.rekening,
                        ukuran_baju: form.ukuran_baju.value,
                        no_sdr:form.no_sdr,
                        hubungan: form.hubungan
                    },{
                        onSuccess: () => {
                            //show success alert
                            Swal.fire({
                                title: 'Success!',
                                text: 'Employee updated successfully.',
                                icon: 'success',
                                showConfirmButton: false,
                                timer: 2000
                            });
                        },
                    });
                }else{
                    Inertia.post(`/apps/karyawan/${props.karyawan.id}`, {
                        _method: 'put',
                        nama_karyawan: form.nama_karyawan,
                        nik_karyawan: form.nik_karyawan,
                        status_kerja: form.status_kerja.value,
                        divisi_id: form.divisi_id.id,
                        pt_id: form.pt_id.id,
                        task_file: foto.value,
                        nama_file: foto.value.name,
                        tanggal_masuk: form.tanggal_masuk,
                        tanggal_kontrak: form.tanggal_kontrak,
                        no_kk: form.no_kk,
                        nik_penduduk: form.nik_penduduk,
                        grade: form.grade.name,
                        jabatan: form.jabatan.name,
                        no_hp: form.no_hp,
                        no_wa: form.no_wa,
                        no_bpjs_kesehatan: form.no_bpjs_kesehatan,
                        no_bpjs_ketenagakerjaan: form.no_bpjs_ketenagakerjaan,
                        gol_darah: form.gol_darah.value,
                        email: form.email,
                        tempat_lahir: form.tempat_lahir,
                        tanggal_lahir: form.tanggal_lahir,
                        alamat_ktp: form.alamat_ktp,
                        alamat_domisili: form.alamat_domisili,
                        jenis_kelamin: form.jenis_kelamin.value,
                        status_pernikahan: form.status_pernikahan.value,
                        pendidikan: form.pendidikan.value,
                        nama_sekolah: form.nama_sekolah,
                        kab_penugasan: form.kab_penugasan,
                        rekening: form.rekening,
                        ukuran_baju: form.ukuran_baju.value,
                        no_sdr:form.no_sdr,
                        hubungan: form.hubungan
                    },{
                        onSuccess: () => {
                            //show success alert
                            Swal.fire({
                                title: 'Success!',
                                text: 'Employee updated successfully.',
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
                data_golongan_darah, data_jenis_kelamin, data_status_pernikahan,
                data_ukuran_baju, data_status_kerja, data_pendidikan, data_jabatan, data_grade,
                storeData, form, fileImage,
            }
        }
    }
</script>

<style>
</style>
