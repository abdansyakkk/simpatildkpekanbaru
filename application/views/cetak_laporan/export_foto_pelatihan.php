<?php
require_once FCPATH . 'vendor/autoload.php';
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\SimpleType\Jc;

$phpWord = new PhpWord();
$section = $phpWord->addSection();

// Function to format day name in Indonesian
function getIndonesianDay($date) {
    $days = [
        'Sunday' => 'Minggu',
        'Monday' => 'Senin',
        'Tuesday' => 'Selasa',
        'Wednesday' => 'Rabu',
        'Thursday' => 'Kamis',
        'Friday' => 'Jumat',
        'Saturday' => 'Sabtu'
    ];
    $englishDay = date('l', strtotime($date));
    return $days[$englishDay] ?? $englishDay;
}

// Judul Dokumen
$section->addText('LAMPIRAN DOKUMENTASI PELATIHAN', ['bold' => true, 'size' => 14], ['align' => 'center']);
$section->addTextBreak(1);

// Format tanggal
$bulan = [
    '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April',
    '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus',
    '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
];

$tgl1 = explode('-', $pelatihan['tanggal_mulai_pelatihan']);
$tgl2 = explode('-', $pelatihan['tanggal_selesai_pelatihan']);
$tglMulai = $tgl1[2] . ' ' . $bulan[$tgl1[1]] . ' ' . $tgl1[0];
$tglSelesai = $tgl2[2] . ' ' . $bulan[$tgl2[1]] . ' ' . $tgl2[0];

$section->addText("Nama Pelatihan: " . $pelatihan['nama_pelatihan'], ['bold' => true]);
$section->addText("Periode: $tglMulai - $tglSelesai", ['italic' => true]);
$section->addTextBreak(2);

// Group activities by day first
$activitiesByDay = [];
foreach ($activities as $act) {
    $dayKey = $act['day_ke'] . '_' . $act['tanggal_activity'];
    $activitiesByDay[$dayKey][] = $act;
}

// Initialize counters
$dayCounter = 1;
$activityLetters = range('a', 'z');

// Loop through each day
foreach ($activitiesByDay as $dayActivities) {
    $firstActivity = $dayActivities[0];
    $hari = getIndonesianDay($firstActivity['tanggal_activity']);
    $tanggal = date('d F Y', strtotime($firstActivity['tanggal_activity']));
    
    // Day heading (Level 1) with numbering
    $section->addText("$dayCounter. $hari, $tanggal", ['bold' => true, 'size' => 12]);
    $dayCounter++;
    
    // Reset activity counter for each day
    $activityCounter = 0;
    
    // Loop through activities in this day
    foreach ($dayActivities as $act) {
        // Activity heading (Level 2) with lettering
        $section->addText(
            $activityLetters[$activityCounter] . ". {$act['nama_kegiatan']} {$act['jam_mulai']}-{$act['jam_selesai']}",
            ['bold' => true, 'size' => 11]
        );
        $activityCounter++;
        
        // Activity description
        if (!empty($act['activity_desc'])) {
            $section->addText($act['activity_desc'], ['size' => 10]);
        }
        
        // Speaker information
        if (!empty($act['nama_narasumber'])) {
            $speakerInfo = "Materi disampaikan oleh ";
            
            if (!empty($act['jabatan'])) {
                $speakerInfo .= $act['jabatan'] . " ";
            }
            
            $speakerInfo .= $act['nama_narasumber'];
            
            if (!empty($act['asal_satker'])) {
                $speakerInfo .= " dari {$act['asal_satker']}";
            }
            
            $section->addText($speakerInfo, ['size' => 10]);
        }
        
        // Photos for this activity
        if (isset($fotoMap[$act['id_activity']])) {
            foreach ($fotoMap[$act['id_activity']] as $foto) {
                $fotoPath = FCPATH . $foto['foto_path'];
                if (file_exists($fotoPath)) {
                    $section->addImage($fotoPath, [
                        'width' => 300,
                        'height' => 200,
                        'alignment' => Jc::CENTER
                    ]);
                    if (!empty($foto['keterangan'])) {
                        $section->addText(
                            "Keterangan Foto: {$foto['keterangan']}", 
                            ['italic' => true, 'size' => 9], 
                            ['align' => 'center']
                        );
                    }
                    $section->addTextBreak(1);
                }
            }
        }
        
        $section->addTextBreak(1);
    }
    
    $section->addTextBreak(1);
}

// Output
$filename = 'Lampiran_Dokumentasi_Pelatihan_' . $pelatihan['nama_pelatihan'] . '_' . date('Ymd_His') . '.docx';
header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
header("Content-Disposition: attachment; filename=\"$filename\"");
header('Cache-Control: max-age=0');

$objWriter = IOFactory::createWriter($phpWord, 'Word2007');
$objWriter->save('php://output');
exit;
?>