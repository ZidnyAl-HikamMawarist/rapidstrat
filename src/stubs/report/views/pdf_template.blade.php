<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Rekapitulasi - {{ $startDate }} s/d {{ $endDate }}</title>
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
            color: #1e293b;
            margin: 0;
            padding: 24px;
            background-color: #ffffff;
        }

        /* Kop Surat Resmi Ujikom */
        .kop-surat {
            text-align: center;
            border-bottom: 3px double #000;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }
        .kop-surat h2 {
            margin: 0;
            font-size: 18px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .kop-surat p {
            margin: 2px 0;
            font-size: 11px;
            color: #475569;
        }

        /* Judul Laporan */
        .judul-laporan {
            text-align: center;
            margin-bottom: 20px;
        }
        .judul-laporan h3 {
            margin: 0;
            font-size: 14px;
            text-transform: uppercase;
            text-decoration: underline;
        }
        .judul-laporan span {
            font-size: 11px;
            color: #64748b;
        }

        /* Tabel Data */
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        table.data-table th, table.data-table td {
            border: 1px solid #000;
            padding: 7px 10px;
            text-align: left;
        }
        table.data-table th {
            background-color: #f1f5f9;
            font-size: 11px;
            text-transform: uppercase;
            font-weight: bold;
        }
        table.data-table tr:nth-child(even) {
            background-color: #f8fafc;
        }

        /* Area Tanda Tangan */
        .tanda-tangan-wrapper {
            width: 100%;
            margin-top: 40px;
        }
        .tanda-tangan-box {
            float: right;
            width: 220px;
            text-align: center;
        }
        .tanda-tangan-space {
            height: 70px;
        }

        @media print {
            body { padding: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body onload="if(window.location.search.includes('print=true')) window.print();">

    <!-- Tombol Cetak Browser jika dibuka langsung -->
    <div class="no-print" style="margin-bottom: 20px; text-align: right;">
        <button onclick="window.print()" style="background-color: #4f46e5; color: white; border: none; padding: 8px 16px; border-radius: 6px; cursor: pointer; font-size: 12px; font-weight: bold;">
            Cetak Dokumen Ini
        </button>
    </div>

    <!-- Kop Surat -->
    <div class="kop-surat">
        <h2>{{ config('app.name', 'SISTEM APLIKASI UJIKOM') }}</h2>
        <p>Lembaga Sertifikasi Profesi / Uji Kompetensi Keahlian Rekayasa Perangkat Lunak</p>
        <p>Alamat: Jl. Pendidikan No. 123 | Telp: (021) 555-0199 | Email: info@sekolah.sch.id</p>
    </div>

    <!-- Judul Dokumen -->
    <div class="judul-laporan">
        <h3>LAPORAN DATA REKAPITULASI</h3>
        <span>Periode: {{ date('d F Y', strtotime($startDate)) }} s/d {{ date('d F Y', strtotime($endDate)) }}</span>
    </div>

    <!-- Tabel Hasil -->
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 35px; text-align: center;">No</th>
                <th>Nama / Pengguna</th>
                <th>Email / Identitas</th>
                <th style="width: 100px;">Peran / Status</th>
                <th style="width: 120px;">Tanggal Dibuat</th>
            </tr>
        </thead>
        <tbody>
            @forelse($records as $index => $item)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td><strong>{{ $item->name ?? '-' }}</strong></td>
                    <td>{{ $item->email ?? '-' }}</td>
                    <td>{{ ucfirst($item->role ?? 'Data') }}</td>
                    <td>{{ $item->created_at ? $item->created_at->format('d/m/Y H:i') : '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center; padding: 20px; color: #64748b;">
                        Tidak ada data yang ditemukan untuk periode ini.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Tanda Tangan Penguji / Penanggung Jawab -->
    <div class="tanda-tangan-wrapper">
        <div class="tanda-tangan-box">
            <p style="margin: 0;">Kota Ujikom, {{ date('d F Y') }}</p>
            <p style="margin: 0; font-weight: bold;">Petugas / Penanggung Jawab,</p>
            <div class="tanda-tangan-space"></div>
            <p style="margin: 0; font-weight: bold; text-decoration: underline;">( ......................................... )</p>
            <p style="margin: 0; font-size: 10px; color: #64748b;">NIP / ID: 2026.UKK.RPL.001</p>
        </div>
        <div style="clear: both;"></div>
    </div>

</body>
</html>
