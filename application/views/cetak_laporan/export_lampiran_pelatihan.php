<?php
// Load TCPDF and FPDI libraries
require_once(FCPATH . 'vendor/tecnickcom/tcpdf/tcpdf.php');
require_once(FCPATH . 'vendor/setasign/fpdi/src/autoload.php');

use setasign\Fpdi\Tcpdf\Fpdi;

// Create new PDF document using FPDI
$pdf = new Fpdi(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// Set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Sistem Pelatihan');
$pdf->SetTitle('Lampiran Dokumen Pelatihan - ' . $pelatihan['nama_pelatihan']);
$pdf->SetSubject('Kumpulan Dokumen Pelatihan');

// Set default header data
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
// $pdf->SetHeaderData('', 0, 'LAMPIRAN DOKUMEN PELATIHAN', $pelatihan['nama_pelatihan']);

// Set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// Set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// Set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// Set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// Add a page
$pdf->AddPage();

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

// Add training info
$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell(0, 10, 'LAMPIRAN DOKUMEN PELATIHAN', 0, 1, 'C');
$pdf->Ln(5);

$pdf->SetFont('helvetica', '', 10);
$pdf->Cell(0, 0, 'Nama Pelatihan: ' . $pelatihan['nama_pelatihan'], 0, 1);
$pdf->Cell(0, 0, 'Periode: ' . $tglMulai . ' - ' . $tglSelesai, 0, 1);
$pdf->Ln(10);

// Loop through each document
foreach ($documents as $doc) {
    $pdf->SetFont('helvetica', 'B', 28);
    $pdf->Cell(0, 0, $doc['nama_dokumen'], 0, 1);
    
    if (!empty($doc['deskripsi'])) {
        $pdf->SetFont('helvetica', 'I', 35);
        $pdf->Cell(0, 0, 'Deskripsi: ' . $doc['deskripsi'], 0, 1);
    }
    
    $pdf->SetFont('helvetica', '', 8);
    $pdf->Cell(0, 0, 'Tanggal Upload: ' . $doc['tanggal_upload'], 0, 1);
    // $pdf->Ln(5);
    
    // Check if file exists and is PDF
    if (file_exists($doc['full_path'])) {
        try {
            // Get page count of the external PDF
            $fullPath = realpath($doc['full_path']);
            $pageCount = $pdf->setSourceFile($fullPath);
 
            // Import each page and add to the current PDF
            for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
                // Import a page from the PDF file
                $templateId = $pdf->importPage($pageNo);
                
                // Get the size of the imported page
                $size = $pdf->getTemplateSize($templateId);
                
                // Add a new page with the appropriate orientation
                $orientation = ($size['width'] > $size['height']) ? 'L' : 'P';
        
                // Tambahkan halaman dengan format yang lebih eksplisit
                $pdf->AddPage($orientation, array($size['width'], $size['height']));
                
                // Gunakan template dengan koordinat 0,0 dan lebar/tinggi sesuai aslinya
                $pdf->useTemplate($templateId, 0, 0, $size['width'], $size['height'], TRUE);
            }
        } catch (Exception $e) {
            $pdf->SetFont('helvetica', 'I', 10);
            $pdf->Cell(0, 0, 'Error memproses dokumen: ' . $doc['nama_dokumen'], 0, 1);
            error_log('PDF processing error: ' . $e->getMessage());
        }
    } else {
        $pdf->SetFont('helvetica', 'I', 10);
        $pdf->Cell(0, 0, 'Dokumen tidak ditemukan: ' . $doc['nama_dokumen'], 0, 1);
    }
    
    // Only add new page if there are more documents to process
    if (next($documents) !== false) {
        $pdf->AddPage();
    }
}

// Add closing section
$pdf->SetFont('helvetica', 'B', 11);
$pdf->Cell(0, 0, 'Mengetahui,', 0, 1, 'R');
$pdf->Ln(15);
$pdf->Cell(0, 0, '_________________________', 0, 1, 'R');
$pdf->SetFont('helvetica', '', 10);
$pdf->Cell(0, 0, 'Penanggung Jawab', 0, 1, 'R');

// Close and output PDF document
$filename = 'Lampiran_Dokumen_Pelatihan_' . $pelatihan['nama_pelatihan'] . '_' . date('Ymd_His') . '.pdf';
$pdf->Output($filename, 'D');
exit;
?>