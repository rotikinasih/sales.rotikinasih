<template>
    <Head>
      <title>Exp Karyawan</title>
    </Head>
    <main>
      <div class="col-xl-12">
        <div class="card">
          <div class="card-header">
            <h5>Daftar Expired Karyawan</h5>
          </div>
          <div class="card-block table-border-style">
            <div class="table-responsive">
              <div class="input-group mb-3">
                <input type="text" class="form-control" v-model="search" placeholder="Cari berdasarkan Nama Lengkap, Nomor Induk Karyawan..." style="width: 0%" @input="debouncedSearch">
                <button class="btn btn theme-bg5 text-white f-12" style="margin-left: 10px;" @click="handleSearch">
                  <i style="margin-left: 10px" class="fa fa-search me-2"></i>
                </button>
              </div>
              <table class="table table-bordered table-hover">
                <thead class="thead-light">
                  <tr>
                    <th class="text-center">#</th>
                    <th class="text-center">Nama Karyawan</th>
                    <th class="text-center">NIK (Karyawan)</th>
                    <th class="text-center">Alasan</th>
                    <th class="text-center">Tanggal</th>
                    <th class="text-center">Jenis</th>
                    <th class="text-center" v-if="hasAnyPermission(['apps.resign.edit'])">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(karyawan, index) in karyawan_status.data" :key="index">
                    <td class="text-center">{{ index + karyawan_status.from }}</td>
                    <td>{{ karyawan.nama_lengkap }}</td>
                    <td>{{ karyawan.nik_karyawan }}</td>
                    <td>{{ karyawan.alasan }}</td>
                    <td>{{ karyawan.tanggal }}</td>
                    <td>{{ karyawan.type }}</td>
                    <td class="text-center" v-if="hasAnyPermission(['apps.resign.edit'])">
                      <a @click="editData(karyawan)" class="label theme-bg3 text-white f-12" style="cursor:pointer; border-radius:10px"><i class="fa fa-pencil-alt"></i> Edit</a>
                      <a @click="deleteData(karyawan.id)" v-if="hasAnyPermission(['apps.resign.delete'])" class="label theme-bg3 text-white f-12" style="cursor:pointer; border-radius:10px; margin-left: 5px;"><i class="fa fa-trash-alt"></i> Delete</a>
                    </td>
                  </tr>
                  <!-- jika data kosong -->
                  <tr v-if="karyawan_status.data[0] == undefined">
                    <td colspan="8" class="text-center">
                      <br>
                      <i class="fa fa-file-excel fa-5x"></i><br><br> Data Kosong
                    </td>
                  </tr>
                </tbody>
              </table>
              <div class="row" style="max-width:100%; overflow-x:hidden">
                <div class="col-md-4">
                  <label v-if="karyawan_status.data[0] != undefined" align="start">Showing {{ karyawan_status.from }} to {{ karyawan_status.to }} of {{ karyawan_status.total }} items</label>
                </div>
                <div class="col-md-8">
                  <Pagination v-if="karyawan_status.data[0] != undefined" :links="karyawan_status.links" align="end" />
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>
  </template>

  <script>
  import LayoutApp from '../../../Layouts/App.vue';
  import { Head, Link } from '@inertiajs/inertia-vue3';
  import Pagination from '../../../Components/Pagination.vue';
  import { ref } from 'vue';
  import { Inertia } from '@inertiajs/inertia';
  import Swal from 'sweetalert2';
  import VueMultiselect from 'vue-multiselect';
  import 'vue-multiselect/dist/vue-multiselect.css';
  import debounce from 'lodash/debounce';

  export default {
    layout: LayoutApp,
    components: {
      Head,
      Link,
      Pagination,
      Swal,
      VueMultiselect,
    },
    props: {
      karyawan_status: Object,
      karyawan: Array,
      karyawan_edit: Array,
    },
    setup(props) {
      const showModal = ref(false);
      const updateSubmit = ref(false);
      const judul = ref(null);
      const id = ref(null);
      const nama_lengkap = ref();
      const karyawan_id = ref();
      const karyawan_id_edit = ref();
      const alasan = ref();
      const tanggal = ref();
      const search = ref('' || (new URL(document.location)).searchParams.get('search'));
      const data_alasan = [
        { name: 'Tidak Memenuhi Target', value: 1 },
        { name: 'Mendapat Pekerjaan Lain', value: 2 },
        { name: 'Melanjutkan Pendidikan', value: 3 },
        { name: 'Faktor Keluarga', value: 4 },
        { name: 'Habis Kontrak', value: 5 },
        { name: 'Usia Pensiun', value: 6 },
        { name: 'Meninggal Dunia', value: 7 },
      ];

      const handleSearch = () => {
        Inertia.get('/karyawan-status', { search: search.value });
      };

      const debouncedSearch = debounce(handleSearch, 1000);

      const editData = (karyawan) => {
        if (updateSubmit.value == false) {
          updateSubmit.value = !updateSubmit.value;
        }
        let data_karyawan = props.karyawan_edit;
        data_karyawan.forEach(data => {
          if (karyawan.karyawan_id == data.id) {
            karyawan_id_edit.value = data;
          }
        });
        judul.value = 'Edit Data';
        id.value = karyawan.id;
        alasan.value = karyawan.alasan;
        tanggal.value = karyawan.tanggal;
      };

      const storeData = () => {
        Inertia.post('/karyawan-status', {
          karyawan_id: karyawan_id.value.id,
          alasan: alasan.value.value,
          tanggal: tanggal.value,
        }, {
          onFinish: () => {
            tutupModal();
          },
          onError: () => {
            Swal.fire({
              title: 'Gagal!',
              text: 'Terjadi kesalahan saat menyimpan data!',
              icon: 'error',
            });
          }
        });
      };

      const updateData = () => {
        Inertia.put(`/karyawan-status/${id.value}`, {
          karyawan_id: karyawan_id_edit.value.id,
          alasan: alasan.value.value,
          tanggal: tanggal.value,
        }, {
          onFinish: () => {
            tutupModal();
          },
          onError: () => {
            Swal.fire({
              title: 'Gagal!',
              text: 'Terjadi kesalahan saat menyimpan data!',
              icon: 'error',
            });
          }
        });
      };

      const deleteData = (id) => {
        Swal.fire({
          title: 'Apakah Anda yakin?',
          text: 'Data yang dihapus tidak dapat dikembalikan!',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Ya, hapus!',
          cancelButtonText: 'Batal'
        }).then((result) => {
          if (result.isConfirmed) {
            Inertia.delete(`/karyawan-status/${id}`, {
              onSuccess: () => {
                Swal.fire({
                  title: 'Terhapus!',
                  text: 'Data berhasil dihapus.',
                  icon: 'success',
                });
              },
              onError: () => {
                Swal.fire({
                  title: 'Gagal!',
                  text: 'Terjadi kesalahan saat menghapus data!',
                  icon: 'error',
                });
              }
            });
          }
        });
      };

      return {
        showModal,
        updateSubmit,
        judul,
        id,
        nama_lengkap,
        karyawan_id,
        karyawan_id_edit,
        alasan,
        tanggal,
        search,
        data_alasan,
        handleSearch,
        debouncedSearch,
        editData,
        storeData,
        updateData,
        deleteData,
      };
    }
  };
  </script>
