<template>
    <Head>
        <title>Karyawan</title>
    </Head>
    <main>
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <Link href="/apps/karyawan/create" v-if="hasAnyPermission(['karyawan.create'])" class="btn theme-bg4 text-white f-12 float-right" style="cursor:pointer; border:none; margin-right: 0px;"><i class="fa fa-plus"></i>Tambah</Link>
                    <button class="btn btn-success dropdown-toggle float-right" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" >
                        <i class="fa fa-file-excel"></i> Excel
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <a  :href="`/apps/karyawan/export`" target="_blank" class="dropdown-item">Export</a>
                        <button @click="importExcel" target="_blank" class="dropdown-item">Import</button>
                    </div>
                    <h5>Daftar Karyawan</h5>
                    <!-- <span class="d-block m-t-5">Page to manage the <code> employees </code> data</span> -->
                </div>
                <div class="card-block table-border-style">
                    <div v-if="session.error" class="alert alert-danger">
                        {{ session.error }}
                    </div>
                    <div v-if="session.success" class="alert alert-success">
                        {{ session.success }}
                    </div>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" v-model="search" placeholder="Cari berdasarkan Nama Lengkap, Nomor Induk Karyawan..." style="width: 0%" @keyup="handleSearch">
                        <button class="btn btn theme-bg5 text-white f-12" style="margin-left: 10px;" @click="handleSearch"><i style="margin-left: 10px" class="fa fa-search me-2"></i></button>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <thead class="thead-light" style="">
                                <tr>
                                    <th style="text-align: center;">#</th>
                                    <th style="text-align: center;">Nama Lengkap</th>
                                    <th style="text-align: center;">NIK (Karyawan)</th>
                                    <th style="text-align: center;">Entitas</th>
                                    <th style="text-align: center;">Divisi</th>
                                    <th style="text-align: center;">Jabatan</th>
                                    <th style="text-align: center;">Status Kerja</th>
                                    <th style="text-align: center;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(kar, index) in karyawan.data" :key="index">
                                    <td style="text-align: center;">{{ index + 1 }}</td>
                                    <td>{{ kar.nama_lengkap }}</td>
                                    <td>{{ kar.nik_karyawan }}</td>
                                    <td v-if="(kar.perusahaan == null)"> </td> 
                                    <td v-else>{{ kar.perusahaan.nama_pt}}</td>
                                    <td v-if="(kar.divisi == null)"> </td>
                                    <td v-else>{{ kar.divisi.nama_divisi}}</td>                                    
                                    <td v-if="(kar.jabatan == null)"> </td>
                                    <td v-else>{{ kar.jabatan.nama_jabatan }}</td>
                                    <td v-if="(kar.status_kerja == null)"></td>
                                    <td v-else-if="(kar.status_kerja == 1)" style="text-align: center;"><b style="color: rgb(250, 213, 4);">Kontrak</b></td>
                                    <td v-else-if="(kar.status_kerja == 2)" style="text-align: center;"><b style="color: rgb(45, 250, 4);">Tetap</b></td>
                                    <td v-else style="text-align: center;"><b style="color: rgb(160, 4, 250);">Training</b></td>
                                    <td class="text-center">
                                        <Link :href="`/apps/karyawan/${kar.id}/edit`" v-if="hasAnyPermission(['karyawan.edit'])" class="label theme-bg3 text-white f-12 me-2" style="cursor:pointer; border-radius:10px" title="Edit" data-toggle="tooltip-inner"><i class="fa fa-pencil-alt me-1"></i></Link>
                                        <a @click.prevent="destroy(kar.id)" v-if="hasAnyPermission(['karyawan.delete'])" class="label theme-bg6 text-white f-12" style="cursor:pointer; border-radius:10px" title="Delete" data-toggle="tooltip-inner"><i class="fa fa-trash"></i></a>
                                        <a @click.prevent="detail(kar)" class="label theme-bg8 text-white f-12" title="Detail" data-toggle="tooltip-inner" style="cursor:pointer; border-radius:10px"><i class="fa fa-info"></i></a>
                                        <a @click.prevent="addKarir(kar)" class="label theme-bg2 text-white f-12" title="Add Historical Organization" data-toggle="tooltip-inner" style="cursor:pointer; border-radius:10px"><i class="fa fa-user-plus"></i></a>
                                        <Link :href="`/apps/karyawan/${kar.id}/list-organisasi`" class="label theme-bg text-white f-12 me-2" style="cursor:pointer; border-radius:10px" title="Historical Organization" data-toggle="tooltip-inner"><i class="fa fa-users"></i></Link>
                                        <a @click.prevent="addPelanggaran(kar)" class="label theme-bg2 text-white f-12" title="Add Violation" data-toggle="tooltip-inner" style="cursor:pointer; border-radius:10px"><i class="fa fa-exclamation-triangle"></i></a>
                                        <Link :href="`/apps/karyawan/${kar.id}/list-pelanggaran`" class="label theme-bg text-white f-12 me-2" style="cursor:pointer; border-radius:10px" title="Violations" data-toggle="tooltip-inner"><i class="fa fa-exclamation-circle"></i></Link>
                                    </td>
                                </tr>
                                <!-- jika data kosong -->
                                <tr v-if="karyawan.data[0] == undefined">
                                    <td colspan="8" class="text-center">
                                        <br>
                                        <i class="fa fa-file-excel fa-5x"></i><br><br>
                                            Data Kosong
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="row">
                            <div class="col-md-4">
                                <label v-if="karyawan.data[0] != undefined" align="start">Showing {{ karyawan.from }} to {{ karyawan.to }} of {{ karyawan.total }} items</label>
                            </div>
                            <div class="col-md-8">
                                <Pagination v-if="karyawan.data[0] != undefined" :links="karyawan.links" align="end"/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-flex flex-row-reverse bd-highlight mb-3">
                    <!-- <button class="btn btn-success dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="margin-right: 25px; margin-top: 2px;">
                        <i class="fa fa-file-excel"></i> Excel
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <a  :href="`/apps/karyawan/export`" target="_blank" class="dropdown-item">Export</a>
                        <button @click="importExcel" target="_blank" class="dropdown-item">Import</button>
                    </div> -->
                </div>
            </div>
        </div>

        <!-- untuk modal detail -->
        <Teleport to="body">
            <modalScroll :show="showModalKarirDetail" @close="showModalKarirDetail = false">
                <template #header>
                    <h5 class="modal-title">Detail Data Karyawan</h5>
                    <a :href="`/apps/karyawan/download-pdf/${id}`" target="_blank" type="button" class="btn btn-danger" style="float: right; margin-right: 0px;"><i class='far fa-file-pdf'></i>Download PDF</a>
                </template>
                <template #body>
                    <div class="mb-3">
                        <div class="form-group mb-3">
                            <img v-if="foto != null" :src="`/storage/${foto}`" alt="photo" style="width:20%"><br>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <div class="form-group mb-3">
                                            <label class="col-form-label">Nama Lengkap :</label>
                                            <input type="text" class="form-control" v-model="nama_lengkap" readonly>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label class="col-form-label">Nama Panggilan :</label><br>
                                            <input type="text" class="form-control" v-model="nama_panggilan" readonly>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label class="col-form-label">Tempat Lahir :</label>
                                            <input type="text" class="form-control" v-model="tempat_lahir" readonly>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label class="col-form-label">Tanggal Lahir :</label>
                                            <input type="text" class="form-control" v-model="tanggal_lahir" readonly>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label class="col-form-label">Umur (Tahun) :</label>
                                            <input type="text" class="form-control" v-model="umur" readonly>
                                        </div>
                                        <div class="form-group mb-3">
                                                <label class="col-form-label">Jenis Kelamin :</label>
                                                <input v-if="jenis_kelamin == null" type="text" class="form-control" value=" " readonly>
                                                <input v-if="jenis_kelamin == 1" type="text" class="form-control" value="Laki-laki" readonly>
                                                <input v-if="jenis_kelamin == 2" type="text" class="form-control" value="Perempuan" readonly>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label class="col-form-label">Golongan Darah :</label>
                                            <input v-if="gol_darah == null" type="text" class="form-control" value=" " readonly>
                                            <input v-if="gol_darah == 1" type="text" class="form-control" value="A" readonly>
                                            <input v-else-if="gol_darah == 2" type="text" class="form-control" value="B" readonly>
                                            <input v-else-if="gol_darah == 3" type="text" class="form-control" value="O" readonly>
                                            <input v-else-if="gol_darah == 4" type="text" class="form-control" value="AB" readonly>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label class="col-form-label">Riwayat Penyakit :</label>
                                            <input type="text" class="form-control" v-model="riwayat_penyakit" readonly>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label class="col-form-label">No. KK :</label>
                                            <input type="text" class="form-control" v-model="no_kk" readonly>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label class="col-form-label">No. KTP  :</label>
                                            <input type="text" class="form-control" v-model="nik_penduduk" readonly>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label class="col-form-label">Kode Pos  :</label>
                                            <input type="text" class="form-control" v-model="kode_pos" readonly>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label class="col-form-label">Alamat KTP :</label>
                                            <input type="text" class="form-control" v-model="alamat_ktp" readonly>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label class="col-form-label">Alamat Domisili :</label>
                                            <input type="text" class="form-control" v-model="alamat_domisili" readonly>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label class="col-form-label">Pendidikan :</label>
                                            <input v-if="pendidikan == null" type="text" class="form-control" value=" " readonly>
                                            <input v-else-if="pendidikan == 1" type="text" class="form-control" value="SD" readonly>
                                            <input v-else-if="pendidikan == 2" type="text" class="form-control" value="SMP" readonly>
                                            <input v-else-if="pendidikan == 3" type="text" class="form-control" value="SMA" readonly>
                                            <input v-else-if="pendidikan == 4" type="text" class="form-control" value="D1" readonly>
                                            <input v-else-if="pendidikan == 5" type="text" class="form-control" value="D2" readonly>
                                            <input v-else-if="pendidikan == 6" type="text" class="form-control" value="D3" readonly>
                                            <input v-else-if="pendidikan == 7" type="text" class="form-control" value="D4" readonly>
                                            <input v-else-if="pendidikan == 8" type="text" class="form-control" value="S1" readonly>
                                            <input v-else-if="pendidikan == 9" type="text" class="form-control" value="S2" readonly>
                                            <input v-else-if="pendidikan == 10" type="text" class="form-control" value="S3" readonly>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label class="col-form-label">Nama Sekolah :</label>
                                            <input type="text" class="form-control" v-model="nama_sekolah" readonly>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label class="col-form-label">Jurusan :</label>
                                            <input type="text" class="form-control" v-model="jurusan" readonly>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label class="col-form-label">Email Pribadi :</label>
                                            <input type="text" class="form-control" v-model="email_pribadi" readonly>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label class="col-form-label">No. Telp/HP :</label>
                                            <input type="text" class="form-control" v-model="no_telp" readonly>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label class="col-form-label">No. WA :</label>
                                            <input type="text" class="form-control" v-model="no_wa" readonly>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label class="col-form-label">No. Keluarga :</label>
                                            <input type="text" class="form-control" v-model="no_keluarga" readonly>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label class="col-form-label">Hubungan Keluarga :</label>
                                            <input v-if="hubungan_keluarga == null" type="text" class="form-control" value=" " readonly>
                                            <input v-if="hubungan_keluarga == 1" type="text" class="form-control" value="Suami/Istri" readonly>
                                            <input v-if="hubungan_keluarga == 2" type="text" class="form-control" value="Ayah" readonly>
                                            <input v-if="hubungan_keluarga == 3" type="text" class="form-control" value="Ibu" readonly>
                                            <input v-if="hubungan_keluarga == 4" type="text" class="form-control" value="Kakak/Adik" readonly>
                                            <input v-if="hubungan_keluarga == 5" type="text" class="form-control" value="Paman/Bibi" readonly>
                                            <input v-if="hubungan_keluarga == 6" type="text" class="form-control" value="Kakek/Nenek" readonly>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label class="col-form-label">Status Pernikahan :</label>
                                            <input v-if="status_pernikahan == null" type="text" class="form-control" value=" " readonly>
                                            <input v-if="status_pernikahan == 1" type="text" class="form-control" value="Belum Menikah" readonly>
                                            <input v-if="status_pernikahan == 2" type="text" class="form-control" value="Menikah" readonly>
                                            <input v-if="status_pernikahan == 3" type="text" class="form-control" value="Janda" readonly>
                                            <input v-if="status_pernikahan == 4" type="text" class="form-control" value="Duda" readonly>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label class="col-form-label">Status Keluarga :</label>
                                            <input v-if="status_keluarga == null" type="text" class="form-control" value=" " readonly>
                                            <input v-if="status_keluarga == 1" type="text" class="form-control" value="Kepala Keluarga" readonly>
                                            <input v-if="status_keluarga == 2" type="text" class="form-control" value="Istri" readonly>
                                            <input v-if="status_keluarga == 3" type="text" class="form-control" value="Anak ke 1" readonly>
                                            <input v-if="status_keluarga == 4" type="text" class="form-control" value="Anak ke 2" readonly>
                                            <input v-if="status_keluarga == 5" type="text" class="form-control" value="Anak ke 3" readonly>
                                            <input v-if="status_keluarga == 6" type="text" class="form-control" value="Lainnya" readonly>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label class="col-form-label">Jenis Sosmed :</label>
                                            <input v-if="jenis_sosmed == null" type="text" class="form-control" value=" " readonly>
                                            <input v-if="jenis_sosmed == 1" type="text" class="form-control" value="Instagram" readonly>
                                            <input v-if="jenis_sosmed == 2" type="text" class="form-control" value="Facebook" readonly>
                                            <input v-if="jenis_sosmed == 3" type="text" class="form-control" value="Tiktok" readonly>
                                            <input v-if="jenis_sosmed == 4" type="text" class="form-control" value="Youtube" readonly>
                                            <input v-if="jenis_sosmed == 5" type="text" class="form-control" value="Lainnya" readonly>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label class="col-form-label">Nama Sosmed :</label>
                                            <input type="text" class="form-control" v-model="nama_sosmed" readonly>
                                        </div>
                                    </div>
                                </div>

                                <!-- data di perusahaan -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <div class="form-group mb-3">
                                            <label class="col-form-label">NIK (Karyawan) :</label>
                                            <input type="text" class="form-control" v-model="nik_karyawan" readonly>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label class="col-form-label">Entitas :</label>
                                            <input type="text" class="form-control" v-model="pt_id" readonly>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label class="col-form-label">Divisi :</label>
                                            <input type="text" class="form-control" v-model="divisi_id" readonly>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label class="col-form-label">Jabatan :</label>
                                            <input type="text" class="form-control" v-model="jabatan_id" readonly>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label class="col-form-label">Grade :</label>
                                            <input type="text" class="form-control" v-model="grade" readonly>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label class="col-form-label">Tanggal Masuk :</label>
                                            <input type="date" class="form-control" v-model="tanggal_masuk" readonly>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label class="col-form-label">Status Kerja :</label>
                                            <input v-if="status_kerja == null" type="text" class="form-control" value=" " readonly>
                                            <input v-if="status_kerja == 1" type="text" class="form-control" value="Kontrak" readonly>
                                            <input v-if="status_kerja == 2" type="text" class="form-control" value="Tetap" readonly>
                                            <input v-if="status_kerja == 3" type="text" class="form-control" value="Training" readonly>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label class="col-form-label">Komposisi Peran :</label>
                                            <input v-if="komposisi_peran == null" type="text" class="form-control" value=" " readonly>
                                            <input v-if="komposisi_peran == 1" type="text" class="form-control" value="Support" readonly>
                                            <input v-if="komposisi_peran == 2" type="text" class="form-control" value="Core" readonly>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label class="col-form-label">Komposisi Generasi :</label>
                                            <input type="text" class="form-control" v-model="komposisi_generasi" readonly>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label class="col-form-label">Tanggal Kontrak :</label>
                                            <input type="date" class="form-control" v-model="tanggal_kontrak" readonly>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label class="col-form-label">Masa Kontrak (Bulan):</label>
                                            <input type="text" class="form-control" v-model="masa_kontrak" readonly>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label class="col-form-label">Akhir Kontrak :</label>
                                            <input type="date" class="form-control" v-model="akhir_kontrak" readonly>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label class="col-form-label">Tanggal Karyawan Tetap :</label>
                                            <input type="date" class="form-control" v-model="tanggal_karyawan_tetap" readonly>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label class="col-form-label">Masa Kerja (Bulan) :</label>
                                            <input type="text" class="form-control" v-model="masa_kerja_bulan" readonly>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label class="col-form-label">Masa Kerja (Tahun) :</label>
                                            <input type="text" class="form-control" v-model="masa_kerja_tahun" readonly>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label class="col-f orm-label">Kota Rekruitmen :</label>
                                            <input type="text" class="form-control" v-model="kota_rekruitmen" readonly>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label class="col-f orm-label">Kota Penugasan :</label>
                                            <input type="text" class="form-control" v-model="kota_penugasan" readonly>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label class="col-form-label">No. NPWP :</label>
                                            <input type="text" class="form-control" v-model="no_npwp" readonly>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label class="col-form-label">No. BPJS Ketenagakerjaan :</label>
                                            <input type="text" class="form-control" v-model="no_bpjs_ketenagakerjaan" readonly>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label class="col-form-label">No. BPJS Kesehatan :</label>
                                            <input type="text" class="form-control" v-model="no_bpjs_kesehatan" readonly>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label class="col-form-label">Email Internal :</label>
                                            <input type="text" class="form-control" v-model="email_internal" readonly>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label class="col-form-label">Nama Bank :</label>
                                            <input type="text" class="form-control" v-model="nama_bank" readonly>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label class="col-form-label">Rekening Bank :</label>
                                            <input type="text" class="form-control" v-model="rekening" readonly>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label class="col-form-label">Clothes Size :</label>
                                            <input v-if="ukuran_baju == null" type="text" class="form-control" value=" " readonly>
                                            <input v-if="ukuran_baju == 0" type="text" class="form-control" value="S" readonly>
                                            <input v-else-if="ukuran_baju == 1" type="text" class="form-control" value="M" readonly>
                                            <input v-else-if="ukuran_baju == 2" type="text" class="form-control" value="L" readonly>
                                            <input v-else-if="ukuran_baju == 3" type="text" class="form-control" value="XL" readonly>
                                            <input v-else-if="ukuran_baju == 4" type="text" class="form-control" value="XXL" readonly>
                                            <input v-else-if="ukuran_baju == 5" type="text" class="form-control" value="Jumbo" readonly>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label class="col-form-label">Pengalaman Kerja Terakhir :</label>
                                            <input type="text" class="form-control" v-model="pengalaman_kerja_terakhir" readonly>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label class="col-form-label">Jabatan Kerja Terakhir :</label>
                                            <input type="text" class="form-control" v-model="jabatan_kerja_terakhir" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
                <template #footer>
                    <button class="btn btn-success" @click="tutupModalDetail" style="float: right; margin-right: 0px;">Keluar</button>
                </template>
            </modalScroll>
        </Teleport>

        <!-- untuk add karir-->
        <Teleport to="body">
            <modal :show="showModalKarir" @close="showModalKarir = false">
                <template #header>
                    <h5 class="modal-title">Riwayat Organisasi</h5>
                </template>
                <template #body>
                    <div class="form-group mb-3">
                        <label class="col-form-label">Nama Lengkap :</label>
                        <input type="text" class="form-control" placeholder="PT Name" v-model="nama" readonly>
                    </div>
                    
                        
                    <div class="form-group mb-3">
                        <label class="col-form-label">Entitas :</label>
                        <VueMultiselect
                            v-model="pt_id"
                            :options="pt"
                            label="nama_pt"
                            track-by="id"
                            :allow-empty="false"
                            deselect-label="Can't remove this value"
                            placeholder="Pilih Entitas"
                        ></VueMultiselect>
                    </div>
                
                    <div class="form-group mb-3">
                        <label class="col-form-label">Divisi :</label>
                        <VueMultiselect
                            v-model="divisi_id"
                            :options="divisi"
                            label="nama_divisi"
                            track-by="id"
                            :allow-empty="false"
                            deselect-label="Can't remove this value"
                            placeholder="Pilih Devisi"
                        ></VueMultiselect>
                    </div>
                
                    <div class="form-group mb-3">
                        <label class="col-form-label">Jabatan :</label>
                        <VueMultiselect
                            v-model="jabatan_id"
                            :options="jabatans"
                            label="nama_jabatan"
                            track-by="id"
                            :allow-empty="false"
                            deselect-label="Can't remove this value"
                            placeholder="Pilih Jabatan"
                        ></VueMultiselect>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="col-form-label">Tanggal Masuk : </label>
                                <input type="date" class="form-control" placeholder="Date of Entry" v-model="tgl_masuk" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="col-form-label">Tanggal Berakhir : </label>
                                <input type="date" class="form-control" placeholder="End Date" v-model="tgl_berakhir" required>
                            </div>
                        </div>
                    </div>
                </template>
                <template #footer>
					<form @submit.prevent="storeKarir">
						<button type="submit" class="btn btn-success text-white m-2">Simpan</button>
					</form>
                    <button
                        class="btn btn-secondary m-2" @click="tutupModal">Keluar</button>
                </template>
            </modal>
        </Teleport>

        <!-- untuk catatan pelanggaran -->
        <Teleport to="body">
            <modal :show="showModalPelanggaran" @close="showModalPelanggaran = false">
                <template #header>
                    <h5 class="modal-title">Catatan Pelanggaran</h5>
                </template>
                <template #body>
                    <div class="form-group mb-3">
                        <label class="col-form-label">Nama Lengkap :</label>
                        <input type="text" class="form-control" v-model="nama" readonly>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="col-form-label">Tanggal : </label>
                                <input type="date" class="form-control" placeholder="Date" v-model="tanggal" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="col-form-label">Tingkat Pelanggaran : </label>
                                <VueMultiselect
                                    v-model="tingkatan"
                                    :options="daftar_tingkatan"
                                    label="name"
                                    track-by="value"
                                    :allow-empty="false"
                                    deselect-label="Can't remove this value"
                                    placeholder="Pilih Status"
                                ></VueMultiselect>
                            </div>
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label class="col-form-label">Catatan : </label>
                        <textarea type="text" class="form-control" placeholder="Notes" v-model="catatan" required></textarea>
                    </div>
                    
                    <div class="form-group mb-3">
                        <label class="col-form-label">Status Tindakan : </label>
                        <VueMultiselect
                            v-model="status"
                            :options="daftar_status"
                            label="name"
                            track-by="value"
                            :allow-empty="false"
                            deselect-label="Can't remove this value"
                            placeholder="Pilih Status"
                        ></VueMultiselect>
                    </div>
                        
                </template>
                <template #footer>
					<form @submit.prevent="storePelanggaran">
						<button type="submit" class="btn btn-success text-white m-2">Simpan</button>
					</form>
                    <button
                        class="btn btn-secondary m-2" @click="tutupModalPelanggaran">Keluar</button>
                </template>
            </modal>
        </Teleport>

        <!-- modal import excel -->
        <Teleport to="body">
            <modal :show="showModalImport" @close="showModalImport = false">
                <template #header>
                    <h5 class="modal-title">Import Excel</h5>
                </template>
                <template #body>
                    <!-- <p class="text-warning">Make sure the data entered is in accordance with the existing format, the data imported is only new data</p>
                    <p class="text-warning">Specifically for the column for employment status, gender, blood group, married status, education, clothes size according to the options in the exact same case</p>
                    <p class="text-warning">Do not change the existing template</p> -->
                    <div class="form-group mb-3">
                        <label for="col-form-label">Contoh Format Excel</label><br>
                        <a :href="`/apps/karyawan/format`" target="_blank" class="btn btn-success text-white"><i class="fa fa-download me-2"></i>Download Format</a>
                    </div>
                    <div class="form-group mb-3">
                        <label class="col-form-label">File :</label><br>
                        <input type="file" id="task_file" @change="selectedTaskFile" required>
                    </div>
                </template>
                <template #footer>
					<form @submit.prevent="storeExcel">
						<button type="submit" class="btn btn-success text-white m-2" :disabled="!file_excel">Import</button>
					</form>
                    <button
                        class="btn btn-secondary m-2" @click="tutupModalImport">Keluar</button>
                </template>
            </modal>
        </Teleport>
    </main>
</template>

<script>
    //import layout
    import LayoutApp from '../../../Layouts/App.vue';
    //import component pagination
    import Pagination from '../../../Components/Pagination.vue';
    //import Heade and Link from Inertia
    import { Head, Link  } from '@inertiajs/inertia-vue3';
    //import ref from vue
    import { ref } from 'vue';
    //import inertia adapter
    import { Inertia } from '@inertiajs/inertia';
    //import sweet alert2
    import Swal from 'sweetalert2';
    import ModalScroll from '../../../Components/ModalScroll.vue';
    import Modal from '../../../Components/Modal.vue';
    import VueMultiselect from 'vue-multiselect';
    import 'vue-multiselect/dist/vue-multiselect.css';
    import axios from 'axios';

    export default {
        //layout
        layout: LayoutApp,

        //register component
        components: {
            Head,
            Link,
            Pagination,
            ModalScroll,
            Modal,
            VueMultiselect,
            Swal
        },
        
        //props
        props: {
            karyawan: Object,
            divisi: Array,
            pt: Array,
            jabatans: Array,
            session: Object,
        },

        // methods: {
        //     downloadPDF() {
        //         Inertia.get('/karyawan/download-pdf');
        //     }
        // },
        
        setup() {
            const showModalKarirDetail = ref(false);
            const showModalKarir = ref(false);
            const showModalPelanggaran = ref(false);
            const showModalImport = ref(false);

            //detail karyawandata pribadi
            const id = ref();
            const nama_lengkap = ref();
            const nama_panggilan = ref();
            const tempat_lahir = ref();
            const tanggal_lahir = ref();
            const umur = ref();
            const jenis_kelamin = ref();
            const gol_darah = ref();
            const riwayat_penyakit = ref();
            const no_kk = ref();
            const nik_penduduk = ref();
            const kode_pos = ref();
            const alamat_ktp = ref();
            const alamat_domisili = ref();
            const pendidikan = ref();
            const nama_sekolah = ref();
            const jurusan = ref();
            const email_pribadi = ref();
            const no_telp = ref();
            const no_wa = ref();
            const no_keluarga = ref();
            const hubungan_keluarga= ref();
            const status_pernikahan = ref();
            const status_keluarga = ref();
            const jenis_sosmed = ref();
            const nama_sosmed = ref();

            //detail karyawan data diperusahaan
            const nik_karyawan = ref();
            const pt_id = ref();
            const divisi_id = ref();
            const jabatan_id = ref();
            const grade = ref();
            const tanggal_masuk = ref();
            const tanggal_kontrak = ref();
            const masa_kontrak = ref();
            const masa_kerja_bulan = ref();
            const masa_kerja_tahun = ref();
            const tanggal_karyawan_tetap = ref();
            const komposisi_peran = ref();
            const komposisi_generasi = ref();
            const nama_bank = ref();
            const rekening = ref();
            const status_kerja = ref();
            const ukuran_baju = ref();
            const akhir_kontrak = ref();
            const no_npwp = ref();
            const no_bpjs_kesehatan = ref();
            const no_bpjs_ketenagakerjaan = ref();
            const kota_rekruitmen = ref();
            const kota_penugasan = ref();
            const email_internal= ref();
            const pengalaman_kerja_terakhir= ref();
            const jabatan_kerja_terakhir= ref();
            const foto = ref();
            
            const tgl_masuk = ref();
            const tgl_berakhir = ref();
            
            
            //save nama karyawan di add history organisasi
            const nama = ref();
            const idnya = ref();
            const jabatan = ref();
            const tgl_gabung_grup = ref();

            //save pelanggaran
            const catatan = ref();
            const tanggal = ref();
            const tingkatan = ref();
            const status = ref();

            //import excel
            const file_excel = ref();
            
            // define state search
            const search = ref('' || (new URL(document.location)).searchParams.get('search'));

            //tutup modal
            const tutupModalDetail = () => {
                showModalKarirDetail.value = false;
            }

            const tutupModalPelanggaran = () => {
                showModalPelanggaran.value = false;
            }

            const tutupModal = () => {
                showModalKarir.value = false;
            }

            const tutupModalImport = () => {
                showModalImport.value = false;
            }

            const importExcel = () => {
                showModalImport.value = true
            }

            const selectedTaskFile = (e) => {
                file_excel.value = e.target.files[0];
            }

            const storeExcel = () => {
                Inertia.post('/apps/karyawan/import',{
                    file: file_excel.value
                },{
                    onSuccess: () => {
                        tutupModalImport();
                    },
                });
            }

            const daftar_tingkatan = [
                { name: 'Ringan', value: 1 },
                { name: 'Sedang', value: 2 },
                { name: 'Serius', value: 3 },
                { name: 'Berat', value: 4 },
            ];

            const daftar_status = [
                { name: 'Teguran Lisan', value: 1 },
                { name: 'SP 1', value: 2 },
                { name: 'SP 2', value: 3 },
                { name: 'SP 3', value: 4 },
            ];

            //define method search
            const handleSearch = () => {
                Inertia.get('/apps/karyawan', {
                    //send params "q" with value from state "search"
                    search: search.value,
                });
            }

            //method destroy
            const destroy = (id) => {
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
                        Inertia.delete(`/apps/karyawan/${id}`);
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

            //method "detail"
            const detail = (kar) => {
                // var lahir = new Date(kar['tanggal_lahir'])
                // var today = new Date();
		        // var age = Math.floor((today-lahir) / (365.25 * 24 * 60 * 60 * 1000));
                
                //detail data pribadi
                id.value = kar['id']
                nama_lengkap.value = kar['nama_lengkap']
                nama_panggilan.value = kar['nama_panggilan']
                tempat_lahir.value = kar['tempat_lahir']
                tanggal_lahir.value = kar['tanggal_lahir']
                umur.value = kar['umur']
                gol_darah.value = kar['gol_darah']
                riwayat_penyakit.value = kar['riwayat_penyakit']
                alamat_ktp.value = kar['alamat_ktp']
                alamat_domisili.value = kar['alamat_domisili']
                jenis_kelamin.value = kar['jenis_kelamin']
                status_pernikahan.value = kar['status_pernikahan']
                status_keluarga.value = kar['status_keluarga']
                no_kk.value = kar['no_kk']
                nik_penduduk.value = kar['nik_penduduk']
                kode_pos.value = kar['kode_pos']
                pendidikan.value = kar['pendidikan']
                nama_sekolah.value = kar['nama_sekolah']
                jurusan.value = kar['jurusan']
                no_keluarga.value = kar['no_keluarga']
                hubungan_keluarga.value = kar['hubungan_keluarga']
                jenis_sosmed.value = kar['jenis_sosmed']
                nama_sosmed.value = kar['nama_sosmed']
                
                //detail data di perusahaan
                nik_karyawan.value = kar['nik_karyawan']
                divisi_id.value = kar['divisi'] == null ? "" : kar['divisi']['nama_divisi']
                pt_id.value = kar['perusahaan'] == null ? "" : kar['perusahaan']['nama_pt']
                jabatan_id.value = kar['jabatan'] == null ? "" : kar['jabatan']['nama_jabatan']
                jabatan.value = kar['jabatan']
                grade.value = kar['grade']
                tanggal_masuk.value = kar['tanggal_masuk']
                tanggal_kontrak.value = kar['tanggal_kontrak']
                masa_kerja_bulan.value = kar['masa_kerja_bulan']
                masa_kerja_tahun.value = kar['masa_kerja_tahun']
                masa_kontrak.value = kar['masa_kontrak']
                tanggal_karyawan_tetap.value = kar['tanggal_karyawan_tetap']
                akhir_kontrak.value = kar['akhir_kontrak']
                status_kerja.value = kar['status_kerja']
                komposisi_peran.value = kar['komposisi_peran']
                komposisi_generasi.value = kar['komposisi_generasi']
                no_bpjs_kesehatan.value = kar['no_bpjs_kesehatan']
                no_bpjs_ketenagakerjaan.value = kar['no_bpjs_ketenagakerjaan']
                email_pribadi.value = kar['email_pribadi']
                kota_rekruitmen.value = kar['kota_rekruitmen']
                kota_penugasan.value = kar['kota_penugasan']
                no_telp.value = kar['no_telp']
                no_wa.value = kar['no_wa']
                no_npwp.value = kar['no_npwp']
                email_internal.value = kar['email_internal']
                nama_bank.value = kar['nama_bank']
                rekening.value = kar['rekening']
                ukuran_baju.value = kar['ukuran_baju']
                pengalaman_kerja_terakhir.value = kar['pengalaman_kerja_terakhir']
                jabatan_kerja_terakhir.value = kar['jabatan_kerja_terakhir']
                foto.value = kar['foto']

                //menampilkan modal
                showModalKarirDetail.value = true
            }

            //method tambah riwayat organisasi
            const addKarir = (kar) => {
                nama.value = kar['nama_lengkap']
                idnya.value = kar['id']
                showModalKarir.value = true
            }

            //method tambah catatan pelanggaran
            const addPelanggaran = (kar) => {
                nama.value = kar['nama_lengkap']
                idnya.value = kar['id']
                console.log(kar['nama_lengkap'])
                showModalPelanggaran.value = true
            }

            //method "storeKarir"
            const storeKarir = () => {
                Inertia.post('/apps/karyawan/storeKarir', {
                    //data
                    karyawan_id : idnya.value,
                    pt_id :pt_id.value.id,
                    divisi_id :divisi_id.value.id,
                    jabatan_id :jabatan_id.value.id,
                    tgl_masuk : tgl_masuk.value,
                    tgl_berakhir : tgl_berakhir.value
                }, {
                    onSuccess: () => {
                        //show success alert
                        Swal.fire({
                            title: 'Success!',
                            text: 'Karir saved successfully.',
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 2000
                        });
                        tutupModal()
                    },
                });
            }

            //method "storePelanggaran"
            const storePelanggaran = () => {
                Inertia.post('/apps/karyawan/storePelanggaran', {
                    //data
                    karyawan_id : idnya.value,
                    catatan : catatan.value,
                    tingkatan: tingkatan.value.value,
                    status: status.value.value,
                    tanggal : tanggal.value,
                }, {
                    onSuccess: () => {
                        //show success alert
                        Swal.fire({
                            title: 'Success!',
                            text: 'Violation saved successfully.',
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 2000
                        });
                        tutupModalPelanggaran()
                    },
                });
            }

            //return
            return {
                search, handleSearch, destroy,
                detail, showModalKarir, tutupModalDetail, showModalKarirDetail, addKarir, tutupModal, id,
                nama_lengkap, nama_panggilan, nik_karyawan, riwayat_penyakit, status_kerja, divisi_id, umur, akhir_kontrak,
                pt_id, foto, tanggal_masuk, tanggal_kontrak, no_kk, nik_penduduk, kode_pos, grade, no_npwp, jenis_sosmed, nama_sosmed,
                jabatan, no_telp, no_wa, no_bpjs_kesehatan, no_bpjs_ketenagakerjaan, gol_darah,
                email_pribadi, tempat_lahir, tanggal_lahir, alamat_ktp, alamat_domisili, komposisi_peran, komposisi_generasi,
                jenis_kelamin, status_pernikahan, pendidikan, nama_sekolah, jurusan, kota_penugasan, kota_rekruitmen, status_keluarga,
                rekening, ukuran_baju, no_keluarga, hubungan_keluarga, jabatan_id, email_internal, pengalaman_kerja_terakhir, jabatan_kerja_terakhir, tanggal_karyawan_tetap, nama_bank, masa_kerja_bulan, masa_kerja_tahun, masa_kontrak,
                idnya, nama, tgl_gabung_grup, tgl_masuk, tgl_berakhir, storeKarir, status, daftar_status,
                showModalPelanggaran, addPelanggaran, tutupModalPelanggaran, storePelanggaran, catatan, tanggal, tingkatan, daftar_tingkatan,
                showModalImport, tutupModalImport, importExcel, selectedTaskFile, file_excel, storeExcel, selectedOption: null,
            }

        }
    }
</script>

<style>

</style>
