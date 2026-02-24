<div class="card">
    <div class="card-body p-0">
        <table class="table-modern">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Pegawai</th>
                    <th>Nomor ND</th>
                    <th>Periode Cuti</th>
                    <th>Jumlah</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $i => $item)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>
                        <div class="fw-semibold" style="font-size:13.5px;">{{ $item->pegawai->nama }}</div>
                        <div class="font-mono text-muted">{{ $item->pegawai->nip }}</div>
                    </td>
                    <td class="font-mono">{{ $item->nomor_nota_dinas }}</td>
                    <td>{{ $item->periode_cuti }}</td>
                    <td>
                        <span class="fw-bold" style="color:#3b5bdb;">{{ $item->cuti_tambahan_jumlah }} hari</span>
                    </td>
                    <td class="text-center">
                        @php
                            $badgeClass = match($item->status) {
                                'menunggu'  => 'badge-menunggu',
                                'disetujui' => 'badge-disetujui',
                                'ditolak'   => 'badge-ditolak',
                                default     => ''
                            };
                        @endphp
                        <span class="badge-status {{ $badgeClass }}">{{ ucfirst($item->status) }}</span>
                    </td>
                    <td class="text-center">
                        <div class="d-flex justify-content-center gap-1">
                            {{-- Detail --}}
                            <a href="{{ route('admin.persetujuan.show', $item->id) }}"
                               class="btn btn-sm btn-icon"
                               style="background:#eef2ff; color:#3b5bdb; border:none;"
                               title="Detail">
                                <i class="bi bi-eye"></i>
                            </a>

                            {{-- Batalkan (hanya jika sudah diproses) --}}
                            @if($item->status !== 'menunggu')
                                <form action="{{ route('admin.persetujuan.cancel', $item->id) }}"
                                      method="POST"
                                      onsubmit="return confirm('Batalkan persetujuan ini? {{ $item->status === 'disetujui' ? 'Sisa cuti tambahan akan dikembalikan.' : '' }}')">
                                    @csrf
                                    <button type="submit"
                                            class="btn btn-sm btn-icon"
                                            style="background:#fff8e1; color:#e65100; border:none;"
                                            title="Batalkan">
                                        <i class="bi bi-arrow-counterclockwise"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-5 text-muted">
                        <i class="bi bi-inbox" style="font-size:2.5rem; opacity:.3;"></i>
                        <p class="mt-2 mb-0" style="font-size:13px;">Tidak ada data permohonan</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>