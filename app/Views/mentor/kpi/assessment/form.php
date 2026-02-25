<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<?php
$namaBulan = [
    1 => 'Januari',
    2 => 'Februari',
    3 => 'Maret',
    4 => 'April',
    5 => 'Mei',
    6 => 'Juni',
    7 => 'Juli',
    8 => 'Agustus',
    9 => 'September',
    10 => 'Oktober',
    11 => 'November',
    12 => 'Desember'
];
?>

<!-- Breadcrumb -->
<div class="row mb-4">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="<?= base_url('kpi/assessment?bulan=' . $bulan . '&tahun=' . $tahun) ?>">Penilaian KPI</a></li>
                <li class="breadcrumb-item active"><?= esc($intern['nama_lengkap']) ?></li>
            </ol>
        </nav>
    </div>
</div>

<!-- Intern Info -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar avatar-lg">
                        <?php if (!empty($intern['foto'])): ?>
                            <img src="<?= base_url('uploads/users/' . $intern['foto']) ?>" alt="" class="rounded-circle">
                        <?php else: ?>
                            <span class="avatar-initial rounded-circle bg-label-primary fs-4">
                                <?= strtoupper(substr($intern['nama_lengkap'], 0, 1)) ?>
                            </span>
                        <?php endif; ?>
                    </div>
                    <div>
                        <h4 class="mb-1"><?= esc($intern['nama_lengkap']) ?></h4>
                        <div class="d-flex gap-3 text-muted">
                            <span><i class="ri-id-card-line me-1"></i><?= esc($intern['nik']) ?></span>
                            <span><i class="ri-building-line me-1"></i><?= esc($intern['nama_divisi'] ?? '-') ?></span>
                            <span><i class="ri-calendar-line me-1"></i>Periode: <?= $namaBulan[$bulan] ?> <?= $tahun ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Auto-calculated Scores (Read-only) -->
<?php if (!empty($autoAssessments)): ?>
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="ri-flashlight-line me-2"></i>Skor Otomatis (Read-only)</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Indikator</th>
                                <th class="text-center">Kategori</th>
                                <th class="text-center">Bobot</th>
                                <th class="text-center">Nilai Raw</th>
                                <th class="text-center">Nilai Weighted</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($autoAssessments as $a): ?>
                                <tr>
                                    <td><?= esc($a['nama_indicator']) ?></td>
                                    <td class="text-center"><span class="badge bg-label-info"><?= ucfirst($a['kategori']) ?></span></td>
                                    <td class="text-center"><?= $a['bobot'] ?>%</td>
                                    <td class="text-center"><?= number_format($a['nilai_raw'], 2) ?></td>
                                    <td class="text-center"><strong><?= number_format($a['nilai_weighted'], 2) ?></strong></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<!-- Manual Assessment Form -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="ri-edit-2-line me-2"></i>Penilaian Manual</h5>
                <small class="text-muted">Berikan skor 1-5 untuk setiap indikator manual</small>
            </div>
            <div class="card-body">
                <form id="assessmentForm">
                    <input type="hidden" name="id_user" value="<?= $intern['id_user'] ?>">
                    <input type="hidden" name="bulan" value="<?= $bulan ?>">
                    <input type="hidden" name="tahun" value="<?= $tahun ?>">

                    <?php foreach ($manualIndicators as $ind): ?>
                        <?php
                        $existing = $existingAssessments[$ind['id_indicator']] ?? null;
                        $existingScore = $existing ? ($existing['nilai_raw'] / 20) : 0; // Convert 100-scale back to 1-5
                        ?>
                        <div class="card border mb-3">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-md-4">
                                        <h6 class="mb-1"><?= esc($ind['nama_indicator']) ?></h6>
                                        <small class="text-muted">
                                            <span class="badge bg-label-info me-1"><?= ucfirst($ind['kategori']) ?></span>
                                            Bobot: <?= $ind['bobot'] ?>%
                                        </small>
                                        <?php if (!empty($ind['deskripsi'])): ?>
                                            <p class="text-muted small mt-1 mb-0"><?= esc($ind['deskripsi']) ?></p>
                                        <?php endif; ?>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Skor (1-5)</label>
                                        <div class="d-flex gap-2 align-items-center">
                                            <?php for ($s = 1; $s <= 5; $s++): ?>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input score-radio" type="radio"
                                                        name="scores[<?= $ind['id_indicator'] ?>]"
                                                        value="<?= $s ?>"
                                                        id="score_<?= $ind['id_indicator'] ?>_<?= $s ?>"
                                                        <?= round($existingScore) == $s ? 'checked' : '' ?>>
                                                    <label class="form-check-label" for="score_<?= $ind['id_indicator'] ?>_<?= $s ?>">
                                                        <?= $s ?>
                                                    </label>
                                                </div>
                                            <?php endfor; ?>
                                        </div>
                                        <small class="text-muted">1=Sangat Kurang, 3=Cukup, 5=Sangat Baik</small>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Catatan (opsional)</label>
                                        <textarea name="catatan[<?= $ind['id_indicator'] ?>]"
                                            class="form-control" rows="2"
                                            placeholder="Catatan untuk intern..."><?= esc($existing['catatan'] ?? '') ?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary" id="btnSubmit">
                            <i class="ri-save-line me-1"></i> Simpan Penilaian
                        </button>
                        <a href="<?= base_url('kpi/assessment?bulan=' . $bulan . '&tahun=' . $tahun) ?>" class="btn btn-outline-secondary">
                            <i class="ri-arrow-left-line me-1"></i> Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    document.getElementById('assessmentForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);

        // Validate at least one score is selected
        const radios = document.querySelectorAll('.score-radio:checked');
        if (radios.length === 0) {
            Swal.fire('Perhatian', 'Pilih minimal satu skor penilaian', 'warning');
            return;
        }

        Swal.fire({
            title: 'Simpan Penilaian?',
            text: 'Penilaian akan disimpan untuk <?= esc($intern['nama_lengkap']) ?>',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Simpan',
            cancelButtonText: 'Batal',
            showLoaderOnConfirm: true,
            preConfirm: () => {
                return csrfFetch('<?= base_url('kpi/assessment/submit') ?>', {
                    method: 'POST',
                    body: new URLSearchParams(new FormData(document.getElementById('assessmentForm')))
                }).then(r => r.json());
            }
        }).then(result => {
            if (result.isConfirmed) {
                const data = result.value;
                Swal.fire({
                    icon: data.success ? 'success' : 'error',
                    title: data.success ? 'Berhasil!' : 'Gagal',
                    text: data.message,
                }).then(() => {
                    if (data.success) {
                        window.location.href = '<?= base_url('kpi/assessment?bulan=' . $bulan . '&tahun=' . $tahun) ?>';
                    }
                });
            }
        });
    });
</script>
<?= $this->endSection() ?>