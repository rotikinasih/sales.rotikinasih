<html>
<head>
    <title>PDF Detail Karyawan</title>
    <style>
        td{
            font-size:15px;
        }

        .row {
            display: flex;
            flex-wrap: wrap;
        }
        .col-6 {
            width: 100%;
        }
    </style>
</head>
<body>
    <h3>Detail Data Karyawan</h3>
    <div class="content">
        <div class="row">
            <div class="col-6">
                <table class="table-form" border="0" width="100%" cellpadding="0" cellspacing="0">
                    <tr>
                        <td width="15%">Nama Lengkap</td>
                        <td width="1%">:</td>
                        <td>{{ $detail_karyawan->nama_lengkap }}</td>
                    </tr>
                    <tr>
                        <td width="15%">Nama Panggilan</td>
                        <td width="1%">:</td>
                        <td>{{ $detail_karyawan->nama_panggilan }}</td>
                    </tr>
                </table>
            </div>

            <div class="col-6">
                <table class="table-form" border="0" width="100%" cellpadding="0" cellspacing="0">
                    <tr>
                        <td width="15%">NIK ( Karyawan)</td>
                        <td width="1%">:</td>
                        <td>{{ $detail_karyawan->nik_karyawan }}</td>
                    </tr>
                    <tr>
                        <td width="15%">Entitas</td>
                        <td width="1%">:</td>
                        <td>{{ $detail_karyawan->perusahaan->nama_pt }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</body>
</html>