<?php
session_start();
include "kalender_koneksiYunifa.php";
require('fpdf/fpdf.php');

$filter_yunifa = $_GET['filter'] ?? 'tahun'; 
$tahun_yunifa  = $_GET['tahun'] ?? date('Y');

$namaBulan_yunifa = [
    1=>'Januari','Februari','Maret','April','Mei','Juni',
    'Juli','Agustus','September','Oktober','November','Desember'
];

$pdf_yunifa = new FPDF('L', 'mm', 'A4');
$pdf_yunifa->AddPage();

$pdf_yunifa->Image('fpdf/kop_surat_resmi.jpg', 30, 8, 230);
$pdf_yunifa->Ln(50); 

function cetakKeterangan_yunifa($pdf, $koneksi, $xStart, $mode, $filter_params = []) {
    $pdf->Ln(5);
    $pdf->SetX($xStart);
    $pdf->SetFont('Arial','B',9);
    $pdf->Cell(0, 5, "Keterangan Kegiatan:", 0, 1);

    if ($mode === 'harian') {
        $tanggal = $filter_params['tanggal'];
        $q = mysqli_query($koneksi, "
            SELECT judul_yunifa, tanggal_mulai_yunifa, waktu_mulai_yunifa, waktu_selesai_yunifa, warna_yunifa 
            FROM kegiatan_yunifa 
            WHERE tanggal_mulai_yunifa = '$tanggal'
            ORDER BY waktu_mulai_yunifa ASC
        ");

        $pdf->SetFont('Arial','',8);
        $no = 1;
        while ($row = mysqli_fetch_assoc($q)) {
            list($r, $g, $b) = sscanf($row['warna_yunifa'], "#%02x%02x%02x");
            $pdf->SetFillColor($r, $g, $b);
            $pdf->SetX($xStart);
            $pdf->Cell(5, 5, '', 1, 0, 'C', true); // kotak warna
            $pdf->Cell(3, 5, '', 0, 0);
            $tgl_fmt = date('d/m/Y', strtotime($row['tanggal_mulai_yunifa']));
            $waktu   = $row['waktu_mulai_yunifa'] . ' - ' . $row['waktu_selesai_yunifa'];
            $pdf->SetTextColor(0,0,0);
            $pdf->Cell(160, 5, $no . '. ' . $row['judul_yunifa'], 0, 0, 'L');
            $pdf->Cell(60,  5, $tgl_fmt . '  ' . $waktu, 0, 1, 'L');
            $no++;
        }

    } else {
        if ($mode === 'tahun') {
            $tahun = $filter_params['tahun'];
            $tgl_dari  = "$tahun-01-01";
            $tgl_sampai = "$tahun-12-31";
        } else {
            $tgl_dari   = $filter_params['tgl_dari'];
            $tgl_sampai = $filter_params['tgl_sampai'];
        }

        $q = mysqli_query($koneksi, "
            SELECT judul_yunifa, tanggal_mulai_yunifa, tanggal_selesai_yunifa, warna_yunifa
            FROM kegiatan_yunifa
            WHERE tanggal_mulai_yunifa BETWEEN '$tgl_dari' AND '$tgl_sampai'
            ORDER BY tanggal_mulai_yunifa ASC
        ");

        $smt1 = []; 
        $smt2 = []; 

        while ($row = mysqli_fetch_assoc($q)) {
            $bln = (int)date('n', strtotime($row['tanggal_mulai_yunifa']));
            if ($bln >= 1 && $bln <= 6) {
                $smt1[] = $row;
            } else {
                $smt2[] = $row;
            }
        }

        $pdf->SetFont('Arial','',8);

        // Fungsi bantu cetak per grup
        $cetakGrup = function($label, $data) use ($pdf, $xStart) {
            if (empty($data)) return;

            $pdf->SetX($xStart);
            $pdf->SetFont('Arial','BI',8);
            $pdf->SetFillColor(220, 220, 220);
            $pdf->Cell(230, 5, "  $label", 0, 1, 'L', true);
            $pdf->SetFont('Arial','',8);

            $no = 1;
            foreach ($data as $row) {
                list($r, $g, $b) = sscanf($row['warna_yunifa'], "#%02x%02x%02x");
                $pdf->SetFillColor($r, $g, $b);
                $pdf->SetX($xStart);
                $pdf->Cell(5, 5, '', 1, 0, 'C', true); // kotak warna
                $pdf->Cell(3, 5, '', 0, 0);

                // Format tanggal
                $tgl_mulai = date('d/m/Y', strtotime($row['tanggal_mulai_yunifa']));
                if (!empty($row['tanggal_selesai_yunifa']) && $row['tanggal_selesai_yunifa'] != $row['tanggal_mulai_yunifa']) {
                    $tgl_selesai = date('d/m/Y', strtotime($row['tanggal_selesai_yunifa']));
                    $tgl_teks = $tgl_mulai . ' s/d ' . $tgl_selesai;
                } else {
                    $tgl_teks = $tgl_mulai;
                }

                $pdf->SetTextColor(0,0,0);
                $pdf->Cell(160, 5, $no . '. ' . $row['judul_yunifa'], 0, 0, 'L');
                $pdf->Cell(60,  5, $tgl_teks, 0, 1, 'L');
                $no++;
            }
            $pdf->Ln(2);
        };

        $ada_smt1 = !empty($smt1);
        $ada_smt2 = !empty($smt2);

        if ($ada_smt1 && $ada_smt2) {
            if ($mode === 'tahun') {
                $cetakGrup("Semester 1 (Januari – Juni $tahun)", $smt1);
                $cetakGrup("Semester 2 (Juli – Desember $tahun)", $smt2);
            } else {
                $cetakGrup("Semester 1", $smt1);
                $cetakGrup("Semester 2", $smt2);
            }
        } elseif ($ada_smt1) {
            $cetakGrup("Semester 1", $smt1);
        } elseif ($ada_smt2) {
            $cetakGrup("Semester 2", $smt2);
        } else {
            $pdf->SetX($xStart);
            $pdf->SetFont('Arial','I',8);
            $pdf->Cell(0, 5, "Tidak ada kegiatan dalam periode ini.", 0, 1);
        }
    }
}

if ($filter_yunifa == 'tahun') {
    $pdf_yunifa->SetFont('Arial','B',14);
    $pdf_yunifa->Cell(0, 7, "KALENDER PENDIDIKAN SMK NEGERI 2 CIMAHI", 0, 1, 'C');
    $pdf_yunifa->Cell(0, 7, "TAHUN $tahun_yunifa", 0, 1, 'C');
    $pdf_yunifa->Ln(5);
    
    $wThn_yunifa  = 10;
    $wBln_yunifa  = 18;
    $wHari_yunifa = 6.1; 
    $wSum_yunifa  = 7;   
    $wSmt_yunifa  = 12;  
    $tinggi_yunifa = 5;
    $xStart_yunifa = 5; 

    $pdf_yunifa->SetFont('Arial','B',6);
    $pdf_yunifa->SetFillColor(200, 200, 200);

    $pdf_yunifa->SetX($xStart_yunifa);
    $pdf_yunifa->Cell($wThn_yunifa, $tinggi_yunifa*2, 'THN', 1, 0, 'C', true);
    $pdf_yunifa->Cell($wBln_yunifa, $tinggi_yunifa*2, 'BLN', 1, 0, 'C', true);
    $pdf_yunifa->Cell($wHari_yunifa * 37, $tinggi_yunifa, 'HARI / TANGGAL', 1, 0, 'C', true);
    $pdf_yunifa->Cell(($wSum_yunifa * 3) + $wSmt_yunifa, $tinggi_yunifa, 'JUMLAH HARI', 1, 1, 'C', true);

    $pdf_yunifa->SetX($xStart_yunifa + $wThn_yunifa + $wBln_yunifa);
    $labels_yunifa = ['Mg','Sn','Sl','Rb','Km','Jm','Sb'];
    for ($i_yunifa = 0; $i_yunifa < 37; $i_yunifa++) {
        $lab_yunifa = $labels_yunifa[$i_yunifa % 7];
        if($lab_yunifa == 'Mg') {
            $pdf_yunifa->SetFillColor(255, 0, 0); $pdf_yunifa->SetTextColor(255,255,255);
        } else {
            $pdf_yunifa->SetFillColor(200, 200, 200); $pdf_yunifa->SetTextColor(0,0,0);
        }
        $pdf_yunifa->Cell($wHari_yunifa, $tinggi_yunifa, $lab_yunifa, 1, 0, 'C', true);
    }
    $pdf_yunifa->SetTextColor(0,0,0);
    $pdf_yunifa->SetFillColor(200, 200, 200);
    $pdf_yunifa->Cell($wSum_yunifa, $tinggi_yunifa, 'HK', 1, 0, 'C', true);
    $pdf_yunifa->Cell($wSum_yunifa, $tinggi_yunifa, 'HL', 1, 0, 'C', true);
    $pdf_yunifa->Cell($wSum_yunifa, $tinggi_yunifa, 'HE', 1, 0, 'C', true);
    $pdf_yunifa->Cell($wSmt_yunifa, $tinggi_yunifa, 'HE SMT', 1, 1, 'C', true);

    $totalHE_Semester_yunifa = 0;
    
    for ($m_yunifa = 1; $m_yunifa <= 12; $m_yunifa++) {
        $pdf_yunifa->SetX($xStart_yunifa);
        $pdf_yunifa->SetFont('Arial','',6);
        
        $tgl_awal_bln_yunifa = "$tahun_yunifa-$m_yunifa-01";
        $jumlah_hari_yunifa = date("t", strtotime($tgl_awal_bln_yunifa));
        $first_day_idx_yunifa = date('w', strtotime($tgl_awal_bln_yunifa)); 
 
        $query_keg_yunifa = mysqli_query($koneksiYunifa, "SELECT * FROM kegiatan_yunifa WHERE MONTH(tanggal_mulai_yunifa) = '$m_yunifa' AND YEAR(tanggal_mulai_yunifa) = '$tahun_yunifa'");
        $event_map_yunifa = [];
        while($row_yunifa = mysqli_fetch_assoc($query_keg_yunifa)) {
            $tgl_keg_yunifa = (int)date('j', strtotime($row_yunifa['tanggal_mulai_yunifa']));
            $event_map_yunifa[$tgl_keg_yunifa] = $row_yunifa['warna_yunifa'];
        }

        $txt_thn_yunifa = ($m_yunifa == 1 || $m_yunifa == 7) ? $tahun_yunifa : '';
        $pdf_yunifa->Cell($wThn_yunifa, $tinggi_yunifa, $txt_thn_yunifa, 'LR', 0, 'C');
        $pdf_yunifa->Cell($wBln_yunifa, $tinggi_yunifa, $namaBulan_yunifa[$m_yunifa], 1, 0, 'L');

        $hk_yunifa = $jumlah_hari_yunifa;
        $hl_yunifa = 0; 
        $he_yunifa = 0;

        for ($col_yunifa = 0; $col_yunifa < 37; $col_yunifa++) {
            $tgl_skrg_yunifa = $col_yunifa - $first_day_idx_yunifa + 1;
            
            if ($tgl_skrg_yunifa > 0 && $tgl_skrg_yunifa <= $jumlah_hari_yunifa) {
                $is_minggu_yunifa = (date('w', strtotime("$tahun_yunifa-$m_yunifa-$tgl_skrg_yunifa")) == 0);
                
                if (isset($event_map_yunifa[$tgl_skrg_yunifa])) {
                    list($r_yunifa, $g_yunifa, $b_yunifa) = sscanf($event_map_yunifa[$tgl_skrg_yunifa], "#%02x%02x%02x");
                    $pdf_yunifa->SetFillColor($r_yunifa, $g_yunifa, $b_yunifa);
                    $pdf_yunifa->Cell($wHari_yunifa, $tinggi_yunifa, $tgl_skrg_yunifa, 1, 0, 'C', true);
                    $hl_yunifa++;
                } elseif ($is_minggu_yunifa) {
                    $pdf_yunifa->SetFillColor(255, 180, 180); 
                    $pdf_yunifa->Cell($wHari_yunifa, $tinggi_yunifa, $tgl_skrg_yunifa, 1, 0, 'C', true);
                    $hl_yunifa++;
                } else {
                    $pdf_yunifa->Cell($wHari_yunifa, $tinggi_yunifa, $tgl_skrg_yunifa, 1, 0, 'C');
                    $he_yunifa++;
                }
            } else {
                $pdf_yunifa->SetFillColor(245, 245, 245);
                $pdf_yunifa->Cell($wHari_yunifa, $tinggi_yunifa, '', 1, 0, 'C', true);
            }
        }

        $totalHE_Semester_yunifa += $he_yunifa;

        $pdf_yunifa->Cell($wSum_yunifa, $tinggi_yunifa, $hk_yunifa, 1, 0, 'C');
        $pdf_yunifa->Cell($wSum_yunifa, $tinggi_yunifa, $hl_yunifa, 1, 0, 'C');
        $pdf_yunifa->Cell($wSum_yunifa, $tinggi_yunifa, $he_yunifa, 1, 0, 'C');
        
        if ($m_yunifa == 6 || $m_yunifa == 12) {
            $pdf_yunifa->SetFont('Arial','B',6);
            $pdf_yunifa->Cell($wSmt_yunifa, $tinggi_yunifa, $totalHE_Semester_yunifa, 1, 1, 'C', true);
            $totalHE_Semester_yunifa = 0; 
        } else {
            $pdf_yunifa->Cell($wSmt_yunifa, $tinggi_yunifa, '', 1, 1, 'C');
        }
    }

    cetakKeterangan_yunifa($pdf_yunifa, $koneksiYunifa, $xStart_yunifa, 'tahun', ['tahun' => $tahun_yunifa]);

} elseif ($filter_yunifa == 'rentang_bulan') {
    $bulan_awal_yunifa  = (int)($_GET['bulan_awal'] ?? date('n'));
    $tahun_awal_yunifa  = (int)($_GET['tahun_awal'] ?? date('Y'));
    $bulan_akhir_yunifa = (int)($_GET['bulan_akhir'] ?? date('n'));
    $tahun_akhir_yunifa = (int)($_GET['tahun_akhir'] ?? date('Y'));

    $pdf_yunifa->SetFont('Arial','B',14);
    $pdf_yunifa->Cell(0, 7, "KALENDER PENDIDIKAN SMK NEGERI 2 CIMAHI", 0, 1, 'C');
    $pdf_yunifa->Cell(0, 7, "TAHUN AJARAN $tahun_awal_yunifa / $tahun_akhir_yunifa", 0, 1, 'C');
    $pdf_yunifa->Ln(5);

    $list_bulan_yunifa = [];
    $curr_b_yunifa = $bulan_awal_yunifa;
    $curr_t_yunifa = $tahun_awal_yunifa;

    while ($curr_t_yunifa < $tahun_akhir_yunifa || ($curr_t_yunifa == $tahun_akhir_yunifa && $curr_b_yunifa <= $bulan_akhir_yunifa)) {
        $list_bulan_yunifa[] = ['bulan' => $curr_b_yunifa, 'tahun' => $curr_t_yunifa];
        $curr_b_yunifa++;
        if ($curr_b_yunifa > 12) {
            $curr_b_yunifa = 1;
            $curr_t_yunifa++;
        }
        if (count($list_bulan_yunifa) > 100) break; 
    }

    $wThn_yunifa  = 10;
    $wBln_yunifa  = 18;
    $wHari_yunifa = 6.1; 
    $wSum_yunifa  = 7;   
    $wSmt_yunifa  = 12;  
    $tinggi_yunifa = 5;
    $xStart_yunifa = 5;

    $pdf_yunifa->SetFont('Arial','B',6);
    $pdf_yunifa->SetFillColor(200, 200, 200);

    $pdf_yunifa->SetX($xStart_yunifa);
    $pdf_yunifa->Cell($wThn_yunifa, $tinggi_yunifa*2, 'THN', 1, 0, 'C', true);
    $pdf_yunifa->Cell($wBln_yunifa, $tinggi_yunifa*2, 'BLN', 1, 0, 'C', true);
    $pdf_yunifa->Cell($wHari_yunifa * 37, $tinggi_yunifa, 'HARI / TANGGAL', 1, 0, 'C', true);
    $pdf_yunifa->Cell(($wSum_yunifa * 3) + $wSmt_yunifa, $tinggi_yunifa, 'JUMLAH HARI', 1, 1, 'C', true);

    $pdf_yunifa->SetX($xStart_yunifa + $wThn_yunifa + $wBln_yunifa);
    $labels_yunifa = ['Mg','Sn','Sl','Rb','Km','Jm','Sb'];
    for ($i_yunifa = 0; $i_yunifa < 37; $i_yunifa++) {
        $lab_yunifa = $labels_yunifa[$i_yunifa % 7];
        if($lab_yunifa == 'Mg') {
            $pdf_yunifa->SetFillColor(255, 0, 0); $pdf_yunifa->SetTextColor(255,255,255);
        } else {
            $pdf_yunifa->SetFillColor(200, 200, 200); $pdf_yunifa->SetTextColor(0,0,0);
        }
        $pdf_yunifa->Cell($wHari_yunifa, $tinggi_yunifa, $lab_yunifa, 1, 0, 'C', true);
    }
    $pdf_yunifa->SetTextColor(0,0,0);
    $pdf_yunifa->SetFillColor(200, 200, 200);
    $pdf_yunifa->Cell($wSum_yunifa, $tinggi_yunifa, 'HK', 1, 0, 'C', true);
    $pdf_yunifa->Cell($wSum_yunifa, $tinggi_yunifa, 'HL', 1, 0, 'C', true);
    $pdf_yunifa->Cell($wSum_yunifa, $tinggi_yunifa, 'HE', 1, 0, 'C', true);
    $pdf_yunifa->Cell($wSmt_yunifa, $tinggi_yunifa, 'HE SMT', 1, 1, 'C', true);

    $totalHE_Semester_yunifa = 0;
    $last_thn_yunifa = "";

    foreach ($list_bulan_yunifa as $item_yunifa) {
        $m_yunifa = $item_yunifa['bulan'];
        $t_yunifa = $item_yunifa['tahun'];

        $pdf_yunifa->SetX($xStart_yunifa);
        $pdf_yunifa->SetFont('Arial','',6);
        
        $tgl_awal_bln_yunifa = "$t_yunifa-$m_yunifa-01";
        $jumlah_hari_yunifa = date("t", strtotime($tgl_awal_bln_yunifa));
        $first_day_idx_yunifa = date('w', strtotime($tgl_awal_bln_yunifa));

        $query_keg_yunifa = mysqli_query($koneksiYunifa, "SELECT * FROM kegiatan_yunifa WHERE MONTH(tanggal_mulai_yunifa) = '$m_yunifa' AND YEAR(tanggal_mulai_yunifa) = '$t_yunifa'");
        $event_map_yunifa = [];
        while($row_yunifa = mysqli_fetch_assoc($query_keg_yunifa)) {
            $tgl_keg_yunifa = (int)date('j', strtotime($row_yunifa['tanggal_mulai_yunifa']));
            $event_map_yunifa[$tgl_keg_yunifa] = $row_yunifa['warna_yunifa'];
        }

        $txt_thn_yunifa = ($t_yunifa != $last_thn_yunifa) ? $t_yunifa : '';
        $pdf_yunifa->Cell($wThn_yunifa, $tinggi_yunifa, $txt_thn_yunifa, 'LR', 0, 'C');
        $last_thn_yunifa = $t_yunifa;

        $pdf_yunifa->Cell($wBln_yunifa, $tinggi_yunifa, $namaBulan_yunifa[$m_yunifa], 1, 0, 'L');

        $hk_yunifa = $jumlah_hari_yunifa;
        $hl_yunifa = 0; 
        $he_yunifa = 0;

        for ($col_yunifa = 0; $col_yunifa < 37; $col_yunifa++) {
            $tgl_skrg_yunifa = $col_yunifa - $first_day_idx_yunifa + 1;
            
            if ($tgl_skrg_yunifa > 0 && $tgl_skrg_yunifa <= $jumlah_hari_yunifa) {
                $is_minggu_yunifa = (date('w', strtotime("$t_yunifa-$m_yunifa-$tgl_skrg_yunifa")) == 0);
                
                if (isset($event_map_yunifa[$tgl_skrg_yunifa])) {
                    list($r_yunifa, $g_yunifa, $b_yunifa) = sscanf($event_map_yunifa[$tgl_skrg_yunifa], "#%02x%02x%02x");
                    $pdf_yunifa->SetFillColor($r_yunifa, $g_yunifa, $b_yunifa);
                    $pdf_yunifa->Cell($wHari_yunifa, $tinggi_yunifa, $tgl_skrg_yunifa, 1, 0, 'C', true);
                    $hl_yunifa++;
                } elseif ($is_minggu_yunifa) {
                    $pdf_yunifa->SetFillColor(255, 180, 180);
                    $pdf_yunifa->Cell($wHari_yunifa, $tinggi_yunifa, $tgl_skrg_yunifa, 1, 0, 'C', true);
                    $hl_yunifa++;
                } else {
                    $pdf_yunifa->Cell($wHari_yunifa, $tinggi_yunifa, $tgl_skrg_yunifa, 1, 0, 'C');
                    $he_yunifa++;
                }
            } else {
                $pdf_yunifa->SetFillColor(245, 245, 245);
                $pdf_yunifa->Cell($wHari_yunifa, $tinggi_yunifa, '', 1, 0, 'C', true);
            }
        }

        $totalHE_Semester_yunifa += $he_yunifa;

        $pdf_yunifa->Cell($wSum_yunifa, $tinggi_yunifa, $hk_yunifa, 1, 0, 'C');
        $pdf_yunifa->Cell($wSum_yunifa, $tinggi_yunifa, $hl_yunifa, 1, 0, 'C');
        $pdf_yunifa->Cell($wSum_yunifa, $tinggi_yunifa, $he_yunifa, 1, 0, 'C');
        
        $is_last_row_yunifa = ($item_yunifa === end($list_bulan_yunifa));

        if ($m_yunifa == 6 || $m_yunifa == 12 || $is_last_row_yunifa) {
            $pdf_yunifa->SetFont('Arial','B',6);
            $pdf_yunifa->Cell($wSmt_yunifa, $tinggi_yunifa, $totalHE_Semester_yunifa, 1, 1, 'C', true);
            $totalHE_Semester_yunifa = 0; 
        } else {
            $pdf_yunifa->Cell($wSmt_yunifa, $tinggi_yunifa, '', 1, 1, 'C');
        }

        if ($pdf_yunifa->GetY() > 180 && !$is_last_row_yunifa) {
            $pdf_yunifa->AddPage();
        }
    }

    // Hitung tgl_dari dan tgl_sampai untuk keterangan
    $tgl_dari_ket   = sprintf('%04d-%02d-01', $tahun_awal_yunifa, $bulan_awal_yunifa);
    $last_day_ket   = date('t', mktime(0,0,0,$bulan_akhir_yunifa,1,$tahun_akhir_yunifa));
    $tgl_sampai_ket = sprintf('%04d-%02d-%02d', $tahun_akhir_yunifa, $bulan_akhir_yunifa, $last_day_ket);

    cetakKeterangan_yunifa($pdf_yunifa, $koneksiYunifa, $xStart_yunifa, 'rentang_bulan', [
        'tgl_dari'   => $tgl_dari_ket,
        'tgl_sampai' => $tgl_sampai_ket
    ]);

} elseif ($filter_yunifa == 'rentang_minggu') {
    $tgl_mulai_rm_yunifa = $_GET['tgl_mulai_rm'] ?? date('Y-m-01');
    $tgl_akhir_rm_yunifa = $_GET['tgl_akhir_rm'] ?? date('Y-m-07');

    $pdf_yunifa->SetFont('Arial','B',14);
    $pdf_yunifa->Cell(0, 7, "KALENDER PENDIDIKAN SMK NEGERI 2 CIMAHI", 0, 1, 'C');
    $pdf_yunifa->Cell(0, 7, "LAPORAN MINGGUAN TANGGAL $tgl_mulai_rm_yunifa s/d. $tgl_akhir_rm_yunifa", 0, 1, 'C');
    $pdf_yunifa->Ln(5);

    $start_ts_yunifa = strtotime($tgl_mulai_rm_yunifa);
    $end_ts_yunifa   = strtotime($tgl_akhir_rm_yunifa);
    $selisih_hari_yunifa = (($end_ts_yunifa - $start_ts_yunifa) / 86400) + 1;

    $wKet_yunifa  = 45;
    $wSum_yunifa  = 10; 
    $wSmt_yunifa  = 15; 
    
    $sisa_ruang_yunifa = 277 - $wKet_yunifa - ($wSum_yunifa * 3) - $wSmt_yunifa;
    $wHari_yunifa = $sisa_ruang_yunifa / $selisih_hari_yunifa; 
    if($wHari_yunifa > 15) $wHari_yunifa = 15;

    $total_lebar_tabel_yunifa = $wKet_yunifa + ($wHari_yunifa * $selisih_hari_yunifa) + ($wSum_yunifa * 3) + $wSmt_yunifa;
    $xStart_yunifa = (297 - $total_lebar_tabel_yunifa) / 2;

    $tinggi_yunifa = 8;
    $pdf_yunifa->SetFont('Arial','B',8);
    $pdf_yunifa->SetFillColor(200, 200, 200);

    $pdf_yunifa->SetX($xStart_yunifa); 
    $pdf_yunifa->Cell($wKet_yunifa, $tinggi_yunifa*2, 'PERIODE', 1, 0, 'C', true);
    $pdf_yunifa->Cell($wHari_yunifa * $selisih_hari_yunifa, $tinggi_yunifa, 'TANGGAL / HARI', 1, 0, 'C', true);
    $pdf_yunifa->Cell(($wSum_yunifa * 3) + $wSmt_yunifa, $tinggi_yunifa, 'REKAP', 1, 1, 'C', true);

    $pdf_yunifa->SetX($xStart_yunifa + $wKet_yunifa); 
    $pdf_yunifa->SetFont('Arial','B',7);
    for ($i_yunifa = 0; $i_yunifa < $selisih_hari_yunifa; $i_yunifa++) {
        $current_ts_yunifa = strtotime("+$i_yunifa day", $start_ts_yunifa);
        $hari_indo_yunifa = ['Sun'=>'Mg','Mon'=>'Sn','Tue'=>'Sl','Wed'=>'Rb','Thu'=>'Km','Fri'=>'Jm','Sat'=>'Sb'];
        $lab_hari_yunifa = $hari_indo_yunifa[date('D', $current_ts_yunifa)];

        if($lab_hari_yunifa == 'Mg') {
            $pdf_yunifa->SetFillColor(255, 0, 0); $pdf_yunifa->SetTextColor(255,255,255);
        } else {
            $pdf_yunifa->SetFillColor(230, 230, 230); $pdf_yunifa->SetTextColor(0,0,0);
        }
        $pdf_yunifa->Cell($wHari_yunifa, $tinggi_yunifa, $lab_hari_yunifa, 1, 0, 'C', true);
    }
    
    $pdf_yunifa->SetTextColor(0,0,0);
    $pdf_yunifa->SetFillColor(200, 200, 200);
    $pdf_yunifa->Cell($wSum_yunifa, $tinggi_yunifa, 'HK', 1, 0, 'C', true);
    $pdf_yunifa->Cell($wSum_yunifa, $tinggi_yunifa, 'HL', 1, 0, 'C', true);
    $pdf_yunifa->Cell($wSum_yunifa, $tinggi_yunifa, 'HE', 1, 0, 'C', true);
    $pdf_yunifa->Cell($wSmt_yunifa, $tinggi_yunifa, 'TOTAL', 1, 1, 'C', true);

    $pdf_yunifa->SetX($xStart_yunifa); 
    $pdf_yunifa->SetFont('Arial','',8);
    
    $periode_teks_yunifa = date('d/m/y', $start_ts_yunifa) . " - " . date('d/m/y', $end_ts_yunifa);
    $pdf_yunifa->Cell($wKet_yunifa, $tinggi_yunifa, $periode_teks_yunifa, 1, 0, 'C');

    $hk_yunifa = 0; $hl_yunifa = 0; $he_yunifa = 0;
    $query_keg_yunifa = mysqli_query($koneksiYunifa, "SELECT * FROM kegiatan_yunifa WHERE tanggal_mulai_yunifa BETWEEN '$tgl_mulai_rm_yunifa' AND '$tgl_akhir_rm_yunifa'");
    $event_map_yunifa = [];
    while($row_yunifa = mysqli_fetch_assoc($query_keg_yunifa)) {
        $event_map_yunifa[$row_yunifa['tanggal_mulai_yunifa']] = $row_yunifa['warna_yunifa'];
    }

    for ($i_yunifa = 0; $i_yunifa < $selisih_hari_yunifa; $i_yunifa++) {
        $ts_skrg_yunifa = strtotime("+$i_yunifa day", $start_ts_yunifa);
        $tgl_sql_yunifa = date('Y-m-d', $ts_skrg_yunifa);
        $tgl_angka_yunifa = date('j', $ts_skrg_yunifa);
        $hk_yunifa++;

        if (isset($event_map_yunifa[$tgl_sql_yunifa])) {
            list($r_yunifa, $g_yunifa, $b_yunifa) = sscanf($event_map_yunifa[$tgl_sql_yunifa], "#%02x%02x%02x");
            $pdf_yunifa->SetFillColor($r_yunifa, $g_yunifa, $b_yunifa);
            $pdf_yunifa->Cell($wHari_yunifa, $tinggi_yunifa, $tgl_angka_yunifa, 1, 0, 'C', true);
            $hl_yunifa++;
        } elseif (date('w', $ts_skrg_yunifa) == 0) {
            $pdf_yunifa->SetFillColor(255, 180, 180);
            $pdf_yunifa->Cell($wHari_yunifa, $tinggi_yunifa, $tgl_angka_yunifa, 1, 0, 'C', true);
            $hl_yunifa++;
        } else {
            $pdf_yunifa->Cell($wHari_yunifa, $tinggi_yunifa, $tgl_angka_yunifa, 1, 0, 'C');
            $he_yunifa++;
        }
    }

    $pdf_yunifa->Cell($wSum_yunifa, $tinggi_yunifa, $hk_yunifa, 1, 0, 'C');
    $pdf_yunifa->Cell($wSum_yunifa, $tinggi_yunifa, $hl_yunifa, 1, 0, 'C');
    $pdf_yunifa->Cell($wSum_yunifa, $tinggi_yunifa, $he_yunifa, 1, 0, 'C');
    $pdf_yunifa->Cell($wSmt_yunifa, $tinggi_yunifa, $he_yunifa, 1, 1, 'C');

    cetakKeterangan_yunifa($pdf_yunifa, $koneksiYunifa, $xStart_yunifa, 'rentang_bulan', [
        'tgl_dari'   => $tgl_mulai_rm_yunifa,
        'tgl_sampai' => $tgl_akhir_rm_yunifa
    ]);

    $current_xStart_ttd = $xStart_yunifa;
} elseif ($filter_yunifa == 'harian' || $filter_yunifa == 'hari') {
    $tanggal_yunifa = $_GET['tanggal'] ?? date('Y-m-d');

    $pdf_yunifa->SetFont('Arial','B',14);
    $pdf_yunifa->Cell(0, 7, "LAPORAN KEGIATAN HARIAN", 0, 1, 'C');
    $pdf_yunifa->Cell(0, 7, "Tanggal: " . date('d F Y', strtotime($tanggal_yunifa)), 0, 1, 'C');
    $pdf_yunifa->Ln(10);

    $lebar_no     = 10;
    $lebar_judul  = 120;
    $lebar_waktu  = 80;
    $lebar_total  = $lebar_no + $lebar_judul + $lebar_waktu;
    $xStart_yunifa = (297 - $lebar_total) / 2;

    $pdf_yunifa->SetX($xStart_yunifa);
    $pdf_yunifa->SetFont('Arial','B',10);
    $pdf_yunifa->SetFillColor(230, 230, 230);
    $pdf_yunifa->Cell($lebar_no,    8, 'No',            1, 0, 'C', true);
    $pdf_yunifa->Cell($lebar_judul, 8, 'Judul Kegiatan',1, 0, 'C', true);
    $pdf_yunifa->Cell($lebar_waktu, 8, 'Waktu Pelaksanaan', 1, 1, 'C', true);

    $query_yunifa = mysqli_query($koneksiYunifa, "
        SELECT * FROM kegiatan_yunifa 
        WHERE tanggal_mulai_yunifa = '$tanggal_yunifa'
        ORDER BY waktu_mulai_yunifa ASC
    ");

    $pdf_yunifa->SetFont('Arial', '', 10);
    $no = 1;

    if (mysqli_num_rows($query_yunifa) > 0) {
        while ($r = mysqli_fetch_assoc($query_yunifa)) {
            $pdf_yunifa->SetX($xStart_yunifa); 

            list($rr, $gg, $bb) = sscanf($r['warna_yunifa'], "#%02x%02x%02x");
            $pdf_yunifa->SetFillColor($rr, $gg, $bb);

            $waktu_lengkap = $r['waktu_mulai_yunifa'] . " s/d " . $r['waktu_selesai_yunifa'];

            $pdf_yunifa->Cell($lebar_no,    8, $no++,               1, 0, 'C', true);
            $pdf_yunifa->Cell($lebar_judul, 8, ' ' . $r['judul_yunifa'], 1, 0, 'L', true);
            $pdf_yunifa->Cell($lebar_waktu, 8, ' ' . $waktu_lengkap,  1, 1, 'L', true);
        }
    } else {
        $pdf_yunifa->SetX($xStart_yunifa);
        $pdf_yunifa->Cell($lebar_total, 10, "Tidak ada jadwal kegiatan untuk tanggal ini.", 1, 1, 'C');
    }

}

$pdf_yunifa->Ln(15);
$y_ttd_yunifa = $pdf_yunifa->GetY();

$lebar_aktif = (isset($lebar_total)) ? $lebar_total : ((isset($total_lebar_tabel_yunifa)) ? $total_lebar_tabel_yunifa : 230);
$x_awal = (isset($xStart_yunifa)) ? $xStart_yunifa : 30;

$lebar_blok_ttd = 60;
$x_blok_kanan = $x_awal + $lebar_aktif - $lebar_blok_ttd;

$pdf_yunifa->SetXY($x_blok_kanan, $y_ttd_yunifa);
$pdf_yunifa->SetFont('Arial','',10);
$pdf_yunifa->Cell($lebar_blok_ttd, 5, "Cimahi, " . date('d F Y'), 0, 1, 'C');

$pdf_yunifa->SetX($x_blok_kanan);
$pdf_yunifa->Cell($lebar_blok_ttd, 5, "Kepala Sekolah,", 0, 1, 'C');

$pdf_yunifa->Ln(2);
$pdf_yunifa->Image('fpdf/ttd_kepsek.png', $x_blok_kanan + 15, $pdf_yunifa->GetY(), 30);
$pdf_yunifa->Ln(18); 

$pdf_yunifa->SetX($x_blok_kanan);
$pdf_yunifa->SetFont('Arial','BU',10);
$pdf_yunifa->Cell($lebar_blok_ttd, 5, "Asep Suwarno, S.E., M.M.Pd.", 0, 1, 'C');

$pdf_yunifa->SetX($x_blok_kanan);
$pdf_yunifa->SetFont('Arial','',10);
$pdf_yunifa->Cell($lebar_blok_ttd, 5, "NIP. 19710409 200604 1 010", 0, 1, 'C');

$pdf_yunifa->Output("I","Laporan_Kalender_SMKN2_Yunifa.pdf");