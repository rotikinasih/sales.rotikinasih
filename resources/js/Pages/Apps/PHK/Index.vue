<template>
    <Head>
        <title>Karyawan PHK</title>
    </Head>
    <main>
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <button @click="buatBaruKategori" class="btn theme-bg4 text-white f-12 float-right" style="cursor:pointer; border:none; margin-right: 0px;"><i class="fa fa-plus"></i>Tambah</button>
                    <h5>Daftar PHK</h5>
                    <!-- <span class="d-block m-t-5">Page to manage the <code> company </code> data</span>  -->
                </div>
                <div class="card-block table-border-style">
                    <div class="table-responsive">

                        <div class="input-group mb-3">
                            <input type="text" class="form-control" v-model="search" placeholder="Cari berdasarkan Nama Karyawan..." @keyup="handleSearch">
                            <button class="btn btn theme-bg5 text-white f-12" style="margin-left: 10px;" @click="handleSearch"> <i style="margin-left: 10px" class="fa fa-search me-2"></i></button>
                        </div>
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nama Karyawan</th>
                                    <th>Penyebab PHK</th>
                                    <th>Tabggal PHK</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(phk, index) in karyawan_phk.data" :key="index">
                                    <td>{{ index + 1 }}</td>
                                    <td>{{ phk.karyawan.nama_lengkap }}</td>
                                    <td v-if="(phk.penyebab_phk == 1)">Affair</td>
                                    <td v-if="(phk.penyebab_phk == 2)">Fraud</td>
                                    <td>{{ phk.tanggal_phk }}</td>
                                    <td>
                                        <a @click="editData(phk)" v-if="hasAnyPermission(['karyawan_phk.edit'])"  class="label theme-bg3 text-white f-12" style="cursor:pointer; border-radius:10px"><i class="fa fa-pencil-alt"></i> Edit</a>
                                    </td>
                                </tr>
                                <!-- jika data kosong -->
                                <tr v-if="karyawan_phk.data[0] == undefined">
                                    <td colspan="4" class="text-center">
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
                        <label class="col-form-label">Nama Karyawan :</label>
                        <VueMultiselect
                            v-model="karyawan_id"
                            :options="karyawan"
                            label="nama_lengkap"
                            track-by="id"
                            :allow-empty="false"
                            deselect-label="Can't remove this value"
                            placeholder="Pilih Karyawan"
                        ></VueMultiselect>
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
						<!-- <button type="button" v-show="updateSubmit" @click="updateData()" class="btn btn-warning text-white m-2">Update</button> -->
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
        },
        //composition API
    

        setup(){
            const showModal = ref(false);
            const updateSubmit = ref(false);
            const judul = ref(null);
            const id = ref(null);
            const nama_lengkap = ref();
            const karyawan_id = ref();
            const penyebab_phk = ref();
            const tanggal_phk = ref();
            //define state search
            const search = ref('' || (new URL(document.location)).searchParams.get('search'));
            //define method search

            const data_penyebab_phk = [
                { name: 'Affair', value: 1 },
                { name: 'Fraud', value: 2 }
            ];

            const handleSearch = () => {
                Inertia.get('/apps/phk', {
                    //send params "search" with value from state "search"
                    search: search.value,
                });
            }

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
                nama_lengkap.value = null
                penyebab_phk.value = null
                tanggal_phk.value = null
                tampilModal()
            }
            //method edit show modal
            const editData = (phk) => {
                if (updateSubmit.value == false) {
                    updateSubmit.value = !updateSubmit.value
                }
                judul.value = 'Edit Entitas'
                id.value = phk.id
                nama_lengkap.value = phk.nama_lengkap
                penyebab_phk.value = phk.penyebab_phk
                tanggal_phk.value = phk.tanggal_phk
                tampilModal()
            }

            const peringatan = () => {
                Swal.fire({
                    title: 'Harap lengkapi semua entri',
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
                if(nama_lengkap.value == null || tanggal_phk.value == null){
                    if(nama_lengkap.value == null && tanggal_phk.value == null){
                        tutupModal();
                        peringatan();
                    }
                    tutupModal();
                    peringatan();
                }else{
                    //send data to server
                    Inertia.put(`/apps/phk/${id.value}`, {
                        //data
                        nama_lengkap: nama_lengkap.value,
                        tanggal_phk: tanggal_phk.value,
                        penyabab_phk: parseInt(penyabab_phk.value)
                    }, {
                        onSuccess: () => {
                            tutupModal()
                            //show success alert
                            Swal.fire({
                                title: 'Sukses!',
                                text: 'Entitas berhasil disimpan.',
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
                if(nama_lengkap.value == null || tanggal_phk.value == null){
                    if(nama_lengkap.value == null && tanggal_phk.value == null){
                        tutupModal();
                        peringatan();
                    }
                    tutupModal();
                    peringatan();
                }
                else{
                    //send data to server
                    Inertia.post('/apps/phk', {
                        //data
                        nama_lengkap: nama_lengkap.value,
                        penyebab_phk: penyebab_phk.value,
                        tanggal_phk: tanggal_phk.value,
                        
                    }, {
                        onSuccess: () => {
                            tutupModal()
                            //show success alert
                            Swal.fire({
                                title: 'Success!',
                                text: 'PT saved successfully.',
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
                nama_lengkap, id, data_penyebab_phk, karyawan_id, penyebab_phk, tanggal_phk,
                tutupModal, buatBaruKategori, updateData,
                storeData, peringatan
            }
        }
    }
</script>
