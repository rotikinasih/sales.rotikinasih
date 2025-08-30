<template>
    <Head>
        <title>Master Produk</title>
    </Head>
    <main>
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <button @click="buatBaruKategori" class="btn theme-bg4 text-white f-12 float-right" style="cursor:pointer; border:none; margin-right: 0px;" v-if="hasAnyPermission(['karyawan.create'])"><i class="fa fa-plus"></i>Tambah</button>
                    <h5>Master Produk</h5>
                    <!-- <span class="d-block m-t-5">Page to manage the <code> division </code> data</span> -->
                </div>
                <div class="card-block table-border-style">
                    <div class="table-responsive">

                        <div class="input-group mb-3">
                            <input
                            type="text"
                            class="form-control"
                            v-model="search"
                            placeholder="Cari berdasarkan Nama Devisi..."
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
                            <thead class="thead-light">
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center">Kode Produk</th>
                                    <th class="text-center">Nama Produk</th>
                                    <th class="text-center">Harga Modal</th>
                                    <th class="text-center">Harga Jual</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center" v-if="hasAnyPermission(['karyawan.edit'])">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(dv, index) in produk.data" :key="index">
                                    <td class="text-center">{{ index + produk.from }}</td>
                                    <td>{{ dv.kode }}</td>
                                    <td>{{ dv.nama_produk }}</td>
                                    <td class="text-center">Rp {{ formatHarga(dv.harga_modal) }}</td>
                                    <td class="text-center">Rp {{ formatHarga(dv.outlet_price) }}</td>
                                    <td class="text-center" v-if="(dv.status == 1)"><b style="color: rgb(9, 240, 9);">Aktif</b></td>
                                    <td class="text-center" v-if="(dv.status == 2)"><b style="color: rgb(247, 76, 9);">Nonaktif</b></td>
                                    <td class="text-center" v-if="hasAnyPermission(['karyawan.edit'])">
                                        <a @click="editData(dv)" v-if="hasAnyPermission(['produk.edit'])" class="label theme-bg3 text-white f-12" style="cursor:pointer; border-radius:10px"><i class="fa fa-pencil-alt"></i> Edit</a>
                                    </td>
                                </tr>
                                <!-- jika data kosong -->
                                <tr v-if="produk.data[0] == undefined">
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
                                <label v-if="produk.data[0] != undefined" align="start">Showing {{ produk.from }} to {{ produk.to }} of {{ produk.total }} items</label>
                            </div>
                            <div class="col-md-8">
                                <Pagination v-if="produk.data[0] != undefined" :links="produk.links" align="end"/>
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
                        <label class="col-form-label">Kode Produk :</label>
                        <input type="text" class="form-control" placeholder="Masukkan Kode Produk" v-model="kode" required>
                    </div>
                    <div class="form-group mb-3">
                        <label class="col-form-label">Nama Produk :</label>
                        <input type="text" class="form-control" placeholder="Masukkan Nama Produk" v-model="nama_produk" required>
                    </div>
                    <div class="form-group mb-3">
    <label class="col-form-label">Outlet Price :</label>
    <input type="number" class="form-control" placeholder="Masukkan Harga Outlet" v-model="outlet_price" min="0">
</div>
<div class="form-group mb-3">
    <label class="col-form-label">Harga Modal :</label>
    <input type="number" class="form-control" placeholder="Masukkan Harga Modal" v-model="harga_modal" min="0">
</div>

                    <div class="form-group mb-3">
                        <label class="col-form-label">Status :</label>
                        <select class="form-control" aria-label="Default select example" v-model="status" required>
                            <option :value="null">Pilih Status</option>
                            <option value="1">Aktif</option>
                            <option value="2">Nonaktif</option>
                        </select>
                    </div>
                   
<div class="form-group mb-3">
    <label class="col-form-label">Tipe Outlet</label>
    <select class="form-control" v-model="tipe_outlet_id" required>
        <option :value="null">Pilih Tipe Outlet</option>
        <option v-for="tipe in tipeOutlets" :key="tipe.id" :value="tipe.id">
            {{ tipe.tipe }}
        </option>
    </select>
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
            Swal
        },
        //props
        props:{
            produk: Object,
            tipeOutlets: Array // <-- tambahkan ini
            
        },
        //composition API
        setup(props){
            const showModal = ref(false);
            const updateSubmit = ref(false);
            const judul = ref(null);
            const id = ref(null);
            const kode = ref(null);
            const nama_produk = ref(null);
            const status = ref(null);

            const outlet_price = ref(null);
const harga_modal = ref(null);
const tipe_outlet_id = ref(null);
const tipe_outlet_ids = ref([]);
const formatHarga = (angka) => {
    if (angka == null || isNaN(angka)) return '0';
    return parseInt(angka).toLocaleString('id-ID');
};


            //define state search
            const search = ref('' || (new URL(document.location)).searchParams.get('search'));
            //define method search
            const handleSearch = () => {
                Inertia.get('/apps/produk', {
                    //send params "search" with value from state "search"
                    search: search.value,
                });
            }

            const debouncedSearch = debounce(handleSearch, 1000);

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
                judul.value = 'Tambah Produk'
                id.value = null
                kode.value = null
                nama_produk.value = null
                outlet_price.value = null
                harga_modal.value = null
                status.value = null
                tipe_outlet_ids.value = [];
                tampilModal()
            }
            //method edit show modal
            const editData = (pe) => {
                if (updateSubmit.value == false) {
                    updateSubmit.value = !updateSubmit.value
                }
                judul.value = 'Edit Produk'
                id.value = pe.id
                kode.value = pe.kode
                nama_produk.value = pe.nama_produk
                outlet_price.value = pe.outlet_price
                harga_modal.value = pe.harga_modal
                status.value = pe.status
                tipe_outlet_id.value = pe.tipe_outlet && pe.tipe_outlet.length > 0 ? pe.tipe_outlet[0].id : null;
                tipe_outlet_ids.value = pe.tipe_outlet
                    ? pe.tipe_outlet.map(t => t.id)
                    : [];
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
                if(kode.value == null || nama_produk.value == null || status.value == null){
                    if(kode.value == null && nama_produk.value == null && status.value == null){
                        tutupModal();
                        peringatan();
                    }
                    tutupModal();
                    peringatan();
                }else{
                    //send data to server
                    Inertia.put(`/apps/produk/${id.value}`, {
                        //data
                        kode: kode.value,
                        nama_produk: nama_produk.value,
                        outlet_price: outlet_price.value,
                        harga_modal: harga_modal.value,
                        status: parseInt(status.value),
                        tipe_outlet_id: tipe_outlet_id.value,
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

            //method "storeData"
            const storeData = () => {
                if(kode.value == null || nama_produk.value == null || status.value == null){
                    tutupModal();
                    peringatan();
                }else{
                    Inertia.post('/apps/produk', {
                        kode: kode.value,
                        nama_produk: nama_produk.value,
                        outlet_price: outlet_price.value,
                        harga_modal: harga_modal.value,
                        status: status.value,
                        tipe_outlet_id: tipe_outlet_id.value,
                    }, {
                        onSuccess: () => {
                            tutupModal()
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

            return {
                search,
                handleSearch,
                showModal,
                editData,
                judul,
                updateSubmit, kode,
                nama_produk, outlet_price, harga_modal, formatHarga, status, id,
                tutupModal, buatBaruKategori, updateData,
                storeData, peringatan,
                debouncedSearch,
                tipe_outlet_id,
            }
        }
    }
</script>
