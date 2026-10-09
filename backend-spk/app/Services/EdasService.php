<?php

namespace App\Services;

class EdasService {
    public function calculate($destinations, $weights) {
        if (empty($destinations))
            return[];

        // 1. Bentuk Matriks Keputusan Awal
        $matrix = [];
        foreach ($destinations as $dest) {
            $matrix[] = [
                'id' => $dest->id,
                'name' => $dest->name,
                'c1' => $dest->c1_population, // Benefit
                'c2' => $dest->c2_urgency, // Benefit
                'c3' => $dest->c3_distance, // Cost
            ];
        }

        // 2. Menghitung Solusi Rata-rata (AV)
        $n = count($matrix);
        $av = [
            'c1' => array_sum(array_column($matrix, 'c1')) / $n,
            'c2' => array_sum(array_column($matrix, 'c2')) / $n,
            'c3' => array_sum(array_column($matrix, 'c3')) / $n
        ];

        // 3. Menghitung PDA dan NDA
        $pda = [];
        $nda = [];
        foreach ($matrix as $i => $row) {
            // Untuk Benefit (C1 & C2) -> PDA = (Row - AV) / AV | NDA = (AV - Row) / AV
            $pda[$i]['c1'] = ($row['c1'] >= $av['c1']) ? ($row['c1'] - $av['c1']) / $av['c1'] : 0;
            $nda[$i]['c1'] = ($av['c1'] > $row['c1']) ? ($av['c1'] - $row['c1']) / $av['c1'] : 0;
            
            $pda[$i]['c2'] = ($row['c2'] >= $av['c2']) ? ($row['c2'] - $av['c2']) / $av['c2'] : 0;
            $nda[$i]['c2'] = ($av['c2'] > $row['c2']) ? ($av['c2'] - $row['c2']) / $av['c2'] : 0;

            // Untuk Cost (C3) -> PDA = (AV - Row) / AV | NDA = (Row - AV) / AV
            $pda[$i]['c3'] = ($av['c3'] >= $row['c3']) ? ($av['c3'] - $row['c3']) / $av['c3'] : 0;
            $nda[$i]['c3'] = ($row['c3'] > $av['c3']) ? ($row['c3'] - $av['c3']) / $av['c3'] : 0;

        }

        // 4. Menghitung Jumlah Terbobot SP & SN
        $sp = [];
        $sn = [];
        foreach ($matrix as $i => $row) {
            $sp[$i] = ($pda[$i]['c1'] * $weights['c1']) + 
                        ($pda[$i]['c2'] * $weights['c2']) +
                        ($pda[$i]['c3'] * $weights['c3']);
            $sn[$i] = ($nda[$i]['c1'] * $weights['c1']) + 
                        ($nda[$i]['c2'] * $weights['c2']) +
                        ($nda[$i]['c3'] * $weights['c3']);
        }

        // 5. Normalisasi NSP & NSN
        $maxSp = max($sp) ?: 1; // ?: 1 untuk mencegah error dibagi No1
        $maxSn = max($sn) ?: 1;

        $nsp = [];
        $nsn = [];
        foreach ($sp as $i => $val) {
            $nsp[$i] = $val / $maxSp;
            $nsn[$i] = 1 - ($sn[$i] / $maxSn);
        }

        // 6. Menghitung Appraisal Score (AS) dan Simpan Dtail
        $results = [];
        foreach ($matrix as $i => $row) {
            $as = ($nsp[$i] + $nsn[$i]) / 2;

            $results[] = [
                'id' => $row['id'],
                'name' => $row['name'],
                'score' => $as,
                'details' => [
                    'c1' => $row['c1'],
                    'c2' => $row['c2'],
                    'c3' => $row['c3'],
                ]
            ];
        }

        // 7. Urutkan hasil berdasarkan AS (Tertinggi ke Terendah)
        usort($results, function($a, $b) {
            return $b['score'] <=> $a['score'];
        });

        // 8. Return seluruh step (agar Frontend bisa merender tabel transparan)
        return [
            'matrix' => $matrix,
            'average' => $av,
            'pda' => $pda,
            'nda' => $nda,
            'sp' => $sp,
            'sn' => $sn,
            'nsp' => $nsp,
            'nsn' => $nsn,
            'ranking' => $results
        ];
    }
}