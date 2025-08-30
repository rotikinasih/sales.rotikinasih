<template>
    <Head>
        <title>Master Kendaraan</title>
    </Head>
    <main>
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <button @click="buatBaru" class="btn theme-bg4 text-white f-12 float-right" style="cursor:pointer; border:none" v-if="hasAnyPermission(['masterkendaraan.create'])">
                        <i class="fa fa-plus"></i> Tambah
                    </button>
                    <h5>Master Kendaraan</h5>
                </div>
                <div class="card-block table-border-style">
                    <div class="table-responsive">
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" v-model="search" placeholder="Cari Kode / Merk / Driver" @input="debouncedSearch">
                            <button class="btn btn theme-bg5 text-white f-12" @click="handleSearch">
                                <i class="fa fa-search"></i>
                            </button>
                        </div>
                        <table class="table table-bordered table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center">Kode Kendaraan</th>
                                    <th class="text-center">Merk Kendaraan</th>
                                    <th class="text-center">Driver</th>
                                    <th class="text-center">Daya Angkut</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center" v-if="hasAnyPermission(['masterkendaraan.edit'])">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(item, index) in masterkendaraan.data" :key="index">
                                    <td class="text-center">{{ index + masterkendaraan.from }}</td>
                                    <td>{{ item.kode_kendaraan }}</td>
                                    <td>{{ item.merk_kendaraan }}</td>
                                    <td>{{ item.driver }}</td>
                                    <td class="text-right">{{ item.daya_angkut }}</td>
                                    <td class="text-center">
                                        <b :style="{ color: item.status == 1 ? 'red' : 'green' }">
                                            {{ item.status == 1 ? 'Nonaktif' : 'Aktif' }}
                                        </b>
                                    </td>
                                    <td class="text-center" v-if="hasAnyPermission(['masterkendaraan.edit'])">
                                        <a @click="editData(item)" class="label theme-bg3 text-white f-12" style="cursor:pointer; border-radius:10px">
                                            <i class="fa fa-pencil-alt"></i> Edit
                                        </a>
                                    </td>
                                </tr>
                                <tr v-if="masterkendaraan.data.length === 0">
                                    <td colspan="7" class="text-center">
                                        <br>
                                        <i class="fa fa-file-excel fa-5x"></i><br><br>
                                        Data Kosong
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="row">
                            <div class="col-md-4">
                                <label v-if="masterkendaraan.data.length" align="start">
                                    Showing {{ masterkendaraan.from }} to {{ masterkendaraan.to }} of {{ masterkendaraan.total }} items
                                </label>
                            </div>
                            <div class="col-md-8">
                                <Pagination v-if="masterkendaraan.data.length" :links="masterkendaraan.links" align="end" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <Teleport to="body">
            <modal :show="showModal" @close="showModal = false">
                <template #header>
                    <h5 class="modal-title">{{ judul }}</h5>
                </template>
                <template #body>
                    <div class="form-group mb-3">
                        <label>Kode Kendaraan</label>
                        <input v-model="kode_kendaraan" type="text" class="form-control" required />
                    </div>
                    <div class="form-group mb-3">
                        <label>Merk Kendaraan</label>
                        <input v-model="merk_kendaraan" type="text" class="form-control" required />
                    </div>
                    <div class="form-group mb-3">
                        <label>Driver</label>
                        <input v-model="driver" type="text" class="form-control" required />
                    </div>
                    <div class="form-group mb-3">
                        <label>Daya Angkut</label>
                        <input v-model="daya_angkut" type="number" class="form-control" required />
                    </div>
                    <div class="form-group mb-3">
                        <label>Status</label>
                        <select v-model="status" class="form-control">
                            <option :value="null">Pilih Status</option>
                            <option value="1">Nonaktif</option>
                            <option value="2">Aktif</option>
                        </select>
                    </div>
                </template>
                <template #footer>
                    <form @submit.prevent="storeData">
                        <button type="submit" v-show="!updateSubmit" class="btn btn-success text-white m-2">Simpan</button>
                        <button type="button" v-show="updateSubmit" @click="updateData" class="btn btn-warning text-white m-2">Update</button>
                    </form>
                    <button class="btn btn-secondary m-2" @click="tutupModal">Keluar</button>
                </template>
            </modal>
        </Teleport>
    </main>
</template>

<script>
import LayoutApp from '../../../Layouts/App.vue';
import { Head } from '@inertiajs/inertia-vue3';
import Pagination from '../../../Components/Pagination.vue';
import { ref, toRefs } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import Modal from '../../../Components/Modal.vue';
import Swal from 'sweetalert2';
import debounce from 'lodash/debounce';

export default {
    layout: LayoutApp,
    components: { Head, Pagination, Modal, Swal },
    props: {
        masterkendaraan: Object
    },
    setup(props) {
        const { masterkendaraan } = toRefs(props);

        const search = ref('');
        const showModal = ref(false);
        const updateSubmit = ref(false);
        const judul = ref('');
        const id = ref(null);
        const kode_kendaraan = ref('');
        const merk_kendaraan = ref('');
        const driver = ref('');
        const daya_angkut = ref('');
        const status = ref(null);

        const handleSearch = () => {
            Inertia.get('/apps/masterkendaraan', { search: search.value }, { preserveState: true });
        };
        const debouncedSearch = debounce(handleSearch, 800);

        const buatBaru = () => {
            updateSubmit.value = false;
            id.value = null;
            kode_kendaraan.value = '';
            merk_kendaraan.value = '';
            driver.value = '';
            daya_angkut.value = '';
            status.value = null;
            judul.value = 'Tambah Kendaraan';
            showModal.value = true;
        };

        const editData = (item) => {
            updateSubmit.value = true;
            id.value = item.id;
            kode_kendaraan.value = item.kode_kendaraan;
            merk_kendaraan.value = item.merk_kendaraan;
            driver.value = item.driver;
            daya_angkut.value = item.daya_angkut;
            status.value = item.status;
            judul.value = 'Edit Kendaraan';
            showModal.value = true;
        };

        const tutupModal = () => {
            showModal.value = false;
        };

        const peringatan = () => {
            Swal.fire('Mohon lengkapi semua field!');
        };

        const storeData = () => {
            if (!kode_kendaraan.value || !merk_kendaraan.value || !driver.value || !daya_angkut.value || !status.value) {
                tutupModal();
                return peringatan();
            }
            Inertia.post('/apps/masterkendaraan', {
                kode_kendaraan: kode_kendaraan.value,
                merk_kendaraan: merk_kendaraan.value,
                driver: driver.value,
                daya_angkut: daya_angkut.value,
                status: status.value,
            }, {
                onSuccess: () => {
                    tutupModal();
                    Swal.fire('Success', 'Data berhasil ditambahkan.', 'success');
                },
            });
        };

        const updateData = () => {
            if (!kode_kendaraan.value || !merk_kendaraan.value || !driver.value || !daya_angkut.value || !status.value) {
                tutupModal();
                return peringatan();
            }
            Inertia.put(`/apps/masterkendaraan/${id.value}`, {
                kode_kendaraan: kode_kendaraan.value,
                merk_kendaraan: merk_kendaraan.value,
                driver: driver.value,
                daya_angkut: daya_angkut.value,
                status: status.value,
            }, {
                onSuccess: () => {
                    tutupModal();
                    Swal.fire('Success', 'Data berhasil diperbarui.', 'success');
                },
            });
        };

        return {
            masterkendaraan,
            search, handleSearch, debouncedSearch,
            showModal, buatBaru, editData, tutupModal,
            kode_kendaraan, merk_kendaraan, driver, daya_angkut, status,
            judul, updateSubmit, storeData, updateData
        };
    }
};
</script>
