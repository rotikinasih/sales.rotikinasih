<template>

    <Head>
        <title>Master Outlet</title>
    </Head>
    <main>
        <div class="col-xl-12">
            <div class="card">

                <div class="card-header">
                    <button @click="buatBaruKategori" class="btn theme-bg4 text-white f-12 float-right"
                        style="cursor:pointer; border:none; margin-right: 0px;"
                        v-if="hasAnyPermission(['outlet.create'])"><i class="fa fa-plus"></i>Tambah</button>
                    <h5>Master Outlet</h5>
                </div>
                <div class="card-block table-border-style">
                    <div class="table-responsive">
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" v-model="search"
                                placeholder="Cari berdasarkan nama outlet..." style="width: 0%"
                                @input="debouncedSearch">
                            <button class="btn btn theme-bg5 text-white f-12" style="margin-left: 10px;"
                                @click="handleSearch">
                                <i style="margin-left: 10px" class="fa fa-search me-2"></i>
                            </button>
                        </div>
                        <table class="table table-bordered table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center">Lokasi</th>
                                    <th class="text-center">Alamat</th>
                                    <th class="text-center">Latitude</th>
                                    <th class="text-center">Longitude</th>
                                    <th class="text-center">Radius</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center" v-if="hasAnyPermission(['outlet.edit'])">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(pss, index) in outlet.data" :key="index">
                                    <td class="text-center">{{ index + outlet.from }}</td>
                                    <td>{{ pss.lokasi }}</td>
                                    <td>{{ pss.alamat }}</td>
                                    <td>{{ pss.lat_pen }}</td>
                                    <td>{{ pss.long_pen }}</td>
                                    <td>{{ pss.radius || '-' }}</td>
                                    <td class="text-center" v-if="pss.status == 1"><b
                                            style="color: rgb(9, 240, 9);">Aktif</b></td>
                                    <td class="text-center" v-if="pss.status == 2"><b
                                            style="color: rgb(247, 76, 9);">Nonaktif</b></td>
                                    <td class="text-center" v-if="hasAnyPermission(['outlet.edit'])">
                                        <a @click="editData(pss)" class="label theme-bg3 text-white f-12"
                                            style="cursor:pointer; border-radius:10px"><i class="fa fa-pencil-alt"></i>
                                            Edit</a>
                                    </td>
                                </tr>
                                <tr v-if="outlet.data[0] == undefined">
                                    <td colspan="8" class="text-center">
                                        <br>
                                        <i class="fa fa-file-excel fa-5x"></i><br><br>
                                        Data Kosong
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="row" style="max-width:100%; overflow-x:hidden">
                            <div class="col-md-4">
                                <label v-if="outlet.data[0] != undefined" align="start">Showing {{ outlet.from
                                    }} to {{ outlet.to }} of {{ outlet.total }} items</label>
                            </div>
                            <div class="col-md-8">
                                <Pagination v-if="outlet.data[0] != undefined" :links="outlet.links"
                                    align="end" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- MODAL -->
        <Teleport to="body">
            <modal :show="showModal" @close="showModal = false">
                <template #body>
                    <div class="modal-scrollable" style="max-height: 70vh; overflow-y: auto; padding-right: 5px;">
                        <div class="form-group mb-3">
                            <label>Lokasi :</label>
                            <input type="text" class="form-control" placeholder="Masukkan Lokasi" v-model="lokasi" required>
                        </div>
                        <div class="form-group mb-3">
                            <label>Alamat:</label>
                            <input type="text" class="form-control" placeholder="Masukkan Alamat" v-model="alamat" required>
                        </div>
                        <div class="form-group mb-3">
                            <label>Lokasi di Peta (klik untuk ambil koordinat):</label>
                            <div id="map" style="height: 300px;"></div>
                        </div>
                        <div class="form-group mb-3">
                            <label>Latitude:</label>
                            <input type="text" class="form-control" v-model="lat_pen">
                        </div>
                        <div class="form-group mb-3">
                            <label>Longitude:</label>
                            <input type="text" class="form-control" v-model="long_pen">
                        </div>
                        <div class="form-group mb-3">
                            <label>Radius (meter):</label>
                            <input type="number" class="form-control" v-model="radius" placeholder="Misalnya 100">
                        </div>
                        <div class="form-group mb-3">
                            <label>Status:</label>
                            <select class="form-control" v-model="status" required>
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
                    </div>
                </template>

                <template #footer>
                    <form @submit.prevent="storeData">
                        <button type="submit" v-show="!updateSubmit" class="btn btn-success text-white m-2">Simpan</button>
                        <button type="button" v-show="updateSubmit" @click="updateData()" class="btn btn-warning text-white m-2">Update</button>
                    </form>
                    <button class="btn btn-secondary m-2" @click="tutupModal">Keluar</button>
                </template>
            </modal>
        </Teleport>
    </main>
</template>


<script>
import LayoutApp from '../../../Layouts/App.vue';
import { Head, Link } from '@inertiajs/inertia-vue3';
import Pagination from '../../../Components/Pagination.vue';
import Modal from '../../../Components/Modal.vue';
import Swal from 'sweetalert2';
import debounce from 'lodash/debounce';
import { ref, nextTick, watch } from 'vue'; // <-- Tambah 'watch'
import { Inertia } from '@inertiajs/inertia';
import L from 'leaflet';
import 'leaflet/dist/leaflet.css';
import markerIcon from 'leaflet/dist/images/marker-icon.png';
import markerShadow from 'leaflet/dist/images/marker-shadow.png';

L.Marker.prototype.options.icon = L.icon({
    iconUrl: markerIcon,
    shadowUrl: markerShadow,
    iconSize: [25, 41],
    iconAnchor: [12, 41],
    popupAnchor: [1, -34],
    shadowSize: [41, 41]
});

export default {
    layout: LayoutApp,
    components: {
        Head,
        Link,
        Pagination,
        Modal,
        Swal
    },
    props: {
        outlet: Object,
        tipeOutlets: Array // <-- tambahkan ini
    },
    setup(props) {
        const showModal = ref(false);
        const updateSubmit = ref(false);
        const judul = ref(null);
        const id = ref(null);
        const lokasi = ref(null);
        const alamat = ref(null);
        const status = ref(null);
        const lat_pen = ref(null);
        const long_pen = ref(null);
        const radius = ref(null);
        const tipe_outlet_id = ref(null);
        let circle = null;

        const search = ref((new URL(document.location)).searchParams.get('search') || '');
        const handleSearch = () => {
            Inertia.get('/apps/outlet', { search: search.value });
        };
        const debouncedSearch = debounce(handleSearch, 1000);

        const tutupModal = () => showModal.value = false;

        const tampilModal = () => {
            showModal.value = true;
            nextTick(() => initMap());
        };

        const buatBaruKategori = () => {
            updateSubmit.value = false;
            judul.value = 'Tambah Outlet';
            id.value = lokasi.value = alamat.value = status.value = lat_pen.value = long_pen.value = radius.value = tipe_outlet_id.value = null;
            tampilModal();
            nextTick(() => {
                initMap();
            });
        };

        const editData = (pe) => {
            updateSubmit.value = true;
            judul.value = 'Edit Outlet';
            id.value = pe.id;
            lokasi.value = pe.lokasi;
            alamat.value = pe.alamat;
            status.value = pe.status;
            lat_pen.value = pe.lat_pen;
            long_pen.value = pe.long_pen;
            radius.value = pe.radius; // <-- Tambah radius
            tipe_outlet_id.value = pe.tipe_outlet_id ?? (pe.tipe_outlet?.id ?? null);
            tampilModal();
            nextTick(() => {
                const defaultLat = pe.lat_pen && !isNaN(parseFloat(pe.lat_pen)) ? parseFloat(pe.lat_pen) : -7.593165;
                const defaultLng = pe.long_pen && !isNaN(parseFloat(pe.long_pen)) ? parseFloat(pe.long_pen) : 110.941913;
                initMap(defaultLat, defaultLng, 13);
            });
        };

        const peringatan = () => {
            Swal.fire({
                title: 'Mohon lengkapi isian!',
                icon: 'warning',
                width: 600,
                padding: '3em',
                color: '#716add',
                backdrop: `rgba(0,0,123,0.4) left top no-repeat`
            });
        };

        const updateData = () => {
            if (!lokasi.value || !status.value || !alamat.value || !lat_pen.value || !long_pen.value) {
                tutupModal();
                peringatan();
            } else {
                Inertia.put(`/apps/outlet/${id.value}`, {
                    lokasi: lokasi.value,
                    status: parseInt(status.value),
                    alamat: alamat.value,
                    lat_pen: lat_pen.value,
                    long_pen: long_pen.value,
                    radius: radius.value, // <-- Tambah radius
                    tipe_outlet_id: tipe_outlet_id.value,
                }, {
                    onSuccess: () => {
                        tutupModal();
                        Swal.fire({ title: 'Success!', text: 'Data berhasil diperbarui.', icon: 'success', timer: 2000, showConfirmButton: false });
                    },
                });
            }
        };

        const storeData = () => {
            if (!lokasi.value || !status.value || !alamat.value || !lat_pen.value || !long_pen.value) {
                tutupModal();
                peringatan();
            } else {
                Inertia.post('/apps/outlet', {
                    lokasi: lokasi.value,
                    status: parseInt(status.value),
                    alamat: alamat.value,
                    lat_pen: lat_pen.value,
                    long_pen: long_pen.value,
                    radius: radius.value, // <-- Tambah radius
                    tipe_outlet_id: tipe_outlet_id.value,
                }, {
                    onSuccess: () => {
                        tutupModal();
                        Swal.fire({ title: 'Success!', text: 'Data berhasil ditambahkan.', icon: 'success', timer: 2000, showConfirmButton: false });
                    },
                });
            }
        };

        // Leaflet map setup
        let map = null;
        let marker = null;
        const initMap = (lat = -7.593165, lng = 110.941913, zoom = 13) => {
            if (map !== null) {
                map.remove();
                map = null;
            }

            const latlng = L.latLng(parseFloat(lat), parseFloat(lng));
            map = L.map('map').setView(latlng, zoom);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            if (lat && lng) {
                marker = L.marker(latlng).addTo(map);
            }

            map.on('click', function (e) {
                const { lat, lng } = e.latlng;
                lat_pen.value = lat.toFixed(6);
                long_pen.value = lng.toFixed(6);

                if (marker) {
                    marker.setLatLng(e.latlng);
                } else {
                    marker = L.marker(e.latlng).addTo(map);
                }

                if (circle) {
                    map.removeLayer(circle);
                }

                if (radius.value && !isNaN(radius.value)) {
                    circle = L.circle(e.latlng, {
                        radius: parseFloat(radius.value),
                        color: 'blue',
                        fillColor: '#3f83f8',
                        fillOpacity: 0.3,
                    }).addTo(map);
                }
            });

            // Gambar circle saat map dibuka jika data radius sudah ada
            if (lat_pen.value && long_pen.value && radius.value) {
                const latlng = L.latLng(parseFloat(lat_pen.value), parseFloat(long_pen.value));
                circle = L.circle(latlng, {
                    radius: parseFloat(radius.value),
                    color: 'blue',
                    fillColor: '#3f83f8',
                    fillOpacity: 0.3,
                }).addTo(map);
            }
        };

        watch(radius, (newVal) => {
            if (map && lat_pen.value && long_pen.value && !isNaN(newVal)) {
                const latlng = L.latLng(parseFloat(lat_pen.value), parseFloat(long_pen.value));

                if (circle) {
                    map.removeLayer(circle);
                }

                circle = L.circle(latlng, {
                    radius: parseFloat(newVal),
                    color: 'blue',
                    fillColor: '#3f83f8',
                    fillOpacity: 0.3,
                }).addTo(map);
            }
        });

        return {
            search, handleSearch, debouncedSearch,
            showModal, tampilModal, tutupModal,
            buatBaruKategori, editData, judul, updateSubmit,
            lokasi, alamat, status, lat_pen, long_pen, radius, tipe_outlet_id, // <-- Tambah radius
            storeData, updateData, peringatan
        };
    }
}
</script>
