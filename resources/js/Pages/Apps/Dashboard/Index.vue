<template>
  <Head>
    <title>Dashboard Penjualan</title>
  </Head>
  <main>
    <!-- Kartu Statistik -->
    <div class="row mb-4 g-4 justify-content-center">
      <StatCard icon="fa-box" :value="total_produk" label="Master Produk" to="/apps/produk" />
      <StatCard icon="fa-shopping-cart" :value="total_order" label="Total Order" to="/apps/orderpenjualan" />
      <StatCard icon="fa-store" :value="total_outlet" label="Total Outlet" to="/apps/outlet" />
      <StatCard icon="fa-truck" :value="total_kendaraan" label="Total Kendaraan" to="/apps/masterkendaraan" />
      <StatCard icon="fa-money-bill-wave" :value="formatRupiah(total_transaksi_tahun)" label="Omset Reguler Tahun Ini" to="/apps/kasir/rekap" />
      <StatCard icon="fa-money-bill-wave" :value="formatRupiah(total_omset_tahun)" label="Omset Pesanan Tahun Ini" to="/apps/orderpenjualan" />
      <!-- <StatCard icon="fa-percent" :value="getPersentaseGrowth() + '%'" label="Growth Tahun Ini" /> -->

    </div>

    <!-- Grafik -->
    <div class="row g-4">
      <!-- Produk Terlaris -->
      <div class="col-12">
        <div class="card border-0 shadow-sm rounded-4">
          <div class="card-header bg-white border-bottom px-4 py-3">
            <h6 class="mb-0 fw-semibold text-dark">Produk Penjualan Reguler Terlaris Tahun {{ tahunSekarang }}</h6>
          </div>
          <div class="card-body px-4 py-4">
            <BarChart :chartData="chartProdukTerlaris" :options="produkOptions" style="height: 300px;" />
          </div>
        </div>
      </div>

      <!-- Grafik Produk Pesanan Terlaris -->
      <div class="col-12">
        <div class="card border-0 shadow-sm rounded-4">
          <div class="card-header bg-white border-bottom px-4 py-3">
            <h6 class="mb-0 fw-semibold text-dark">Grafik Produk Pesanan Terlaris Tahun {{ tahunSekarang }}</h6>
          </div>
          <div class="card-body px-4 py-4">
            <BarChart :chartData="chartProdukPesananTerlaris" :options="produkOptions" style="height: 300px;" />
          </div>
        </div>
      </div>

      <!-- Grafik Transaksi -->
      <div class="col-12">
        <div class="card border-0 shadow-sm rounded-4">
          <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center px-4 py-3">
            <h6 class="mb-0 fw-semibold text-dark">Grafik Omset Penjualan Reguler Per Bulan ({{ tahunSekarang }})</h6>
            <span class="badge bg-light text-dark px-3 py-2 fs-6">
              <i class="fa fa-chart-bar me-1"></i>
              {{ formatRupiah(total_transaksi_tahun) }}
            </span>
          </div>
          <div class="card-body px-4 py-4">
            <BarChart :chartData="chartTransaksiTanggal" :options="transaksiOptions" style="height: 300px;" />
          </div>
        </div>
      </div>

      <!-- Grafik Omset Pesanan Per Bulan -->
      <div class="col-12">
        <div class="card border-0 shadow-sm rounded-4">
          <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center px-4 py-3">
            <h6 class="mb-0 fw-semibold text-dark">Grafik Omset Pesanan Per Bulan ({{ tahunSekarang }})</h6>
            <span class="badge bg-light text-dark px-3 py-2 fs-6">
              <i class="fa fa-chart-bar me-1"></i>
              {{ formatRupiah(total_omset_tahun) }}
            </span>
          </div>
          <div class="card-body px-4 py-4">
            <BarChart :chartData="chartOmsetBulan" :options="transaksiOptions" style="height: 300px;" />
          </div>
        </div>
      </div>

      
    </div>
  </main>
</template>

<script>
import LayoutApp from '../../../Layouts/App.vue';
import { Head } from '@inertiajs/inertia-vue3';
import { computed, ref } from 'vue';
import { BarChart } from 'vue-chart-3';
import { Chart, registerables } from "chart.js";
Chart.register(...registerables);

const StatCard = {
  props: ['icon', 'value', 'label', 'to'],
  template: `
    <div class="col-xl-2 col-lg-3 col-md-4 col-6">
      <div
        class="card border-0 text-center py-4 rounded-4 stat-card"
        tabindex="0"
        @mouseover="hover = true"
        @mouseleave="hover = false"
        @mousedown="active = true"
        @mouseup="active = false"
        @click="navigate"
        :style="{
          cursor: 'pointer',
          transform: hover ? 'scale(1.04)' : active ? 'scale(0.98)' : 'scale(1)',
          boxShadow: hover ? '0 10px 30px rgba(0,0,0,0.08)' : '0 4px 12px rgba(0,0,0,0.05)'
        }"
      >
        <div class="fs-2 mb-2 text-secondary">
          <i :class="'fa ' + icon"></i>
        </div>
        <div class="fw-bold fs-3 mb-1">{{ value }}</div>
        <div class="text-muted">{{ label }}</div>
      </div>
    </div>
  `,
  data() {
    return { hover: false, active: false }
  },
  methods: {
    navigate() {
      if (this.to) {
        this.$inertia.get(this.to)
      }
    }
  }
};

export default {
  layout: LayoutApp,
  components: { Head, StatCard, BarChart },
  props: {
    total_produk: Number,
    total_order: Number,
    total_outlet: Number,
    total_kendaraan: Number,
    trx_per_tanggal: Array,
    produk_terlaris: Array,
    total_omset_tahun: { type: Number, default: 0 },
    omset_per_bulan: { type: Array, default: () => [] },
    produk_pesanan_terlaris: { type: Array, default: () => [] },
  },
  setup(props) {
    const tahunSekarang = new Date().getFullYear().toString();

    const chartTransaksiTanggal = {
      labels: props.trx_per_tanggal.map(x => x.tanggal),
      datasets: [{
        label: 'Total Transaksi',
        data: props.trx_per_tanggal.map(x => parseInt(x.total)),
        backgroundColor: '#2563eb',
        borderRadius: 8,
      }]
    };

    const chartProdukTerlaris = {
      labels: props.produk_terlaris.map(x => x.nama_produk),
      datasets: [{
        label: 'Jumlah Terjual',
        data: props.produk_terlaris.map(x => x.total_jual),
        backgroundColor: '#10b981',
        borderRadius: 8,
      }]
    };

    // Card Omset Tahun Ini
    const formatRupiah = (angka) => {
      if (!angka) return 'Rp 0';
      return 'Rp ' + Number(angka).toLocaleString('id-ID');
    };

    // Grafik Omset Per Bulan
    const chartOmsetBulan = {
      labels: props.omset_per_bulan.map(x => x.bulan),
      datasets: [{
        label: 'Omset Pesanan',
        data: props.omset_per_bulan.map(x => parseInt(x.total)),
        backgroundColor: '#f59e42',
        borderRadius: 8,
      }]
    };

    // Grafik Produk Pesanan Terlaris
    const chartProdukPesananTerlaris = {
      labels: props.produk_pesanan_terlaris.map(x => x.nama_produk),
      datasets: [{
        label: 'Jumlah Terjual Pesanan',
        data: props.produk_pesanan_terlaris.map(x => x.total_jual),
        backgroundColor: '#eab308',
        borderRadius: 8,
      }]
    };

    const total_transaksi_tahun = computed(() => {
      return props.trx_per_tanggal
        .filter(x => x.tanggal.startsWith(tahunSekarang))
        .reduce((sum, x) => sum + parseInt(x.total), 0);
    });

    const getPersentaseGrowth = () => {
      // contoh statis, kamu bisa ganti dengan data asli
      return 12;
    };

    const transaksiOptions = ref({
      responsive: true,
      plugins: {
        legend: { display: false },
        tooltip: {
          callbacks: {
            label: context => 'Rp ' + parseInt(context.raw).toLocaleString('id-ID')
          }
        }
      },
      scales: {
        y: {
          ticks: {
            callback: value => 'Rp ' + value.toLocaleString('id-ID')
          },
          beginAtZero: true
        }
      }
    });

    const produkOptions = ref({
      responsive: true,
      plugins: {
        legend: { display: false },
        tooltip: {
          callbacks: {
            label: context => context.raw + ' pcs'
          }
        }
      },
      scales: {
        y: {
          beginAtZero: true,
          ticks: {
            stepSize: 1,
            precision: 0
          }
        }
      }
    });

    return {
      tahunSekarang,
      formatRupiah,
      chartOmsetBulan,
      chartProdukPesananTerlaris,
      total_transaksi_tahun,
      chartTransaksiTanggal,
      chartProdukTerlaris,
      transaksiOptions,
      produkOptions,
      getPersentaseGrowth
    };
  }
};
</script>

<style scoped>
.stat-card {
  background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
  border-radius: 1rem;
  min-height: 120px;
  transition: transform 0.2s, box-shadow 0.2s;
}
.stat-card:hover {
  box-shadow: 0 10px 30px rgba(0,0,0,0.08);
}
.stat-card .fa {
  color: #0f172a;
}
.card {
  background: #ffffff;
  border-radius: 1rem;
  transition: all 0.3s ease-in-out;
}
.card-header {
  background-color: #ffffff !important;
  border-bottom: 1px solid #e5e7eb;
}
.card-body {
  background-color: #f9fafb;
  border-radius: 0 0 1rem 1rem;
}
.badge.bg-light {
  background-color: #f3f4f6 !important;
  font-weight: 500;
}
</style>