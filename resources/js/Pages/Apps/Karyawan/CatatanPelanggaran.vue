<template>
    <Head>
        <title>Violations</title>
    </Head>
    <main>
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <h5>{{ nama }}</h5>
                    <span class="d-block m-t-5">Page to manage the list of <code>{{ nama }}</code> violations</span>
                </div>
                <div class="card-block table-border-style">
                    <div class="table-responsive">
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" v-model="search" placeholder="search by notes..." @keyup="handleSearch">
                            <button class="btn btn theme-bg5 text-white f-12" @click="handleSearch"> <i class="fa fa-search me-2"></i></button>
                        </div>
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Full Name</th>
                                    <th scope="col">Level</th>
                                    <th scope="col">Notes</th>
                                    <th scope="col">Date</th>
                                    <th scope="col" style="width:20%">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(list, index) in lists.data" :key="index">
                                    <td>{{ index + 1 }}</td>
                                    <td>{{ list.karyawan.nama_karyawan }}</td>
                                    <td v-if="(list.tingkatan == 0)"><a class="label theme-bg8 text-white f-12" style="border-radius:10px">Misdemeanor</a></td>
                                    <td v-else-if="(list.tingkatan == 1)"><a class="label theme-bg7 text-white f-12" style="border-radius:10px">Moderate Offence</a></td>
                                    <td v-else><a class="label theme-bg6 text-white f-12" style="border-radius:10px">Serious Offence</a></td>
                                    <td>{{ list.catatan }}</td>
                                    <td>{{ list.tanggal }}</td>
                                    <td class="text-center">
                                        <a @click="editData(list)" class="label theme-bg3 text-white f-12" style="cursor:pointer"><i class="fa fa-pencil-alt"></i> Edit</a>
                                    </td>
                                </tr>
                                <!-- jika data kosong -->
                                <tr v-if="lists.data[0] == undefined">
                                    <td colspan="7" class="text-center">
                                        <br>
                                        <i class="fa fa-file-excel fa-5x"></i><br><br>
                                            No Data To Display
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="row mb-3" style="max-width:100%; overflow-x:hidden">
                            <div class="col-md-4">
                                <label v-if="lists.data[0] != undefined" align="start">Showing {{ lists.from }} to {{ lists.to }} of {{ lists.total }} items</label>
                            </div>
                            <div class="col-md-8">
                                <Pagination v-if="lists.data[0] != undefined" :links="lists.links" align="end"/>
                            </div>

                        </div>
                        <Link href="/apps/karyawan" class="btn btn-success shadow-sm" style="float:right">BACK</Link>
                    </div>
                </div>
            </div>
        </div>
        <!-- untuk modal untuk edit data -->
        <Teleport to="body">
            <modal :show="showModal" @close="showModal = false">
                <template #header>
                    <h5 class="modal-title">Edit Data</h5>
                </template>
                <template #body>
                    <div class="form-group mb-3">
                        <label class="col-form-label">Full Name :</label>
                        <input type="text" class="form-control" :value="nama" readonly>
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
					<form>
						<button type="button" @click="updateData()" class="btn btn-success text-white m-2">Update</button>
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
    import Modal from '../../../Components/Modal.vue';
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
            Modal,
            VueMultiselect
        },

        //props
        props: {
            lists: Object,
            nama: String,
            id_karyawan: String,
        },

        setup(props) {
            //define state search
            const search = ref('' || (new URL(document.location)).searchParams.get('search'));
            const showModal = ref(false);
            const catatan = ref();
            const tingkatan = ref();
            const tanggal = ref();
            const id_list = ref();

            const daftar_tingkatan = [
                { name: 'Misdemeanor', value: 0 },
                { name: 'Moderate Offence', value: 1 },
                { name: 'Serious Offence', value: 2 },
            ];

            //define method search
            const handleSearch = () => {
                Inertia.get(`/apps/karyawan/${props.id_karyawan}/list-pelanggaran`, {
                    search: search.value,
                });
            }

            //edit data
            const editData = (list) => {
                //tingkatan
                daftar_tingkatan.forEach(function (data) {
                    if(data.value == list.tingkatan){
                        tingkatan.value = data
                    }
                })
                catatan.value = list.catatan
                tanggal.value = list.tanggal
                id_list.value = list.id
                showModal.value = true
            }

            //tutup modal
            const tutupModal = () => {
                showModal.value = false
            }

            //update data
            const updateData = () => {
                Inertia.put(`/apps/karyawan/${id_list.value}/list-pelanggaran`, {
                    id : id_list.value,
                    karyawan_id : parseInt(props.id_karyawan),
                    catatan : catatan.value,
                    tingkatan: tingkatan.value.value,
                    tanggal: tanggal.value,
                },{
                    onSuccess: () => {
                        tutupModal()
                        //show success alert
                        Swal.fire({
                            title: 'Success!',
                            text: 'Violations updated successfully.',
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 2000
                        });
                    },
                });

            }

            //return
            return {
                search,
                handleSearch,
                editData, showModal, tutupModal, catatan, tingkatan, tanggal, daftar_tingkatan,
                updateData
            }

        }
    }
</script>

<style>

</style>
