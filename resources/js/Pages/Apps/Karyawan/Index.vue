<template>
     <Head>
        <title>Employees</title>
    </Head>
    <main>
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <h5>Employees</h5>
                    <span class="d-block m-t-5">Page to manage the <code> employees </code> data</span>
                </div>
                <div class="card-block table-border-style">
                    <div v-if="session.error" class="alert alert-danger">
                        {{ session.error }}
                    </div>
                    <div v-if="session.success" class="alert alert-success">
                        {{ session.success }}
                    </div>
                    <div class="table-responsive">
                        <div class="input-group mb-3">
                            <Link href="/apps/karyawan/create" v-if="hasAnyPermission(['karyawan.create'])"  class="btn theme-bg4 text-white f-12" style="cursor:pointer; border:none"><i class="fa fa-plus"></i> Add</Link>
                            <input type="text" class="form-control" v-model="search" placeholder="search by Employees name or Employment Identity Number or Resident Number NIK..." @keyup="handleSearch">
                            <button class="btn btn theme-bg5 text-white f-12" @click="handleSearch"> <i class="fa fa-search me-2"></i></button>
                        </div>
                        <div class="d-flex flex-row-reverse bd-highlight mb-3">
                            <button class="btn btn-success dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fa fa-file-excel"></i> Excel
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a  :href="`/apps/karyawan/export`" target="_blank" class="dropdown-item">Export</a>
                                <button @click="importExcel" target="_blank" class="dropdown-item">Import</button>
                            </div>
                        </div>
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Employees Name</th>
                                    <th>Position</th>
                                    <th>Employment Identity Number</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(kar, index) in karyawan.data" :key="index">
                                    <td>{{ index + 1 }}</td>
                                    <td>{{ kar.nama_karyawan }}</td>
                                    <td>{{ kar.jabatan.nama_jabatan }}</td>
                                    <td>{{ kar.nik_karyawan }}</td>
                                    <td v-if="(kar.status_kerja == 0)"><a class="label theme-bg2 text-white f-12" style="border-radius:10px">Contract</a></td>
                                    <td v-else><a class="label theme-bg text-white f-12" style="border-radius:10px">Fixed</a></td>
                                    <td class="text-center">
                                        <Link :href="`/apps/karyawan/${kar.id}/edit`" v-if="hasAnyPermission(['karyawan.edit'])" class="label theme-bg3 text-white f-12 me-2" style="cursor:pointer" title="Edit" data-toggle="tooltip-inner"><i class="fa fa-pencil-alt me-1"></i></Link>
                                        <a @click.prevent="destroy(kar.id)" v-if="hasAnyPermission(['karyawan.delete'])" class="label theme-bg6 text-white f-12" style="cursor:pointer" title="Delete" data-toggle="tooltip-inner"><i class="fa fa-trash"></i></a>
                                        <a @click.prevent="detail(kar)" class="label theme-bg8 text-white f-12" title="Detail" data-toggle="tooltip-inner" style="cursor:pointer"><i class="fa fa-info"></i></a>
                                        <a @click.prevent="addKarir(kar)" class="label theme-bg2 text-white f-12" title="Add Historical Organization" data-toggle="tooltip-inner" style="cursor:pointer"><i class="fa fa-user-plus"></i></a>
                                        <Link :href="`/apps/karyawan/${kar.id}/list-organisasi`" class="label theme-bg text-white f-12 me-2" style="cursor:pointer" title="Historical Organization" data-toggle="tooltip-inner"><i class="fa fa-users"></i></Link>
                                        <a @click.prevent="addPelanggaran(kar)" class="label theme-bg2 text-white f-12" title="Add Violation" data-toggle="tooltip-inner" style="cursor:pointer"><i class="fa fa-exclamation-triangle"></i></a>
                                        <Link :href="`/apps/karyawan/${kar.id}/list-pelanggaran`" class="label theme-bg text-white f-12 me-2" style="cursor:pointer" title="Violations" data-toggle="tooltip-inner"><i class="fa fa-exclamation-circle"></i></Link>
                                    </td>
                                </tr>
                                <!-- jika data kosong -->
                                <tr v-if="karyawan.data[0] == undefined">
                                    <td colspan="4" class="text-center">
                                        <br>
                                        <i class="fa fa-file-excel fa-5x"></i><br><br>
                                            No Data To Display
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="row" style="max-width:100%; overflow-x:hidden">
                            <div class="col-md-4">
                                <label v-if="karyawan.data[0] != undefined" align="start">Showing {{ karyawan.from }} to {{ karyawan.to }} of {{ karyawan.total }} items</label>
                            </div>
                            <div class="col-md-8">
                                <Pagination v-if="karyawan.data[0] != undefined" :links="karyawan.links" align="end"/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- untuk modal detail -->
        <Teleport to="body">
            <modalScroll :show="showModalKarirDetail" @close="showModalKarirDetail = false">
                <template #header>
                    <h5 class="modal-title">Employee Details</h5>
                </template>
                <template #body>
                    <div class="mb-3">
                        <div class="form-group mb-3">
                            <img v-if="foto != null" :src="`/storage/${foto}`" alt="photo" style="width:20%"><br>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <div class="form-group mb-3">
                                    <label class="col-form-label">Employee Name :</label>
                                    <input type="text" class="form-control" v-model="nama_karyawan" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <div class="form-group mb-3">
                                    <label class="col-form-label">Employment Identity Number :</label><br>
                                    <input type="text" class="form-control" v-model="nik_karyawan" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <div class="form-group mb-3">
                                    <label class="col-form-label">Employment Status :</label>
                                    <input v-if="status_kerja == 0" type="text" class="form-control" value="Contract" readonly>
                                    <input v-if="status_kerja == 1" type="text" class="form-control" value="Fixed" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <div class="form-group mb-3">
                                    <label class="col-form-label">Division :</label>
                                    <input type="text" class="form-control" v-model="divisi_id" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <div class="form-group mb-3">
                                    <label class="col-form-label">PT :</label>
                                    <input type="text" class="form-control" v-model="pt_id" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <div class="form-group mb-3">
                                    <label class="col-form-label">Position :</label>
                                    <input type="text" class="form-control" v-model="jabatan_id" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <div class="form-group mb-3">
                                    <label class="col-form-label">Entry Date :</label>
                                    <input type="date" class="form-control" v-model="tanggal_masuk" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <div class="form-group mb-3">
                                    <label class="col-form-label">Start of Contract :</label>
                                    <input type="date" class="form-control" v-model="tanggal_kontrak" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <div class="form-group mb-3">
                                    <label class="col-form-label">End of Contract :</label>
                                    <input type="date" class="form-control" v-model="akhir_kontrak" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <div class="form-group mb-3">
                                    <label class="col-form-label">Blood Group :</label>
                                    <input v-if="gol_darah == 0" type="text" class="form-control" value="A" readonly>
                                    <input v-else-if="gol_darah == 1" type="text" class="form-control" value="B" readonly>
                                    <input v-else-if="gol_darah == 2" type="text" class="form-control" value="O" readonly>
                                    <input v-else-if="gol_darah == 3" type="text" class="form-control" value="AB" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <div class="form-group mb-3">
                                    <label class="col-form-label">KK Number :</label>
                                    <input type="text" class="form-control" v-model="no_kk" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <div class="form-group mb-3">
                                    <label class="col-form-label">Resident Number (NIK)  :</label>
                                    <input type="text" class="form-control" v-model="nik_penduduk" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <div class="form-group mb-3">
                                    <label class="col-form-label">HP Number :</label>
                                    <input type="text" class="form-control" v-model="no_hp" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <div class="form-group mb-3">
                                    <label class="col-form-label">WA Number :</label>
                                    <input type="text" class="form-control" v-model="no_wa" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <div class="form-group mb-3">
                                    <label class="col-form-label">BPJS of Employment:</label>
                                    <input type="text" class="form-control" v-model="no_bpjs_ketenagakerjaan" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <div class="form-group mb-3">
                                    <label class="col-form-label">BPJS of Health:</label>
                                    <input type="text" class="form-control" v-model="no_bpjs_kesehatan" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <div class="form-group mb-3">
                                    <label class="col-form-label">Email :</label>
                                    <input type="text" class="form-control" v-model="email" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <div class="form-group mb-3">
                                    <label class="col-form-label">Place of Birth :</label>
                                    <input type="text" class="form-control" v-model="tempat_lahir" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <div class="form-group mb-3">
                                    <label class="col-form-label">Date of Birth :</label>
                                    <input type="text" class="form-control" v-model="tanggal_lahir" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <div class="form-group mb-3">
                                    <label class="col-form-label">Age :</label>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" v-model="umur" readonly>
                                        <span class="input-group-text">years old</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <div class="form-group mb-3">
                                    <label class="col-form-label">Grade :</label>
                                    <input type="text" class="form-control" v-model="grade" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <div class="form-group mb-3">
                                    <label class="col-form-label">Gender :</label>
                                    <input v-if="jenis_kelamin == 0" type="text" class="form-control" value="Male" readonly>
                                    <input v-if="jenis_kelamin == 1" type="text" class="form-control" value="Female" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="form-group mb-3">
                            <label class="col-form-label">KTP Address :</label>
                            <textarea type="text" class="form-control" v-model="alamat_ktp" readonly></textarea>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="form-group mb-3">
                            <label class="col-form-label">Residence Address :</label>
                            <textarea type="text" class="form-control" v-model="alamat_domisili" readonly></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <div class="form-group mb-3">
                                    <label class="col-form-label">Married Status :</label>
                                    <input v-if="status_pernikahan == 0" type="text" class="form-control" value="Single" readonly>
                                    <input v-if="status_pernikahan == 1" type="text" class="form-control" value="Married" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <div class="form-group mb-3">
                                    <label class="col-form-label">Education :</label>
                                    <input v-if="pendidikan == 0" type="text" class="form-control" value="SD" readonly>
                                    <input v-else-if="pendidikan == 1" type="text" class="form-control" value="SMP" readonly>
                                    <input v-else-if="pendidikan == 2" type="text" class="form-control" value="SMA" readonly>
                                    <input v-else-if="pendidikan == 3" type="text" class="form-control" value="S1" readonly>
                                    <input v-else-if="pendidikan == 4" type="text" class="form-control" value="S2" readonly>
                                    <input v-else-if="pendidikan == 5" type="text" class="form-control" value="S3" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <div class="form-group mb-3">
                                    <label class="col-form-label">School Name :</label>
                                    <input type="text" class="form-control" v-model="nama_sekolah" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <div class="form-group mb-3">
                                    <label class="col-form-label">Assignment District :</label>
                                    <input type="text" class="form-control" v-model="kab_penugasan" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <div class="form-group mb-3">
                                    <label class="col-form-label">Rekening Bank:</label>
                                    <input type="text" class="form-control" v-model="rekening" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <div class="form-group mb-3">
                                    <label class="col-form-label">Clothes Size :</label>
                                    <input v-if="ukuran_baju == 0" type="text" class="form-control" value="S" readonly>
                                    <input v-else-if="ukuran_baju == 1" type="text" class="form-control" value="M" readonly>
                                    <input v-else-if="ukuran_baju == 2" type="text" class="form-control" value="L" readonly>
                                    <input v-else-if="ukuran_baju == 3" type="text" class="form-control" value="XL" readonly>
                                    <input v-else-if="ukuran_baju == 4" type="text" class="form-control" value="XXL" readonly>
                                    <input v-else-if="ukuran_baju == 5" type="text" class="form-control" value="Jumbo" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <div class="form-group mb-3">
                                    <label class="col-form-label">Contact Number Family :</label>
                                    <input type="text" class="form-control" v-model="no_sdr" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <div class="form-group mb-3">
                                    <label class="col-form-label">Family Relationship :</label>
                                    <input type="text" class="form-control" v-model="hubungan" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
                <template #footer>
                    <button
                        class="btn btn-success m-2" @click="tutupModalDetail">Close</button>
                </template>
            </modalScroll>
        </Teleport>
        <!-- untuk add karir-->
        <Teleport to="body">
            <modal :show="showModalKarir" @close="showModalKarir = false">
                <template #header>
                    <h5 class="modal-title">Historical Organization</h5>
                </template>
                <template #body>
                    <div class="form-group mb-3">
                        <label class="col-form-label">Employee Name :</label>
                        <input type="text" class="form-control" placeholder="PT Name" v-model="nama" readonly>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="col-form-label">Group Joining Date : </label>
                                <input type="date" class="form-control" placeholder="Group Joining Date" v-model="tgl_gabung_grup" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="col-form-label">Division :</label>
                                <VueMultiselect
                                    v-model="divisi_id"
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
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="col-form-label">Date of Entry : </label>
                                <input type="date" class="form-control" placeholder="Date of Entry" v-model="tgl_masuk" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="col-form-label">End Date : </label>
                                <input type="date" class="form-control" placeholder="End Date" v-model="tgl_berakhir" required>
                            </div>
                        </div>
                    </div>
                </template>
                <template #footer>
					<form @submit.prevent="storeKarir">
						<button type="submit" class="btn btn-success text-white m-2">Save</button>
					</form>
                    <button
                        class="btn btn-secondary m-2" @click="tutupModal">Close</button>
                </template>
            </modal>
        </Teleport>
        <!-- untuk catatan pelanggaran -->
        <Teleport to="body">
            <modal :show="showModalPelanggaran" @close="showModalPelanggaran = false">
                <template #header>
                    <h5 class="modal-title">Violation Record</h5>
                </template>
                <template #body>
                    <div class="form-group mb-3">
                        <label class="col-form-label">Employee Name :</label>
                        <input type="text" class="form-control" v-model="nama" readonly>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="col-form-label">Date : </label>
                                <input type="date" class="form-control" placeholder="Date" v-model="tanggal" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="col-form-label">Level Violation : </label>
                                <VueMultiselect
                                    v-model="tingkatan"
                                    :options="daftar_tingkatan"
                                    label="name"
                                    track-by="value"
                                    :allow-empty="false"
                                    deselect-label="Can't remove this value"
                                    placeholder="Select level of violation"
                                ></VueMultiselect>
                            </div>
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label class="col-form-label">Notes : </label>
                        <textarea type="text" class="form-control" placeholder="Notes" v-model="catatan" required></textarea>
                    </div>
                </template>
                <template #footer>
					<form @submit.prevent="storePelanggaran">
						<button type="submit" class="btn btn-success text-white m-2">Save</button>
					</form>
                    <button
                        class="btn btn-secondary m-2" @click="tutupModalPelanggaran">Close</button>
                </template>
            </modal>
        </Teleport>
        <!-- modal import excel -->
        <Teleport to="body">
            <modal :show="showModalImport" @close="showModalImport = false">
                <template #header>
                    <h5 class="modal-title">Import Excel</h5>
                </template>
                <template #body>
                    <p class="text-warning">Make sure the data entered is in accordance with the existing format, the data imported is only new data</p>
                    <p class="text-warning">Specifically for the column for employment status, gender, blood group, married status, education, clothes size according to the options in the exact same case</p>
                    <div class="form-group mb-3">
                        <label for="col-form-label">Format Example</label><br>
                        <a :href="`/apps/karyawan/format`" target="_blank" class="btn btn-success text-white"><i class="fa fa-download me-2"></i>Download Format</a>
                    </div>
                    <div class="form-group mb-3">
                        <label class="col-form-label">File :</label><br>
                        <input type="file" id="task_file" @change="selectedTaskFile" required>
                    </div>
                </template>
                <template #footer>
					<form @submit.prevent="storeExcel">
						<button type="submit" class="btn btn-success text-white m-2" :disabled="!file_excel">Import</button>
					</form>
                    <button
                        class="btn btn-secondary m-2" @click="tutupModalImport">Close</button>
                </template>
            </modal>
        </Teleport>
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
    import { ref } from 'vue';
    //import inertia adapter
    import { Inertia } from '@inertiajs/inertia';
    //import sweet alert2
    import Swal from 'sweetalert2';
    import ModalScroll from '../../../Components/ModalScroll.vue';
    import Modal from '../../../Components/Modal.vue';
    import VueMultiselect from 'vue-multiselect';
    import 'vue-multiselect/dist/vue-multiselect.css';
    import axios from 'axios';

    export default {
        //layout
        layout: LayoutApp,

        //register component
        components: {
            Head,
            Link,
            Pagination,
            ModalScroll,
            Modal,
            VueMultiselect,
            Swal
        },

        //props
        props: {
            karyawan: Object,
            divisi: Array,
            session: Object,
        },

        setup() {
            const showModalKarirDetail = ref(false);
            const showModalKarir = ref(false);
            const showModalPelanggaran = ref(false);
            const showModalImport = ref(false);
            //untuk detail
            const nama_karyawan = ref();
            const nik_karyawan = ref();
            const status_kerja = ref();
            const divisi_id = ref();
            const pt_id = ref();
            const foto = ref();
            const tanggal_masuk = ref();
            const tanggal_kontrak = ref();
            const no_kk = ref();
            const nik_penduduk = ref();
            const grade = ref();
            const jabatan = ref();
            const no_hp = ref();
            const no_wa = ref();
            const no_bpjs_kesehatan = ref();
            const no_bpjs_ketenagakerjaan = ref();
            const gol_darah = ref();
            const email = ref();
            const tempat_lahir = ref();
            const tanggal_lahir = ref();
            const alamat_ktp = ref();
            const alamat_domisili = ref();
            const jenis_kelamin = ref();
            const status_pernikahan = ref();
            const pendidikan = ref();
            const nama_sekolah = ref();
            const kab_penugasan = ref();
            const rekening = ref();
            const ukuran_baju = ref();
            const no_sdr = ref();
            const hubungan = ref();
            const umur = ref();
            const akhir_kontrak = ref();
            const jabatan_id = ref();
            //save nama karyawan di add history organisasi
            const idnya = ref();
            const nama = ref();
            const tgl_gabung_grup = ref();
            const tgl_masuk = ref();
            const tgl_berakhir = ref();
            //save pelanggaran
            const catatan = ref();
            const tanggal = ref();
            const tingkatan = ref();
            //import excel
            const file_excel = ref();

            // define state search
            const search = ref('' || (new URL(document.location)).searchParams.get('search'));

            //tutup modal
            const tutupModalDetail = () => {
                showModalKarirDetail.value = false;
            }

            const tutupModalPelanggaran = () => {
                showModalPelanggaran.value = false;
            }

            const tutupModal = () => {
                showModalKarir.value = false;
            }

            const tutupModalImport = () => {
                showModalImport.value = false;
            }

            const importExcel = () => {
                showModalImport.value = true
            }

            const selectedTaskFile = (e) => {
                file_excel.value = e.target.files[0];
            }

            const storeExcel = () => {
                Inertia.post('/apps/karyawan/import',{
                    file: file_excel.value
                },{
                    onSuccess: () => {
                        tutupModalImport();
                    },
                });
            }

            const daftar_tingkatan = [
                { name: 'Misdemeanor', value: 0 },
                { name: 'Moderate Offence', value: 1 },
                { name: 'Serious Offence', value: 2 },
            ];

            //define method search
            const handleSearch = () => {
                Inertia.get('/apps/karyawan', {
                    //send params "q" with value from state "search"
                    search: search.value,
                });
            }

            //method destroy
            const destroy = (id) => {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                })
                .then((result) => {
                    if (result.isConfirmed) {
                        Inertia.delete(`/apps/karyawan/${id}`);
                        Swal.fire({
                            title: 'Deleted!',
                            text: 'Employee deleted successfully.',
                            icon: 'success',
                            timer: 2000,
                            showConfirmButton: false,
                        });
                    }
                })
            }

            //method "detail"
            const detail = (kar) => {
                var lahir = new Date(kar['tanggal_lahir'])
                var today = new Date();
		        var age = Math.floor((today-lahir) / (365.25 * 24 * 60 * 60 * 1000));
                umur.value = age
                nama_karyawan.value = kar['nama_karyawan']
                nik_karyawan.value = kar['nik_karyawan']
                status_kerja.value = kar['status_kerja']
                divisi_id.value = kar['divisi']['nama_divisi']
                pt_id.value = kar['perusahaan']['nama_pt']
                jabatan_id.value = kar['jabatan']['nama_jabatan']
                foto.value = kar['foto']
                tanggal_masuk.value = kar['tanggal_masuk']
                tanggal_kontrak.value = kar['tanggal_kontrak']
                akhir_kontrak.value = kar['akhir_kontrak']
                no_kk.value = kar['no_kk']
                nik_penduduk.value = kar['nik_penduduk']
                grade.value = kar['grade']
                jabatan.value = kar['jabatan']
                no_hp.value = kar['no_hp']
                no_wa.value = kar['no_wa']
                no_bpjs_kesehatan.value = kar['no_bpjs_kesehatan']
                no_bpjs_ketenagakerjaan.value = kar['no_bpjs_ketenagakerjaan']
                gol_darah.value = kar['gol_darah']
                email.value = kar['email']
                tempat_lahir.value = kar['tempat_lahir']
                tanggal_lahir.value = kar['tanggal_lahir']
                alamat_ktp.value = kar['alamat_ktp']
                alamat_domisili.value = kar['alamat_domisili']
                jenis_kelamin.value = kar['jenis_kelamin']
                status_pernikahan.value = kar['status_pernikahan']
                pendidikan.value = kar['pendidikan']
                nama_sekolah.value = kar['nama_sekolah']
                kab_penugasan.value = kar['kab_penugasan']
                rekening.value = kar['rekening']
                ukuran_baju.value = kar['ukuran_baju']
                no_sdr.value = kar['no_sdr']
                hubungan.value = kar['hubungan']
                //menampilkan modal
                showModalKarirDetail.value = true
            }

            //method tambah riwayat organisasi
            const addKarir = (kar) => {
                nama.value = kar['nama_karyawan']
                idnya.value = kar['id']
                showModalKarir.value = true
            }

            //method tambah catatan pelanggaran
            const addPelanggaran = (kar) => {
                nama.value = kar['nama_karyawan']
                idnya.value = kar['id']
                console.log(kar['nama_karyawan'])
                showModalPelanggaran.value = true
            }

            //method "storeKarir"
            const storeKarir = () => {
                Inertia.post('/apps/karyawan/storeKarir', {
                    //data
                    karyawan_id : idnya.value,
                    divisi_id :divisi_id.value.id,
                    tgl_gabung_grup : tgl_gabung_grup.value,
                    tgl_masuk : tgl_masuk.value,
                    tgl_berakhir : tgl_berakhir.value
                }, {
                    onSuccess: () => {
                        //show success alert
                        Swal.fire({
                            title: 'Success!',
                            text: 'Karir saved successfully.',
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 2000
                        });
                        tutupModal()
                    },
                });
            }

            //method "storePelanggaran"
            const storePelanggaran = () => {
                Inertia.post('/apps/karyawan/storePelanggaran', {
                    //data
                    karyawan_id : idnya.value,
                    catatan : catatan.value,
                    tingkatan: tingkatan.value.value,
                    tanggal : tanggal.value,
                }, {
                    onSuccess: () => {
                        //show success alert
                        Swal.fire({
                            title: 'Success!',
                            text: 'Violation saved successfully.',
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 2000
                        });
                        tutupModalPelanggaran()
                    },
                });
            }

            //return
            return {
                search, handleSearch, destroy,
                detail, showModalKarir, tutupModalDetail, showModalKarirDetail, addKarir, tutupModal,
                nama_karyawan, nik_karyawan, status_kerja, divisi_id, umur, akhir_kontrak,
                pt_id, foto, tanggal_masuk, tanggal_kontrak, no_kk, nik_penduduk, grade,
                jabatan, no_hp, no_wa, no_bpjs_kesehatan, no_bpjs_ketenagakerjaan, gol_darah,
                email, tempat_lahir, tanggal_lahir, alamat_ktp, alamat_domisili,
                jenis_kelamin, status_pernikahan, pendidikan, nama_sekolah, kab_penugasan,
                rekening, ukuran_baju, no_sdr, hubungan, jabatan_id,
                idnya, nama, tgl_gabung_grup, tgl_masuk, tgl_berakhir, storeKarir,
                showModalPelanggaran, addPelanggaran, tutupModalPelanggaran, storePelanggaran, catatan, tanggal, tingkatan, daftar_tingkatan,
                showModalImport, tutupModalImport, importExcel, selectedTaskFile, file_excel, storeExcel
            }

        }
    }
</script>

<style>

</style>
