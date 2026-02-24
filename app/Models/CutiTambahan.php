<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class CutiTambahan extends Model
{
    protected $table = 'cuti_tambahan';

    protected $fillable = [
        'pegawai_id',
        'nomor_nota_dinas',
        'tanggal_nota_dinas',
        'cuti_tambahan_jumlah',
        'cuti_tahunan_jumlah',
        'tanggal_cuti',
        'tanggal_cuti_tambahan',
        'tanggal_cuti_tahunan',
        'alasan_cuti',
        'alamat_cuti',
        'status',
        'catatan_admin',
    ];

    protected $casts = [
        'tanggal_nota_dinas'    => 'date',
        'tanggal_cuti'          => 'array',
        'tanggal_cuti_tambahan' => 'array',
        'tanggal_cuti_tahunan'  => 'array',
        'cuti_tahunan_jumlah'   => 'float', // ← float agar support 0.5
    ];

    // ──────────── HELPER INTERNAL ────────────

    /**
     * Normalisasi item cuti tahunan.
     * Support format lama (string) dan baru (array {tanggal, sesi}).
     */
    private function normalizeTahunanItem(mixed $item): array
    {
        if (is_string($item)) {
            return ['tanggal' => $item, 'sesi' => 'penuh'];
        }
        return [
            'tanggal' => $item['tanggal'],
            'sesi'    => $item['sesi'] ?? 'penuh',
        ];
    }

    private function sesiLabel(string $sesi): string
    {
        return match($sesi) {
            'pagi'  => ' (Pagi)',
            'siang' => ' (Siang)',
            default => '',
        };
    }

    // ──────────── RELASI ────────────

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class);
    }

    // ──────────── ACCESSORS ────────────

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'menunggu'  => '<span class="badge bg-warning text-dark">Menunggu</span>',
            'disetujui' => '<span class="badge bg-success">Disetujui</span>',
            'ditolak'   => '<span class="badge bg-danger">Ditolak</span>',
            default     => '<span class="badge bg-secondary">Unknown</span>',
        };
    }

    /**
     * Semua tanggal cuti (gabungan tambahan + tahunan)
     */
    public function getPeriodeCutiAttribute(): string
    {
        if (empty($this->tanggal_cuti)) return '-';

        return collect($this->tanggal_cuti)
            ->map(fn($tgl) => Carbon::parse($tgl)->translatedFormat('d F Y'))
            ->join(', ');
    }

    public function getPeriodeCutiTambahanAttribute(): string
    {
        if (empty($this->tanggal_cuti_tambahan)) return '-';

        return collect($this->tanggal_cuti_tambahan)
            ->map(fn($tgl) => Carbon::parse($tgl)->translatedFormat('d F Y'))
            ->join(', ');
    }

    public function getPeriodeCutiTahunanAttribute(): string
    {
        if (empty($this->tanggal_cuti_tahunan)) return '-';

        return collect($this->tanggal_cuti_tahunan)
            ->map(function ($item) {
                $normalized = $this->normalizeTahunanItem($item);
                return Carbon::parse($normalized['tanggal'])->translatedFormat('d F Y')
                    . $this->sesiLabel($normalized['sesi']);
            })
            ->join(', ');
    }

    // ── Tanggal Mulai / Selesai (Gabungan) ──

    public function getTanggalMulaiAttribute(): ?string
    {
        if (empty($this->tanggal_cuti)) return null;
        return Carbon::parse(collect($this->tanggal_cuti)->first())->translatedFormat('d F Y');
    }

    public function getTanggalSelesaiAttribute(): ?string
    {
        if (empty($this->tanggal_cuti)) return null;
        return Carbon::parse(collect($this->tanggal_cuti)->last())->translatedFormat('d F Y');
    }

    // ── Tanggal Mulai / Selesai (Tambahan) ──

    public function getTanggalMulaiTambahanAttribute(): ?string
    {
        if (empty($this->tanggal_cuti_tambahan)) return null;
        return Carbon::parse(collect($this->tanggal_cuti_tambahan)->first())->translatedFormat('d F Y');
    }

    public function getTanggalSelesaiTambahanAttribute(): ?string
    {
        if (empty($this->tanggal_cuti_tambahan)) return null;
        return Carbon::parse(collect($this->tanggal_cuti_tambahan)->last())->translatedFormat('d F Y');
    }

    // ── Tanggal Mulai / Selesai (Tahunan) ──

    public function getTanggalMulaiTahunanAttribute(): ?string
    {
        if (empty($this->tanggal_cuti_tahunan)) return null;
        $first = $this->normalizeTahunanItem(collect($this->tanggal_cuti_tahunan)->first());
        return Carbon::parse($first['tanggal'])->translatedFormat('d F Y');
    }

    public function getTanggalSelesaiTahunanAttribute(): ?string
    {
        if (empty($this->tanggal_cuti_tahunan)) return null;
        $last = $this->normalizeTahunanItem(collect($this->tanggal_cuti_tahunan)->last());
        return Carbon::parse($last['tanggal'])->translatedFormat('d F Y');
    }
}