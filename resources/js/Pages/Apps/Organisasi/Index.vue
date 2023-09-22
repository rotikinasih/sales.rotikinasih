<template>
    <Head>
        <title>Karir</title>
    </Head>
    <main>
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <button @click="buatBaruKategori" class="btn theme-bg4 text-white f-12 float-right" style="cursor:pointer; border:none; margin-right: 0px;" v-if="hasAnyPermission(['apps.list-organisasi.create'])"><i class="fa fa-plus"></i>Tambah</button>
                    <button class="btn btn-success dropdown-toggle float-right" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="cursor:pointer; border:none;">
                        <i class="fa fa-file-excel"></i> Excel
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <a  :href="`/apps/list-organisasi/export`" target="_blank" class="dropdown-item">Export</a>
                        <!-- <button @click="importExcel" target="_blank" class="dropdown-item">Import</button> -->
                    </div>
                    <h5>Daftar Karir</h5>
                    <!-- <span class="d-block m-t-5">Page to manage the list of <code>{{ nama }}</code> organizations</span> -->
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
                                    <th scope="col" class="text-center">#</th>
                                    <th scope="col" class="text-center">Nama Lengkap</th>
                                    <th scope="col" class="text-center">Kategori Karir</th>
                                    <th scope="col" class="text-center">Entitas</th>
                                    <th scope="col" class="text-center">Divisi</th>
                                    <th scope="col" class="text-center">Jabatan</th>
                                    <th scope="col" class="text-center">Posisi</th>
                                    <th scope="col" class="text-center">Tanggal Masuk</th>
                                    <th scope="col" class="text-center">Tanggal Berakhir</th>
                                    <th scope="col" style="text-align: center" v-if="hasAnyPermission(['apps.list-organisasi.edit'])">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(list, index) in lists.data" :key="index">
                                    <td class="text-center">{{ index + lists.from}}</td>
                                    <td>{{ list.karyawan.nama_lengkap }}</td>
                                    <td v-if="(list.kategori_karir == null || list.kategori_karir == 0)"></td> 
                                    <td v-if="(list.kategori_karir == 1)">Promosi</td>
                                    <td v-if="(list.kategori_karir == 2)">Demosi</td>
                                    <td v-if="(list.kategori_karir == 3)">Mutasi</td>
                                    <td v-if="list.pt_id == null"></td>
                                    <td v-else>{{ list.perusahaan.nama_pt }}</td>
                                    <td v-if="list.divisi_id == null"></td>
                                    <td v-else>{{ list.divisi.nama_divisi }}</td>
                                    <td v-if="list.jabatan_id == null"></td>
                                    <td v-else>{{ list.jabatan.nama_jabatan }}</td>
                                    <td v-if="list.posisi_id == null"></td>
                                    <td v-else>{{ list.posisi.nama_posisi }}</td>
                                    <td>{{ list.tgl_masuk }}</td>
                                    <td>{{ list.tgl_berakhir }}</td>
                                    <td class="text-center" v-if="hasAnyPermission(['apps.list-organisasi.edit'])">
                                        <a @click="editData(list)" class="label theme-bg3 text-white f-12" style="cursor:pointer; border-radius:10px" v-if="hasAnyPermission(['apps.list-organisasi.edit'])"><i class="fa fa-pencil-alt"></i> Edit</a>
                                    </td>
                                </tr>
                                <!-- jika data kosong -->
                                <tr v-if="lists.data[0] == undefined">
                                    <td colspan="10" class="text-center">
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
                        <!-- <Link href="/apps/list-organisasi" class="btn btn-secondary shadow-sm" style="float: right; margin-right: 2px">Kembali</Link> -->
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
                    <div class="form-group mb-0 mt-0">
                        <label class="col-form-label" >Nama Karyawan :</label>
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
                    <div class="form-group mb-0 mt-0">
                        <label class="col-form-label">Kategori :</label>
                        <VueMultiselect
                            v-model="kategori_karir"
                            :options="data_kategori_karir"
                            label="name"
                            track-by="value"
                            :allow-empty="false"
                            deselect-label="Can't remove this value"
                            placeholder="Pilih Kategori Karir"
                        ></VueMultiselect>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-0 mt-0">
                                <label class="col-form-label" >Entitas :</label>
                                <VueMultiselect
                                    v-model="pt_id"
                                    :options="perusahaan"
                                    label="nama_pt"
                                    track-by="id"
                                    :allow-empty="false"
                                    deselect-label="Can't remove this value"
                                    placeholder="Pilih Entitas"
                                ></VueMultiselect>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-0 mt-0">
                                <label class="col-form-label" >Divisi :</label>
                                <VueMultiselect
                                    v-model="divisi_id"
                                    :options="divisi"
                                    label="nama_divisi"
                                    track-by="id"
                                    :allow-empty="false"
                                    deselect-label="Can't remove this value"
                                    placeholder="Pilih Divisi"
                                ></VueMultiselect>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-0 mt-0">
                                <label class="col-form-label" >Jabatan :</label>
                                <VueMultiselect
                                    v-model="jabatan_id"
                                    :options="jabatan"
                                    label="nama_jabatan"
                                    track-by="id"
                                    :allow-empty="false"
                                    deselect-label="Can't remove this value"
                                    placeholder="Pilih Jabatan"
                                ></VueMultiselect>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-0 mt-0">
                                <label class="col-form-label" >Posisi :</label>
                                <VueMultiselect
                                    v-model="posisi_id"
                                    :options="posisi"
                                    label="nama_posisi"
                                    track-by="id"
                                    :allow-empty="false"
                                    deselect-label="Can't remove this value"
                                    placeholder="Pilih Posisi"
                                ></VueMultiselect>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-0 mt-0">
                                <label class="col-form-label">Tanggal Masuk :</label>
                                <input type="date" class="form-control" placeholder="Masukkan Tanggal Pelatihan" v-model="tgl_masuk" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-0 mt-0">
                                <label class="col-form-label">Tanggal Berakhir :</label>
                                <input type="date" class="form-control" placeholder="MasukkanTanggal Berakhir" v-model="tgl_berakhir" required>
                            </div>
                        </div>
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
            karyawan: Array,
            perusahaan: Array,
            divisi: Array,
            jabatan: Array,
            posisi: Array,

        },

        setup(props) {
            //define state search
            const search = ref('' || (new URL(document.location)).searchParams.get('search'));
            const showModal = ref(false);
            const updateSubmit = ref(false);
            const judul = ref(null);
            const id = ref(null);
            const divisi_id = ref();
            const pt_id = ref();
            const jabatan_id = ref();
            const posisi_id = ref();
            const karyawan_id = ref();
            const kategori_karir = ref();
            const tgl_masuk = ref();
            const tgl_berakhir = ref();
            const id_list = ref();

            //define method search
            const handleSearch = () => {
                Inertia.get('/apps/list-organisasi', {
                    //send params "search" with value from state "search"
                    search: search.value,
                });
            }

            const data_kategori_karir = [
                { name: 'Promosi', value: 1 },
                { name: 'Demosi', value: 2 },
                { name: 'Mutasi', value: 3 },
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
                judul.value = 'Tambah Karir'
                // id.value = null
                karyawan_id.value = null
                kategori_karir.value = null
                pt_id.value = null
                divisi_id.value = null
                jabatan_id.value = null
                posisi_id.value = null
                tgl_masuk.value = null
                tgl_berakhir.value = null
                tampilModal()
            }

            // console.log(buatBaruKategori)

            //method edit show modal
            const editData = (krr) => {
                console.log(krr);
                
                if (updateSubmit.value == false) {
                    updateSubmit.value = !updateSubmit.value
                }
                //karyawan
                let data_karyawan = props.karyawan
                data_karyawan.forEach(data => {
                    if(krr.karyawan_id == data.id){
                        karyawan_id.value = data
                    }
                })

                let data_entitas = props.perusahaan
                data_entitas.forEach(data => {
                    if(krr.pt_id == data.id){
                        pt_id.value = data
                    }
                })

                let data_divisi = props.divisi
                data_divisi.forEach(data => {
                    if(krr.divisi_id == data.id){
                        divisi_id.value = data
                    }
                })

                let data_jabatan = props.jabatan
                data_jabatan.forEach(data => {
                    if(krr.jabatan_id == data.id){
                        jabatan_id.value = data
                    }
                })

                let data_posisi = props.posisi
                data_posisi.forEach(data => {
                    if(krr.posisi_id == data.id){
                        posisi_id.value = data
                    }
                })

                //penyebab_phk
                data_kategori_karir.forEach(function (data) {
                    if(data.value == krr.kategori_karir){
                        kategori_karir.value = data
                    }
                })

                judul.value = 'Edit Karir'
                id.value = krr.id
                // kategori_karir.value = krr.kategori_karir
                tgl_masuk.value = krr.tgl_masuk
                tgl_berakhir.value = krr.tgl_berakhir
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
                if(karyawan_id.value == null || kategori_karir.value.value == null ||  pt_id.value == null || divisi_id.value == null || jabatan_id.value == null || posisi_id.value == null  || tgl_masuk.value == null){
                    tutupModal();
                    peringatan();
                }else{
                    //send data to server
                    Inertia.put(`/apps/list-organisasi/${id.value}/update`, {
                        //data
                        karyawan_id: karyawan_id.value.id,
                        pt_id: pt_id.value.id,
                        divisi_id: divisi_id.value.id,
                        jabatan_id: jabatan_id.value.id,
                        posisi_id: posisi_id.value.id,
                        kategori_karir: kategori_karir.value.value,
                        tgl_masuk: tgl_masuk.value,
                        tgl_berakhir: tgl_berakhir.value,
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
                if(karyawan_id.value == null || kategori_karir.value.value == null ||  pt_id.value == null || divisi_id.value == null || jabatan_id.value == null || posisi_id.value == null  || tgl_masuk.value == null){
                    tutupModal();
                    peringatan();
                }
                else{
                    //send data to server
                    Inertia.post('/apps/list-organisasi/store', {
                        //data
                        karyawan_id: karyawan_id.value.id,
                        pt_id: pt_id.value.id,
                        divisi_id: divisi_id.value.id,
                        jabatan_id: jabatan_id.value.id,
                        posisi_id: posisi_id.value.id,
                        kategori_karir: kategori_karir.value.value,
                        tgl_masuk: tgl_masuk.value,
                        tgl_berakhir: tgl_berakhir.value,
                        
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


            //return
            return {
                search,
                handleSearch, id,
                editData, showModal, tutupModal, divisi_id, karyawan_id, jabatan_id, pt_id, posisi_id, kategori_karir, tgl_masuk, tgl_berakhir, buatBaruKategori, data_kategori_karir,
                updateData, storeData, peringatan, updateSubmit, judul,
            }

        }
    }
</script>

<style>

</style>
