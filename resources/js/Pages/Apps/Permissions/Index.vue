<template>
    <Head>
        <title>Permissions</title>
    </Head>
    <main>
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <button @click="buatBaruKategori" class="btn theme-bg4 text-white f-12 float-right" style="cursor:pointer; border:none; margin-right: 0px;"><i class="fa fa-plus"></i>Tambah</button>
                    <h5>Permissions</h5>
                    <!-- <span class="d-block m-t-5">Page to manage the <code> permission </code> data</span> -->
                </div>
                <div class="card-block table-border-style">
                    <div class="table-responsive">

                        <div class="input-group mb-3">
                            <input type="text" class="form-control" v-model="search" placeholder="Cari berdasarkan name permission..." @keyup="handleSearch">
                            <button class="btn btn theme-bg5 text-white f-12" style="margin-left: 10px;" @click="handleSearch"><i style="margin-left: 10px" class="fa fa-search me-2"></i></button>
                        </div>
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nama Permissions</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(pe, index) in permissions.data" :key="pe.id">
                                    <td>{{ index + 1 }}</td>
                                    <td>{{ pe.name }}</td>
                                    <td>
                                        <a @click="editData(pe)" v-if="hasAnyPermission(['permissions.edit'])"  class="label theme-bg3 text-white f-12" style="cursor:pointer; border-radius:10px"><i class="fa fa-pencil-alt"></i> Edit</a>
                                    </td>
                                </tr>
                                <!-- jika data kosong -->
                                <tr v-if="permissions.data[0] == undefined">
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
                                <label v-if="permissions.data[0] != undefined" align="start">Showing {{ permissions.from }} to {{ permissions.to }} of {{ permissions.total }} items</label>
                            </div>
                            <div class="col-md-8">
                                <Pagination v-if="permissions.data[0] != undefined" :links="permissions.links" align="end"/>
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
                        <label class="col-form-label">Nama Permission :</label>
                        <input type="text" class="form-control" placeholder="Masukkan Nama Permission" v-model="name" required>
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

    export default {
        //layout
        layout: LayoutApp,

        //register component
        components: {
            Head,
            Link,
            Pagination,
            Modal,
            Swal,
        },

        //props
        props: {
            permissions: Object,
        },

        setup() {

            const showModal = ref(false);
            const updateSubmit = ref(false);
            const judul = ref(null);
            const id = ref(null);
            const name = ref();
            
            //define state search
            const search = ref('' || (new URL(document.location)).searchParams.get('search'));

            //define method search
            const handleSearch = () => {
                Inertia.get('/apps/permissions', {
                    //send params "search" with value from state "search"
                    search: search.value,
                });
            }

             //membuat kategori
            const buatBaruKategori = () =>{
                if(updateSubmit.value == true){
                    updateSubmit.value = !updateSubmit.value
                }
                judul.value = 'Tambah Permissions'
                id.value = null
                name.value = null
                tampilModal()
            }

            //tampil modal
            const tampilModal = () => {
                showModal.value = true
            }

            //tutup modal
            const tutupModal = () => {
                showModal.value = false
            }

            //method edit show modal
            const editData = (pe) => {
                // console.log(pe.name);
                
                if (updateSubmit.value == false) {
                    updateSubmit.value = !updateSubmit.value
                }
                
                judul.value = 'Edit Permissions'
                id.value = pe.id
                name.value = pe.name
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
                if(name.value == null){
                    tutupModal();
                    peringatan();
                }else{
                    //send data to server
                    Inertia.put(`/apps/permissions/${id.value}`, {
                        //data
                        name: name.value
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
                if(name.value == null){
                    tutupModal();
                    peringatan();
                }
                else{
                    //send data to server
                    Inertia.post('/apps/permissions', {
                        //data
                        name: name.value,
                        
                    }, {
                        onSuccess: () => {
                            tutupModal()
                            //show success alert
                            Swal.fire({
                                title: 'Success!',
                                text: 'Data berhasil ditambah.',
                                icon: 'success',
                                showConfirmButton: false,
                                timer: 2000
                            });
                        },
                    });
                }
            }


            //return
            return {
                search,
                handleSearch,
                showModal,
                editData,
                judul,
                updateSubmit,
                name,
                tutupModal, buatBaruKategori, updateData,
                storeData, peringatan
            }

        }
    }
</script>

<style>

</style>
