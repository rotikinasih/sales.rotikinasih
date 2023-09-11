<template>
    <Head>
        <title>Pelatihan Karyawan</title>
    </Head>
    <main>
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <button @click="buatBaruKategori" class="btn theme-bg4 text-white f-12 float-right" style="cursor:pointer; border:none; margin-right: 0px;"><i class="fa fa-plus"></i>Tambah</button>
                    <h5>Daftar Pelatihan</h5>
                    <!-- <span class="d-block m-t-5">Page to manage the <code> company </code> data</span>  -->
                </div>
                <div class="card-block table-border-style">
                    <div class="table-responsive">

                        <div class="input-group mb-3">
                            <input type="text" class="form-control" v-model="search" placeholder="Cari berdasarkan Nama Karyawan..." @keyup="handleSearch">
                            <!-- <input type="text" v-model="search" @input="searchPelatihan" placeholder="Cari Karyawan" /> -->
                            <button class="btn btn theme-bg5 text-white f-12" style="margin-left: 10px;" @click="handleSearch"> <i style="margin-left: 10px" class="fa fa-search me-2"></i></button>
                        </div>
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr class="thead-light">
                                    <!-- <th class="text-center">#</th> -->
                                    <th class="text-center">Nama Karyawan</th>
                                    <th class="text-center">Jenis Pelatihan</th>
                                    <th class="text-center">Tanggal Pelatiahan</th>
                                    <th class="text-center">Lama Pelatiahan</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(plth, index) in pelatihan.data" :key="index">
                                    <!-- <td>{{ index + 1 }}</td> -->
                                    <td>{{ plth.karyawan.nama_lengkap }}</td>
                                    <td>{{ plth.jenis_pelatihan}}</td>
                                    <td>{{ plth.tanggal_pelatihan}}</td>
                                    <td>{{ plth.lama_pelatihan }}</td>
                                    <td class="text-center">
                                        <a @click="editData(plth)" v-if="hasAnyPermission(['apps.pelatihan.edit'])"  class="label theme-bg3 text-white f-12" style="cursor:pointer; border-radius:10px"><i class="fa fa-pencil-alt"></i> Edit</a>
                                    </td>
                                </tr>
                                <!-- jika data kosong -->
                                <tr v-if="pelatihan.data[0] == undefined">
                                    <td colspan="6" class="text-center">
                                        <br>
                                        <i class="fa fa-file-excel fa-5x"></i><br><br>
                                            Data Kosong
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="row" style="max-width:100%; overflow-x:hidden">
                            <div class="col-md-4">
                                <label v-if="pelatihan.data[0] != undefined" align="start">Showing {{ pelatihan.from }} to {{ pelatihan.to }} of {{ pelatihan.total }} items</label>
                            </div>
                            <div class="col-md-8">
                                <Pagination v-if="pelatihan.data[0] != undefined" :links="pelatihan.links" align="end"/>
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
                            v-model="karyawan_id"
                            :options="karyawan"
                            label="nama_lengkap"
                            track-by="id"
                            :allow-empty="false"
                            deselect-label="Can't remove this value"
                            placeholder="Pilih Karyawan"
                        ></VueMultiselect>
                        <!-- <input type="text" class="form-control" v-model="karyawan_id"> -->
                    </div>
                    <div class="form-group mb-3">
                        <label class="col-form-label">Jenis Pelatihan :</label>
                        <input type="text" class="form-control" placeholder="Masukkan Jenis Pelatihan " v-model="jenis_pelatihan" required>
                    </div>
                    <div class="form-group mb-3">
                        <label class="col-form-label">Tanggal Pelatihan :</label>
                        <input type="date" class="form-control" placeholder="Masukkan Tanggal Pelatihan" v-model="tanggal_pelatihan" required>
                    </div>
                    <div class="form-group mb-3">
                        <label class="col-form-label">Lama Pelatihan :</label>
                        <input type="text" class="form-control" placeholder="Masukkan Lama Pelatihan" v-model="lama_pelatihan" required>
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
            pelatihan: Object,
            karyawan: Array,
        },

        // methods: {
        //     searchPelatihan() {
        //     this.$inertia.get(`/apps/pelatihan`, { search: this.search });
        //     },
        // },

        // data() {
        //     return {
        //     search: '',
        //     };
        // },
        //composition API
    

        setup(props){
            const showModal = ref(false);
            const updateSubmit = ref(false);
            const judul = ref(null);
            const id = ref(null);
            const nama_lengkap = ref();
            const karyawan_id = ref();
            const karyawan_id_edit = ref();
            const jenis_pelatihan = ref();
            const tanggal_pelatihan = ref();
            const lama_pelatihan = ref();
            // define state search
            const search = ref('' || (new URL(document.location)).searchParams.get('search'));
            //define method search
            const handleSearch = () => {
                Inertia.get('/apps/pelatihan', {
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
                judul.value = 'Tambah Pelatihan'
                id.value = null
                karyawan_id.value = null
                jenis_pelatihan.value = null
                tanggal_pelatihan.value = null
                lama_pelatihan.value = null
                tampilModal()
            }

            // console.log(buatBaruKategori)

            //method edit show modal
            const editData = (plth) => {
                console.log(plth);
                
                if (updateSubmit.value == false) {
                    updateSubmit.value = !updateSubmit.value
                }
                //karyawan
                let data_karyawan = props.karyawan
                data_karyawan.forEach(data => {
                    if(plth.karyawan_id == data.id){
                        karyawan_id.value = data
                        // form.divisi_id = data
                    }
                })
                judul.value = 'Edit Pelatihan'
                id.value = plth.id
                jenis_pelatihan.value = plth.jenis_pelatihan
                tanggal_pelatihan.value = plth.tanggal_pelatihan
                lama_pelatihan.value = plth.lama_pelatihan
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
                if(karyawan_id.value == null || jenis_pelatihan.value == null || tanggal_pelatihan.value == null){
                    tutupModal();
                    peringatan();
                }else{
                    //send data to server
                    Inertia.put(`/apps/pelatihan/${id.value}`, {
                        //data
                        karyawan_id: karyawan_id.value.id,
                        jenis_pelatihan: jenis_pelatihan.value,
                        tanggal_pelatihan: tanggal_pelatihan.value,
                        lama_pelatihan: lama_pelatihan.value,
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
                if(karyawan_id.value == null || jenis_pelatihan.value == null || tanggal_pelatihan.value == null){
                    tutupModal();
                    peringatan();
                }
                else{
                    //send data to server
                    Inertia.post('/apps/pelatihan', {
                        //data
                        karyawan_id: karyawan_id.value.id,
                        jenis_pelatihan: jenis_pelatihan.value,
                        tanggal_pelatihan: tanggal_pelatihan.value,
                        lama_pelatihan: lama_pelatihan.value,
                        
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
                showModal,
                search, 
                handleSearch,
                editData,
                judul,
                updateSubmit,
                nama_lengkap, id, karyawan_id, jenis_pelatihan, tanggal_pelatihan, lama_pelatihan, karyawan_id_edit, 
                tutupModal, buatBaruKategori, updateData,
                storeData, peringatan
            }

            
        }
    }
</script>