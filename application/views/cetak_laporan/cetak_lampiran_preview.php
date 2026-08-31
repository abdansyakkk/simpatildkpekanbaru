<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

// Load PHPWord
require_once FCPATH . 'vendor/autoload.php'; // Pastikan autoload Composer dimuat

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;

// Inisialisasi data bulan
$bulan = array(
    '01' => 'Januari', '02' => 'Februari', '03' => 'Maret',
    '04' => 'April', '05' => 'Mei', '06' => 'Juni',
    '07' => 'Juli', '08' => 'Agustus', '09' => 'September',
    '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
);

// Format tanggal
$tglMulai = explode('-', $pelatihan['tanggal_mulai_pelatihan']);
$tglSelesai = explode('-', $pelatihan['tanggal_selesai_pelatihan']);
$tgl1 = $tglMulai[2] . ' ' . $bulan[$tglMulai[1]] . ' ' . $tglMulai[0];
$tgl2 = $tglSelesai[2] . ' ' . $bulan[$tglSelesai[1]] . ' ' . $tglSelesai[0];

// Inisialisasi PHPWord
$phpWord = new PhpWord();
$section = $phpWord->addSection();

// Header
$section->addText('LAMPIRAN DATA PELATIHAN', ['bold' => true, 'size' => 14], ['align' => 'center']);
$section->addText('Dokumen ini berisi rincian lengkap pelaksanaan pelatihan yang telah diselenggarakan.', ['italic' => true, 'size' => 11], ['align' => 'center']);
$section->addTextBreak(1);

// Paragraf deskripsi
$description = "Pelatihan {$pelatihan['nama_pelatihan']} merupakan kegiatan strategis dalam rangka penguatan kompetensi sumber daya manusia yang diselenggarakan di {$pelatihan['tempat']}, Kabupaten/Kota {$pelatihan['kab_kota']}, Provinsi {$pelatihan['provinsi']}. Pelatihan dilaksanakan mulai tanggal $tgl1 hingga $tgl2, pada tahun {$pelatihan['tahun']}.";
$section->addText($description, ['size' => 11], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::BOTH]);

$section->addTextBreak(1);

// Tabel data pelatihan
$table = $section->addTable(['borderSize' => 6, 'borderColor' => '999999', 'cellMargin' => 80]);

$table->addRow();
$table->addCell(3000)->addText('Nama Pelatihan');
$table->addCell(9000)->addText($pelatihan['nama_pelatihan']);

$table->addRow();
$table->addCell(3000)->addText('Provinsi');
$table->addCell(9000)->addText($pelatihan['provinsi']);

$table->addRow();
$table->addCell(3000)->addText('Kabupaten/Kota');
$table->addCell(9000)->addText($pelatihan['kab_kota']);

$table->addRow();
$table->addCell(3000)->addText('Tempat');
$table->addCell(9000)->addText($pelatihan['tempat']);

$table->addRow();
$table->addCell(3000)->addText('Tanggal Mulai');
$table->addCell(9000)->addText($tgl1);

$table->addRow();
$table->addCell(3000)->addText('Tanggal Selesai');
$table->addCell(9000)->addText($tgl2);

$table->addRow();
$table->addCell(3000)->addText('Tahun');
$table->addCell(9000)->addText($pelatihan['tahun']);

$section->addTextBreak(3);

// Tanda tangan
$section->addText('Mengetahui,', ['bold' => true], ['align' => 'right']);
$section->addTextBreak(2);
$section->addText('........................................', ['underline' => 'single'], ['align' => 'right']);
$section->addText('Penanggung Jawab', [], ['align' => 'right']);

// Export ke Word
$filename = 'Lampiran_Pelatihan_' . date('Ymd_His') . '.docx';
header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
header("Content-Disposition: attachment; filename=\"$filename\"");
header('Cache-Control: max-age=0');

$objWriter = IOFactory::createWriter($phpWord, 'Word2007');
$objWriter->save('php://output');
exit;