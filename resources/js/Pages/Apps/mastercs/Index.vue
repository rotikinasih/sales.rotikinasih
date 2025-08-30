<template>
    <Head>
        <title>Master CS</title>
    </Head>
    <main>
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <button @click="tampilModalBaru" class="btn theme-bg4 text-white f-12 float-right" style="cursor:pointer; border:none;">Tambah</button>
                    <h5>Master CS</h5>
                </div>
                <div class="card-block table-border-style">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center">Nama CS</th>
                                    <th class="text-center">No HP</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(cs, index) in csList.data" :key="index">
                                    <td class="text-center">{{ index + csList.from }}</td>
                                    <td>{{ cs.nama }}</td>
                                    <td>{{ cs.no_hp }}</td>
                                    <td class="text-center">
                                        <span v-if="cs.status == 1" style="color:green;">Aktif</span>
                                        <span v-else style="color:red;">Nonaktif</span>
                                    </td>
                                    <td class="text-center">
                                        <a @click="editCS(cs)" class="label theme-bg3 text-white f-12" style="cursor:pointer; border-radius:10px"><i class="fa fa-pencil-alt"></i> Edit</a>
                                    </td>
                                </tr>
                                <tr v-if="csList.data.length === 0">
                                    <td colspan="5" class="text-center">
                                        <br>
                                        <i class="fa fa-user fa-5x"></i><br><br>
                                        Data Kosong
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="row">
                            <div class="col-md-4">
                                <label v-if="csList.data.length > 0">Showing {{ csList.from }} to {{ csList.to }} of {{ csList.total }} items</label>
                            </div>
                            <div class="col-md-8">
                                <Pagination v-if="csList.data.length > 0" :links="csList.links" align="end"/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal -->
        <Teleport to="body">
            <modal :show="showModal" @close="showModal = false">
                <template #header>
                    <h5 class="modal-title">{{ judul }}</h5>
                </template>
                <template #body>
                    <div class="form-group mb-3">
                        <label class="col-form-label">Nama CS :</label>
                        <input type="text" class="form-control" placeholder="Masukkan Nama CS" v-model="nama" required>
                    </div>
                    <div class="form-group mb-3">
                        <label class="col-form-label">No HP :</label>
                        <input type="text" class="form-control" placeholder="Masukkan No HP" v-model="no_hp" required>
                    </div>
                    <div class="form-group mb-3">
                        <label class="col-form-label">Status :</label>
                        <select class="form-control" v-model="status" required>
                            <option :value="null">Pilih Status</option>
                            <option value="1">Aktif</option>
                            <option value="2">Nonaktif</option>
                        </select>
                    </div>
                </template>
                <template #footer>
                    <form @submit.prevent="storeCS">
                        <button type="submit" v-show="!updateSubmit" class="btn btn-success text-white m-2">Simpan</button>
                        <button type="button" v-show="updateSubmit" @click="updateCS()" class="btn btn-warning text-white m-2">Update</button>
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
import Modal from '../../../Components/Modal.vue';
import { ref } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import Swal from 'sweetalert2';

export default {
    layout: LayoutApp,
    components: { Head, Pagination, Modal, Swal },
    props: { csList: Object },
    setup(props) {
        const showModal = ref(false);
        const updateSubmit = ref(false);
        const judul = ref(null);
        const id = ref(null);
        const nama = ref(null);
        const no_hp = ref(null);
        const status = ref(null);

        const tampilModalBaru = () => {
            updateSubmit.value = false;
            judul.value = 'Tambah CS';
            id.value = null;
            nama.value = null;
            no_hp.value = null;
            status.value = null;
            showModal.value = true;
        };

        const tutupModal = () => {
            showModal.value = false;
        };

        const editCS = (cs) => {
            updateSubmit.value = true;
            judul.value = 'Edit CS';
            id.value = cs.id;
            nama.value = cs.nama;
            no_hp.value = cs.no_hp;
            status.value = cs.status;
            showModal.value = true;
        };

        const peringatan = () => {
            Swal.fire({
                title: 'Mohon lengkapi isian!',
                icon: 'warning',
                showConfirmButton: false,
                timer: 2000
            });
        };

        const storeCS = () => {
            if (!nama.value || !no_hp.value || !status.value) {
                tutupModal();
                peringatan();
            } else {
                Inertia.post('/apps/mastercs', {
                    nama: nama.value,
                    no_hp: no_hp.value,
                    status: status.value,
                }, {
                    onSuccess: () => {
                        tutupModal();
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
        };

        const updateCS = () => {
            if (!nama.value || !no_hp.value || !status.value) {
                tutupModal();
                peringatan();
            } else {
                Inertia.put(`/apps/mastercs/${id.value}`, {
                    nama: nama.value,
                    no_hp: no_hp.value,
                    status: status.value,
                }, {
                    onSuccess: () => {
                        tutupModal();
                        Swal.fire({
                            title: 'Success!',
                            text: 'Data berhasil diupdate.',
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 2000
                        });
                    },
                });
            }
        };

        return {
            showModal,
            updateSubmit,
            judul,
            id,
            nama,
            no_hp,
            status,
            tampilModalBaru,
            tutupModal,
            editCS,
            storeCS,
            updateCS,
        };
    }
};
</script>