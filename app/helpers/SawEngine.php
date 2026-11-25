<?php
/**
 * Simple Additive Weighting (SAW) Engine
 * Berdasarkan BLUEPRINT.docx & Analisis Rancang Bangun
 */

class SawEngine {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Jalankan perhitungan SAW dengan filter Kategori dan Budget
     */
    public function calculate(?int $kategoriId = null, ?float $minBudget = null, ?float $maxBudget = null): array {
        // 1. Ambil kriteria dan bobot W
        $stmtCrit = $this->pdo->query("SELECT * FROM kriteria ORDER BY id ASC");
        $criteria = $stmtCrit->fetchAll();

        if (empty($criteria)) {
            return [
                'success' => false,
                'message' => 'Kriteria penilaian belum dikonfigurasi.',
                'results' => []
            ];
        }

        // 2. Filter alternatif berdasarkan kategori dan rentang harga
        $sql = "SELECT p.*, k.nama_kategori 
                FROM produk_alternatif p
                JOIN kategori_mebel k ON p.kategori_id = k.id
                WHERE 1=1";
        $params = [];

        if ($kategoriId !== null && $kategoriId > 0) {
            $sql .= " AND p.kategori_id = ?";
            $params[] = $kategoriId;
        }

        if ($minBudget !== null && $minBudget > 0) {
            $sql .= " AND p.harga >= ?";
            $params[] = $minBudget;
        }

        if ($maxBudget !== null && $maxBudget > 0) {
            $sql .= " AND p.harga <= ?";
            $params[] = $maxBudget;
        }

        $sql .= " ORDER BY p.id ASC";
        $stmtProd = $this->pdo->prepare($sql);
        $stmtProd->execute($params);
        $alternatives = $stmtProd->fetchAll();

        if (empty($alternatives)) {
            return [
                'success' => true,
                'total_alternatives' => 0,
                'criteria' => $criteria,
                'matrix_x' => [],
                'matrix_r' => [],
                'results' => [],
                'message' => 'Tidak ditemukan mebel aluminium yang sesuai dengan rentang anggaran tersebut.'
            ];
        }

        // 3. Ambil data nilai matriks keputusan X untuk alternatif terpilih
        $altIds = array_column($alternatives, 'id');
        $placeholders = implode(',', array_fill(0, count($altIds), '?'));
        
        $stmtVals = $this->pdo->prepare("
            SELECT n.*, k.kode_kriteria 
            FROM nilai_alternatif n
            JOIN kriteria k ON n.kriteria_id = k.id
            WHERE n.produk_id IN ($placeholders)
        ");
        $stmtVals->execute($altIds);
        $rawValues = $stmtVals->fetchAll();

        // Susun Matriks Keputusan X[produk_id][kriteria_id]
        $matrixX = [];
        foreach ($alternatives as $alt) {
            foreach ($criteria as $c) {
                $matrixX[$alt['id']][$c['id']] = 0.0;
            }
        }
        foreach ($rawValues as $rv) {
            $matrixX[$rv['produk_id']][$rv['kriteria_id']] = (float)$rv['nilai'];
        }

        // 4. Cari nilai Max (untuk Benefit) dan Min (untuk Cost) pada tiap kriteria j
        $extrema = []; // [kriteria_id => ['min' => x, 'max' => y]]
        foreach ($criteria as $c) {
            $cId = $c['id'];
            $colValues = [];
            foreach ($alternatives as $alt) {
                $colValues[] = $matrixX[$alt['id']][$cId];
            }
            $extrema[$cId] = [
                'max' => !empty($colValues) ? max($colValues) : 1,
                'min' => !empty($colValues) ? min($colValues) : 1,
            ];
        }

        // 5. Normalisasi Matriks R:
        // Benefit: r_ij = x_ij / max(x_j)
        // Cost:    r_ij = min(x_j) / x_ij
        $matrixR = [];
        $weightedR = [];
        $scoresV = [];

        foreach ($alternatives as $alt) {
            $pId = $alt['id'];
            $vScore = 0.0;

            foreach ($criteria as $c) {
                $cId = $c['id'];
                $val = $matrixX[$pId][$cId];
                $weight = (float)$c['bobot'];

                if (strtolower($c['jenis']) === 'benefit') {
                    $maxVal = $extrema[$cId]['max'];
                    $r = ($maxVal > 0) ? ($val / $maxVal) : 0;
                } else {
                    // Cost
                    $minVal = $extrema[$cId]['min'];
                    $r = ($val > 0) ? ($minVal / $val) : 0;
                }

                $matrixR[$pId][$cId] = round($r, 4);
                $weighted = $weight * $r;
                $weightedR[$pId][$cId] = round($weighted, 4);
                $vScore += $weighted;
            }

            $scoresV[$pId] = round($vScore, 4);
        }

        // 6. Buat struktur hasil perangkingan
        $rankedResults = [];
        foreach ($alternatives as $alt) {
            $pId = $alt['id'];
            $vScore = $scoresV[$pId];
            $matchPercent = round($vScore * 100, 2);

            $rankedResults[] = [
                'produk' => $alt,
                'score_v' => $vScore,
                'match_percent' => $matchPercent,
                'raw_x' => $matrixX[$pId],
                'normalized_r' => $matrixR[$pId],
                'weighted_r' => $weightedR[$pId],
            ];
        }

        // Urutkan dari V terbesar ke terkecil
        usort($rankedResults, function($a, $b) {
            if ($a['score_v'] == $b['score_v']) return 0;
            return ($a['score_v'] > $b['score_v']) ? -1 : 1;
        });

        // Berikan nomor peringkat (ranking)
        $rank = 1;
        foreach ($rankedResults as &$res) {
            $res['rank'] = $rank++;
        }
        unset($res);

        return [
            'success' => true,
            'total_alternatives' => count($alternatives),
            'criteria' => $criteria,
            'extrema' => $extrema,
            'matrix_x' => $matrixX,
            'matrix_r' => $matrixR,
            'weighted_r' => $weightedR,
            'results' => $rankedResults,
            'filter' => [
                'kategori_id' => $kategoriId,
                'min_budget' => $minBudget,
                'max_budget' => $maxBudget,
            ]
        ];
    }
}

