<template>
  <Head>
    <title>Purchase Product</title>
  </Head>
  <main>
    <div class="col-xl-12">
      <div class="card">
        <div class="card-header">
          <h5>Order Produksi ke Produksi</h5>
          <!-- Dropdown Outlet -->
          <select class="form-control mt-2" v-model="outlet_id" @change="getData">
            <option v-for="o in outlet" :key="o.id" :value="o.id">{{ o.lokasi }}</option>
          </select>
        </div>
        <div class="card-body">
          <form @submit.prevent="storeData">
            <div class="form-group mb-2">
              <label>Outlet</label>
              <select class="form-control" v-model="outlet_id" required>
                <option value="">Pilih Outlet</option>
                <option v-for="o in outlet" :key="o.id" :value="o.id">
                  {{ o.lokasi }}
                </option>
              </select>
            </div>

            <div class="form-group mb-2">
              <label>Produk & Jumlah</label>
              <div v-for="(p, idx) in produkList" :key="idx" class="d-flex gap-2 mb-2">
                <select class="form-control" v-model="p.master_produk_id" required>
                  <option value="">Pilih Produk</option>
                  <option v-for="prd in produk" :key="prd.id" :value="prd.id">
                    {{ prd.nama_produk }}
                  </option>
                </select>
                <input
                  type="number"
                  class="form-control"
                  v-model="p.jumlah_beli"
                  min="1"
                  required
                  placeholder="Jumlah"
                />
                <button type="button" class="btn btn-danger" @click="hapusProduk(idx)">
                  <i class="fa fa-times"></i>
                </button>
              </div>
              <button type="button" class="btn btn-sm btn-primary" @click="tambahProduk">
                <i class="fa fa-plus"></i> Tambah Produk
              </button>
            </div>
            <div class="form-group mb-2">
              <label>Tanggal Pengiriman</label>
              <input type="date" class="form-control" v-model="tanggal_pengiriman" required />
            </div>
            <div class="form-group mb-2">
              <label>Jam Pengiriman</label>
              <select class="form-control" v-model="jam_pengiriman" required>
                <option value="">Pilih Waktu</option>
                <option value="Pagi">Pagi</option>
                <option value="Siang">Siang</option>
                <option value="Sore">Sore</option>
                <option value="Malam">Malam</option>
              </select>
            </div>
            <div class="form-group mb-2">
              <label>Tanggal Pembuatan</label>
              <input type="date" class="form-control" v-model="tanggal_pembuatan" required />
            </div>

            <button type="submit" class="btn btn-success mt-3">Simpan</button>
          </form>
        </div>
      </div>
    </div>
  </main>
</template>

<script>
import { ref } from 'vue'
import { Inertia } from '@inertiajs/inertia'
import { Head } from '@inertiajs/inertia-vue3'
import Swal from 'sweetalert2'
import LayoutApp from '../../../Layouts/App.vue'

export default {
  layout: LayoutApp,
  components: { Head },
  props: { produk: Array, outlet: Array, outlet_id: [String, Number] },
  setup(props) {
    const outlet_id = ref(props.outlet_id || (props.outlet?.[0]?.id ?? ''))
    const produkList = ref([{ master_produk_id: null, jumlah_beli: 1 }])
    const tanggal_pengiriman = ref('')
    const jam_pengiriman = ref('')
    const tanggal_pembuatan = ref('')

    const getData = () => {
      Inertia.get('/apps/order-produksi-kasir', {
        outlet_id: outlet_id.value,
      }, { preserveState: true, replace: true })
    }

    const tambahProduk = () => produkList.value.push({ master_produk_id: null, jumlah_beli: 1 })
    const hapusProduk = (idx) => { if (produkList.value.length > 1) produkList.value.splice(idx, 1) }

    const storeData = () => {
      if (
        !outlet_id.value ||
        produkList.value.some((p) => !p.master_produk_id || p.jumlah_beli < 1) ||
        !tanggal_pengiriman.value ||
        !jam_pengiriman.value ||
        !tanggal_pembuatan.value
      ) {
        return Swal.fire('Perhatian', 'Semua isian wajib diisi!', 'warning')
      }

      Inertia.post(
        '/apps/order-produksi-kasir',
        {
          outlet_id: outlet_id.value,
          produkList: produkList.value,
          tanggal_pengiriman: tanggal_pengiriman.value,
          jam_pengiriman: jam_pengiriman.value,
          tanggal_pembuatan: tanggal_pembuatan.value,
        },
        {
          onSuccess: () => {
            outlet_id.value = ''
            produkList.value = [{ master_produk_id: null, jumlah_beli: 1 }]
            tanggal_pengiriman.value = ''
            jam_pengiriman.value = ''
            tanggal_pembuatan.value = ''
            Swal.fire('Sukses', 'Order berhasil dibuat!', 'success')
          },
        }
      )
    }

    return {
      outlet_id,
      produkList,
      tambahProduk,
      hapusProduk,
      storeData,
      tanggal_pengiriman,
      jam_pengiriman,
      tanggal_pembuatan,
      outlet: props.outlet,
      getData,
    }
  },
}
</script>
