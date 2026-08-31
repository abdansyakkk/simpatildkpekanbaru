<?php

use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Shared\Converter;
use PhpOffice\PhpWord\SimpleType\Jc;
use PhpOffice\PhpWord\Style\Font;
use PhpOffice\PhpWord\Style\Paragraph;
use PhpOffice\PhpWord\Style\Tab;
use PhpOffice\PhpWord\Style\TOC;
use PhpOffice\PhpWord\Style\Image;

use function PHPSTORM_META\type;

defined('BASEPATH') OR exit('No direct script access allowed');

class wordGenerator_pdwk{
    protected $CI;
    
    public function __construct()
    {
        $this->CI =& get_instance();
        if (!function_exists('generate_list_style')){
            include_once(APPPATH . 'helpers/word_helper.php');
        }
    }
    

    public function generate($data = [])
    {
        try{

            $phpword = new PhpWord();
            $pelatihan = is_array($data) ? (object)$data['pelatihan'] : $data->pelatihan;
            // helper flags & lists for non-Latsar (PJJ/PDWK)
            $isNonLatsar = in_array((int)($pelatihan->id_jenis_pelatihan ?? 0), [1,2], true);
            $wiList       = ($isNonLatsar && isset($pelatihan->wi_list)       && is_array($pelatihan->wi_list))       ? $pelatihan->wi_list       : [];
            $pengajarList = ($isNonLatsar && isset($pelatihan->pengajar_list) && is_array($pelatihan->pengajar_list)) ? $pelatihan->pengajar_list : [];
            $wiRapat      = ($isNonLatsar && isset($pelatihan->wi_rapat)) ? $pelatihan->wi_rapat : null;

            // headcount: prefer auto for PJJ/PDWK, else legacy field
            $jumlahWiPengajar = $isNonLatsar
                ? (int)($pelatihan->jumlah_wi_pengajar_auto ?? (count($wiList) + count($pengajarList) + ($wiRapat ? 1 : 0)))
                : (int)($pelatihan->jumlah_wi_pengajar ?? 0);

            $durasi = is_array($data) ? ($data['durasi'] ?? 0) : $data->durasi ?? 0;
            $ketua_loka = is_array($data) ? ($data['ketua_loka'] ?? 0) : $data->ketua_loka ?? 0;
            
            $phpword->addTitleStyle(1, ['size'=>16, 'bold'=>true], ['alignment'=>'center']);
            $phpword->addTitleStyle(2, ['size'=>12, 'bold'=>true]);
            $phpword->setDefaultFontName('Times New Roman');
            $phpword->setDefaultFontSize(12);
            
            $fontstyle=['name' => 'Times New Roman', 'size' => 12];
            $phpword->addTitleStyle(1, ['size'=>16, 'bold'=>true], ['alignment'=>'center']);
            $phpword->addTitleStyle(2, ['size'=>14, 'bold'=>true]);
            $phpword->addTitleStyle(3, ['size'=>12, 'bold'=>true]);
            $phpword->addTitleStyle(4, ['size'=>12]);
            $paragraphstyle = [
                'alignment' => 'both',
                'indentation' => ['firstLine' => 700],
                'spacing' => 240,
                'lineHeight' => 1.5,
                'spaceAfter' => 0
            ];
            $paragraphstyle2 = [
                'alignment' => 'both',
                'indentation' => ['left' => 720],
                'spacing' => 240,
                'lineHeight' => 1.5,
                'spaceAfter' => 0
            ];
            // Add TOC style (important for formatting)
            $tocFontStyle = ['spaceAfter' => 60]; // Adjust spacing as needed
            $tocStyle = [
                'tabPos'    => 9000, // Position in twips where page number aligns (9000 ≈ 6.25 inches from left)
                'indent'    => 200,  // Indent for sub-level headings
                'tabLeader' => PhpOffice\PhpWord\Style\TOC::TAB_LEADER_DOT // Leader dots between title and page number
            ];

// assets\cover\Cover_PDWK_Penyelenggaraan.png

        //LAPORAN PENYELENGGARAAN//
        $coverSection = $phpword->addSection([
            'pageSizeW' => \PhpOffice\PhpWord\Shared\Converter::inchToTwip(8.5),
            'pageSizeH' => \PhpOffice\PhpWord\Shared\Converter::inchToTwip(11),
            'marginTop' => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(1),
            'marginLeft' => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(1),
            'marginRight' => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(1),
            'marginBottom' => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(1),
        ]);

        // Add background image with proper centering
        $imageWidth = \PhpOffice\PhpWord\Shared\Converter::cmToTwip(1.05); // ~3.9 inches wide
        $coverSection->addImage(
            'assets/cover/Cover_PDWK_Penyelenggaraan.png',
            [
                'width' => $imageWidth,
                'positioning' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
                'posHorizontal' => \PhpOffice\PhpWord\Style\Image::POSITION_HORIZONTAL_CENTER,
                'posHorizontalRel' => \PhpOffice\PhpWord\Style\Image::POSITION_RELATIVE_TO_PAGE,
                'posVertical' => \PhpOffice\PhpWord\Style\Image::POSITION_VERTICAL_CENTER,
                'posVerticalRel' => \PhpOffice\PhpWord\Style\Image::POSITION_RELATIVE_TO_PAGE,
                'wrap' => \PhpOffice\PhpWord\Style\Image::WRAP_BEHIND,
            ]
        );

        // Rest of your cover content remains the same...
        $coverSection->addTextBreak(13); // Adds 4 line breaks (you can adjust this number)
        $coverSection->addText(
            strtoupper($pelatihan->nama_kegiatan),
            [
                'name' => 'Times New Roman',
                'size' => 16,
                'bold' => true,
                'color' => '000000',
                'allCaps' => true,
            ],
            [
                'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER,
                'spaceAfter' => 300,
            ]
        );

            // Add location information
            $coverSection->addText(
                'Di Wilayah Kerja Kantor Kementerian Agama Kabupaten ' . $pelatihan->kab_kota,
                [
                    'name' => 'Times New Roman',
                    'size' => 12,
                ],
                [
                    'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER,
                    'spaceAfter' => 300,
                ]
            );

            // Add date information (using your dynamic data)
            $coverSection->addText("" . $data['tanggal_mulai'] . " s.d " . $data['tanggal_selesai'] . ".",
                [
                    'name' => 'Times New Roman',
                    'size' => 12,
                ],
                [
                    'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER,
                    'spaceAfter' => 600,
                ]
            );

            // Add PANITIA header
            $coverSection->addText(
                'PANITIA :',
                [
                    'name' => 'Times New Roman',
                    'size' => 12,
                    'bold' => true,
                ],
                [
                    'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER,
                    'spaceAfter' => 200,
                ]
            );

            // Add committee members (using your dynamic data)
            $committeeStyle = [
                'name' => 'Times New Roman',
                'size' => 12,
            ];
            $paragraphStyle = [
                'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER,
                'spaceAfter' => 100,
            ];

            // $coverSection->addText($pelatihan->ketua_panitia->nama, $committeeStyle, $paragraphStyle);
            // $coverSection->addText($pelatihan->akademis->nama, $committeeStyle, $paragraphStyle);
            // $coverSection->addText($pelatihan->keuangan->nama, $committeeStyle, $paragraphStyle);
            // $coverSection->addText($pelatihan->administrasi->nama, $committeeStyle, $paragraphStyle);

            if (!empty($pelatihan->ketua_panitia)) {
                $coverSection->addText($pelatihan->ketua_panitia->nama, $committeeStyle, $paragraphStyle);
            }

            if (!empty($pelatihan->akademis)) {
                $coverSection->addText($pelatihan->akademis->nama, $committeeStyle, $paragraphStyle);
            }

            if (!empty($pelatihan->keuangan)) {
                $coverSection->addText($pelatihan->keuangan->nama, $committeeStyle, $paragraphStyle);
            }

            if (!empty($pelatihan->administrasi)) {
                $coverSection->addText($pelatihan->administrasi->nama, $committeeStyle, $paragraphStyle);
            }


            // Add footer text
            $coverSection->addTextBreak(4); // Add some space

            $coverSection->addText(
                'LOKA PENDIDIKAN DAN PELATIHAN KEAGAMAAN PEKANBARU',
                [
                    'name' => 'Times New Roman',
                    'size' => 12,
                    'bold' => true,
                ],
                [
                    'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER,
                    'spaceAfter' => 100,
                ]
            );

            $coverSection->addText(
                "TAHUN $pelatihan->tahun",
                [
                    'name' => 'Times New Roman',
                    'size' => 12,
                    'bold' => true,
                ],
                [
                    'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER,
                ]
            );

            // Add page break after cover
            // Kata Pengantar
            $section1 = $phpword->addSection();
            // Add footer with page numbering
            $footer1 = $section1->addFooter();
            $footer1->addPreserveText('{PAGE}', null, ['alignment' => 'right']);
            $section1->addTitle("KATA PENGANTAR", 1);
               $section1->addTextBreak(1);
            // $section2->setStyle(['tabs' => []]);
            $section1->addText("Puji dan syukur kehadirat Allah SWT, berkat rahmat serta karunianya, laporan " . $pelatihan->nama_kegiatan . " di Wilayah Kerja Kantor Kementerian Agama " . $pelatihan->kab_kota . " ini telah dapat disusun.", $fontstyle, $paragraphstyle);
            $section1->addText("Laporan pelatihan ini terdiri dari tiga bab. Bab I Pendahuluan, memuat organisasi diklat, nama unit/satuan kerja, nama diklat yang diselenggarakan, dasar hukum, sumber pembiayaan, susunan panitia, dan alamat penyelenggara. Bab II berisi tentang pelaksanaan " . $pelatihan->nama_kegiatan . " yang mencakup tujuan dan sasaran, kurikulum, peserta, widyaiswara/narasumber, evaluasi, penyelenggaraan, keuangan, penjaminan mutu, dan lain-lain. Bab III sebagai penutup terdiri dari Laporan Keuangan / SPJ Akhir Pelatihan ini. Laporan ini juga dilengkapi dengan lampiran-lampiran sebagai bukti fisik.", $fontstyle, $paragraphstyle);
            $section1->addText("Diharapkan bahwa dengan selesainya laporan ini, semua kegiatan yang berhubungan dengan " . $pelatihan->nama_kegiatan . "dapat dipertanggungjawabkan.", $fontstyle, $paragraphstyle);
   
            $section1->addTextBreak(2);
            $section1->addText("Pekanbaru, $pelatihan->bulan_ttd_lap $pelatihan->tahun,", $fontstyle, ['alignment' => 'both', 'indentation' => ['left' => Converter::cmToTwip(9.75)]]);
            $section1->addText('Ketua Panitia', array_merge($fontstyle, ['bold' => true]), ['alignment' => 'both', 'indentation' => ['left' => Converter::cmToTwip(9.75)]]);
            $section1->addTextBreak(3);
            $section1->addText("{$pelatihan->ketua_panitia->nama}", array_merge($fontstyle, ['bold' => true]), ['alignment' => 'both', 'indentation' => ['left' => Converter::cmToTwip(9.75)]]);
            $section1->addText("NIP. {$pelatihan->ketua_panitia->NIP}", array_merge($fontstyle, ['bold' => true]), ['alignment' => 'both', 'indentation' => ['left' => Converter::cmToTwip(9.75)]]);
                
            
            // Daftar Isi
            $section1->addPageBreak();
            $section1->addTitle("DAFTAR ISI", 1);
            $toc1 = $section1->addTOC($tocFontStyle, $tocStyle);

            // BAB I
            $section1->addPageBreak();
            $section1->setStyle([
                'tabs' => [
                    new Tab('left', 3000),
                    new Tab('left', 3500)
                    ]
            ]);
            $section1->addTitle("BAB I", 1);
            $section1->addTitle("PENDAHULUAN", 1);
            $section1->addTextBreak(1);
            $section1->addTitle("A. Organisasi Diklat", 2);
            $section1->addText("Dalam Peraturan Menteri Agama Nomor 75 Tahun 2015 tentang Penyelenggaraan Pendidikan dan Pelatihan Pegawai pada Kementerian Agama dinyatakan bahwa Loka Diklat Keagamaan (LDK) adalah Unit Pelaksana Teknis Diklat  Kementerian Agama yang berkedudukan di daerah. LDK mempunyai tugas melaksanakan Diklat  Administrasi dan Diklat Tenaga Teknis Keagamaan bagi pegawai di wilayah masing-masing dengan berpedoman kepada Kebijakan Kepala Badan Litbang dan Diklat Kementerian Agama", $fontstyle, $paragraphstyle);
            $section1->addText("Dalam melaksanakan keputusan tersebut, Loka Diklat Keagamaan Pekanbaru melaksanakan suatu jenis pendidikan dan pelatihan, yaitu $pelatihan->nama_kegiatan" , $fontstyle, $paragraphstyle);
            
            $section1->addTitle("B. Nama Unit/Satuan Kerja", 2);
            $section1->addText("Nama unit/satuan kerja penyelenggara adalah Loka Pendidikan dan Pelatihan Keagamaan Pekanbaru Jl. Yos Sudarso Kel. Lembah Damai, Kec. Rumbai Pesisir 28263, Website http://www.ldkpekanbaru.kemenag.go.id, Email: loka.pekanbaru@kemenag.go.id, Telp. 0761 8034201.", $fontstyle, $paragraphstyle);
            
            $section1->addTitle("C. Nama Diklat yang Diselenggarakan", 2);
            $section1->addText("Nama Diklat yang diselenggarakan adalah “$pelatihan->nama_kegiatan di Wilayah Kerja Kantor Kementerian Agama $pelatihan->kab_kota ”", $fontstyle, $paragraphstyle);
            
            $section1->addTitle("D. Dasar Hukum", 2);
            $section1->addText("Dasar $pelatihan->nama_pelatihan bagi Wilayah Kerja Kementerian Agama $pelatihan->kab_kota ini adalah : ", $fontstyle, $paragraphstyle);

            $style = generate_list_style($phpword, 'decimal');

            $section1->addListItem("Undang-Undang Nomor 20 Tahun 2023 tentang Aparatur Sipil Negara (ASN)", 0, $fontstyle, $style);
            $section1->addListItem("Peraturan Pemerintah RI Nomor 11 tahun 2017 tentang Manajemen Pegawai Negeri Sipil", 0, $fontstyle, $style);
            $section1->addListItem("Peraturan Menteri Agama RI Nomor  42 Tahun 2016 tentang Organisasi dan Tata Kerja Kementerian Agama;", 0, $fontstyle, $style);
            $section1->addListItem("Peraturan Menteri Agama Nomor 10 Tahun 2018 tentang perubahan PMA No. 59 Tahun 2015 Tentang Organisasi dan Tata Keja Balai Pendidikan dan Pelatihan Keagamaan;", 0, $fontstyle, $style);
            $section1->addListItem("Keputusan Menteri Agama RI Nomor 75 Tahun 2015 tentang Penyelenggaraan Pendidikan dan Pelatihan Pegawai pada Kementerian Agama;", 0, $fontstyle, $style);
            $section1->addListItem("Keputusan Kepala Badan Litbang dan Diklat Kementerian Agama Nomor 62 Tahun 2017 Tentang Kurikulum Pendidikan dan Pelatihan Tenaga Teknis Pendidikan dan Keagamaan;", 0, $fontstyle, $style);
            $section1->addListItem("DIPA Loka Pendidikan dan Pelatihan Keagamaan Pekanbaru Tahun 2025 Nomor : SP-DIPA/025.11.2.690527/2025 Tanggal 02 Desember 2024;", 0, $fontstyle, $style);

            $section1->addTitle("E. Sumber Pembiayaan", 2);
            $section1->addText("Sumber biaya penyelenggaraan Pelatihan ini dibebankan pada DIPA Loka Diklat Keagamaan Pekanbaru Nomor : Nomor : SP-DIPA/025.11.2.690527/2025 Tanggal 02 Desember 2024;", $fontstyle, $paragraphstyle);
            
            $section1->addTitle("F. Susunan Panitia", 2);
            $section1->addText("Adapun susunan panitia penyelenggara $pelatihan->nama_kegiatan adalah sebegai berikut:", $fontstyle, $paragraphstyle);
            $style = generate_list_style($phpword, 'decimal');
            // $section1->addListItem("Ketua\t:{$pelatihan->ketua_panitia->nama}", 0, $fontstyle, $style);
            // $section1->addListItem("Seketaris/Akademis\t:{$pelatihan->akademis->nama}", 0, $fontstyle, $style);
            // $section1->addListItem("Anggota/Keuangan\t:{$pelatihan->keuangan->nama}", 0, $fontstyle, $style);
            // $section1->addListItem("Anggota\t:{$pelatihan->administrasi->nama}", 0, $fontstyle, $style);

        if (!empty($pelatihan->ketua_panitia) && !empty($pelatihan->ketua_panitia->nama)) {
            $section1->addListItem(
                "Ketua\t: {$pelatihan->ketua_panitia->nama}",
                0, $fontstyle, $style
            );
        }

        if (!empty($pelatihan->akademis) && !empty($pelatihan->akademis->nama)) {
            $section1->addListItem(
                "Seketaris/Akademis\t: {$pelatihan->akademis->nama}",
                0, $fontstyle, $style
            );
        }

        if (!empty($pelatihan->keuangan) && !empty($pelatihan->keuangan->nama)) {
            $section1->addListItem(
                "Anggota/Keuangan\t: {$pelatihan->keuangan->nama}",
                0, $fontstyle, $style
            );
        }

        if (!empty($pelatihan->administrasi) && !empty($pelatihan->administrasi->nama)) {
            $section1->addListItem(
                "Anggota\t: {$pelatihan->administrasi->nama}",
                0, $fontstyle, $style
            );
        }




            $section1->addTitle("G. Alamat Penyelenggara", 2);
            $section1->addText("Alamat Pelaksanaan $pelatihan->nama_pelatihan dilaksanakan di $pelatihan->alamat", $fontstyle, $paragraphstyle);

            $section1->addTitle("H. Waktu Pelaksanaan", 2);
            $section1->addText("Pelaksanaan Pelatihan berlangsung selama terhitung tanggal " . $data['tanggal_mulai'] . " s.d " . $data['tanggal_selesai'] . ".", $fontstyle, $paragraphstyle);

            $section1->addTitle("I. Tempat Pelaksanaan", 2);
            $section1->addText("Pelatihan dilaksanakan di $pelatihan->tempat", $fontstyle, $paragraphstyle);

            // BAB II
            $section1->addPageBreak();
            $section1->addTitle("BAB II", 1);
            $section1->addTitle("PELAKSANAAN DIKLAT", 1);
            $section1->addTextBreak(1); // Adds 1 line break
            
            $section1->addTitle("A. Tujuan dan Sasaran", 2);
            // $section2->addText("$pelatihan->nama_kegiatan di $pelatihan->tempat Tahun $pelatihan->tahun ini memiliki tujuan dan sasaran sebagai berikut :", $fontstyle, $paragraphstyle);
            
            $style = generate_list_style($phpword, 'decimal');

            $section1->addListItem("Tujuan", 0, array_merge($fontstyle, ['size' => 12]), $style);
            $section1->addText("Secara umum Diklat ini bertujuan untuk : ", $fontstyle, $paragraphstyle);
            
             $style = generate_list_style($phpword, 'lowerLetter');

            $section1->addListItem("Meningkatkan pengetahuan, keahlian, keterampilan dan sikap untuk dapat melaksanakan tugas jabatan secara profesional dengan dilandasi kepribadian dan kode etik pegawai sesuai dengan kebutuhan Kementerian Agama.", 0, $fontstyle, $style, ['indentation' => ['left' => 720, 'hanging' => 360]]);
            $section1->addListItem("Menciptakan aparatur yang mampu berperan sebagai pembaharu dan perekat persatuan dan kesatuan bangsa.", 0, $fontstyle, $style, ['indentation' => ['left' => 720, 'hanging' => 360]]);
            $section1->addListItem("Memantapkan sikap dan semangat pengabdian yang berorientasi pada pelayanan, pengayoman  dan pemberdayaan masyarakat.", 0, $fontstyle, $style, ['indentation' => ['left' => 720, 'hanging' => 360]]);
            $section1->addListItem("Menciptakan kesamaan visi, dinamika pola pikir dan mengembangkan  sinergi dalam melaksanakan tugas pemerintah umum dan pembangunan demi terwujudnya kepemerintahan yang baik dan bersih.", 0, $fontstyle, $style, ['indentation' => ['left' => 720, 'hanging' => 360]]);
            $style = generate_list_style($phpword, 'decimal', 2);

            $section1->addListItem("Sasaran", 0, $fontstyle, $style);
            $section1->addText("Adapun sasaran Pelatihan $pelatihan->nama_kegiatan Tahun $pelatihan->tahun adalah tersedianya $pelatihan->jumlah_peserta orang alumni pelatihan yang cakap dan kompeten dalam menjalankan tugas dan fungsi sesuai jabatan yang di emban", $fontstyle, $paragraphstyle);
            
            $section1->addTitle("B. Implementasi Kurikulum", 2);
            // Get first materi object
            $materi = !empty($pelatihan->materi) ? $pelatihan->materi[0] : null;

            if ($materi) {
                $section1->addText(
                    "Kurikulum Pelatihan ini disesuaikan dengan kurikulum $materi->asal_kursil Badan Litbang dan Diklat Kementerian Agama Republik Indonesia.",
                    $fontstyle,
                    $paragraphstyle
                );
            } else {
                $section1->addText(
                    "Kurikulum Pelatihan ini disesuaikan dengan kurikulum Badan Litbang dan Diklat Kementerian Agama Republik Indonesia.",
                    $fontstyle,
                    $paragraphstyle
                );
            }
            
            $style1 = generate_list_style($phpword, 'decimal');

            $section1->addListItem("Mata Diklat dan Jumlah Jam Pelajaran", 0, $fontstyle, $style1);
            $section1->addText("Mata Diklat $pelatihan->nama_pelatihan ini sebanyak $materi->jumlah_jp jam pelajaran dengan jenis mata diklat  materi kelompok dasar $materi->jp_kel_dasar, kelompok inti $materi->jp_kel_inti JP dan kelompok penunjang $materi->jp_kel_penunjang JP. ", $fontstyle, $paragraphstyle);
            $section1->addText("Kelompok dasar yaitu kelompok mata pelajaran yang bertujuan untuk menanamkan, memperkuat dan meningkatkan profesionalisme, kesetiaan dan ketaatan peserta sebagai dasar dalam melaksanakan tugas jabatannya sebagai abdi negara dan abdi masyarakat. Kelompok Inti yaitu kelompok mata pelajaran yang bertujuan untuk membekali peserta dengan pengetahuan dibidang tugas pokok yang bersangkutan. Kelompok penunjang adalah kelompok mata pelajaran yang bertujuan untuk memperluas pengetahuan dan wawasan, serta mempertajam pemahaman dan penghayatan peserta terhadap berbagai faktor, termasuk lingkungan", $fontstyle, $paragraphstyle);
            
            $section1->addListItem("Jadwal Diklat (Terlampir)", 0, $fontstyle, $style1);

            $section1->addTitle("C. Rencana dan Realisasi Peserta", 2);
            $section1->addText("Jumlah, asal daerah, status kepegawaian, dan jenis kelamin peserta Pelatihan sebagai berikut :", $fontstyle, $paragraphstyle);
            
            $style2 = generate_list_style($phpword, 'decimal');
            $section1->addListItem("Jumlah dan Asal Peserta", 0, $fontstyle, $style2);
            $section1->addText("Peserta terdiri dari $pelatihan->jumlah_peserta orang $pelatihan->jabatan_peserta yang ada di wilayah kerja Kantor Kementerian Agama $pelatihan->kab_kota", $fontstyle, $paragraphstyle);

            $section1->addListItem("Status Kepegawaian dan Jenis Kelamin", 0, $fontstyle, $style2);
            $section1->addText("Peserta terdiri dari ASN sebanyak $pelatihan->jumlah_peserta_asn orang dan non ASN sebanyak $pelatihan->jumlah_peserta_non_asn orang dengan rincian $pelatihan->jumlah_peserta_laki orang laki-laki dan $pelatihan->jumlah_peserta_wanita orang perempuan. Pendidikan terakhir dari peserta adalah sebagai berikut:", $fontstyle, $paragraphstyle);

            $section1->addText("SMA/MA\t: $pelatihan->jumlah_pendidikan_peserta_sma", $fontstyle, $paragraphstyle);
            $section1->addText("D3\t: $pelatihan->jumlah_pendidikan_peserta_d3", $fontstyle, $paragraphstyle);
            $section1->addText("S1\t: $pelatihan->jumlah_pendidikan_peserta_s1", $fontstyle, $paragraphstyle);
            $section1->addText("S2\t: $pelatihan->jumlah_pendidikan_peserta_s2", $fontstyle, $paragraphstyle);
            $section1->addText("S3\t: $pelatihan->jumlah_pendidikan_peserta_s3", $fontstyle, $paragraphstyle);

            $section1->addTitle("D. Rencana dan Realisasi Widyaiswara/Tenaga Pengajar", 2);
            $section1->addText("Jumlah, asal daerah, dan jenjang akademik Widyaiswara/Tenaga Pengajar Pelatihan ini adalah sebagai berikut :", $fontstyle, $paragraphstyle);

            $style3 = generate_list_style($phpword, 'decimal');
            $section1->addListItem("Jumlah dan Asal Widyaiswara/Tenaga Pengajar", 0, $fontstyle, $style3);

            // ======== NEW: prefer tbl_pelatihan_pengajar (non-Latsar) ========
            if ($isNonLatsar) {
                $section1->addText("Jumlah widyaiswara dan tenaga pengajar adalah {$jumlahWiPengajar} orang. Widyaiswara terdiri dari:", $fontstyle, $paragraphstyle);

                // Widyaiswara
                $style = generate_list_style($phpword, 'decimal');
                foreach ($wiList as $wi) {
                    $asal = trim((string)($wi->asal_satker ?? ''));
                    $section1->addListItem(
                        "{$wi->nama}" . ($asal ? " berasal dari {$asal}" : ""),
                        0, $fontstyle, $style,
                        ['indentation' => ['left' => 720, 'hanging' => 360]]
                    );
                }

                // WI rapat kelulusan (opsional)
                if (!empty($wiRapat)) {
                    $section1->addText("Widyaiswara Rapat Kelulusan: {$wiRapat->nama}", $fontstyle, $paragraphstyle);
                }

                // Pengajar
                $section1->addText("Tenaga Pengajar terdiri dari :", $fontstyle, $paragraphstyle);
                foreach ($pengajarList as $pg) {
                    $labelJab = !empty($pg->jabatan) ? " ({$pg->jabatan})" : "";
                    $section1->addListItem(
                        "{$pg->nama}{$labelJab}",
                        0, $fontstyle, $style,
                        ['indentation' => ['left' => 720, 'hanging' => 360]]
                    );
                }
            } else {
                // ======== Fallback: legacy fields for Latsar / old data ========
                $section1->addText("Jumlah widyaiswara dan tenaga pengajar adalah {$jumlahWiPengajar} orang. Widyaiswara terdiri dari :", $fontstyle, $paragraphstyle);

                $style = generate_list_style($phpword, 'decimal');

                if (isset($pelatihan->wi_1)) {
                    $section1->addListItem("{$pelatihan->wi_1->nama} berasal dari {$pelatihan->wi_1->asal_satker}", 0, $fontstyle, $style, ['indentation' => ['left' => 720, 'hanging' => 360]]);
                }
                if (isset($pelatihan->wi_2)) {
                    $section1->addListItem("{$pelatihan->wi_2->nama} berasal dari {$pelatihan->wi_2->asal_satker}", 0, $fontstyle, $style, ['indentation' => ['left' => 720, 'hanging' => 360]]);
                }
                if (isset($pelatihan->wi_3)) {
                    $section1->addListItem("{$pelatihan->wi_3->nama} berasal dari {$pelatihan->wi_3->asal_satker}", 0, $fontstyle, $style, ['indentation' => ['left' => 720, 'hanging' => 360]]);
                }
                if (isset($pelatihan->wi_4)) {
                    $section1->addListItem("{$pelatihan->wi_4->nama} berasal dari {$pelatihan->wi_4->asal_satker}", 0, $fontstyle, $style, ['indentation' => ['left' => 720, 'hanging' => 360]]);
                }

                $section1->addText("Tenaga Pengajar terdiri dari :", $fontstyle, $paragraphstyle);
                if (isset($pelatihan->pengajar_1)) {
                    $section1->addListItem("{$pelatihan->pengajar_1->nama} {$pelatihan->pengajar_1->jabatan}", 0, $fontstyle, $style, ['indentation' => ['left' => 720, 'hanging' => 360]]);
                }
                if (isset($pelatihan->pengajar_2)) {
                    $section1->addListItem("{$pelatihan->pengajar_2->nama} {$pelatihan->pengajar_2->jabatan}", 0, $fontstyle, $style, ['indentation' => ['left' => 720, 'hanging' => 360]]);
                }
                if (isset($pelatihan->pengajar_3)) {
                    $section1->addListItem("{$pelatihan->pengajar_3->nama} {$pelatihan->pengajar_3->jabatan}", 0, $fontstyle, $style, ['indentation' => ['left' => 720, 'hanging' => 360]]);
                }
            }
            // ======== /NEW ========

            $section1->addListItem("Jenjang Akademik/Kualifikasi Widyaiswara/Tenaga Pengajar", 0, $fontstyle, $style3);
            $section1->addText("Jenjang akademik/kualifikasi pendidikan widyaiswara/tenaga pengajar pada pelatihan ini adalah :", $fontstyle, $paragraphstyle);

            $section1->addText("Setara D2/D3\t: {$pelatihan->jumlah_pendidikan_wi_d2} orang", $fontstyle, $paragraphstyle);
            $section1->addText("S1\t: {$pelatihan->jumlah_pendidikan_wi_s1} orang", $fontstyle, $paragraphstyle);
            $section1->addText("S2\t: {$pelatihan->jumlah_pendidikan_wi_s2} orang", $fontstyle, $paragraphstyle);
            $section1->addText("S3\t: {$pelatihan->jumlah_pendidikan_wi_s3} orang", $fontstyle, $paragraphstyle);
            $section1->addText("(curriculum vitae/biodata terlampir).", $fontstyle, $paragraphstyle);
            $section1->addListItem("Daftar hadir Narasumber (Terlampir)", 0, $fontstyle, $style3);

            
            $section1->addTitle("E. Hasil Evaluasi Pelatihan", 2);
            $section1->addText("Evaluasi dilakukan terhadap penyelenggara, widyaiswara/Tenaga Pengajar, dan peserta sebagai berikut :", $fontstyle, $paragraphstyle);

            $style = generate_list_style($phpword, 'decimal');

            $section1->addListItem("Hasil Evaluasi Terhadap Peserta Pelatihan (terlampir)", 0, $fontstyle, $style);
            $section1->addListItem("Hasil Evaluasi Terhadap Widyaiswara/Narasumber (terlampir)", 0, $fontstyle, $style);
            $section1->addListItem("Hasil Evaluasi terhadap Panitia Penyelenggara Pelatihan (terlampir)", 0, $fontstyle, $style);

            $section1->addTitle("F. Realisasi Konsumsi dan Akomodasi", 2);

            $style = generate_list_style($phpword, 'lowerLetter');

            $section1->addListItem("Konsumsi", 0, $fontstyle, $style);
            $section1->addText("Terdapat pada Lampiran RAB", $fontstyle, $paragraphstyle);
            
            $section1->addListItem("Akomodasi", 0, $fontstyle, $style);
            $section1->addText("Asrama	: -", $fontstyle, $paragraphstyle);
            $section1->addText("Ruang Belajar	: Memadai tersedia sesuai kapasitas", $fontstyle, $paragraphstyle);
            $section1->addText("Alat bantu belajar	: Tersedia", $fontstyle, $paragraphstyle);
            $section1->addText("Ruang Makan	: -", $fontstyle, $paragraphstyle);
            $section1->addText("Sarana Olah Raga  	: -", $fontstyle, $paragraphstyle);

            $section1->addListItem("Rencana RAB", 0, $fontstyle, $style);
            $section1->addText("Terlampir rencana RAB (seluruh akun kegiatan)", $fontstyle, $paragraphstyle);

            $section1->addListItem("Realisasi RAB", 0, $fontstyle, $style);
            $section1->addText("Terlampir realisasi RAB (seluruh akun kegiatan)", $fontstyle, $paragraphstyle);

            $section1->addTitle("G. Penjaminan Mutu", 2);
            $section1->addText("Dalam rangka penjaminan mutu dilakukan usaha pembelajaran yang maksimal 10 JP per hari dengan ketentuan materi 30% teori dan 70% praktek. Dengan demikian, peserta dapat memahami materi lebih dalam serta mendapat waktu istirahat yang cukup, selain itu dilakukan monitoring setiap hari terhadap pelaksanaan pembelajaran yang terjadi di dalam ruangan kelas", $fontstyle, $paragraphstyle);

            //BAB III
            $section1->addPageBreak();
            $section1->addTitle("BAB III", 1);
            $section1->addTitle("PENUTUP", 1);
            $section1->addTextBreak(1); // Adds 1 line break
            $section1->addText("Dengan berakhirnya pelatihan ini, diharapkan peserta dapat menerapkan ilmu yang telah diperoleh dalam pekerjaan mereka sehari-hari. Kami mengucapkan terima kasih kepada seluruh pihak yang telah berkontribusi dalam pelaksanaan pelatihan ini, termasuk para peserta, narasumber, panitia, dan semua yang terlibat", $fontstyle, $paragraphstyle);
            $section1->addTextBreak(1); // Adds 1 line break
            $section1->addText("Semoga ilmu dan pengalaman yang didapatkan dalam pelatihan ini dapat bermanfaat serta memberikan dampak positif dalam peningkatan kompetensi dan profesionalisme. Kami berharap kegiatan serupa dapat terus diadakan untuk mendukung pengembangan sumber daya manusia yang lebih baik. Segala bentuk pesan dan kritikan akan laporan pelaksanaan akan sangat dibutuhkan untuk koreksi agar kegiatan berikutnya menjadi lebih baik lagi.", $fontstyle, $paragraphstyle);
            $section1->addTextBreak(1); // Adds 1 line break

            $section1->addText("Pekanbaru, $pelatihan->bulan_ttd_lap $pelatihan->tahun,", $fontstyle, ['alignment' => 'both', 'indentation' => ['left' => Converter::cmToTwip(9.75)]]);
            $section1->addText('Ketua Panitia', array_merge($fontstyle, ['bold' => true]), ['alignment' => 'both', 'indentation' => ['left' => Converter::cmToTwip(9.75)]]);
            $section1->addTextBreak(3);
            $section1->addText("{$pelatihan->ketua_panitia->nama}", array_merge($fontstyle, ['bold' => true]), ['alignment' => 'both', 'indentation' => ['left' => Converter::cmToTwip(9.75)]]);
            $section1->addText("NIP. {$pelatihan->ketua_panitia->NIP}", array_merge($fontstyle, ['bold' => true]), ['alignment' => 'both', 'indentation' => ['left' => Converter::cmToTwip(9.75)]]);

            $coverSection2 = $phpword->addSection([
            'pageSizeW' => \PhpOffice\PhpWord\Shared\Converter::inchToTwip(8.5),
            'pageSizeH' => \PhpOffice\PhpWord\Shared\Converter::inchToTwip(11),
            'marginTop' => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(1),
            'marginLeft' => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(1),
            'marginRight' => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(1),
            'marginBottom' => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(1),
        ]);

        // Add logo (adjust path and size as needed)
        $coverSection2->addImage(
            'assets/cover/Logo_Kemenag.png',
            [
                'width' => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(0.2),
                'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER,
                'marginTop' => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(1),
            ]
        );

        $coverSection2->addTextBreak(2); // Add space before footer

        // Add "TERM OF REFERENCES" text
        $coverSection2->addText(
            'TERM OF REFERENCES',
            [
                'name' => 'Times New Roman',
                'size' => 14,
                'bold' => true,
                'allCaps' => true,
            ],
            [
                'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER,
                'spaceAfter' => 150,
            ]
        );

        $coverSection2->addText(
            strtoupper($pelatihan->nama_kegiatan),
            [
                'name' => 'Times New Roman',
                'size' => 16,
                'bold' => true,
                'color' => '000000',
                'allCaps' => true,
            ],
            [
                'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER,
                'spaceAfter' => 300,
            ]
        );

            // Add location information
        $coverSection2->addText(
            'Di Wilayah Kerja Kantor Kementerian Agama Kabupaten ' . $pelatihan->kab_kota,
            [
                'name' => 'Times New Roman',
                'size' => 12,
            ],
            [
                'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER,
                'spaceAfter' => 300,
            ]
        );

               // Add logo (adjust path and size as needed)
        $coverSection2->addImage(
            'assets/cover/three_line_cover.png',
            [
                'width' => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(0.1),
                'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER,
                'marginTop' => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(1),
                'spaceAfter' => 600,
            ]
        );

         $coverSection2->addTextBreak(3); // Add space before footer

            $coverSection2->addText(
                'KEMENTERIAN AGAMA',
                [
                    'name' => 'Times New Roman',
                    'size' => 12,
                    'bold' => true,
                ],
                [
                    'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER,
                    'spaceAfter' => 100,
                ]
            );

         $coverSection2->addText(
                'LOKA PENDIDIKAN DAN PELATIHAN KEAGAMAAN PEKANBARU',
                [
                    'name' => 'Times New Roman',
                    'size' => 12,
                    'bold' => true,
                ],
                [
                    'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER,
                    'spaceAfter' => 100,
                ]
            );

            $coverSection2->addText(
                "TAHUN $pelatihan->tahun",
                [
                    'name' => 'Times New Roman',
                    'size' => 12,
                    'bold' => true,
                ],
                [
                    'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER,
                ]
            );

            // Kata Pengantar
            $section2 = $phpword->addSection();
            $section2->addTitle("KATA PENGANTAR", 1);
            $section2->addTextBreak(1); // Adds 1 line break
            $section2->addText("Puji dan syukur kita panjatkan kehadirat Tuhan Yang Maha Esa, karena berkat rahmat serta karunia-Nya Term Of References (TOR) pelaksanaan kegiatan $pelatihan->nama_kegiatan ini dapat disusun.", $fontstyle, $paragraphstyle);
            $section2->addText("TOR pelatihan ini memuat latar belakang, dasar hukum, maksud dan tujuan, kepesertaan, metode pelaksanaan kegiatan, waktu dan tempat pelaksanaan, serta biaya kegiatan. TOR ini diharapkan dapat menjadi acuan tata kelola pelatihan dari tahap persiapan hingga pelaporan.", $fontstyle, $paragraphstyle);
            $section2->addText("Akhir kata, kritik dan saran yang bersifat membangun sangat diharapkan dalam upaya pengembangan TOR ini guna peningkatan dan perbaikan kualitas pelatihan di Loka Diklat Keagamaan Pekanbaru dimasa yang akan datang", $fontstyle, $paragraphstyle);

            $section2->addTextBreak(1);
            $section2->addText("Pekanbaru, $pelatihan->bulan_ttd_lap $pelatihan->tahun,", $fontstyle, ['alignment' => 'both', 'indentation' => ['left' => Converter::cmToTwip(9.75)]]);
            $section2->addText('Kepala Loka Pendidikan dan Pelatihan Keagamaan Pekanbaru', array_merge($fontstyle, ['bold' => true]), ['alignment' => 'both', 'indentation' => ['left' => Converter::cmToTwip(9.75)]]); 
            $section2->addTextBreak(3);
            $section2->addText("$ketua_loka->nama", array_merge($fontstyle, ['bold' => true]), ['alignment' => 'both', 'indentation' => ['left' => Converter::cmToTwip(9.75)]]);
            $section2->addText("NIP. $ketua_loka->NIP", array_merge($fontstyle, ['bold' => true]), ['alignment' => 'both', 'indentation' => ['left' => Converter::cmToTwip(9.75)]]);

            //Term of References
            $section2->addPageBreak();
            $section2->addTitle("TERM OF REFERENCES", 1);
            $section2->addTitle("$pelatihan->nama_kegiatan", 1);
            $section2->addTextBreak(1);
            $section2->addTitle("A. Latar Belakang", 2);
            $section2->addText("Peraturan Menteri Agama RI Nomor 15 Tahun 2021 Tentang Organisasi dan Tata Kerja Unit Pelaksana Teknis Pendidikan dan Pelatihan Keagamaan terkait penyelenggaraan Pendidikan dan Pelatihan Pegawai Negeri Sipil di lingkungan Kantor Kementerian Agama bahwa UPT Pendidikan dan Pelatihan Keagamaan mempunyai tugas melaksanakan pendidikan dan pelatihan tenaga administrasi dan tenaga teknis pendidikan dan keagamaan kepada ASN Kementerian Agama di wilayah kerja masing-masing dengan berpedoman kepada kebijakan Kepala Badan Litbang dan pelatihan Kementerian Agama.", $fontstyle, $paragraphstyle);
            $section2->addText("Pelatihan di Wilayah Kerja (PDWK) adalah pelatihan yang dilaksanakan di luar kampus pada wilayah kerja Pusdiklat dan Balai Diklat Keagamaan, berdasarkan pertimbangan kebutuhan dan tujuan mengembangkan kompetensi teknis substantif peserta pelatihan di wilayah tersebut. Pelaksanaan PDWK pada Pusdiklat berbasis provinsi atau gabungan provinsi. Pelaksanaan PDWK pada Balai Diklat Keagamaan berbasis kabupaten/kota atau gabungan kabupaten/kota.", $fontstyle, $paragraphstyle);
            $section2->addText("Berdasarkan hasil Analisis Kebutuhan Pelatihan (AKP) Loka Pelatihan Keagamaan Pekanbaru tahun 2023, jenis pelatihan yang perlu dilaksanakan adalah $pelatihan->nama_kegiatan. Berdasarkan realita tersebut, Loka Pendidikan dan Pelatihan Keagamaan Pekanbaru memandang perlu melaksanakan $pelatihan->nama_kegiatan di Wilayah Kerja Kantor Kementerian Agama $pelatihan->kab_kota", $fontstyle, $paragraphstyle);

            $section2->addTitle("B. Dasar Hukum", 2);

            $style = generate_list_style($phpword, 'decimal');

            $section2->addListItem("Undang-Undang Nomor 20 Tahun 2023 tentang Aparatur Sipil Negara;", 0, $fontstyle, $style, ['indentation' => ['left' => 720, 'hanging' => 360]]);
            $section2->addListItem("Peraturan Pemerintah Republik Indonesia Nomor 17 Tahun 2020 Tentang Perubahan Atas Peraturan Pemerintah Nomor 11 Tahun 2017 Tentang Manajemen Pegawai Negeri Sipil;", 0, $fontstyle, $style, ['indentation' => ['left' => 720, 'hanging' => 360]]);
            $section2->addListItem("Peraturan Presiden Nomor 12 Tahun 2023 tentang Kementerian Agama (Lembaran Negara Republik Indonesia Tahun 2023 Nomor 21);", 0, $fontstyle, $style, ['indentation' => ['left' => 720, 'hanging' => 360]]);
            $section2->addListItem("Peraturan Menteri Pendayagunaan Aparatur Negara Nomor PER/18/M.PAN/II/2003 tentang Pedoman Organisasi Unit Pelaksana Teknis Kementerian dan Lembaga Pemerintahan Nonkementerian;", 0, $fontstyle, $style, ['indentation' => ['left' => 720, 'hanging' => 360]]);
            $section2->addListItem("Peraturan Menteri Agama Nomor 59 Tahun 2015 tentang Organisasi Tata Kerja Balai Diklat dan Pelatihan Keagamaan;", 0, $fontstyle, $style, ['indentation' => ['left' => 720, 'hanging' => 360]]);
            $section2->addListItem("Peraturan Menteri Agama RI Nomor  42 Tahun 2016 tentang Organisasi dan Tata Kerja Kementerian Agama;", 0, $fontstyle, $style, ['indentation' => ['left' => 720, 'hanging' => 360]]);
            $section2->addListItem("Peraturan Menteri Agama Nomor 15 Tahun 2021 tentang Organisasi Dan Tata Kerja Unit Pelaksana Teknis Pendidikan Dan Pelatihan Keagamaan;", 0, $fontstyle, $style, ['indentation' => ['left' => 720, 'hanging' => 360]]);
            $section2->addListItem("DIPA Loka Pendidikan dan Pelatihan Keagamaan Pekanbaru Tahun 2025 Nomor : SP-DIPA/025.11.2.690527/2025 Tanggal 02 Desember 2024.", 0, $fontstyle, $style, ['indentation' => ['left' => 720, 'hanging' => 360]]);

            $section2->addTitle("C. Nama Pelatihan", 2);
            $section2->addText("Sesuai dengan kurikulum yang berlaku pelatihan ini bernama $pelatihan->nama_kegiatan di $pelatihan->tempat.", $fontstyle, $paragraphstyle);
            
            $section2->addTitle("D. Tujuan Pelatihan", 2);
            $section2->addText("Pelatihan ini bertujuan:", $fontstyle, $paragraphstyle);

            $style = generate_list_style($phpword, 'decimal');

            if (!empty($pelatihan->materi)) {
                foreach ($pelatihan->materi as $materi) {
                    if (!empty($materi->parsed_tujuan)) {
                        foreach ($materi->parsed_tujuan as $item) {
                            $section2->addListItem($item['judul'], 0, $fontstyle, $style, ['indentation' => ['left' => 720, 'hanging' => 360]]);
                            if (!empty($item['deskripsi'])) {
                                $section2->addText($item['deskripsi'], $fontstyle, $paragraphstyle2);
                            }
                        }
                    }
                }
            }

            
            $section2->addTitle("E. Peserta Pelatihan", 2);
            $section2->addText("Peserta pelatihan ini berjumlah $pelatihan->jumlah_peserta orang yang terdiri dari $pelatihan->jabatan_peserta yang belum pernah mengikuti pelatihan sejenis dan diberi tugas oleh pejabat berwenang untuk mengikuti pelatihan tersebut.", $fontstyle, $paragraphstyle);
            
            $section2->addTitle("F. Pelaksanaan Kegiatan", 2);
            $section2->addText("Prinsip pelaksanaan Pelatihan adalah pembelajaran bagi orang dewasa (andragogi). Sesuai sasaran capaian kompetensi yang harus diperoleh para Pegawai ASN Kementerian Agama, maka pemilihan metode pengajaran harus disesuaikan dengan karakteristik peserta pelatihan dan aktualisasi kegiatan yang dilaksanakan di lapangan. Untuk itu, metodologi yang memungkinkan untuk dilaksanakan sebagai berikut :", $fontstyle, $paragraphstyle);
           
            $style = generate_list_style($phpword, 'bullet');

            // Common indentation array
            $indentation = ['indentation' => ['left' => 720, 'hanging' => 360]];

            $section2->addListItem("Tanya jawab", 0, $fontstyle, $style, $indentation);
            $section2->addListItem("Pemberian tugas", 0, $fontstyle, $style, $indentation);
            $section2->addListItem("Upload video / bahan tayang / bahan ajar", 0, $fontstyle, $style, $indentation);
            $section2->addListItem("Eksplorasi pengalaman peserta", 0, $fontstyle, $style, $indentation);
            $section2->addListItem("Ekplorasi kebutuhan peserta", 0, $fontstyle, $style, $indentation);
            $section2->addListItem("Ceramah", 0, $fontstyle, $style, $indentation);
            $section2->addListItem("Latihan", 0, $fontstyle, $style, $indentation);
            $section2->addListItem("Studi kasus", 0, $fontstyle, $style, $indentation);
            $section2->addListItem("Demonstrasi", 0, $fontstyle, $style, $indentation);
            $section2->addListItem("Diskusi", 0, $fontstyle, $style, $indentation);

            $section2->addTitle("G. Tempat dan Waktu Pelaksanaan", 2);
            $section2->addText("Pelatihan ini dilaksanakan di $pelatihan->tempat " . $data['tanggal_mulai'] . " s.d " . $data['tanggal_selesai'] . " dengan jumlah $materi->jumlah_jp Jam Pelajaran (JP).", $fontstyle, $paragraphstyle);

            $section2->addTitle("H. Panitia dan Tenaga Pengajar", 2);
            $section2->addText("Penyelenggara pelatihan ini adalah Loka Pendidikan dan Pelatihan Keagamaan Pekanbaru , dengan susunan panitia sebagai berikut : ", $fontstyle, $paragraphstyle);
            $phpword->setDefaultParagraphStyle(['tabs' => [ new Tab('left', 3000) ]]);

            if (isset($pelatihan->penanggung_jawab))  { $section2->addText("Penanggung Jawab\t: {$pelatihan->penanggung_jawab->nama}"); }
            if (isset($pelatihan->ketua_panitia))     { $section2->addText("Ketua Panitia\t: {$pelatihan->ketua_panitia->nama}"); }
            if (isset($pelatihan->akademis))          { $section2->addText("Bidang Akademis\t: {$pelatihan->akademis->nama}"); }
            if (isset($pelatihan->administrasi))      { $section2->addText("Bidang Administrasi\t: {$pelatihan->administrasi->nama}"); }
            if (isset($pelatihan->keuangan))          { $section2->addText("Bidang Keuangan\t: {$pelatihan->keuangan->nama}"); }

            // ======== NEW: WI & Pengajar listing ========
            if ($isNonLatsar) {
                // WI
                if (!empty($wiList)) {
                    $section2->addText("Widyaiswara\t:", $fontstyle, $paragraphstyle);
                    $n = 1;
                    foreach ($wiList as $wi) {
                        $section2->addText("\t: {$n}. {$wi->nama}");
                        $n++;
                    }
                } else {
                    $section2->addText("Widyaiswara\t: -");
                }

                // WI rapat kelulusan
                if (!empty($wiRapat)) {
                    $section2->addText("WI Rapat Kelulusan\t: {$wiRapat->nama}");
                }

                // Pengajar
                if (!empty($pengajarList)) {
                    $section2->addText("Tenaga Pengajar\t:", $fontstyle, $paragraphstyle);
                    $n = 1;
                    foreach ($pengajarList as $pg) {
                        $section2->addText("\t: {$n}. {$pg->nama}");
                        $n++;
                    }
                } else {
                    $section2->addText("Tenaga Pengajar\t: -");
                }
            } else {
                // Fallback (legacy)
                if (isset($pelatihan->wi_1)) { $section2->addText("Widyaiswara\t: 1. {$pelatihan->wi_1->nama}"); }
                if (isset($pelatihan->wi_2)) { $section2->addText("\t: 2. {$pelatihan->wi_2->nama}"); }
                if (isset($pelatihan->wi_3)) { $section2->addText("\t: 3. {$pelatihan->wi_3->nama}"); }

                if (isset($pelatihan->pengajar_1)) { $section2->addText("Tenaga Pengajar\t: 1. {$pelatihan->pengajar_1->nama}"); }
                if (isset($pelatihan->pengajar_2)) { $section2->addText("\t: 2. {$pelatihan->pengajar_2->nama}"); }
                if (isset($pelatihan->pengajar_3)) { $section2->addText("\t: 3. {$pelatihan->pengajar_3->nama}"); }
            }
            // ======== /NEW ========

            // Fix the missing number in your sentence:
            $section2->addText("Tenaga pengajar dalam pelatihan ini berjumlah {$jumlahWiPengajar} orang dengan persyaratan:", $fontstyle, $paragraphstyle);

            $style = generate_list_style($phpword, 'decimal');

            $section2->addListItem("Widyaiswara;", 0, [], $style, ['indentation' => ['left' => 720, 'hanging' => 360]]);
            $section2->addListItem("Pejabat Struktural Loka Pendidikan dan Pelatihan Keagamaan Pekanbaru;", 0, [], $style, ['indentation' => ['left' => 720, 'hanging' => 360]]);
            $section2->addListItem("Pejabat Struktural dari Kementerian Agama;", 0, [], $style, ['indentation' => ['left' => 720, 'hanging' => 360]]);
            $section2->addListItem("Mampu mengisi waktu yang diberikan oleh panitia dengan kegiatan pembelajaran dengan materi yang sesuai ditetapkan dalam jadwal;", 0, [], $style, ['indentation' => ['left' => 720, 'hanging' => 360]]);
            $section2->addListItem("Mempersiapkan bahan yang dibutuhkan untuk setiap rincian kegiatan yang dipersyaratkan panitia;", 0, [], $style, ['indentation' => ['left' => 720, 'hanging' => 360]]);

            $section2->addTitle("I. Materi Pelatihan", 2);
            $section2->addText("Materi pelatihan ini terlampir sesuai dengan Kurikulum dan Silabus Pelatihan.", $fontstyle, $paragraphstyle);
            
            $section2->addTitle("J. Jadwal Tentatif", 2);
            $section2->addText("Jadwal tentatif pelatihan ini terlampir.", $fontstyle, $paragraphstyle);
            
            $section2->addTitle("K. Pembiayaan", 2);
            $section2->addText("Pembiayaan Pelatihan ini dibebankan kepada DIPA Loka Pendidikan dan Pelatihan Keagamaan Pekanbaru Tahun 2025 Nomor : SP-DIPA/025.11.2.690527/2025 Tanggal 02 Desember 2024.", $fontstyle, $paragraphstyle);
            
            $section2->addTitle("L. Penutup", 2);
            $section2->addText("Demikian Term of reference (TOR) ini dibuat dengan harapan dapat menjadi pedoman pelaksanaan pelatihan ini.", $fontstyle, $paragraphstyle);

            $section2->addText("Pekanbaru, $pelatihan->bulan_ttd_lap $pelatihan->tahun,", $fontstyle, ['alignment' => 'both', 'indentation' => ['left' => Converter::cmToTwip(9.75)]]);
            $section2->addText('Kepala Loka Pendidikan dan Pelatihan Keagamaan Pekanbaru', array_merge($fontstyle, ['bold' => true]), ['alignment' => 'both', 'indentation' => ['left' => Converter::cmToTwip(9.75)]]); 
            $section2->addTextBreak(3);
            $section2->addText("$ketua_loka->nama", array_merge($fontstyle, ['bold' => true]), ['alignment' => 'both', 'indentation' => ['left' => Converter::cmToTwip(9.75)]]);
            $section2->addText("NIP. $ketua_loka->NIP", array_merge($fontstyle, ['bold' => true]), ['alignment' => 'both', 'indentation' => ['left' => Converter::cmToTwip(9.75)]]);

            //Buku Panduan//
            // Cover Buku Panduan
             $coverSection3 = $phpword->addSection([
            'pageSizeW' => \PhpOffice\PhpWord\Shared\Converter::inchToTwip(8.5),
            'pageSizeH' => \PhpOffice\PhpWord\Shared\Converter::inchToTwip(11),
            'marginTop' => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(1),
            'marginLeft' => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(1),
            'marginRight' => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(1),
            'marginBottom' => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(1),
        ]);

        // Add background image with proper centering
        $imageWidth = \PhpOffice\PhpWord\Shared\Converter::cmToTwip(1.05); // ~3.9 inches wide
        $coverSection3->addImage(
            'assets/cover/Cover_PDWK_Panduan.png',
            [
                'width' => $imageWidth,
                'positioning' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
                'posHorizontal' => \PhpOffice\PhpWord\Style\Image::POSITION_HORIZONTAL_CENTER,
                'posHorizontalRel' => \PhpOffice\PhpWord\Style\Image::POSITION_RELATIVE_TO_PAGE,
                'posVertical' => \PhpOffice\PhpWord\Style\Image::POSITION_VERTICAL_CENTER,
                'posVerticalRel' => \PhpOffice\PhpWord\Style\Image::POSITION_RELATIVE_TO_PAGE,
                'wrap' => \PhpOffice\PhpWord\Style\Image::WRAP_BEHIND,
            ]
        );

        // Rest of your cover content remains the same...
        $coverSection3->addTextBreak(10); // Adds 4 line breaks (you can adjust this number)
        // Add location information
            $coverSection3->addText(
                'PELATIHAN DI WILAYAH KERJA (PDWK)',
                [
              'name' => 'Times New Roman',
                'size' => 16,
                'bold' => true,
                'color' => '000000',
                'allCaps' => true,
                ],
                [
                    'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER,
                    'spaceAfter' => 300,
                ]
            );
        $coverSection3->addText(
            strtoupper($pelatihan->nama_kegiatan),
            [
                'name' => 'Times New Roman',
                'size' => 16,
                'bold' => true,
                'color' => '000000',
                'allCaps' => true,
            ],
            [
                'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER,
                'spaceAfter' => 300,
            ]
        );

            // Add location information
            $coverSection3->addText(
                'Di Wilayah Kerja Kantor Kementerian Agama Kabupaten ' . $pelatihan->kab_kota,
                [
                    'name' => 'Times New Roman',
                    'size' => 12,
                ],
                [
                    'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER,
                    'spaceAfter' => 300,
                ]
            );

            // Add date information (using your dynamic data)
            $coverSection3->addText("" . $data['tanggal_mulai'] . " s.d " . $data['tanggal_selesai'] . ".",
                [
                    'name' => 'Times New Roman',
                    'size' => 12,
                ],
                [
                    'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER,
                    'spaceAfter' => 600,
                ]
            );

            // // Add PANITIA header
            // $coverSection3->addText(
            //     'PANITIA :',
            //     [
            //         'name' => 'Times New Roman',
            //         'size' => 12,
            //         'bold' => true,
            //     ],
            //     [
            //         'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER,
            //         'spaceAfter' => 200,
            //     ]
            // );

            // // Add committee members (using your dynamic data)
            // $committeeStyle = [
            //     'name' => 'Times New Roman',
            //     'size' => 12,
            // ];
            // $paragraphStyle = [
            //     'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER,
            //     'spaceAfter' => 100,
            // ];

            // $coverSection3->addText($pelatihan->ketua_panitia->nama, $committeeStyle, $paragraphStyle);
            // $coverSection3->addText($pelatihan->akademis->nama, $committeeStyle, $paragraphStyle);
            // $coverSection3->addText($pelatihan->keuangan->nama, $committeeStyle, $paragraphStyle);
            // $coverSection3->addText($pelatihan->administrasi->nama, $committeeStyle, $paragraphStyle);

            // Add footer text
            $coverSection3->addTextBreak(8); // Add some space

            $coverSection3->addText(
                'LOKA PENDIDIKAN DAN PELATIHAN KEAGAMAAN PEKANBARU',
                [
                    'name' => 'Times New Roman',
                    'size' => 12,
                    'bold' => true,
                ],
                [
                    'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER,
                    'spaceAfter' => 100,
                ]
            );

            $coverSection3->addText(
                "TAHUN $pelatihan->tahun",
                [
                    'name' => 'Times New Roman',
                    'size' => 12,
                    'bold' => true,
                ],
                [
                    'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER,
                ]
            );
            
            // Kata Pengantar
            $section3 = $phpword->addSection();
                        // Add footer with page numbering
            $footer3 = $section3->addFooter();
            $footer3->addPreserveText('{PAGE}', null, ['alignment' => 'right']);
            $section3->addTitle("KATA PENGANTAR", 1);
            $section3->addTextBreak(1);
            $section3->addText("Puji syukur kita ucapkan kepada Allah Subhanahu wa ta’ala, yang senantiasa melimpahkan rahmat dan nikmat serta inayah-Nya kepada kita. Atas  rahmat dan petunjuk-Nya Panduan Penyelenggaraan $pelatihan->nama_kegiatan telah selesai disusun. Panduan Pelatihan ini disusun dalam 5 bab. Dalam panduan ini dikemukakan juga perihal ketentuan-ketentuan yang harus dilaksanakan oleh penyelenggara dan peserta berdasarkan Desain Program Penyelenggaraan Pelatihan dari $materi->asal_kursil", $fontstyle, $paragraphstyle);
            $section3->addText("Panduan ini disusun sebagai acuan bagi panitia, peserta, narasumber dan semua pihak yang terlibat dalam pelaksanaan pelatihan ini, dengan harapan terjadinya sinergi dan hubungan yang harmonis antara peserta dengan peserta dan antara peserta dengan panitia dan penceramah/narasumber, sehingga pelaksanaan pelatihan ini dapat berjalan sesuai dengan yang diharapkan.", $fontstyle, $paragraphstyle);
            $section3->addText("Apabila ada hal penting dalam proses pelaksanaan pelatihan yang belum  tercantum dalam panduan ini, akan diatur kemudian.", $fontstyle, $paragraphstyle);

            $section3->addTextBreak(2);
            $section3->addText("Pekanbaru, $pelatihan->bulan_ttd_lap $pelatihan->tahun,", $fontstyle, ['alignment' => 'both', 'indentation' => ['left' => Converter::cmToTwip(9.75)]]);
            $section3->addText('Kepala Loka Pendidikan dan Pelatihan Keagamaan Pekanbaru', array_merge($fontstyle, ['bold' => true]), ['alignment' => 'both', 'indentation' => ['left' => Converter::cmToTwip(9.75)]]); 
            $section3->addTextBreak(3);
            $section3->addText('H. Aprianto, S.Ag., M.A.', array_merge($fontstyle, ['bold' => true]), ['alignment' => 'both', 'indentation' => ['left' => Converter::cmToTwip(9.75)]]);

            // Daftar Isi
            $section3->addPageBreak();
            $section3->addTitle("DAFTAR ISI", 1);
            $toc3 = $section3->addTOC($tocFontStyle, $tocStyle);

            // BAB I
            $section3->addPageBreak();
            $section3->setStyle([
                'tabs' => [
                    new Tab('left', 3000),
                    new Tab('left', 3500)
                    ]
            ]);
            $section3->addTitle("BAB I", 1);
            $section3->addTitle("PENDAHULUAN", 1);
            $section3->addTextBreak(1); // Adds 1 line break
            $section3->addTitle("A. Latar Belakang", 2);
            $section3->addText("Peraturan Menteri Agama (PMA) Nomor 19 Tahun 2020 tentang Penyelenggaraan Pelatihan Sumber Daya Manusia pada Kementerian Agama menyatakan bahwa untuk mewujudkan Sumber Daya Manusia pada Kementerian Agama yang memiliki integritas, profesionalitas, inovasi, tanggung jawab dan keteladanan perlu diselenggarakan pelatihan secara terencana dan berjenjang.", $fontstyle, $paragraphstyle);
            $section3->addText("Selanjutnya berdasarkan Pasal 2 PMA, penyelenggaraan pelatihan sumber daya manusia bertujuan mengembangkan kompetensi sumber daya manusia meliputi pengetahuan, keterampilan, dan sikap/perilaku", $fontstyle, $paragraphstyle);
            $section3->addText("Salah satu kegiatan pelatihan yang menjadi agenda tahun 2025 Loka Pendidikan dan Pelatihan Keagamaan Pekanbaru adalah $pelatihan->nama_pelatihan Pelaksanaan pelatihan mempunyai tiga unsur pokok yang saling melengkapi yaitu peserta, narasumber dan panitia pelaksana. Selama pelaksanaan pelatihan berlangsung ketiga unsur tersebut diharapkan melakukan kegiatan sesuai dengan tugas masing-masing secara terkoordinasi sehingga kelancaran pelaksanaan dan pencapaian tujuan pelatihan dapat terwujud. Panduan dan tata tertib ini diharapkan dapat memperlancar komunikasi dan interaksi antar semua pihak dalam pelaksanaan pelatihan.", $fontstyle, $paragraphstyle);
            
            $section3->addTitle("B. Dasar Hukum Penyelenggaraan", 2);

            $style = generate_list_style($phpword, 'decimal');

            $section3->addListItem("Undang-undang Nomor 1 Tahun 2004 tentang Perbendaharaan Negara, Menteri Keuangan berwenang menetapkan sistem akuntansi dan pelaporan keuangan;", 0, $fontstyle, $style, $indentation);
            $section3->addListItem("Peraturan Pemerintah Nomor 8 Tahun 2006 tentang Pelaporan Keuangan dan Kinerja Instansi Pemerintah (Lembaran Negara Republik Indonesia Tahun 2006 Nomor 25, Tambahan Lembaran Negara Republik Indonesia Nomor 4614);", 0, $fontstyle, $style, $indentation);
            $section3->addListItem("Peraturan Pemerintah Nomor 39 Tahun 2006 tentang Tata Cara Pengendalian dan Evaluasi Pelaksanaan Rencana Pembangunan (Lembaran Negara Republik Indonesia Tahun 2006 Nomor 96, Tambahan Lembaran Negara Republik Indonesia Nomor 4663);", 0, $fontstyle, $style, $indentation);
            $section3->addListItem("Peraturan Pemerintah Nomor 40 Tahun 2006 tentang Tata Cara Penyusunan Rencana Pembangunan Nasional (Lembaran Negara Republik Indonesia Tahun 2006 Nomor 97, Tambahan Lembaran Negara Republik Indonesia Nomor 4664);", 0, $fontstyle, $style, $indentation);
            $section3->addListItem("Peraturan Presiden Nomor 29 Tahun 2014 tentang Sistem Akuntabilitas Kinerja Instansi Pemerintah;", 0, $fontstyle, $style, $indentation);
            $section3->addListItem("Peraturan Menteri Negara PAN dan RB Nomor 25 Tahun 2012 tentang Petunjuk Pelaksanaan Evaluasi Akuntabilitas Kinerja Instansi Pemerintah;", 0, $fontstyle, $style, $indentation);
            $section3->addListItem("Peraturan Menteri Negara PAN dan RB Nomor 53 Tahun 2014 tentang Petunjuk Teknis Perjanjian Kinerja, Pelaporan Kinerja dan Tata Cara Reviu Atas Laporan Kinerja Instansi Pemerintah;", 0, $fontstyle, $style, $indentation);
            $section3->addListItem("Peraturan Menteri Negara PAN dan RB Nomor 12 Tahun 2015 tentang Pedoman Evaluasi atas Implementasi Sistem Akuntabilitas Kinerja Instansi Pemerintah;", 0, $fontstyle, $style, $indentation);
            $section3->addListItem("Keputusan Menteri Agama Nomor 94 tentang Pedoman Perjanjian Kinerja, Pelaporan Kinerja dan Tata Cara Reviu atas Laporan Kinerja Kementerian Agama;", 0, $fontstyle, $style, $indentation);
            $section3->addListItem("Peraturan Lembaga Administrasi Negara Nomor 10 Tahun 2018 tentang Pengembangan Kompetensi Pegawai Negeri Sipil;", 0, $fontstyle, $style, $indentation);
            $section3->addListItem("Surat Keputusan Kepala Badan Litbang dan Diklat Kementerian Agama RI. Nomor 67 tahun 2021 tentang Petunjuk Pelaksanaan Penyelenggaraan Pelatihan Pada Badan Penelitian Dan Pengembangan Dan Pendidikan Dan Pelatihan Kementerian Agama;", 0, $fontstyle, $style, $indentation);
            $section3->addListItem("DIPA Loka Pendidikan dan Pelatihan Keagamaan Pekanbaru Tahun 2025 Nomor : SP-DIPA/025.11.2.690527/2025 Tanggal 02 Desember 2024;", 0, $fontstyle, $style, $indentation);

            $section3->addTitle("C. Tujuan, Sasaran dan Kompetensi Pelatihan", 2);
            
            $style11 = generate_list_style($phpword, 'decimal');

            $section3->addListItem("Tujuan;", 0, $fontstyle, $style11);
            $section3->addText("$pelatihan->nama_kegiatan bertujuan dan berupaya Membekali Pegawai dan meningkatkan kualitas pegawai di Lingkungan Kantor KementerianAgama sehingga terpenuhi standar kompetensi yang diperlukan dalam pelaksanaan tugas-tugas kedinasan.", $fontstyle, $paragraphstyle);

            $section3->addListItem("Sasaran", 0, $fontstyle, $style11);

            $style = generate_list_style($phpword, 'lowerLetter');

            $section3->addListItem("Terwujudnya $pelatihan->jumlah_peserta peserta $pelatihan->nama_pelatihan dalam memenuhi kecakapan untuk mengelola dan mengembangkan penilaian Kinerja ASN", 0, $fontstyle, $style, $indentation);
            $section3->addListItem("Terwujudnya peserta $pelatihan->nama_pelatihan yang memiliki komptensi dalam melaksanakan tugas dan fungsinya sebagaimana dipersyaratkan dalam jabatannya dan yang memiliki integritas yang tinggi untuk meningkatkan kemampuannya.", 0, $fontstyle, $style, $indentation);

            $section3->addListItem("Kompetensi Pelatihan", 0, $fontstyle, $style11);
            $section3->addText("Setelah mengikuti $pelatihan->nama_pelatihan peserta mampu $materi->tujuan_pelatihan", $fontstyle, $paragraphstyle);

            // BAB II
            $section3->addPageBreak();
            $section3->addTitle("BAB II", 1);
            $section3->addTitle("PELAKSANAAN DIKLAT", 1);
            $section3->addTextBreak(1); // Adds 1 line break
            $section3->addTitle("A. Waktu, Tempat Pelaksanaan dan Tutorial Pelatihan (Non Klasikal)", 2);
            $style = generate_list_style($phpword, 'decimal');

            $section3->addListItem("Waktu Pelaksanaan", 0, $fontstyle, $style);
            $section3->addText("Pelaksanaan Kegiatan $pelatihan->nama_pelatihan di Wilayah Kerja Kantor Kementerian Agama $pelatihan->kab_kota dilaksanakan pada tanggal" . $data['tanggal_mulai'] . " s.d " . $data['tanggal_selesai'] . ".", $fontstyle, $paragraphstyle);
            
            $section3->addListItem("Tempat Pelaksanaan", 0, $fontstyle, $style);
            $section3->addText("Kegiatan dilaksanakan di $pelatihan->tempat", $fontstyle, $paragraphstyle);
            
            $section3->addListItem("Alamat dan Akun resmi :", 0, $fontstyle, $style);
            
            $style = generate_list_style($phpword, 'lowerLetter');

            $section3->addListItem("Penilaian terhadap Widyaiswara, Panitia dan peserta secara kuantitatif melalui simdiklat.kemenag.go.id", 0, $fontstyle, $style, $indentation);
            $section3->addListItem("Evaluasi Penyelenggaraan pelatihan melalui : simdiklat.kemenag.go.id", 0, $fontstyle, $style, $indentation);
            
            $section3->addTitle("B. Panitia Pelaksana", 2);

            // First level: decimal numbering (1., 2., 3.)
            $decimalStyle = generate_list_style($phpword, 'decimal');

            // Second level: alpha list (a., b., c.)
            $alphaStyle = generate_list_style($phpword, 'lowerLetter');

            // Third level: bullet points
            

            // 1. Kepanitiaan
            $section3->addListItem("Kepanitiaan", 0, $fontstyle, $decimalStyle);
            $section3->addText("Susunan Kepanitiaan dan tugas penyelenggara pelatihan meliputi :", $fontstyle, $paragraphstyle);

            // a. Penanggung jawab
            $section3->addListItem("Penanggung jawab :", 0, $fontstyle, $alphaStyle, $indentation);
            // Bullet points for responsibilities
            $bulletStyle = generate_list_style($phpword, 'bullet');
            $section3->addListItem("Mengarahkan Ketua Panitia", 0, $fontstyle, $bulletStyle, $indentation);
            $section3->addListItem("Memantau Pelaksanaan Tugas panitia", 0, $fontstyle, $bulletStyle, $indentation);
            $section3->addListItem("Mempertanggung jawabkan Penyelenggaraan orientasi", 0, $fontstyle, $bulletStyle, $indentation);
            $section3->addListItem("Menegakkan kedisiplinan panitia dan peserta", 0, $fontstyle, $bulletStyle, $indentation);

            // b. Ketua
            $section3->addListItem("Ketua :", 0, $fontstyle, $alphaStyle, $indentation);
            // Bullet points for responsibilities
            $bulletStyle = generate_list_style($phpword, 'bullet');
            $section3->addListItem("Mengkoordinir persiapan, pelaksanaan sampai selesai", 0, $fontstyle, $bulletStyle, $indentation);
            $section3->addListItem("Membuat / menyusun laporan penyelengaraan pelatihan", 0, $fontstyle, $bulletStyle, $indentation);
            $section3->addListItem("Berkoordinasi dengan pihak terkait", 0, $fontstyle, $bulletStyle, $indentation);
            $section3->addListItem("Melaporkan kepada penanggung jawab", 0, $fontstyle, $bulletStyle, $indentation);

            // c. akademik
            $section3->addListItem("Akademik :", 0, $fontstyle, $alphaStyle, $indentation);
            // Bullet points for responsibilities
            $bulletStyle = generate_list_style($phpword, 'bullet');
            $section3->addListItem("Mengkonfirmasi kehadiran peserta / Narasumber", 0, $fontstyle, $bulletStyle, $indentation);
            $section3->addListItem("Mengelola data SIMDIKLAT/LMS PJJ", 0, $fontstyle, $bulletStyle ,$indentation);
            $section3->addListItem("Mengantar/memperkenalkan dan mendamping Narasumber", 0, $fontstyle, $bulletStyle, $indentation);
            $section3->addListItem("Memberikan penilaian terhadap sikap dan perilaku peserta", 0, $fontstyle, $bulletStyle, $indentation);
            $section3->addListItem("Mengumpulkan bahan ajar dan produk", 0, $fontstyle, $bulletStyle, $indentation);
            $section3->addListItem("Membuat dan mengedarkan daftar hadir peserta", 0, $fontstyle, $bulletStyle, $indentation);
            $section3->addListItem("Mengolah Nilai Peserta", 0, $fontstyle, $bulletStyle, $indentation);
            $section3->addListItem("Menghimpun dan mengolah data hasil evaluasi peserta, narasumber dan panitia pelatihan", 0, $fontstyle, $bulletStyle);

            // d. Anggota 1
            $section3->addListItem("Anggota 1 :", 0, $fontstyle, $alphaStyle,  $indentation);
            // Bullet points for responsibilities
            $bulletStyle = generate_list_style($phpword, 'bullet');
            $section3->addListItem("Membuat daftar hadir narasumber", 0, $fontstyle, $bulletStyle, $indentation);
            $section3->addListItem("Membuat SPJ Keuangan", 0, $fontstyle, $bulletStyle, $indentation);
            $section3->addListItem("Mengumpulkan surat tugas narasumber", 0, $fontstyle, $bulletStyle, $indentation);
            $section3->addListItem("Menyusun laporan pertanggungjawaban keuangan", 0, $fontstyle, $bulletStyle, $indentation);
            $section3->addListItem("Mempertanggungjawabkan SPJ ke bendahara", 0, $fontstyle, $bulletStyle, $indentation);
            $section3->addListItem("Melakukan verifikasi kelengkapan persyaratan pada saat registrasi", 0, $fontstyle, $bulletStyle, $indentation);
            $section3->addListItem("Menyiapkan sarana dan prasarana pembelajaran", 0, $fontstyle, $bulletStyle, $indentation);
            $section3->addListItem("Membuat dokumentasi kegiatan", 0, $fontstyle, $bulletStyle, $indentation);
            $section3->addListItem("Mendampingi peserta di dalam kelas", 0, $fontstyle, $bulletStyle, $indentation);

            // e. Widyaiswara pendamping Akademis 
            $section3->addListItem("Widyaiswara pendamping Akademis :", 0, $fontstyle, $alphaStyle, $indentation);
            // Bullet points for responsibilities
            $bulletStyle = generate_list_style($phpword, 'bullet');
            $section3->addListItem("Menyusun rencana kegiatan", 0, $fontstyle, $bulletStyle, $indentation);
            $section3->addListItem("Menyusun jadwal berpedoman pada kurikulum", 0, $fontstyle, $bulletStyle, $indentation);
            $section3->addListItem("Mendampingi peserta mencari sumber-sumber pembelajaran pelatihan", 0, $fontstyle, $bulletStyle, $indentation);
            $section3->addListItem("Memberikan penilaian terhadap peserta melalui tes penugasan, maupun produk", 0, $fontstyle, $bulletStyle, $indentation);
            $section3->addListItem("Membuat laporan pembelajaran", 0, $fontstyle, $bulletStyle, $indentation);

            // Continue similarly for other roles (Akademik, Anggota 1, Widyaiswara)

            // 2. Hak dan Kewajiban Panitia
            $section3->addListItem("Hak dan Kewajiban Panitia", 0, $fontstyle, $decimalStyle);
            // a. Hak Panitia
            $alphaStyle1 = generate_list_style($phpword, 'lowerLetter');
            $section3->addListItem("Hak Panitia", 0, $fontstyle, $alphaStyle1,  $indentation);
            // Bullet points for hak
            $bulletStyle = generate_list_style($phpword, 'bullet');
            $section3->addListItem("Mengusulkan penolakan peserta yang tidak sesuai dengan persyaratan, data dan alokasi", 0, $fontstyle, $bulletStyle, $indentation);
            $section3->addListItem("Penginapan perjalanan dinas", 0, $fontstyle, $bulletStyle);
            $section3->addListItem("Penggantian biaya transportasi perjalanan dinas Pergi-Pulang", 0, $fontstyle, $bulletStyle, $indentation);
            $section3->addListItem("Uang harian perjalanan dinas ", 0, $fontstyle, $bulletStyle, $indentation);
            $section3->addListItem("Layanan kesehatan; obat-obatan ringan", 0, $fontstyle, $bulletStyle, $indentation);
            $section3->addListItem("Memperoleh uang harian dan transport at cost sesuai ketentuan yang berlaku", 0, $fontstyle, $bulletStyle, $indentation);
            // Continue other hak items

            // b. Kewajiban Panitia
            $section3->addListItem("Kewajiban Panitia", 0, $fontstyle, $alphaStyle1, $indentation);
            // Bullet points for kewajiban
            $bulletStyle = generate_list_style($phpword, 'bullet');
            $section3->addListItem("Menyiapkan sarana pelatihan", 1, $fontstyle, $bulletStyle, $indentation);
            $section3->addListItem("Mengelola registrasi peserta", 1, $fontstyle, $bulletStyle, $indentation);
            $section3->addListItem("Mendampingi widyaiswara/fasilitator dan peserta selama pelatihan", 1, $fontstyle, $bulletStyle, $indentation);
            $section3->addListItem("Menilai sikap peserta", 1, $fontstyle, $bulletStyle, $indentation);
            $section3->addListItem("Membuat spj keuangan", 1, $fontstyle, $bulletStyle, $indentation);
            $section3->addListItem("Membuat laporan penyelenggaraan", 1, $fontstyle, $bulletStyle, $indentation);
            // Continue other kewajiban items

            // 3. Fasilitas Pelatihan
            $section3->addListItem("Fasilitas Pelatihan", 0, $fontstyle, $decimalStyle,  $indentation);
            // Bullet points for fasilitas
            $alphaStyle2 = generate_list_style($phpword, 'lowerLetter');
            $section3->addListItem("Ruang pembelajaran yang representatif", 0, $fontstyle, $alphaStyle2,  $indentation);
            $section3->addListItem("Alat bantu pembelajaran; LCD Proyektor, soundsystem dll", 0, $fontstyle, $alphaStyle2,  $indentation);
            $section3->addListItem("Pelatihan kit", 0, $fontstyle, $alphaStyle2, $indentation);

            // C. Kualifikasi, Hak, Kewajiban dan Alokasi Peserta
            $section3->addTitle("C. Kualifikasi, Hak, Kewajiban dan Alokasi Peserta", 2);

            // 1. Kualifikasi Peserta
            $style4 = generate_list_style($phpword, 'decimal');
            $section3->addListItem("Kualifikasi Peserta", 0, $fontstyle, $style4);
            $section3->addText("Persyaratan Umum :", $fontstyle, $paragraphstyle);

            // a. Persyaratan Umum
            $style5 = generate_list_style($phpword, 'lowerLetter');
            $section3->addListItem("Sehat jasmani dan rohani, dibuktikan dengan surat keterangan sehat dari dokter, puskesmas atau rumah sakit pemerintah", 0, $fontstyle, $style5, $indentation);
            $section3->addListItem("Siap dan mampu mengikuti seluruh kegiatan pelatihan dari awal hingga akhir, dibuktikan surat pernyataan kesiapan/kesanggupan", 0, $fontstyle, $style5, $indentation);
            $section3->addListItem("Kebijakan internal Loka Diklat Keagmaan Pekanbaru, peserta tidak diperkenankan mengikuti Pelatihan lebih dari satu kali untuk jenis yang sama dalam setahun yang diselenggarakan oleh Loka Diklat Keagamaan Pekanbaru", 0, $fontstyle, $style5, $indentation);

            $section3->addText("Persyaratan Khusus :", $fontstyle, $paragraphstyle);

            // a. Persyaratan Khusus
            $style6 = generate_list_style($phpword, 'lowerLetter');
            $section3->addListItem("Calon Peserta $pelatihan->nama_kegiatan adalah $pelatihan->jabatan_peserta di Wilayah kerja Kantor Kementerian Agama $pelatihan->kab_kota, Penetapan peserta pelatihan bersifat selektif dan merupakan penugasan dari instansi yang bersangkutan yang dibuktikan dengan surat tugas", 0, $fontstyle, $style6, $indentation);
            $section3->addListItem("Menyerahkan print out Biodata dari aplikasi Simdiklat yang datanya telah direvisi", 0, $fontstyle, $style6, $indentation);
            $section3->addListItem("Menyerahkan Surat Tugas dari Kantor Kementerian Agama Kabupaten/ Kota", 0, $fontstyle, $style6, $indentation);
            $section3->addListItem("Menyerahkan foto copy SK terakhir, foto copy NPWP dan foto copy rekening buku tabungan yang masih aktif (disarankan BRI)", 0, $fontstyle, $style6, $indentation);
            $section3->addListItem("Menyerahkan 2 lembar pas foto berlatar belakang merah ukuran 4 x 6", 0, $fontstyle, $style6, $indentation);
            $section3->addListItem("Menyerahkan foto copy kartu ASKES/BPJS (bila ada)", 0, $fontstyle, $style6, $indentation);
            $section3->addListItem("Membawa peralatan belajar yang dibutuhkan (Laptop, terminal kabel dll)", 0, $fontstyle, $style6, $indentation);

            // 2. Hak Peserta
            $section3->addListItem("Hak Peserta", 0, $fontstyle, $style4);
            // a. Hak Peserta
            $style7 = generate_list_style($phpword, 'lowerLetter');
            $section3->addListItem("Peserta berhak mendapatkan pelayanan dalam proses pendidikan dan pelatihan", 0, $fontstyle, $style7, $indentation);
            $section3->addListItem("Peserta berhak mendapatkan konsumsi sesuai ketentuan yang berlaku", 0, $fontstyle, $style7, $indentation);
            $section3->addListItem("Peserta memperoleh uang saku sesuai ketentuan yang berlaku", 0, $fontstyle, $style7, $indentation);

            // 3. Kewajiban Peserta
            $section3->addListItem("Kewajiban Peserta", 0, $fontstyle, $style4);
            // a. Kewajiban Peserta
            $style8 = generate_list_style($phpword, 'lowerLetter');
            $section3->addListItem("Setiap peserta wajib mentaati segala tata tertib, peraturan dan ketentuan yang dikeluarkan oleh panitia pelaksana", 0, $fontstyle, $style8, $indentation);
            $section3->addListItem("Setiap peserta wajib mengikuti program pelatihan sesuai dengan jadwal yang ditentukan", 0, $fontstyle, $style8, $indentation);
            $section3->addListItem("Setiap peserta wajib mengembangkan keilmuan/ketrampilan di tempat tugas", 0, $fontstyle, $style8, $indentation);
            $section3->addListItem("Pakaian peserta selama mengikuti pelatihan (baik tatap muka maupun daring) adalah baju atas berwarna putih, bawah berwarna hitam, peserta laki-laki mengenakan dasi", 0, $fontstyle, $style8, $indentation);

            // 4. Alokasi Peserta Pelatihan
            $section3->addListItem("Alokasi Peserta Pelatihan", 0, $fontstyle, $style4);
            $section3->addText("Peserta berjumlah $pelatihan->jumlah_peserta orang berasal dari Wilayah Kerja Kantor Kementerian Agama $pelatihan->kab_kota, dialokasikan ke dalam ruangan yang representatif.", $fontstyle, $paragraphstyle);

            //BAB III
            $section3->addPageBreak();
            $section3->addTitle("BAB III", 1);
            $section3->addTitle("KURIKULUM PELATIHAN", 1);
            $section3->addTextBreak(1); // Adds 1 line break
            $section3->addTitle("A. Struktur Program", 2);
            // Create a table with 2 columns
            $table = $section3->addTable([
                'borderSize' => 6,
                'borderColor' => '000000',
                'cellMargin' => 50
            ]);

            // Add table headers
            $table->addRow();
            $table->addCell(1000)->addText('No', ['bold' => true], ['align' => 'center']);
            $table->addCell(8000)->addText('Mata Pelatihan', ['bold' => true], ['align' => 'left']);

            // Add Kelompok Dasar section
            $table->addRow();
            $table->addCell(1000)->addText('A.', ['bold' => true], ['align' => 'center']);
            $table->addCell(8000)->addText('Kelompok Dasar', ['bold' => true], ['align' => 'left']);

            if (!empty($pelatihan->materi)) {
                foreach ($pelatihan->materi as $materi) {
                    if (!empty($materi->kel_dasar_parsed)) {
                        $counter = 1;
                        foreach ($materi->kel_dasar_parsed as $item) {
                            $table->addRow();
                            $table->addCell(1000)->addText('');
                            $table->addCell(8000)->addText($item);
                            $counter++;
                        }
                    } else {
                        $table->addRow();
                        $table->addCell(1000)->addText('');
                        $table->addCell(8000)->addText('Materi tidak tersedia');
                    }
                }
            } else {
                $table->addRow();
                $table->addCell(1000)->addText('');
                $table->addCell(8000)->addText('Tidak ada materi pelatihan');
            }

            // Add empty row for spacing
            $table->addRow();
            $table->addCell(1000)->addText('');
            $table->addCell(8000)->addText('');

            // Add Kelompok Inti section
            $table->addRow();
            $table->addCell(1000)->addText('B.', ['bold' => true], ['align' => 'center']);
            $table->addCell(8000)->addText('Kelompok Inti', ['bold' => true], ['align' => 'left']);

            if (!empty($pelatihan->materi)) {
                foreach ($pelatihan->materi as $materi) {
                    if (!empty($materi->kel_inti_parsed)) {
                        $counter = 1;
                        foreach ($materi->kel_inti_parsed as $item) {
                            $table->addRow();
                            $table->addCell(1000)->addText('');
                            $table->addCell(8000)->addText($item);
                            $counter++;
                        }
                    } else {
                        $table->addRow();
                        $table->addCell(1000)->addText('');
                        $table->addCell(8000)->addText('Materi tidak tersedia');
                    }
                }
            } else {
                $table->addRow();
                $table->addCell(1000)->addText('');
                $table->addCell(8000)->addText('Tidak ada materi pelatihan');
            }

            // Add empty row for spacing
            $table->addRow();
            $table->addCell(1000)->addText('');
            $table->addCell(8000)->addText('');

            // Add Kelompok Penunjang section
            $table->addRow();
            $table->addCell(1000)->addText('C.', ['bold' => true], ['align' => 'center']);
            $table->addCell(8000)->addText('Kelompok Penunjang', ['bold' => true], ['align' => 'left']);

            if (!empty($pelatihan->materi)) {
                foreach ($pelatihan->materi as $materi) {
                    if (!empty($materi->kel_penunjang_parsed)) {
                        $counter = 1;
                        foreach ($materi->kel_penunjang_parsed as $item) {
                            $table->addRow();
                            $table->addCell(1000)->addText('');
                            $table->addCell(8000)->addText($item);
                            $counter++;
                        }
                    } else {
                        $table->addRow();
                        $table->addCell(1000)->addText('');
                        $table->addCell(8000)->addText('Materi tidak tersedia');
                    }
                }
            } else {
                $table->addRow();
                $table->addCell(1000)->addText('');
                $table->addCell(8000)->addText('Tidak ada materi pelatihan');
            }

            $section3->addTextBreak(1);
            $section3->addTitle("B. Metode Pelatihan dan Tenaga Pengajar", 2);
            // Define styles
            $alphaStyle = generate_list_style($phpword, 'lowerLetter');
            
            $decimalStyle = generate_list_style($phpword, 'decimal'); // 1., 2., 3.
            $bulletStyle = generate_list_style($phpword, 'bullet'); // bullet points

            // A. Metode
            $section3->addListItem("Metode", 0, $fontstyle, $alphaStyle);
            $section3->addText("Prinsip pelaksanaan Pelatihan adalah pembelajaran bagi orang dewasa (andragogi). Sesuai sasaran capaian kompetensi yang harus diperoleh para Pegawai ASN Kementerian Agama, maka pemilihan metode pengajaran harus disesuaikan dengan karakteristik peserta pelatihan dan aktualisasi kegiatan yang dilaksanakan di lapangan. Untuk itu, metodologi yang memungkinkan untuk dilaksanakan sebagai berikut:", $fontstyle, $paragraphstyle);

            // Add bullet points for methods
            $section3->addListItem("Tanya jawab", 0, $fontstyle, $bulletStyle, $indentation);
            $section3->addListItem("Pemberian tugas", 0, $fontstyle, $bulletStyle, $indentation);
            $section3->addListItem("Upload Video / bahan tayang / bahan ajar", 0, $fontstyle, $bulletStyle, $indentation);
            $section3->addListItem("Eksplorasi pengalaman peserta", 0, $fontstyle, $bulletStyle, $indentation);
            $section3->addListItem("Eksplorasi kebutuhan peserta", 0, $fontstyle, $bulletStyle, $indentation);
            $section3->addListItem("Ceramah", 0, $fontstyle, $bulletStyle, $indentation);
            $section3->addListItem("Latihan", 0, $fontstyle, $bulletStyle, $indentation);
            $section3->addListItem("Studi kasus", 0, $fontstyle, $bulletStyle, $indentation);
            $section3->addListItem("Demonstrasi", 0, $fontstyle, $bulletStyle, $indentation);
            $section3->addListItem("Diskusi", 0, $fontstyle, $bulletStyle, $indentation);

            // B. Tenaga Pengajar 
            $section3->addListItem("Tenaga Pengajar", 0, $fontstyle, $alphaStyle);
            $section3->addText("Tenaga pengajar Pelatihan terdiri atas para Pejabat LDK Pekanbaru/Pejabat Kemenag/Widyaiswara/Widyaiswara luar biasa dan Tenaga Ahli atau Tenaga Profesional di bidang yang sesuai dengan kompetensinya, dengan ketentuan:", $fontstyle, $paragraphstyle);

            // Add decimal list for teacher requirements
            $section3->addListItem("Memiliki pengetahuan tentang kurikulum dan menguasai materi yang akan diberikan;", 0, $fontstyle, $decimalStyle, $indentation);
            $section3->addListItem("Menguasai metodologi pengajaran yang tepat serta mampu menerapkan metode belajar bagi orang dewasa (andragogi), Mempunyai kredibilitas, dedikasi, dan reputasi yang baik.", 0, $fontstyle, $decimalStyle, $indentation);
            $section3->addListItem("Sehat jasmani dan rohani, cakap, serta memiliki etika dan kemampuan berkomunikasi yang baik.", 0, $fontstyle, $decimalStyle, $indentation);
            $section3->addListItem("Mampu memotivasi peserta Pelatihan.", 0, $fontstyle, $decimalStyle, $indentation);

            // C. Kelulusan
            $section3->addListItem("Kelulusan", 0, $fontstyle, $alphaStyle);

            // Add decimal list for graduation
            $decimalStyle1 = generate_list_style($phpword, 'decimal'); // 1., 2., 3.
            $section3->addListItem("Kelulusan peserta pelatihan ditentukan dengan prosedur berikut:", 0, $fontstyle, $decimalStyle1, $indentation);

            // Add nested bullet points for graduation requirements
            $bulletStyle4 = generate_list_style($phpword, 'bullet'); // bullet points
            $section3->addListItem("Setiap peserta pelatihan dinyatakan LULUS dan berhak mendapatkan STTP jika memenuhi persyaratan:", 0, $fontstyle, $bulletStyle4, $indentation);
            $section3->addListItem("Mengikuti seluruh kegiatan pelatihan dengan memenuhi syarat kehadiran > 85%.", 0, $fontstyle, $bulletStyle4, $indentation);
            $section3->addListItem("Mendapatkan nilai sikap minimal kualifikasi 'BAIK'.", 0, $fontstyle, $bulletStyle4, $indentation);
            $section3->addListItem("Memperoleh nilai hasil pelatihan (NHP) minimal 76,00.", 0, $fontstyle, $bulletStyle4, $indentation);

            $section3->addListItem("Peserta pelatihan yang tidak memenuhi persyaratan sebagaimana disebutkan pada butir a, dinyatakan tidak lulus.", 0, $fontstyle, $decimalStyle1, $indentation);

            // D. Evaluasi Pelatihan
            $section3->addListItem("Evaluasi Pelatihan", 0, $fontstyle, $alphaStyle);
            $section3->addText("Evaluasi yang akan dilaksanakan pada kegiatan ini adalah sebagai berikut:", $fontstyle, $paragraphstyle);

            // Add decimal list for evaluation points
            $decimalStyle2 = generate_list_style($phpword, 'decimal'); // 1., 2., 3.
            $section3->addListItem("Evaluasi terhadap peserta pelatihan, meliputi tiga ranah yaitu Pengetahuan, Keterampilan dan sikap perilaku.", 0, $fontstyle, $decimalStyle2, $indentation);
            $section3->addListItem("Instrumen Evaluasi dalam ranah pengetahuan diimplementasikan melalui Pre test dan post test yang akan dilaksanakan secara online serta melalui penugasan penugasan dari widyaiswara;", 0, $fontstyle, $decimalStyle2, $indentation);
            $section3->addListItem("Instrumen penilaian keterampilan adalah observasi proses dan observasi produk;", 0, $fontstyle, $decimalStyle2, $indentation);
            $section3->addListItem("Instrumen penilaian sikap didapatkan dari observasi proses kegiatan oleh panitia pelaksana, yang indikatornya diturunkan dari Lima Nilai Kementerian Agama;", 0, $fontstyle, $decimalStyle2, $indentation);
            $section3->addListItem("Nilai akhir peserta diperoleh dari penilaian sikap, pengetahuan, ketrampilan dan Rencana Tindak Lanjut;", 0, $fontstyle, $decimalStyle2, $indentation);
            $section3->addListItem("Evaluasi Penyelenggaraan Pelatihan berupa penilaian terhadap WI dan panitia penyelenggara akan dilaksanakan melalui penilaian online di Simdiklat -Single Sign On (simdiklat-kemenag.id)", 0, $fontstyle, $decimalStyle2, $indentation);

            // E. Sertifikat Pelatihan
            $section3->addListItem("Sertifikat Pelatihan", 0, $fontstyle, $alphaStyle);
            $section3->addText("Peserta Pelatihan yang memenuhi syarat kelulusan menerima Sertifikat Kelulusan. Batas kelulusan untuk peserta pelatihan adalah 76. Peserta dinyatakan lulus dengan nilai kumulatif minimal 76 ( tujuh puluh enam) berdasarkan hasil evaluasi peserta pelatihan yang diatur dalam Standar Evaluasi sesuai Keputusan Kepala Badan Litbang dan Diklat Nomor 67 Tahun 2021 tentang Petunjuk Pelaksanaan Penyelenggaraan Pelatihan pada Badan Diklat Kementerian Agama", $fontstyle, $paragraphstyle);

            //BAB IV
            $section3->addPageBreak();
            $section3->addTitle("BAB IV", 1);
            $section3->addTitle("PEMBIAYAAN DAN LAPORAN", 1);
            $section3->addTextBreak(1);
            $section3->addTitle("A. Pembiayaan", 2);
            $section3->addText("Biaya $pelatihan->nama_kegiatan di Wilayah Kerja Kantor Kementerian Agama $pelatihan->kab_kota dibebankan pada Daftar Isian Pelaksanaan Anggaran DIPA Loka Pendidikan dan Pelatihan Keagamaan Pekanbaru Tahun 2025 Nomor : SP-DIPA/025.11.2.690527/2025 Tanggal 02 Desember 2024", $fontstyle, $paragraphstyle);
            $section3->addText("Terkait dengan penyelenggaraan kegiatan Pelatihan tersebut peserta tidak dipungut biaya apapun", $fontstyle, $paragraphstyle);

            $section3->addTitle("B. Laporan", 2);
            $section3->addText("Sebagai pertanggungjawaban dalam pelaksanaan pelatihan akan disusun dalam bentuk laporan penyelenggaraan dan evaluasi pelaksanaan pelatihan yang meliputi :", $fontstyle, $paragraphstyle);

            $style = generate_list_style($phpword, 'decimal');

            $section3->addListItem("Laporan Persiapan Penyelenggaraan Pelatihan (panitia)", 0, $fontstyle, $style, $indentation);
            $section3->addListItem("Laporan Akhir Kegiatan (Panitia)", 0, $fontstyle, $style, $indentation);


             //BAB V
            $section3->addPageBreak();
            $section3->addTitle("BAB V", 1);
            $section3->addTitle("PENUTUP", 1);
            $section3->addTextBreak(1);

            $style = generate_list_style($phpword, 'decimal');

            $section3->addListItem("Hal-hal yang belum tercantum dalam buku panduan akan diatur kemudian", 0, $fontstyle, $style);
            $section3->addListItem("Para peserta wajib mematuhi ketentuan-ketentuan yang telah ditetapkan", 0, $fontstyle, $style);
            $section3->addListItem("Ketua kelas ikut bertanggungjawab atas terlaksananya ketertiban pelatihan", 0, $fontstyle, $style);
            $section3->addListItem("Pelanggaran terhadap ketentuan yang telah dijelaskan dalam buku panduan akan mengakibatkan peserta kehilangan haknya sebagai peserta, dan akan dikembalikan secara baik-baik kepada instansi pengirim.", 0, $fontstyle, $style);

            $section3->addTextBreak(1);
            $section3->addText("Pekanbaru, $pelatihan->bulan_ttd_lap $pelatihan->tahun,", $fontstyle, ['alignment' => 'both', 'indentation' => ['left' => Converter::cmToTwip(9.75)]]);
            $section3->addText('Kepala Loka Pendidikan dan Pelatihan Keagamaan Pekanbaru', array_merge($fontstyle, ['bold' => true]), ['alignment' => 'both', 'indentation' => ['left' => Converter::cmToTwip(9.75)]]); 
            $section3->addTextBreak(3);
            $section3->addText("$ketua_loka->nama", array_merge($fontstyle, ['bold' => true]), ['alignment' => 'both', 'indentation' => ['left' => Converter::cmToTwip(9.75)]]);
            
            //Berita Acara
            $section3->addPageBreak();
            $section3->addText("BERITA ACARA", ['bold' => true], ['alignment' => Jc::CENTER]);
            $section3->addText("Evaluasi Kelulusan Peserta", ['bold' => true], ['alignment' => Jc::CENTER]);
            $section3->addText("Pelatihan $pelatihan->nama_pelatihan", ['bold' => true], ['alignment' => Jc::CENTER]);
            $section3->addText("Loka Pendidikan dan Pelatihan Keagamaan Pekanbaru", ['bold' => true], ['alignment' => Jc::CENTER]);
            $section3->addText("Tahun $pelatihan->tahun", ['bold' => true], ['alignment' => Jc::CENTER]);
            $section3->addTextBreak(1);

            $section3->addText("Pada hari ini " . $data['tanggal_selesai'] . " pukul 13.30 s.d 14.00 WIB bertempat di $pelatihan->tempat telah diadakan Rapat Evaluasi Kelulusan Peserta $pelatihan->nama_pelatihan yang diselenggarakan dari tanggal" . $data['tanggal_mulai'] . " s.d " . $data['tanggal_selesai'] . "." , $fontstyle, $paragraphstyle);
            $section3->addText("Berdasarkan Rapat Evaluasi Kelulusan maka hasil akhir nilai peserta pelatihan adalah dari $pelatihan->jumlah_peserta orang, peserta dinyatakan lulus sebanyak $pelatihan->jumlah_lulus orang dan tidak lulus $pelatihan->jumlah_tidak_lulus orang dengan rekapitulasi kualifikasi nilai peserta sebagaimana terlampir.", $fontstyle, $paragraphstyle);
            $section3->addText("Demikian berita acara ini dibuat sebagaimana mestinya.", $fontstyle, $paragraphstyle);

            $section3->addTextBreak(2);
            $section3->addText("Pekanbaru, $pelatihan->bulan_ttd_lap $pelatihan->tahun,", $fontstyle, ['alignment' => 'both', 'indentation' => ['left' => Converter::cmToTwip(9.75)]]);
            $section3->addText('Panitia Penyelenggara', array_merge($fontstyle, ['bold' => true]), ['alignment' => 'both', 'indentation' => ['left' => Converter::cmToTwip(9.75)]]); 
            $section3->addText('Ketua', array_merge($fontstyle, ['bold' => true]), ['alignment' => 'both', 'indentation' => ['left' => Converter::cmToTwip(9.75)]]); 
            $section3->addTextBreak(3);
            $section3->addText("{$pelatihan->ketua_panitia->nama}", array_merge($fontstyle, ['bold' => true]), ['alignment' => 'both', 'indentation' => ['left' => Converter::cmToTwip(9.75)]]);
            $section3->addText("NIP. {$pelatihan->ketua_panitia->NIP}", array_merge($fontstyle, ['bold' => true]), ['alignment' => 'both', 'indentation' => ['left' => Converter::cmToTwip(9.75)]]);
 
            $tempFile = tempnam(sys_get_temp_dir(), 'word_');
            $writer = IOFactory::createWriter($phpword, 'Word2007');
            $writer->save($tempFile);
            
            $filename = 'dokumen_' . time() . '.docx';
            $target = FCPATH . 'downloads/' . $filename;
            rename($tempFile, $target);

            
            // $writer = IOFactory::createWriter($phpword, 'Word2007');
            // $writer->save($filepath);

    
        return $filename;
    } catch (Exception $e) {
        log_message('error', $e->getMessage());
        // Add this to see the actual error during development:
        show_error($e->getMessage(), 500, 'Document Generation Error');
        return false;
    }

    }
}
?>