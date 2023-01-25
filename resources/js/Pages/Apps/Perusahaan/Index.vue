<template>
    <Head>
        <title>PT</title>
    </Head>
    <main>
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <h5>PT</h5>
                    <span class="d-block m-t-5">Page to manage the <code> company </code> data</span>
                </div>
                <div class="card-block table-border-style">
                    <div class="table-responsive">

                        <div class="input-group mb-3">
                            <button @click="buatBaruKategori" class="btn theme-bg4 text-white f-12" style="cursor:pointer; border:none"><i class="fa fa-plus"></i> Add</button>
                            <input type="text" class="form-control" v-model="search" placeholder="search by PT name..." @keyup="handleSearch">
                            <button class="btn btn theme-bg5 text-white f-12" @click="handleSearch"> <i class="fa fa-search me-2"></i></button>
                        </div>
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>PT Name</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(pe, index) in perusahaan.data" :key="index">
                                    <td>{{ index + 1 }}</td>
                                    <td>{{ pe.nama_pt }}</td>
                                    <td v-if="(pe.status == 0)"><a class="label theme-bg6 text-white f-12" style="border-radius:10px">Inactive</a></td>
                                    <td v-else><a class="label theme-bg8 text-white f-12" style="border-radius:10px">Active</a></td>
                                    <td>
                                        <a @click="editData(pe)" class="label theme-bg3 text-white f-12" style="cursor:pointer"><i class="fa fa-pencil-alt"></i> Edit</a>
                                    </td>
                                </tr>
                                <!-- jika data kosong -->
                                <tr v-if="perusahaan.data[0] == undefined">
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
                                <label v-if="perusahaan.data[0] != undefined" align="start">Showing {{ perusahaan.from }} to {{ perusahaan.to }} of {{ perusahaan.total }} items</label>
                            </div>
                            <div class="col-md-8">
                                <Pagination v-if="perusahaan.data[0] != undefined" :links="perusahaan.links" align="end"/>
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
                        <label class="col-form-label">PT Name :</label>
                        <input type="text" class="form-control" placeholder="PT Name" v-model="nama_pt" required>
                    </div>
                    <div class="form-group mb-3">
                        <label class="col-form-label">Status :</label>
                        <select class="form-control" aria-label="Default select example" v-model="status" required>
                            <option :value="null">Select Status</option>
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                </template>
                <template #footer>
					<form @submit.prevent="storeData">
						<button type="submit" v-show="!updateSubmit" class="btn btn-success text-white m-2">Save</button>
						<button type="button" v-show="updateSubmit" @click="updateData()" class="btn btn-success text-white m-2">Update</button>
					</form>
                    <button
                        class="btn btn-secondary m-2" @click="tutupModal">Close</button>
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
        layout : LayoutApp,
        //register component
        components:{
            Head,
            Link,
            Pagination,
            Modal,
            Swal
        },
        //props
        props:{
            perusahaan: Object
        },
        //composition API
        setup(props){
            const showModal = ref(false);
            const updateSubmit = ref(false);
            const judul = ref(null);
            const id = ref(null);
            const nama_pt = ref(null)
            const status = ref(null);
            //define state search
            const search = ref('' || (new URL(document.location)).searchParams.get('search'));
            //define method search
            const handleSearch = () => {
                Inertia.get('/apps/perusahaan', {
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
                judul.value = 'Create PT'
                id.value = null
                nama_pt.value = null
                status.value = null
                tampilModal()
            }
            //method edit show modal
            const editData = (pe) => {
                if (updateSubmit.value == false) {
                    updateSubmit.value = !updateSubmit.value
                }
                judul.value = 'Edit PT'
                id.value = pe.id
                nama_pt.value = pe.nama_pt
                status.value = pe.status
                tampilModal()
            }

            //method update data
            const updateData = () => {
                if(nama_pt.value == null || status.value == null){
                    if(nama_pt.value == null && status.value == null){

                        tutupModal();
                        Swal.fire({
                            title: 'Please complete all entries',
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
                    tutupModal();
                    Swal.fire({
                        title: 'Please complete all entries',
                        width: 600,
                        padding: '3em',
                        color: '#716add',
                        backdrop: `
                            rgba(0,0,123,0.4)
                            left top
                            no-repeat
                        `
                    })
                }else{
                    //send data to server
                    Inertia.put(`/apps/perusahaan/${id.value}`, {
                        //data
                        nama_pt: nama_pt.value,
                        status: parseInt(status.value)
                    }, {
                        onSuccess: () => {
                            tutupModal()
                            //show success alert
                            Swal.fire({
                                title: 'Success!',
                                text: 'PT updated successfully.',
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
                if(nama_pt.value == null || status.value == null){
                    tutupModal();
                    Swal.fire({
                        title: 'Please complete all entries',
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
                else{

                    console.log(status.value)
                    //send data to server
                    Inertia.post('/apps/perusahaan', {
                        //data
                        nama_pt: nama_pt.value,
                        status: status.value,
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
                nama_pt, status, id,
                tutupModal, buatBaruKategori, updateData,
                storeData
            }
        }
    }
</script>
