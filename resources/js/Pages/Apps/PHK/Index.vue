<template>
    <Head>
        <title>Karyawan PHK</title>
    </Head>
    <main>
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <button @click="buatBaruKategori" class="btn theme-bg4 text-white f-12 float-right" style="cursor:pointer; border:none; margin-right: 0px;" v-if="hasAnyPermission(['apps.phk.create'])"><i class="fa fa-plus"></i>Tambah</button>
                    <button class="btn btn-success dropdown-toggle float-right" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="cursor:pointer; border:none;"><i class="fa fa-file-excel"></i> Excel</button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <a  :href="`/apps/phk/export`" target="_blank" class="dropdown-item">Export</a>
                        <!-- <button @click="importExcel" target="_blank" class="dropdown-item">Import</button> -->
                    </div>
                    <h5>Daftar PHK</h5>
                    <!-- <span class="d-block m-t-5">Page to manage the <code> company </code> data</span>  -->
                </div>
                <div class="card-block table-border-style">
                    <div class="table-responsive">

                        <div class="input-group mb-3">
                            <input type="text" class="form-control" v-model="search" placeholder="Cari berdasarkan Nama Karyawan..." @keyup="handleSearch">
                            <button class="btn btn theme-bg5 text-white f-12" style="margin-left: 10px;" @click="handleSearch"> <i style="margin-left: 10px" class="fa fa-search me-2"></i></button>
                        </div>
                        <table class="table table-bordered table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center">Nama Karyawan</th>
                                    <th class="text-center">NIK (Karyawan)</th>
                                    <!-- <th class="text-center">Entitas</th>
                                    <th class="text-center">Divisi</th> -->
                                    <th class="text-center">Penyebab PHK</th>
                                    <th class="text-center">Tanggal PHK</th>
                                    <th class="text-center" v-if="hasAnyPermission(['apps.phk.edit'])">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(phk, index) in karyawan_phk.data" :key="index">
                                    <td class="text-center">{{ index + karyawan_phk.from }}</td>
                                    <td>{{ phk.karyawan.nama_lengkap }}</td>
                                    <td>{{ phk.karyawan.nik_karyawan }}</td>
                                    <!-- <td v-if="(phk.karyawan.pt_id == null)"></td>
                                    <td v-if="(phk.karyawan.pt_id)">{{ phk.karyawan.perusahaan.nama_pt }}</td>
                                    <td v-if="(phk.karyawan.divisi_id == null)"></td>
                                    <td v-if="(phk.karyawan.divisi_id)">{{ phk.karyawan.divisi.nama_divisi }}</td> -->
                                    <td v-if="(phk.penyebab_phk == 1)">Affair</td>
                                    <td v-if="(phk.penyebab_phk == 2)">Fraud</td>
                                    <td>{{ phk.tanggal_phk }}</td>
                                    <td class="text-center" v-if="hasAnyPermission(['apps.phk.edit'])">
                                        <a @click="editData(phk)" v-if="hasAnyPermission(['apps.phk.edit'])"  class="label theme-bg3 text-white f-12" style="cursor:pointer; border-radius:10px"><i class="fa fa-pencil-alt"></i> Edit</a>
                                    </td>
                                </tr>
                                <!-- jika data kosong -->
                                <tr v-if="karyawan_phk.data[0] == undefined">
                                    <td colspan="8" class="text-center">
                                        <br>
                                        <i class="fa fa-file-excel fa-5x"></i><br><br>
                                            Data Kosong
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="row" style="max-width:100%; overflow-x:hidden">
                            <div class="col-md-4">
                                <label v-if="karyawan_phk.data[0] != undefined" align="start">Showing {{ karyawan_phk.from }} to {{ karyawan_phk.to }} of {{ karyawan_phk.total }} items</label>
                            </div>
                            <div class="col-md-8">
                                <Pagination v-if="karyawan_phk.data[0] != undefined" :links="karyawan_phk.links" align="end"/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- untuk modal -->
        <Teleport to="body">
            <modal :show="showModal" @close="showModal = false">
                <template #header>
                    <h5 class="modal-title">{{ judul }}</h5>
                </template>
                <template #body>
                    <div class="form-group mb-3">
                        <label class="col-form-label" >Nama Karyawan :</label>
                        <VueMultiselect
                            v-show="!updateSubmit"
                            v-model="karyawan_id"
                            :options="karyawan"
                            label="nama_lengkap"
                            track-by="id"
                            :allow-empty="false"
                            deselect-label="Can't remove this value"
                            placeholder="Pilih Karyawan"
                        ></VueMultiselect>
                        <VueMultiselect
                            v-show="updateSubmit"
                            v-model="karyawan_id_edit"
                            :options="karyawan_edit"
                            label="nama_lengkap"
                            track-by="id"
                            :allow-empty="false"
                            deselect-label="Can't remove this value"
                            placeholder="Pilih Karyawan" 
                            :disabled="isDisabled"
                        ></VueMultiselect>
                        <!-- <input type="text" v-show="updateSubmit" class="form-control" v-model="karyawan_id" readonly> -->
                    </div>
                    <div class="form-group mb-3">
                        <label class="col-form-label">Penyebab PHK :</label>
                        <VueMultiselect
                            v-model="penyebab_phk"
                            :options="data_penyebab_phk"
                            label="name"
                            track-by="value"
                            :allow-empty="false"
                            deselect-label="Can't remove this value"
                            placeholder="Pilih Penyebab PHK"
                        ></VueMultiselect>
                    </div>
                    <div class="form-group mb-3">
                        <label class="col-form-label">Tanggal PHK :</label>
                        <input type="date" class="form-control" placeholder="Masukkan Tanggal THK" v-model="tanggal_phk" required>
                    </div>
                </template>
                <template #footer>
					<form @submit.prevent="storeData">
						<button type="submit" v-show="!updateSubmit" class="btn btn-success text-white m-2">Simpan</button>
						<button type="button" v-show="updateSubmit" @click="updateData()" class="btn btn-warning text-white m-2">Update</button>
					</form>
                    <button
                        class="btn btn-secondary m-2" @click="tutupModal">Keluar</button>
                </template>
            </modal>
        </Teleport>
    </main>
</template>

<script>
    //import layout
    import LayoutApp from '../../../Layouts/App.vue';
    //import Head and useForm from Inertia
    import { Head, Link } from '@inertiajs/inertia-vue3';
    //import pagination
    import Pagination from '../../../Components/Pagination.vue';
    //import ref
    import { ref } from 'vue';
    //imprt inertia
    import { Inertia } from '@inertiajs/inertia';
    //import modal
    import Modal from '../../../Components/Modal.vue';
    import Swal from 'sweetalert2';
    import VueMultiselect from 'vue-multiselect';
    import 'vue-multiselect/dist/vue-multiselect.css';


    export default {
        //layout
        layout : LayoutApp,
        //register component
        components:{
            Head,
            Link,
            Pagination,
            Modal,
            Swal,
            VueMultiselect,
        },
        //props
        props:{
            karyawan_phk: Object,
            karyawan: Array,
            karyawan_edit: Array,
        },
        //composition API
    

        setup(props){
            const showModal = ref(false);
            const updateSubmit = ref(false);
            const judul = ref(null);
            const id = ref(null);
            const nama_lengkap = ref();
            const karyawan_id = ref();
            const karyawan_id_edit = ref();
            const penyebab_phk = ref();
            const tanggal_phk = ref();
            //define state search
            const search = ref('' || (new URL(document.location)).searchParams.get('search'));
            //define method search
            const handleSearch = () => {
                Inertia.get('/apps/phk', {
                    //send params "search" with value from state "search"
                    search: search.value,
                });
            }
            
            const data_penyebab_phk = [
                { name: 'Affair', value: 1 },
                { name: 'Fraud', value: 2 }
            ];

            //tampil modal
            const tampilModal = () => {
                showModal.value = true
            }

            //tutup modal
            const tutupModal = () => {
                showModal.value = false
            }
            
            //membuat kategori
            const buatBaruKategori = () =>{
                if(updateSubmit.value == true){
                    updateSubmit.value = !updateSubmit.value
                }
                judul.value = 'Tambah PHK'
                id.value = null
                karyawan_id.value = null
                penyebab_phk.value = null
                tanggal_phk.value = null
                tampilModal()
            }

            //method edit show modal
            const editData = (phk) => {
                console.log(phk.karyawan_id);
                
                if (updateSubmit.value == false) {
                    updateSubmit.value = !updateSubmit.value
                }
                //penyebab_phk
                data_penyebab_phk.forEach(function (data) {
                    if(data.value == phk.penyebab_phk){
                        penyebab_phk.value = data
                    }
                })

                //divisi
                let data_karyawan = props.karyawan_edit
                data_karyawan.forEach(data => {
                    if(phk.karyawan_id == data.id){
                        karyawan_id_edit.value = data
                        // form.divisi_id = data
                    }
                })
                judul.value = 'Edit PHK'
                id.value = phk.id
                // karyawan_id.value = data_karyawan.phk.karyawan_id
                tanggal_phk.value = phk.tanggal_phk
                tampilModal()
            }

            const peringatan = () => {
                Swal.fire({
                    title: 'Mohon lengkapi isian!!',
                    width: 600,
                    padding: '3em',
                    color: '#716add',
                    backdrop: `
                        rgba(0,0,123,0.4)
                        left top
                        no-repeat
                    `
                })
            }

            //method update data
            const updateData = () => {
                if(karyawan_id.value == null || penyebab_phk.value == null || tanggal_phk.value == null){
                    tutupModal();
                    peringatan();
                }else{
                    //send data to server
                    Inertia.put(`/apps/phk/${id.value}`, {
                        //data
                        karyawan_id: karyawan_id.value.id,
                        tanggal_phk: tanggal_phk.value.value,
                        penyabab_phk: penyabab_phk.value
                    }, {
                        onSuccess: () => {
                            tutupModal()
                            //show success alert
                            Swal.fire({
                                title: 'Sukses!',
                                text: 'Data berhasil diedit.',
                                icon: 'success',
                                showConfirmButton: false,
                                timer: 2000
                            });
                        },
                    });
                }
            }

            //method "storeData"
            const storeData = () => {
                if(karyawan_id.value == null || penyebab_phk.value == null || tanggal_phk.value == null){
                    tutupModal();
                    peringatan();
                }
                else{
                    //send data to server
                    Inertia.post('/apps/phk', {
                        //data
                        karyawan_id: karyawan_id.value.id,
                        penyebab_phk: penyebab_phk.value.value,
                        tanggal_phk: tanggal_phk.value,
                        
                    }, {
                        onSuccess: () => {
                            tutupModal()
                            //show success alert
                            Swal.fire({
                                title: 'Success!',
                                text: 'Data behasil ditambah.',
                                icon: 'success',
                                showConfirmButton: false,
                                timer: 2000
                            });
                        },
                    });
                }
            }

            return {
                search,
                handleSearch,
                showModal,
                editData,
                judul,
                updateSubmit,
                nama_lengkap, id, data_penyebab_phk, karyawan_id, karyawan_id_edit, penyebab_phk, tanggal_phk,
                tutupModal, buatBaruKategori, updateData,
                storeData, peringatan, isDisabled: true,
            }
        }
    }
</script>