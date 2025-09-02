<template>
  <Head>
    <title>Konfirmasi Distribusi</title>
  </Head>
  <main>
    <div class="col-xl-12">
      <div class="card">
        <div class="card-header">
          <h5>Konfirmasi Purchase Order</h5>
          <!-- Dropdown Outlet -->
          <select class="form-control mt-2" v-model="outlet_id" @change="getData">
            <option v-for="o in outlets" :key="o.id" :value="o.id">{{ o.lokasi }}</option>
          </select>
        </div>
        <div class="card-body">
          <div v-if="distribusi.length">
            <div
              v-for="item in distribusi"
              :key="item.id"
              class="border rounded p-3 mb-3 shadow-sm bg-light"
            >
              <div class="d-flex justify-content-between align-items-center mb-2">
                <strong>Kode: {{ item.order_penjualan.kode_distribusi }}</strong>
                <span class="badge bg-warning text-dark">Status: Dikirim</span>
              </div>

              <ul class="mb-2">
                <li
                  v-for="detail in item.order_penjualan.details"
                  :key="detail.id"
                >
                  {{ detail.master_produk.nama_produk }} - {{ detail.jumlah_beli }}
                </li>
              </ul>

              <div class="d-flex gap-2">
                <button class="btn btn-success btn-sm" @click="konfirmasi(item.id, 2)">
                  <i class="fa fa-check"></i> Terima
                </button>
                <button class="btn btn-danger btn-sm" @click="konfirmasi(item.id, 3)">
                  <i class="fa fa-times"></i> Tolak
                </button>
              </div>
            </div>
          </div>
          <div v-else class="text-muted text-center">
            <em>Tidak ada distribusi yang perlu dikonfirmasi.</em>
          </div>
        </div>
      </div>
    </div>
  </main>
</template>

<script>
import { Head } from '@inertiajs/inertia-vue3'
import Swal from 'sweetalert2'
import { Inertia } from '@inertiajs/inertia'
import LayoutApp from '../../../Layouts/App.vue'
import { ref } from 'vue'

export default {
  layout: LayoutApp,
  components: { Head },
  props: {
    distribusi: Array,
    outlets: Array,
    outlet_id: [String, Number],
  },
  setup(props) {
    const outlet_id = ref(props.outlet_id || (props.outlets?.[0]?.id ?? ''))
    const getData = () => {
      Inertia.get('/apps/konfirmasi-distribusi-kasir', {
        outlet_id: outlet_id.value,
      }, { preserveState: true, replace: true })
    }
    const konfirmasi = (id, status) => {
      const actionText = status === 2 ? 'menerima' : 'menolak'
      Swal.fire({
        title: `Yakin ingin ${actionText} distribusi ini?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Ya',
        cancelButtonText: 'Batal',
      }).then((result) => {
        if (result.isConfirmed) {
          Inertia.put(`/apps/konfirmasi-distribusi-kasir/${id}`, {
            status_konfirmasi: status,
          }, {
            onSuccess: () => {
              Swal.fire('Berhasil', 'Distribusi dikonfirmasi.', 'success')
            },
            onError: () => {
              Swal.fire('Gagal', 'Terjadi kesalahan.', 'error')
            },
          })
        }
      })
    }
    return {
      distribusi: props.distribusi,
      outlets: props.outlets,
      outlet_id,
      getData,
      konfirmasi,
    }
  },
}
</script>
