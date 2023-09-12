<template>
    <Head>
        <title>Mutasi</title>
    </Head>
    <main>
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <h5>Daftar Mutasi</h5>
                    <!-- <span class="d-block m-t-5">Page to manage the list of <code>{{ nama }}</code> organizations</span> -->
                    <button class="btn btn-success dropdown-toggle float-right" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="cursor:pointer; border:none; margin-right: 0px;">
                        <i class="fa fa-file-excel"></i> Excel
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <a  :href="`/apps/list-organisasi/export`" target="_blank" class="dropdown-item">Export</a>
                        <!-- <button @click="importExcel" target="_blank" class="dropdown-item">Import</button> -->
                    </div>
                </div>
                <div class="card-block table-border-style">
                    <div class="table-responsive">
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" v-model="search" placeholder="Cari berdasarkan Nama Lengkap..." @keyup="handleSearch">
                            <button class="btn btn theme-bg5 text-white f-12" style="margin-left: 10px;" @click="handleSearch"><i style="margin-left: 10px" class="fa fa-search me-2"></i></button>                        
                        </div>
                        <table class="table table-bordered table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <!-- <th scope="col">#</th> -->
                                    <th scope="col" class="text-center">Nama Lengkap</th>
                                    <th scope="col" class="text-center">Entitas</th>
                                    <th scope="col" class="text-center">Divisi</th>
                                    <th scope="col" class="text-center">Posisi</th>
                                    <!-- <th scope="col">Tanggal Gabung Group</th> -->
                                    <th scope="col" class="text-center">Tanggal Masuk</th>
                                    <th scope="col" class="text-center">Tanggal Berakhir</th>
                                    <!-- <th scope="col" style="text-align: center">Aksi</th> -->
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(list, index) in lists.data" :key="index">
                                    <!-- <td>{{ index + 1 }}</td> -->
                                    <td>{{ list.karyawan.nama_lengkap }}</td>
                                    <td>{{ list.perusahaan.nama_pt}}</td>
                                    <td>{{ list.divisi.nama_divisi}}</td>
                                    <td v-if="list.jabatan_id == null"></td>
                                    <td v-else>{{ list.jabatan.nama_jabatan }}</td>
                                    <!-- <td>{{ list.tgl_gabung_grup }}</td> -->
                                    <td>{{ list.tgl_masuk }}</td>
                                    <td>{{ list.tgl_berakhir }}</td>
                                    <!-- <td class="text-center">
                                        <a @click="editData(list)" class="label theme-bg3 text-white f-12" style="cursor:pointer; border-radius:10px"><i class="fa fa-pencil-alt"></i> Edit</a>
                                    </td> -->
                                </tr>
                                <!-- jika data kosong -->
                                <tr v-if="lists.data[0] == undefined">
                                    <td colspan="7" class="text-center">
                                        <br>
                                        <i class="fa fa-file-excel fa-5x"></i><br><br>
                                            Data Kosong
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
                        <!-- <Link href="/apps/karyawan" class="btn btn-success shadow-sm" style="float:right">BACK</Link> -->
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
                        <label class="col-form-label">Nama Lengkap :</label>
                        <input type="text" class="form-control" :value="id_list" readonly>
                    </div>
                    <div class="row">
                        <!-- <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="col-form-label">Tanggal Bergabung Group : </label>
                                <input type="date" class="form-control" placeholder="Group Joining Date" v-model="tgl_gabung_grup" required>
                            </div>
                        </div> -->
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="col-form-label">Divisi :</label>
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
                                <label class="col-form-label">Tanggal Masuk : </label>
                                <input type="date" class="form-control" placeholder="Date of Entry" v-model="tgl_masuk" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="col-form-label">Tanggal Berakhir : </label>
                                <input type="date" class="form-control" placeholder="End Date" v-model="tgl_berakhir" required>
                            </div>
                        </div>
                    </div>
                </template>
                <template #footer>
					<form>
						<button type="button" @click="updateData()" class="btn btn-warning text-white m-2">Update</button>
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
            // id_karyawan: String,
            divisi: Array
        },

        setup(props) {
            //define state search
            const search = ref('' || (new URL(document.location)).searchParams.get('search'));
            const showModal = ref(false);
            const divisi_id = ref();
            const tgl_gabung_grup = ref();
            const tgl_masuk = ref();
            const tgl_berakhir = ref();
            const id_list = ref();

            //define method search
            const handleSearch = () => {
                Inertia.get(`/apps/list-organisasi`, {
                    search: search.value,
                });
            }

            //edit data
            const editData = (list) => {
                // console.log(list.id)
                let datadiv_nya = props.divisi
                datadiv_nya.forEach(datadiv => {
                    if(list.divisi_id == datadiv.id){
                        divisi_id.value = datadiv
                    }
                })
                tgl_gabung_grup.value = list.tgl_gabung_grup
                tgl_masuk.value = list.tgl_masuk
                tgl_berakhir.value = list.tgl_berakhir
                id_list.value = list.id
                showModal.value = true
            }

            //tutup modal
            const tutupModal = () => {
                showModal.value = false
            }

            //update data
            const updateData = () => {
                Inertia.put(`/apps/list-organisasi`, {
                    id : id_list.value,
                    karyawan_id : parseInt(props.id_karyawan),
                    divisi_id : divisi_id.value.id,
                    tgl_gabung_grup: tgl_gabung_grup.value,
                    tgl_masuk: tgl_masuk.value,
                    tgl_berakhir: tgl_berakhir.value
                },{
                    onSuccess: () => {
                        tutupModal()
                        //show success alert
                        Swal.fire({
                            title: 'Success!',
                            text: 'History Organizations updated successfully.',
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
                editData, showModal, tutupModal, divisi_id, tgl_gabung_grup, tgl_masuk, tgl_berakhir,
                updateData
            }

        }
    }
</script>

<style>

</style>
