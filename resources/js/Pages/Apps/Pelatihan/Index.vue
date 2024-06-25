<template>
    <Head>
        <title>Pelatihan Karyawan</title>
    </Head>
    <main>
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <button @click="buatBaruKategori" class="btn theme-bg4 text-white f-12 float-right" style="cursor:pointer; border:none; margin-right: 0px;" v-if="hasAnyPermission(['apps.pelatihan.create'])"><i class="fa fa-plus"></i>Tambah</button>
                    <button class="btn btn-success dropdown-toggle float-right" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="cursor:pointer; border:none;"><i class="fa fa-file-excel"></i> Excel</button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <a  :href="`/apps/pelatihan/export`" target="_blank" class="dropdown-item">Export</a>
                        <!-- <button @click="importExcel" target="_blank" class="dropdown-item">Import</button> -->
                    </div>
                    <h5>Daftar Pelatihan</h5>
                    <!-- <span class="d-block m-t-5">Page to manage the <code> company </code> data</span>  -->
                </div>
                <div class="card-block table-border-style">
                    <div class="table-responsive">

                        <div class="input-group mb-3">
                            <input
                            type="text"
                            class="form-control"
                            v-model="search"
                            placeholder="Cari berdasarkan Nama Pelatihan..."
                            style="width: 0%"
                            @input="debouncedSearch"
                            >
                            <button
                                class="btn btn theme-bg5 text-white f-12"
                                style="margin-left: 10px;"
                                @click="handleSearch"
                            >
                                <i style="margin-left: 10px" class="fa fa-search me-2"></i>
                            </button>
                        </div>
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr class="thead-light">
                                    <th class="text-center">#</th>
                                    <th class="text-center">Nama Karyawan</th>
                                    <th class="text-center">Kategori Pelatihan</th>
                                    <th class="text-center">Nama Pelatihan</th>
                                    <th class="text-center">Tanggal Mulai</th>
                                    <th class="text-center">Tanggal Selesai</th>
                                    <th class="text-center">Durasi (Jam)</th>
                                    <th class="text-center" v-if="hasAnyPermission(['apps.pelatihan.edit'])">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(plth, index) in pelatihan.data" :key="index">
                                    <td class="text-center">{{ pelatihan.from + index }}</td>
                                    <td>{{ plth.karyawan.nama_lengkap }}</td>
                                    <td v-if="(plth.kategori_pelatihan == null)"></td>
                                    <td v-if="(plth.kategori_pelatihan == 1)">Internal Perusahaan</td>
                                    <td v-if="(plth.kategori_pelatihan == 2)">Personal (Individu)</td>
                                    <td v-if="(plth.kategori_pelatihan == 3)">Pemerintah</td>
                                    <td>{{ plth.nama_pelatihan}}</td>
                                    <td>{{ plth.tanggal_mulai}}</td>
                                    <td>{{ plth.tanggal_selesai}}</td>
                                    <td class="text-center">{{ plth.durasi_pelatihan }}</td>
                                    <td class="text-center" v-if="hasAnyPermission(['apps.pelatihan.edit'])">
                                        <a @click="editData(plth)" v-if="hasAnyPermission(['apps.pelatihan.edit'])" class="label theme-bg3 text-white f-12" style="cursor:pointer; border-radius:10px"><i class="fa fa-pencil-alt"></i> Edit</a>
                                        <a @click="deleteData(plth.id)" v-if="hasAnyPermission(['apps.pelatihan.delete'])" class="label theme-bg3 text-white f-12" style="cursor:pointer; border-radius:10px; margin-left: 5px;"><i class="fa fa-trash-alt"></i> Delete</a>
                                    </td>
                                </tr>
                                <!-- jika data kosong -->
                                <tr v-if="pelatihan.data[0] == undefined">
                                    <td colspan="7" class="text-center">
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
                        <label class="col-form-label">Kategori Pelatihan :</label>
                        <VueMultiselect
                            v-model="kategori_pelatihan"
                            :options="data_kategori_pelatihan"
                            label="name"
                            track-by="value"
                            :allow-empty="false"
                            deselect-label="Can't remove this value"
                            placeholder="Pilih Kategori Pelatihan"
                        ></VueMultiselect>
                    </div>
                    <div class="form-group mb-3">
                        <label class="col-form-label">Nama Pelatihan :</label>
                        <input type="text" class="form-control" placeholder="Masukkan Nama Pelatihan " v-model="nama_pelatihan" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="col-form-label">Tanggal Mulai :</label>
                                <input type="date" class="form-control" placeholder="Masukkan Tanggal Mulai" v-model="tanggal_mulai" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="col-form-label">Tanggal Selesai :</label>
                                <input type="date" class="form-control" placeholder="Masukkan Tanggal Selesai" v-model="tanggal_selesai" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="col-form-label">Durasi Pelatihan (Jam) :</label>
                                <input type="number" class="form-control" placeholder="Masukkan Durasi Pelatihan (Jam)" v-model="durasi_pelatihan" required>
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
    //import debounce [searching]
    import debounce from 'lodash/debounce';



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
            const kategori_pelatihan = ref();
            const nama_pelatihan = ref();
            const tanggal_mulai = ref();
            const tanggal_selesai = ref();
            const durasi_pelatihan = ref();
            // define state search
            const search = ref('' || (new URL(document.location)).searchParams.get('search'));
            //define method search
            const handleSearch = () => {
                Inertia.get('/apps/pelatihan', {
                    //send params "search" with value from state "search"
                    search: search.value,
                });
            }

            const debouncedSearch = debounce(handleSearch, 1000);

            const data_kategori_pelatihan = [
                { name: 'Internal Perusahaan', value: 1 },
                { name: 'Personal (Individu)', value: 2 },
                { name: 'Pemerintah', value: 3 },
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
                judul.value = 'Tambah Pelatihan'
                id.value = null
                karyawan_id.value = null
                kategori_pelatihan.value = null
                nama_pelatihan.value = null
                tanggal_mulai.value = null
                tanggal_selesai.value = null
                durasi_pelatihan.value = null
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
                 //penyebab_phk
                data_kategori_pelatihan.forEach(function (data) {
                    if(data.value == plth.kategori_pelatihan){
                        kategori_pelatihan.value = data
                    }
                })

                judul.value = 'Edit Pelatihan'
                id.value = plth.id
                nama_pelatihan.value = plth.nama_pelatihan
                tanggal_mulai.value = plth.tanggal_mulai
                tanggal_selesai.value = plth.tanggal_selesai
                durasi_pelatihan.value = plth.durasi_pelatihan
                tampilModal()
            }

            const deleteData = (id) => {
                Swal.fire({
                    title: 'Apakah Anda Yakin?',
                    text: "Anda tidak akan dapat mengembalikan ini!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya!',
                    cancelButtonText: 'Batal'
                })
                .then((result) => {
                    if (result.isConfirmed) {
                        Inertia.post(`/apps/pelatihan/${id}/delete`);
                        Swal.fire({
                            title: 'Sukses!',
                            text: 'Data Karyawan berhasil dihapus.',
                            icon: 'success',
                            timer: 2000,
                            showConfirmButton: false,
                        });
                    }
                })
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
                if(karyawan_id.value == null || kategori_pelatihan.value == null || nama_pelatihan.value == null || tanggal_mulai.value == null){
                    tutupModal();
                    peringatan();
                }else{
                    //send data to server
                    Inertia.put(`/apps/pelatihan/${id.value}`, {
                        //data
                        karyawan_id: karyawan_id.value.id,
                        kategori_pelatihan: kategori_pelatihan.value.value,
                        nama_pelatihan: nama_pelatihan.value,
                        tanggal_mulai: tanggal_mulai.value,
                        tanggal_selesai: tanggal_selesai.value,
                        durasi_pelatihan: durasi_pelatihan.value,
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
                if(karyawan_id.value == null || kategori_pelatihan.value == null || nama_pelatihan.value == null || tanggal_mulai.value == null){
                    tutupModal();
                    peringatan();
                }
                else{
                    //send data to server
                    Inertia.post('/apps/pelatihan', {
                        //data
                        karyawan_id: karyawan_id.value.id,
                        kategori_pelatihan: kategori_pelatihan.value.value,
                        nama_pelatihan: nama_pelatihan.value,
                        tanggal_mulai: tanggal_mulai.value,
                        tanggal_selesai: tanggal_selesai.value,
                        durasi_pelatihan: durasi_pelatihan.value,

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
                nama_lengkap, id, karyawan_id, kategori_pelatihan, nama_pelatihan, tanggal_mulai, tanggal_selesai, durasi_pelatihan, karyawan_id_edit,
                tutupModal, buatBaruKategori, updateData,
                storeData, peringatan, data_kategori_pelatihan, deleteData,
                debouncedSearch,
            }


        }
    }
</script>
