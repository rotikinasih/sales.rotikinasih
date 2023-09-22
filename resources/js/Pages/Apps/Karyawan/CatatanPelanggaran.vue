<template>
    <Head>
        <title>Pelanggaran {{ nama }}</title>
    </Head>
    <main>
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <h5>Riwayat Pelanggaran {{ nama }}</h5>
                    <!-- <span class="d-block m-t-5">Page to manage the list of <code>{{ nama }}</code> violations</span> -->
                </div>
                <div class="card-block table-border-style">
                    <div class="table-responsive">
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" v-model="search" placeholder="Cari berdasarkan Catatan..." @keyup="handleSearch">
                            <button class="btn btn theme-bg5 text-white f-12" style="margin-left: 10px;" @click="handleSearch"><i style="margin-left: 10px" class="fa fa-search me-2"></i></button>                        
                        </div>
                        <table class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr>
                                    <th scope="col" class="text-center">#</th>
                                    <th scope="col" class="text-center">Nama Lengkap</th>
                                    <th scope="col" class="text-center">Catatan</th>
                                    <th scope="col" class="text-center">Tanggal</th>
                                    <th scope="col" class="text-center">Sanksi</th>
                                    <th scope="col" class="text-center">Status</th>
                                    <!-- <th scope="col" class="text-center">Aksi</th> -->
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(list, index) in lists.data" :key="index">
                                    <td class="text-center">{{ index + lists.from }}</td>
                                    <td>{{ list.karyawan.nama_lengkap }}</td>
                                    <td>{{ list.tanggal }}</td>
                                    <td>{{ list.catatan }}</td>
                                    <td class="text-center" v-if="(list.tingkatan == null)"> </td>
                                    <td class="text-center" v-if="(list.tingkatan == 1)"><a class="label theme-bg9 text-white f-12" style="border-radius:10px">Teguran Lisan</a></td>
                                    <td class="text-center" v-if="(list.tingkatan == 2)"><a class="label theme-bg9 text-white f-12" style="border-radius:10px">Teguran Tertulis</a></td>
                                    <td class="text-center" v-if="(list.tingkatan == 3)"><a class="label theme-bg10 text-white f-12" style="border-radius:10px">SP 1</a></td>
                                    <td class="text-center" v-if="(list.tingkatan == 4)"><a class="label theme-bg7 text-white f-12" style="border-radius:10px">SP 2</a></td>
                                    <td class="text-center" v-if="(list.tingkatan == 4)"><a class="label theme-bg6 text-white f-12" style="border-radius:10px">SP 3</a></td>
                                    <td class="text-center" v-if="(list.status == null)"> </td>
                                    <td class="text-center" v-if="(list.status == 1)"><b style="color: rgb(240, 167, 9);">Diproses</b></td>
                                    <td class="text-center" v-if="(list.status == 2)"><b style="color: rgb(9, 240, 28);">Selesai</b></td>
                                    <!-- <td class="text-center">
                                        <a @click="editData(list)" class="label theme-bg3 text-white f-12" style="cursor:pointer; border-radius:10px"><i class="fa fa-pencil-alt"></i> </a>
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
                        <div class="row mb-3" style="overflow-x:hidden">
                            <div class="col-md-4">
                                <label v-if="lists.data[0] != undefined" align="start">Showing {{ lists.from }} to {{ lists.to }} of {{ lists.total }} items</label>
                            </div>
                            <div class="col-md-8" style="float: right;">
                                <Pagination v-if="lists.data[0] != undefined" :links="lists.links" align="end"/>
                            </div>
                        </div>
                        <Link href="/apps/karyawan" class="btn btn-secondary shadow-sm" style="float: right; margin-right: 2px">Kembali</Link>
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
                        <input type="text" class="form-control" :value="nama" readonly>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="col-form-label">Tanggal : </label>
                                <input type="date" class="form-control" placeholder="Date" v-model="tanggal" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="col-form-label">Tingkat Pelanggaran : </label>
                                <VueMultiselect
                                    v-model="tingkatan"
                                    :options="daftar_tingkatan"
                                    label="name"
                                    track-by="value"
                                    :allow-empty="false"
                                    deselect-label="Can't remove this value"
                                    placeholder="Pilih Tingkat Pelanggaran"
                                ></VueMultiselect>
                            </div>
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label class="col-form-label">Catatan : </label>
                        <textarea type="text" class="form-control" placeholder="Notes" v-model="catatan" required></textarea>
                    </div>
                    <div class="form-group mb-3">
                        <label class="col-form-label">Status Tindakan :</label>
                        <VueMultiselect
                                    v-model="status"
                                    :options="daftar_status"
                                    label="name"
                                    track-by="value"
                                    :allow-empty="false"
                                    deselect-label="Can't remove this value"
                                    placeholder="Pilih Status"
                        ></VueMultiselect>
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
            id_karyawan: String,
        },

        setup(props) {
            //define state search
            const search = ref('' || (new URL(document.location)).searchParams.get('search'));
            const showModal = ref(false);
            const catatan = ref();
            const tingkatan = ref();
            const tanggal = ref();
            const status = ref();
            const id_list = ref();

            const daftar_tingkatan = [
                { name: 'Ringan', value: 1 },
                { name: 'Sedang', value: 2 },
                { name: 'Serius', value: 3 },
                { name: 'Berat', value: 4 },
            ];

            const daftar_status = [
            { name: 'Teguran Lisan', value: 1 },
                { name: 'SP 1', value: 2 },
                { name: 'SP 2', value: 3 },
                { name: 'SP 3', value: 4 },
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

                //status tindakan
                daftar_status.forEach(function (data) {
                    if(data.value == list.status){
                        status.value = data
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
                    status: status.value.value,
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
                editData, showModal, tutupModal, catatan, tingkatan, tanggal, status, daftar_tingkatan, daftar_status, 
                updateData
            }

        }
    }
</script>

<style>

</style>
