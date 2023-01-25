<?php

namespace App\Http\Controllers\Apps;

use App\Http\Controllers\Controller;
use App\Models\CatatanPelanggaran;
use App\Models\Karyawan;
use App\Models\MasterDivisi;
use App\Models\MasterPerusahaan;
use App\Models\RiwayatOrganisasi;
use Illuminate\Http\Request;
use Inertia\Inertia;

class KaryawanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $search = request()->search;
        //get karyawan
        $karyawan = Karyawan::with('perusahaan', 'divisi')->when($search, function($karyawan, $search) {
            $karyawan = $karyawan->where('nama_karyawan', 'like', '%'. $search . '%');
        })->latest()->paginate(10)->onEachSide(1);

        //get data divisi
        $divisi = MasterDivisi::where('status', 1)->get();
        //get data PT
        $perusahaan = MasterPerusahaan::where('status', 1)->get();

        //return inertia
        return Inertia::render('Apps/Karyawan/Index', [
            'karyawan'  => $karyawan,
            'divisi'    => $divisi,
            'pt'        => $perusahaan
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //get PT
        $perusahaan = MasterPerusahaan::where('status', 1)->get();
        //get divisi
        $divisi = MasterDivisi::where('status', 1)->get();

        return Inertia::render('Apps/Karyawan/Create', [
            'perusahaan'        => $perusahaan,
            'divisi'            => $divisi,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        /**
         * validate
         */
        $this->validate($request, [
            'nama_karyawan'                 => 'required',
            'nik_karyawan'                  => 'required',
            'status_kerja'                  => 'required',
            'divisi_id'                     => 'required',
            'pt_id'                         => 'required',
            // 'foto'                          => 'required',
            'tanggal_masuk'                 => 'required',
            'tanggal_kontrak'               => 'required',
            'no_kk'                         => 'required',
            'nik_penduduk'                  => 'required',
            'grade'                         => 'required',
            'jabatan'                       => 'required',
            'no_hp'                         => 'required',
            'no_wa'                         => 'required',
            // 'no_bpjs_kesehatan'             => 'required',
            // 'no_bpjs_ketenagakerjaan'       => 'required',
            'gol_darah'                     => 'required',
            'email'                         => 'required',
            'tempat_lahir'                  => 'required',
            'tanggal_lahir'                 => 'required',
            'alamat_ktp'                    => 'required',
            'alamat_domisili'               => 'required',
            'jenis_kelamin'                 => 'required',
            'status_pernikahan'             => 'required',
            'pendidikan'                    => 'required',
            'nama_sekolah'                  => 'required',
            'kab_penugasan'                 => 'required',
            // 'rekening'                      => 'required',
            'ukuran_baju'                   => 'required',
            'no_sdr'                        => 'required',
            'hubungan'                      => 'required',
        ]);

        //create karyawan
        Karyawan::create([
            'nama_karyawan'             => $request->nama_karyawan,
            'nik_karyawan'              => $request->nik_karyawan,
            'status_kerja'              => $request->status_kerja,
            'divisi_id'                 => $request->divisi_id,
            'pt_id'                     => $request->pt_id,
            'foto'                      => $request->foto,
            'tanggal_masuk'             => $request->tanggal_masuk,
            'tanggal_kontrak'           => $request->tanggal_kontrak,
            'no_kk'                     => $request->no_kk,
            'nik_penduduk'              => $request->nik_penduduk,
            'grade'                     => $request->grade,
            'jabatan'                   => $request->jabatan,
            'no_hp'                     => $request->no_hp,
            'no_wa'                     => $request->no_wa,
            'no_bpjs_kesehatan'         => $request->no_bpjs_kesehatan,
            'no_bpjs_ketenagakerjaan'   => $request->no_bpjs_ketenagakerjaan,
            'gol_darah'                 => $request->gol_darah,
            'email'                     => $request->email,
            'tempat_lahir'              => $request->tempat_lahir,
            'tanggal_lahir'             => $request->tanggal_lahir,
            'alamat_ktp'                => $request->alamat_ktp,
            'alamat_domisili'           => $request->alamat_domisili,
            'jenis_kelamin'             => $request->jenis_kelamin,
            'status_pernikahan'         => $request->status_pernikahan,
            'pendidikan'                => $request->pendidikan,
            'nama_sekolah'              => $request->nama_sekolah,
            'kab_penugasan'             => $request->kab_penugasan,
            'rekening'                  => $request->rekening,
            'ukuran_baju'               => $request->ukuran_baju,
            'no_sdr'                    => $request->no_sdr,
            'hubungan'                  => $request->hubungan
        ]);
        //upload image CEK BUKU DI CRUD KATEGORII YAAA
        // $image = $request->file('foto');
        // $image->storeAs('public/categories', $image->hashName());

        // if($request->file('image')->store('image', 'public'));

        // $karyawan->update([
        //     'qr_code'   => $file_name
        // ]);
        // $request->file('foto')->store('pictures');
        return redirect()->route('apps.karyawan.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(karyawan $karyawan)
    {
        //get PT
        $perusahaan = MasterPerusahaan::where('status', 1)->get();
        //get divisi
        $divisi = MasterDivisi::where('status', 1)->get();

        return Inertia::render('Apps/Karyawan/Edit', [
            'karyawan'          => $karyawan,
            'perusahaan'        => $perusahaan,
            'divisi'            => $divisi,
        ]);
    }

    /**
     *
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, karyawan $karyawan)
    {
        /**
         * validate
         */
        $this->validate($request, [
            'nama_karyawan'                 => 'required',
            'nik_karyawan'                  => 'required',
            'status_kerja'                  => 'required',
            'divisi_id'                     => 'required',
            'pt_id'                         => 'required',
            // 'foto'                          => 'required',
            'tanggal_masuk'                 => 'required',
            'tanggal_kontrak'               => 'required',
            'no_kk'                         => 'required',
            'nik_penduduk'                  => 'required',
            'grade'                         => 'required',
            'jabatan'                       => 'required',
            'no_hp'                         => 'required',
            'no_wa'                         => 'required',
            // 'no_bpjs_kesehatan'             => 'required',
            // 'no_bpjs_ketenagakerjaan'       => 'required',
            'gol_darah'                     => 'required',
            'email'                         => 'required',
            'tempat_lahir'                  => 'required',
            'tanggal_lahir'                 => 'required',
            'alamat_ktp'                    => 'required',
            'alamat_domisili'               => 'required',
            'jenis_kelamin'                 => 'required',
            'status_pernikahan'             => 'required',
            'pendidikan'                    => 'required',
            'nama_sekolah'                  => 'required',
            'kab_penugasan'                 => 'required',
            // 'rekening'                      => 'required',
            'ukuran_baju'                   => 'required',
            'no_sdr'                        => 'required',
            'hubungan'                      => 'required',
        ]);

        //update karyawan
        $karyawan->update([
            'nama_karyawan'             => $request->nama_karyawan,
            'nik_karyawan'              => $request->nik_karyawan,
            'status_kerja'              => $request->status_kerja,
            'divisi_id'                 => $request->divisi_id,
            'pt_id'                     => $request->pt_id,
            'foto'                      => $request->foto,
            'tanggal_masuk'             => $request->tanggal_masuk,
            'tanggal_kontrak'           => $request->tanggal_kontrak,
            'no_kk'                     => $request->no_kk,
            'nik_penduduk'              => $request->nik_penduduk,
            'grade'                     => $request->grade,
            'jabatan'                   => $request->jabatan,
            'no_hp'                     => $request->no_hp,
            'no_wa'                     => $request->no_wa,
            'no_bpjs_kesehatan'         => $request->no_bpjs_kesehatan,
            'no_bpjs_ketenagakerjaan'   => $request->no_bpjs_ketenagakerjaan,
            'gol_darah'                 => $request->gol_darah,
            'email'                     => $request->email,
            'tempat_lahir'              => $request->tempat_lahir,
            'tanggal_lahir'             => $request->tanggal_lahir,
            'alamat_ktp'                => $request->alamat_ktp,
            'alamat_domisili'           => $request->alamat_domisili,
            'jenis_kelamin'             => $request->jenis_kelamin,
            'status_pernikahan'         => $request->status_pernikahan,
            'pendidikan'                => $request->pendidikan,
            'nama_sekolah'              => $request->nama_sekolah,
            'kab_penugasan'             => $request->kab_penugasan,
            'rekening'                  => $request->rekening,
            'ukuran_baju'               => $request->ukuran_baju,
            'no_sdr'                    => $request->no_sdr,
            'hubungan'                  => $request->hubungan
        ]);

        // if($request->file('foto')->store('image', 'public'));
        //redirect
        return redirect()->route('apps.karyawan.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //find karyawan by ID
        $karyawan = Karyawan::findOrFail($id);
        //delete gambar di storage
        // $gambar_qrcode = $karyawan->qr_code;
        // //mengubah qrcode menjadi tulisan kecil dan menghilangkan .png
        // $lower_format = Str::lower($gambar_qrcode);
        // $new_format = substr($lower_format,0, -4);
        // // hapus salinan dari milon barcode
        // Storage::disk('local')->delete('public'.$new_format.'qrcode'.'.png');
        // Storage::disk('public')->delete('storage/','public'.$new_format.'qrcode'.'.png');
        // //hapus di storage link
        // Storage::disk('public')->delete('storage/', $gambar_qrcode);

        //delete karyawan
        $karyawan->delete();
        //redirect
        return redirect()->route('apps.karyawan.index');
    }

    public function storeKarir(Request $request)
    {
        /**
         * validate
         */
        $this->validate($request, [
            'karyawan_id'           => 'required',
            'divisi_id'             => 'required',
            'tgl_gabung_grup'       => 'required',
            'tgl_masuk'             => 'required',
        ]);

        //create riwayat organisasi
        RiwayatOrganisasi::create([
            'karyawan_id'       => $request->karyawan_id,
            'divisi_id'         => $request->divisi_id,
            'tgl_gabung_grup'   => $request->tgl_gabung_grup,
            'tgl_masuk'         => $request->tgl_masuk,
            'tgl_berakhir'      => $request->tgl_berakhir
        ]);

        //redirect
        return redirect()->route('apps.karyawan.index');
    }

    //list karir
    public function listKarir($id){
        // $lists = RiwayatOrganisasi::with('karyawan','divisi')->where('karyawan_id', $id)->latest()->get();


        // if($lists) {
        //     // return response()->json([
        //     //     'success' => true,
        //     //     'data'    => $list
        //     // ]);
        //     return Inertia::render('Apps/Karyawan/ListOrganisasi', [
        //         'lists'         => $lists,
        //         'nama'          => $nama_karyawan
        //     ]);
        // }

        // return response()->json([
        //     'success' => false,
        //     'data'    => null
        // ]);

        $search = request()->search;
        //get list
        // $lists = RiwayatOrganisasi::with('karyawan', 'divisi')->whereHas('divisi', function($q) use($search, $id){
        //     $q->where('nama_divisi', 'like', '%'. $search . '%');
        //     })->where('karyawan_id', $id)->latest()->paginate(10)->onEachSide(1);

        // $nama_karyawan = Karyawan::where('id', $id)->first()->nama_karyawan;

        // return Inertia::render('Apps/Karyawan/ListOrganisasi', [
        //     'lists'         => $lists,
        //     'nama'          => $nama_karyawan
        // ]);
    }

    public function storePelanggaran(Request $request)
    {
        /**
         * validate
         */
        $this->validate($request, [
            'karyawan_id'           => 'required',
            'catatan'               => 'required',
            'tanggal'               => 'required',
            'tingkatan'             => 'required'
        ]);

        //create pelanggaran
        CatatanPelanggaran::create([
            'karyawan_id'       => $request->karyawan_id,
            'catatan'           => $request->catatan,
            'tanggal'           => $request->tanggal,
            'tingkatan'         => $request->tingkatan
        ]);

        //redirect
        return redirect()->route('apps.karyawan.index');
    }
}
