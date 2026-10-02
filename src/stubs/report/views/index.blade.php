@extends('layouts.admin')

@section('title', 'Laporan & Rekapitulasi')
@section('page-title', 'Cetak Laporan')
@section('page-subtitle', 'Filter dan export laporan data dalam format PDF')

@section('content')
<div class="space-y-6">

    <!-- Filter Card (Light Mode) -->
    <div class="p-6 rounded-2xl bg-white border border-slate-200/80 shadow-sm">
        <h3 class="text-sm font-bold text-slate-900 mb-1">Filter Periode Laporan</h3>
        <p class="text-xs text-slate-500 mb-5">Tentukan rentang tanggal data yang ingin dicetak</p>

        <form action="{{ route('reports.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 items-end">
            <!-- Tanggal Awal -->
            <div>
                <label for="start_date" class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-2">Tanggal Mulai</label>
                <input type="date" id="start_date" name="start_date" value="{{ $startDate }}"
                       class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-slate-800 text-xs focus:bg-white focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 outline-none transition font-medium">
            </div>

            <!-- Tanggal Akhir -->
            <div>
                <label for="end_date" class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-2">Tanggal Selesai</label>
                <input type="date" id="end_date" name="end_date" value="{{ $endDate }}"
                       class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-slate-800 text-xs focus:bg-white focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 outline-none transition font-medium">
            </div>

            <!-- Tombol Filter -->
            <div>
                <button type="submit" class="w-full py-2.5 px-4 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-xl transition border border-slate-200 flex items-center justify-center gap-2">
                    <i class="fa-solid fa-filter text-indigo-600"></i>
                    <span>Terapkan Filter</span>
                </button>
            </div>

            <!-- Tombol Export PDF & Print -->
            <div class="flex items-center gap-2">
                <a href="{{ route('reports.pdf', ['start_date' => $startDate, 'end_date' => $endDate]) }}" target="_blank"
                   class="flex-1 py-2.5 px-3 bg-gradient-to-r from-rose-600 to-red-600 hover:from-rose-500 hover:to-red-500 text-white text-xs font-semibold rounded-xl shadow-md shadow-rose-600/25 transition flex items-center justify-center gap-1.5">
                    <i class="fa-solid fa-file-pdf"></i>
                    <span>Export PDF</span>
                </a>
                <a href="{{ route('reports.print', ['start_date' => $startDate, 'end_date' => $endDate]) }}" target="_blank"
                   class="py-2.5 px-3 bg-white hover:bg-slate-50 text-slate-700 text-xs font-semibold rounded-xl border border-slate-200 shadow-sm transition flex items-center justify-center gap-1.5" title="Print Langsung">
                    <i class="fa-solid fa-print text-slate-500"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Data Preview Table (Light Mode) -->
    <div class="rounded-2xl bg-white border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="text-sm font-bold text-slate-900">Preview Data Rekapitulasi</h3>
                <p class="text-xs text-slate-500 mt-0.5">Periode: <strong class="text-indigo-600">{{ date('d M Y', strtotime($startDate)) }}</strong> s/d <strong class="text-indigo-600">{{ date('d M Y', strtotime($endDate)) }}</strong></p>
            </div>
            <span class="text-xs text-slate-500 font-medium">Total Ditemukan: <strong class="text-slate-800">{{ $records->total() ?? count($records) }} data</strong></span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 uppercase tracking-wider text-[10px] border-b border-slate-200 font-bold">
                    <tr>
                        <th class="py-3 px-5 w-12 text-center">No</th>
                        <th class="py-3 px-5">Nama / Identitas</th>
                        <th class="py-3 px-5">Email / Keterangan</th>
                        <th class="py-3 px-5">Peran / Status</th>
                        <th class="py-3 px-5">Tanggal Dibuat</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($records as $index => $item)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="py-3.5 px-5 text-center text-slate-400 font-mono font-medium">{{ $records->firstItem() ? $records->firstItem() + $index : $index + 1 }}</td>
                            <td class="py-3.5 px-5 font-bold text-slate-900">{{ $item->name ?? '-' }}</td>
                            <td class="py-3.5 px-5 text-slate-600">{{ $item->email ?? '-' }}</td>
                            <td class="py-3.5 px-5">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-indigo-50 text-indigo-700 border border-indigo-200">
                                    {{ ucfirst($item->role ?? 'Data') }}
                                </span>
                            </td>
                            <td class="py-3.5 px-5 text-slate-500">{{ $item->created_at ? $item->created_at->format('d/m/Y H:i') : '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-10 text-center text-slate-400">
                                <i class="fa-solid fa-inbox text-3xl mb-2 block text-slate-300"></i>
                                Tidak ada data pada rentang tanggal yang dipilih.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(method_exists($records, 'links'))
            <div class="p-4 border-t border-slate-100">
                {{ $records->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
