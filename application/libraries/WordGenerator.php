<?php

use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Shared\Converter;
use PhpOffice\PhpWord\SimpleType\Jc;
use PhpOffice\PhpWord\Style\Font;
use PhpOffice\PhpWord\Style\Paragraph;
use PhpOffice\PhpWord\Style\Tab;

use function PHPSTORM_META\type;

defined('BASEPATH') OR exit('No direct script access allowed');

class wordGenerator{
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
            $wiList       = ($isNonLatsar && isset($pelatihan->wi_list)      && is_array($pelatihan->wi_list))      ? $pelatihan->wi_list      : [];
            $pengajarList = ($isNonLatsar && isset($pelatihan->pengajar_list) && is_array($pelatihan->pengajar_list)) ? $pelatihan->pengajar_list : [];
            $wiRapat      = ($isNonLatsar && isset($pelatihan->wi_rapat)) ? $pelatihan->wi_rapat : null;

            // tiny helper to print "Nama berasal dari Satker"
            $teacherLine = function($o) {
                $nm  = isset($o->nama) ? $o->nama : '-';
                $sat = isset($o->asal_satker) && $o->asal_satker !== '' ? $o->asal_satker : '—';
                return "{$nm} berasal dari {$sat}";
            };

            $durasi = is_array($data) ? ($data['durasi'] ?? 0) : $data->durasi ?? 0;
            $ketua_loka = is_array($data) ? ($data['ketua_loka'] ?? 0) : $data->ketua_loka ?? 0;
            
            $phpword->addTitleStyle(1, ['size'=>16, 'bold'=>true], ['alignment'=>'center']);
            $phpword->addTitleStyle(2, ['size'=>12, 'bold'=>true]);
            $phpword->setDefaultFontName('Times New Roman');
            $phpword->setDefaultFontSize(12);
            
            $fontstyle=['name' => 'Times New Roman', 'size' => 12];
            $paragraphstyle = [
                'alignment' => 'both',
                'indentation' => ['firstLine' => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(1)],
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
                'assets/cover/Cover_PJJ_Panduan_Penyelenggaraan.png',
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
            $coverSection->addTextBreak(30); // Adds 4 line breaks (you can adjust this number)
            $coverSection->addText(
                strtoupper($pelatihan->nama_kegiatan),
                [
                    'name' => 'Times New Roman',
                    'size' => 16,
                    'bold' => true,
                    'color' => 'FFFFFF', // Changed to white
                    'allCaps' => true,
                ],
                [
                    'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::START,
                    'spaceAfter' => 300,
                ]
            );


            // Kata Pengantar
            $section1 = $phpword->addSection();
            $footer = $section1->addFooter();
            $footer->addPreserveText('{PAGE}', null, ['alignment' => 'right']);
            $section1->addTitle("KATA PENGANTAR");
            $section1->addText("Puji syukur kita ucapkan kepada Allah Subhanahu wa ta’ala, yang senantiasa melimpahkan rahmat dan nikmat serta inayah-Nya kepada kita. Atas rahmat dan petunjuk-Nya Panduan Penyelenggaraan Pelatihan Jarak Jauh (PJJ) " . $pelatihan->nama_pelatihan . " telah selesai disusun.", $fontstyle, $paragraphstyle);
            $section1->addText("Panduan ini antara lain memuat tujuan, garis-garis besar program, peserta dan narasumber, tata tertib, hak dan kewajiban peserta dan lain sebagainya. Panduan ini disusun sebagai acuan bagi panitia, peserta, narasumber dan semua pihak yang terlibat dalam pelaksanaan pelatihan ini, dengan harapan terjadinya sinergi dan hubungan yang harmonis antara peserta dengan peserta dan antara peserta dengan panitia dan penceramah/ narasumber, sehingga pelaksanaan pelatihan ini dapat berjalan sesuai dengan yang diharapkan. Apabila ada hal penting dalam proses pelaksanaan pelatihan yang belum tercantum dalam panduan ini, akan diatur kemudian.", $fontstyle, $paragraphstyle);
            $section1->addText("Apabila ada hal penting dalam proses pelaksanaan pelatihan yang belum tercantum dalam panduan ini, akan diatur kemudian.", $fontstyle, $paragraphstyle);
                    
            //Pendahuluan
            $section1->addPageBreak();
            $section1->addTitle("PENDAHULUAN");
            $section1->addTitle("A. Latar Belakang", 2);
            $section1->addText("Peraturan Menteri Agama (PMA) Nomor 19 Tahun 2020 tentang Penyelenggaraan Pelatihan Sumber Daya Manusia pada Kementerian Agama menyatakan bahwa untuk mewujudkan Sumber Daya Manusia pada Kementerian Agama yang memiliki integritas, profesionalitas, inovasi, tanggung jawab dan keteladanan perlu diselenggarakan pelatihan secara terencana dan berjenjang.", $fontstyle, $paragraphstyle);
            $section1->addText("Selanjutnya berdasarkan Pasal 2 PMA, penyelenggaraan pelatihan sumber daya manusia bertujuan mengembangkan kompetensi sumber daya manusia meliputi pengetahuan, keterampilan, dan sikap/perilaku.", $fontstyle, $paragraphstyle);
            $section1->addText("Salah satu kegiatan pelatihan yang menjadi agenda tahun " . $pelatihan->tahun . " Loka Pendidikan dan Pelatihan Keagamaan Pekanbaru adalah " . $pelatihan->nama_pelatihan . ". Pelaksanaan pelatihan mempunyai tiga unsur pokok yang saling melengkapi yaitu peserta, narasumber dan panitia pelaksana. Selama pelaksanaan pelatihan berlangsung ketiga unsur tersebut diharapkan melakukan kegiatan sesuai dengan tugas masing-masing secara terkoordinasi sehingga kelancaran pelaksanaan dan pencapaian tujuan pelatihan dapat terwujud.", $fontstyle, $paragraphstyle);
            $section1->addText("Panduan dan tata tertib ini diharapkan dapat memperlancar komunikasi dan interaksi antar semua pihak dalam pelaksanaan pelatihan.", $fontstyle, $paragraphstyle);		
        
            $section1->addTitle("B. Tujuan", 2);
            $section1->addText("Panduan ini dibuat sebagai acuan bagi penyelenggara, peserta, widyaiswara, serta semua pihak yang terkait dalam pelaksanaan kegiatan pelatihan, sehingga pelatihan ini dapat berjalan sesuai yang diharapkan.", $fontstyle, $paragraphstyle);

            $section1->addTitle("C. Ruang Lingkup", 2);
            $section1->addText("Panduan ini memuat antara lain:", $fontstyle, $paragraphstyle);
            $style = generate_list_style($phpword, 'decimal');
            $section1->addListItem("Pendahuluan", 0, $fontstyle, $style);
            $section1->addListItem("Garis-Garis Besar Program Pelatihan", 0, $fontstyle, $style);
            $section1->addListItem("Peserta dan Narasumber/WI", 0, $fontstyle, $style);
            $section1->addListItem("Penyelenggaraan Pelatihan", 0, $fontstyle, $style);
            $section1->addListItem("Tata Tertib Pelatihan", 0, $fontstyle, $style);
    
            //Garis Besar Pelatihan
            $section1->addPageBreak();

            $style = generate_list_style($phpword, 'decimal');

            $section1->addTitle("GARIS-GARIS BESAR PROGRAM PELATIHAN");
            $section1->addTitle("A. Kelompok Dasar", 2);
            $section1->addText("Kelompok dasar yaitu kelompok mata pelatihan yang bertujuan untuk menanamkan, memperkuat dan meningkatkan kesetiaan dan ketaatan peserta sebagai dasar dalam melaksanakan tugas atau jabatannya sebagai abdi negara dan abdi masyarakat, yang terdiri dari:", $fontstyle, $paragraphstyle);

            if (!empty($pelatihan->materi)) {
                
                foreach ($pelatihan->materi as $materi) {
                    //Akses property yang sudah diparsing
                    if (!empty($materi->kel_dasar_parsed)) {
                        foreach ($materi->kel_dasar_parsed as $item) {
                            $section1->addListItem($item, 0, $fontstyle, $style);
                        }
                    } else {
                        $section1->addText('Materi tidak tersedia', $fontstyle);
                    }
                }
            } else {
                $section1->addText('Tidak ada materi pelatihan', $fontstyle);
            }
        
            $section1->addTitle("B. Kelompok Inti", 2);
            $section1->addText("Kelompok inti yaitu kelompok mata pelatihan yang bertujuan untuk membekali peserta dengan berbagai pengetahuan dan keterampilan di bidang tugas pokok yang bersangkutan. Kelompok ini terdiri dari:", $fontstyle, $paragraphstyle);

            $style = generate_list_style($phpword, 'decimal');

            if (!empty($pelatihan->materi)) {
                
                foreach ($pelatihan->materi as $materi) {
                    //Akses property yang sudah diparsing
                    if (!empty($materi->kel_inti_parsed)) {
                        foreach ($materi->kel_inti_parsed as $item) {
                            $section1->addListItem($item, 0, $fontstyle, $style);
                        }
                    } else {
                        $section1->addText('Materi tidak tersedia', $fontstyle);
                    }
                }
            } else {
                $section1->addText('Tidak ada materi pelatihan', $fontstyle);
            }

            // $section3->addListItem("Peraturan Perundang Undangan Zakat", 0, $fontstyle, $style);

            $section1->addTitle("C. Kelompok Penunjang", 2);
            $section1->addText("Kelompok penunjang adalah kelompok mata pelatihan yang bertujuan untuk memperluas pengetahuan dan wawasan, serta mempertajam pemahaman dan penghayatan peserta terhadap berbagai faktor lingkungan sebagai penunjang pelaksanaan tugas pokok. Kelompok penunjang terdiri dari:", $fontstyle, $paragraphstyle);

            $style = generate_list_style($phpword, 'decimal');

            if (!empty($pelatihan->materi)) {
                
                foreach ($pelatihan->materi as $materi) {
                    //Akses property yang sudah diparsing
                    if (!empty($materi->kel_penunjang_parsed)) {
                        foreach ($materi->kel_penunjang_parsed as $item) {
                            $section1->addListItem($item, 0, $fontstyle, $style);
                        }
                    } else {
                        $section1->addText('Materi tidak tersedia', $fontstyle);
                    }
                }
            } else {
                $section1->addText('Tidak ada materi pelatihan', $fontstyle);
            }

            // $section3->addListItem("Overview", 0, $fontstyle, $style);
            // $section3->addListItem("Building Learning Commitment", 0, $fontstyle, $style);
            // $section3->addListItem("Evaluasi Program", 0, $fontstyle, $style);
            // $section3->addListItem("Rencana Tindak Lanjut", 0, $fontstyle, $style);
            // $section3->addListItem("Ujian", 0, $fontstyle, $style);
    
            //PESERTA DAN FASILITATOR/WIDYAISWARA
            $section1->addPageBreak();
            $section1->addTitle("PESERTA DAN FASILITATOR/WIDYAISWARA");
            $section1->addTitle("Peserta", 2);

            $alphaStyle = generate_list_style($phpword, 'upperLetter');

            $section1->addListItem("Persyaratan peserta: ", 0, ['size'=>12,'bold'=> true], $alphaStyle);
            // $section3->addTitle("Persyaratan Peserta", )

            $style = generate_list_style($phpword, 'decimal');

            $section1->addListItem("Peserta ditunjuk oleh atasan unit kerja masing-masing.", 0, $fontstyle, $style);
            $section1->addListItem("Belum pernah mengikuti pelatihan sejenis.", 0, $fontstyle, $style);
            $section1->addListItem("Menguasai penggunaan  IT secara mandiri sebagai pendukung pelaksanaan Pelatihan Jarak Jauh.", 0, $fontstyle, $style);
            $section1->addListItem("Siap dan mampu mengikuti seluruh kegiatan pelatihan dari awal hingga akhir.", 0, $fontstyle, $style);
            $section1->addListItem("Memiliki komitmen untuk mengembangkan ilmu yang diperoleh dari pelatihan tersebut pada unit kerja masing-masing.", 0, $fontstyle, $style);
            $section1->addListItem("Kelengkapan administrasi yang harus diupload ke google form.", 0, $fontstyle, $style);
            $section1->addListItem("Surat penugasan dari Pejabat Pembina Kepegawaian Instansi format PDF.", 0, $fontstyle, $style);
            $section1->addListItem("Fotokopi Surat Keputusan (SK) terakhir format PDF.", 0, $fontstyle, $style);
            $section1->addListItem("Pas foto format JPG terbaru dengan ketentuan (kisaran 472 x 709 pixel, file berbentuk jpg atau png dengan ukuran minimun 400 kb dan maksimal 1 mb) untuk pas foto berlatar belakang merah, pria/wanita mengenakan pakaian putih dengan dasi berwarna hitam, pria dapat mengenakan jas warna hitam tanpa peci, apabila wanita berjilbab pendek, mengenakan jibab berwarna putih dengan dasi berwarna hitam apabila wanita berjibab panjang mengenakan jilbab putih tanpa dasi.", 0, $fontstyle, $style);
            $section1->addListItem("Nomor handphone aktif dengan jenis layanan prabayar (kartu dengan pengisian ulang pulsa) untuk penggantian biaya komunikasi berupa pulsa.", 0, $fontstyle, $style);
        
            $section1->addListItem("Jumlah dan alokasi peserta", 0, $fontstyle, $alphaStyle);
            $section1->addText("Jumlah peserta seluruhnya " . $pelatihan->jumlah_peserta . " orang;", $fontstyle, $paragraphstyle);
            $section1->addText("Alokasi peserta berada di lingkungan " . $pelatihan->jabatan_peserta . " pada Loka Pendidikan dan Pelatihan Keagamaan Pekanbaru;", $fontstyle, $paragraphstyle);
            
            // New WI List from tbl_pelatihan_pengajar
            $section1->addListItem("Tenaga Fasilitator/Widyaiswara", 0, $fontstyle, $alphaStyle);
            $section1->addText("Tenaga Fasilitator/Widyaiswara pengajar pelatihan ini berasal dari :", $fontstyle, $paragraphstyle);

            $styleDec = generate_list_style($phpword, 'decimal');

            if ($isNonLatsar && (!empty($wiList) || $wiRapat)) {
                foreach ($wiList as $o) {
                    $section1->addListItem($teacherLine($o), 0, $fontstyle, $styleDec);
                }
                if ($wiRapat) {
                    $section1->addListItem($teacherLine($wiRapat) . " (Rapat Kelulusan)", 0, $fontstyle, $styleDec);
                }
            } else {
                // Fallback ke field lama bila bukan PJJ/PDWK
                if (isset($pelatihan->wi_1)) { $section1->addListItem("{$pelatihan->wi_1->nama} berasal dari {$pelatihan->wi_1->asal_satker}", 0, $fontstyle, $styleDec); }
                if (isset($pelatihan->wi_2)) { $section1->addListItem("{$pelatihan->wi_2->nama} berasal dari {$pelatihan->wi_2->asal_satker}", 0, $fontstyle, $styleDec); }
                if (isset($pelatihan->wi_3)) { $section1->addListItem("{$pelatihan->wi_3->nama} berasal dari {$pelatihan->wi_3->asal_satker}", 0, $fontstyle, $styleDec); }
            }

            $section1->addListItem("Pengajar/Fasilitator", 0, $fontstyle, $alphaStyle);

            if ($isNonLatsar && !empty($pengajarList)) {
                foreach ($pengajarList as $o) {
                    $section1->addListItem($teacherLine($o), 0, $fontstyle, $styleDec);
                }
            } else {
                if (isset($pelatihan->pengajar_1)) { $section1->addListItem("{$pelatihan->pengajar_1->nama} berasal dari {$pelatihan->pengajar_1->asal_satker}", 0, $fontstyle, $styleDec); }
                if (isset($pelatihan->pengajar_2)) { $section1->addListItem("{$pelatihan->pengajar_2->nama} berasal dari {$pelatihan->pengajar_2->asal_satker}", 0, $fontstyle, $styleDec); }
                if (isset($pelatihan->pengajar_3)) { $section1->addListItem("{$pelatihan->pengajar_3->nama} berasal dari {$pelatihan->pengajar_3->asal_satker}", 0, $fontstyle, $styleDec); }
            }

            //PENYELENGGARAAN PELATIHAN
            $section1->addPageBreak();
            $section1->addTitle("PENYELENGGARAAN PELATIHAN");                                                        

            $alphaStyle = generate_list_style($phpword, 'upperLetter');

            $section1->addListItem("Penyelenggara", 0, $fontstyle, $alphaStyle);
            $section1->addText("Penyelenggara Pelatihan Jarak Jauh (PJJ) {$pelatihan->nama_pelatihan} berasal dari Loka Pendidikan dan Pelatihan Keagamaan Pekanbaru dengan susunan panitia sebagai berikut:", $fontstyle, $paragraphstyle);
            $phpword->addParagraphStyle('penyelenggaraStyle', [
                'tabs' => [ new Tab('left', 3000) ],
            ]);
            
            if (isset($pelatihan->penanggung_jawab)) {
                $section1->addText("Penanggung Jawab\t: {$pelatihan->penanggung_jawab->nama}", $fontstyle, 'penyelenggaraStyle');
            }

            if (isset($pelatihan->nama_ketua_panitia)) {
                $section1->addText("Ketua\t: {$pelatihan->nama_ketua_panitia}", $fontstyle, 'penyelenggaraStyle');
            }

            if (isset($pelatihan->nama_akademis)) {
                $section1->addText("Bidang Akademis\t: {$pelatihan->nama_akademis}", $fontstyle, 'penyelenggaraStyle');
            }

            if (isset($pelatihan->nama_administrasi)) {
                $section1->addText("Bidang Administrasi\t: {$pelatihan->nama_administrasi}", $fontstyle, 'penyelenggaraStyle');
            }

            if (isset($pelatihan->nama_keuangan)) {
                $section1->addText("Bidang Keuangan\t: {$pelatihan->nama_keuangan}", $fontstyle, 'penyelenggaraStyle');
            }


            $section1->addListItem("Lama (Durasi Waktu) Pelatihan", 0, $fontstyle, $alphaStyle);
            if (isset($pelatihan->materi)){
                $section1->addText("Pelatihan ini dilaksanakan selama $durasi hari, mulai dari tanggal " . $data['tanggal_mulai'] . " s.d " . $data['tanggal_selesai'] . ", dengan jumlah jam pelatihan sebanyak$materi->jumlah_jp Jam Pelatihan (JP).", $fontstyle, $paragraphstyle);
            }

            $section1->addListItem("Tempat Pelatihan", 0, $fontstyle, $alphaStyle);
            $section1->addText("Pelatihan ini dilaksanakan secara virtual melalui whatsapp group, zoom meeting, dan LMS PJJ Kementerian Agama.", $fontstyle, $paragraphstyle);
           
            $section1->addListItem("Pembiayaan", 0, $fontstyle, $alphaStyle);
            $section1->addText("Pembiayaan Pelatihan ini dibebankan kepada Daftar Isian Penyelenggaraan Anggaran (DIPA) Petikan Loka Pendidikan dan Pelatihan Keagamaan Pekanbaru Nomor : SP-DIPA-025.11.2.690527/2024 tanggal 30 November 2024.", $fontstyle, $paragraphstyle);
            
            $section1->addListItem("Sertifikat", 0, $fontstyle, $alphaStyle);
            $section1->addText("Peserta pelatihan yang sudah mengikuti dan menyelesaikan seluruh program yang ditentukan akan diberikan sertifikat.", $fontstyle, $paragraphstyle);

            //TATA TERTIB
            $section1->addPageBreak();
            $section1->addTitle("TATA TERTIB");
            $section1->addText("Di Video Conference/Live Chat", array_merge($fontstyle, ['bold' => true]));

            $alphaStyle = generate_list_style($phpword, 'upperLetter');

            $section1->addListItem("Video Conference", 0, $fontstyle, $alphaStyle);

            $style = generate_list_style($phpword, 'decimal');

            $section1->addListItem("Setiap peserta wajib menghidupkan kamera selama berlangsungnya proses pelatihan;", 0, array_merge($fontstyle, ['size' => 12]), $style);
            $section1->addListItem("Setiap peserta wajib menggunakan nama dengan format nama_asal satker selama berlangsungnya proses pelatihan;", 0, $fontstyle, $style);
            $section1->addListItem("Setiap peserta wajib menonaktifkan/mute suara selama berlangsungnya proses pelatihan dan mengaktifkan suara/unmute suara ketika bertanya atau menjawab pertanyaan;", 0, $fontstyle, $style);
            $section1->addListItem("Setiap peserta wajib mematuhi tata tertib, peraturan dan ketentuan yang ditetapkan oleh panitia pelaksana;", 0, $fontstyle, $style);

            $section1->addListItem("Live Chat", 0, $fontstyle, $alphaStyle);
            $section1->addText("Setiap peserta wajib mengikuti diskusi di forum live chat dengan memberikan respon terhadap topik yang diberikan narasumber.", $fontstyle, $paragraphstyle);
            
            $section1->addListItem("Hak dan Kewajiban Peserta", 0, $fontstyle, $alphaStyle);

            $style = generate_list_style($phpword, 'decimal');

            $section1->addListItem("Hak Peserta", 0, array_merge($fontstyle, ['size' => 12]), $style);
            $section1->addText("Peserta yang dinyatakan lulus berhak mendapatkan sertifikat pelatihan;
            Penggantian biaya komunikasi dalam bentuk pulsa telepon atau paket data internet.
            ", $fontstyle, $paragraphstyle);

            $section1->addListItem("Kewajiban Peserta", 0, array_merge($fontstyle, ['size' => 12]), $style);
            $section1->addText("Setiap peserta wajib mematuhi tata tertib, peraturan dan ketentuan yang ditetapkan oleh panitia pelaksana;", $fontstyle, $paragraphstyle);
            $section1->addText("Setiap peserta wajib mengikuti seluruh program pelatihan sesuai dengan jadwal yang ditetapkan.", $fontstyle, $paragraphstyle);
            $section1->addText("Peserta wajib mengikuti pembukaan dan penutupan pelatihan dan hadir sebelum acara dimulai dengan berpakaian yang telah ditentukan oleh panitia;", $fontstyle, $paragraphstyle);
            $section1->addText("Untuk mendukung kelancaran proses pembelajaran, para peserta diwajibkan memilih pengurus kelas minimal terdiri dari ketua dan sekretaris kelas bertugas sebagai penghubung antara panitia/fasilitator dengan peserta pelatihan;", $fontstyle, $paragraphstyle);
            $section1->addText("Peserta harus mengikuti materi pelatihan yang diselenggarakan setiap hari sesuai dengan jadwal serta diwajibkan mengisi daftar hadir di LMS PJJ Kemenag;", $fontstyle, $paragraphstyle);
            $section1->addText("Pakaian selama pelatihan berlangsung, peserta wajib berpakaian kemeja lengan panjang berwarna putih dan celana panjang berwarna gelap, rapi, sopan dan memakai dasi (perempuan menyesuaikan);", $fontstyle, $paragraphstyle);
            $section1->addText("Peserta yang ada keperluan/urusan penting dan tidak dapat mengikuti kegiatan pelatihan harus memberitahukan kepada panitia;", $fontstyle, $paragraphstyle);
            $section1->addText("Untuk menyelaraskan interaksi dan komunikasi antar peserta diharuskan melepaskan perbedaan status, pangkat/golongan, jabatan serta status sosial lainnya.", $fontstyle, $paragraphstyle);
            
            $section1->addListItem("Penutup", 0, $fontstyle, $alphaStyle);
            $section1->addText("Demikian Panduan ini disusun untuk dijadikan acuan dalam pelaksanaan $pelatihan->nama_kegiatan baik bagi peserta, tenaga pengajar, maupun oleh penyelenggara. Regulasi yang belum diatur di dalam panduan ini akan disampaikan lebih lanjut oleh Panitia Penyelenggara.", $fontstyle, $paragraphstyle);

            $section1->addText("Pekanbaru, $pelatihan->bulan_ttd_lap $pelatihan->tahun,", $fontstyle, ['alignment' => 'both', 'indentation' => ['left' => Converter::cmToTwip(9.75)]]);
            $section1->addText('Kepala Loka Pendidikan dan Pelatihan Keagamaan Pekanbaru', array_merge($fontstyle, ['bold' => true]), ['alignment' => 'both', 'indentation' => ['left' => Converter::cmToTwip(9.75)]]); 
            $section1->addTextBreak(3);
            $section1->addText('H. Aprianto, S.Ag., M.A.', array_merge($fontstyle, ['bold' => true]), ['alignment' => 'both', 'indentation' => ['left' => Converter::cmToTwip(9.75)]]);

            //LAPORAN PENYELENGGARAAN//
            $coverSection2 = $phpword->addSection([
                'pageSizeW' => \PhpOffice\PhpWord\Shared\Converter::inchToTwip(8.5),
                'pageSizeH' => \PhpOffice\PhpWord\Shared\Converter::inchToTwip(11),
                'marginTop' => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(1),
                'marginLeft' => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(1),
                'marginRight' => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(1),
                'marginBottom' => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(1),
            ]);

            // Add background image with proper centering
            $imageWidth = \PhpOffice\PhpWord\Shared\Converter::cmToTwip(1); // ~3.9 inches wide
            $coverSection2->addImage(
                'assets/cover/Cover_PJJ_Penyelenggaraan.png',
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
            $coverSection2->addTextBreak(9); // Adds 4 line breaks (you can adjust this number)
            $coverSection2->addText(
                strtoupper($pelatihan->nama_kegiatan),
                [
                    'name' => 'Times New Roman',
                    'size' => 13,
                    'bold' => true,
                    'color' => '244061',
                ],
                [
                    'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::START,
                    'spaceAfter' => 300,
                ]
            );

            // Add date information (using your dynamic data)
            $coverSection2->addText("" . $data['tanggal_mulai'] . " s.d " . $data['tanggal_selesai'] . ".",
                [
                    'name' => 'Times New Roman',
                    'size' => 12,
                    'color' => '244061', // hex format, same as #244061
                ],
                [
                    'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::START,
                    'spaceAfter' => 600,
                ]
            );

            // Add PANITIA header
            $coverSection2->addText(
                'PANITIA :',
                [
                    'name' => 'Times New Roman',
                    'size' => 12,
                    'bold' => true,
                ],
                [
                    'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::START,
                    'spaceAfter' => 200,
                ]
            );

            // Add committee members (using your dynamic data)
            $committeeStyle = [
                'name' => 'Times New Roman',
                'size' => 12,
            ];
            $paragraphStyle = [
                'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::START,
                'spaceAfter' => 100,
            ];

            $coverSection2->addText($pelatihan->nama_ketua_panitia, $committeeStyle, $paragraphStyle);
            $coverSection2->addText($pelatihan->nama_akademis, $committeeStyle, $paragraphStyle);
            $coverSection2->addText($pelatihan->nama_keuangan, $committeeStyle, $paragraphStyle);
            $coverSection2->addText($pelatihan->nama_administrasi, $committeeStyle, $paragraphStyle);

            // // Add footer text
            // $coverSection->addTextBreak(4); // Add some space

            // $coverSection->addText(
            //     'LOKA PENDIDIKAN DAN PELATIHAN KEAGAMAAN PEKANBARU',
            //     [
            //         'name' => 'Times New Roman',
            //         'size' => 12,
            //         'bold' => true,
            //     ],
            //     [
            //         'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER,
            //         'spaceAfter' => 100,
            //     ]
            // );

            // $coverSection->addText(
            //     "TAHUN $pelatihan->tahun",
            //     [
            //         'name' => 'Times New Roman',
            //         'size' => 12,
            //         'bold' => true,
            //     ],
            //     [
            //         'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER,
            //     ]
            // );

            // Kata Pengantar
            // $section3->setStyle(['tabs' => []]);
            $section2 = $phpword->addSection();
            $section2->addTitle("KATA PENGANTAR");
            $section2->addText("Puji syukur kehadirat Tuhan Yang Maha Esa atas rahmat dan karunia-Nya, sehingga $pelatihan->nama_kegiatan ini dapat diselesaikan dengan baik.", $fontstyle, $paragraphstyle);
            $section2->addText("Laporan ini disusun sebagai bentuk pertanggungjawaban atas pelaksanaan pelatihan yang telah dilaksanakan. Laporan pelatihan ini terdiri dari tiga bab. Bab I Pendahuluan, memuat organisasi diklat, nama unit/satuan kerja, nama diklat yang diselenggarakan, dasar hukum, sumber pembiayaan, susunan panitia, dan alamat penyelenggara. Bab II berisi tentang pelaksanaan Pelatihan Jarak Jauh $pelatihan->nama_pelatihan untuk $pelatihan->tempat tahun $pelatihan->tahun yang mencakup tujuan dan sasaran, kurikulum, peserta, widyaiswara/narasumber, evaluasi, penyelenggaraan, keuangan, penjaminan mutu, dan lain-lain. Bab III sebagai penutup terdiri dari kesimpulan dan saran-saran. Laporan ini juga dilengkapi dengan lampiran-lampiran sebagai bukti fisik.", $fontstyle, $paragraphstyle);
            $section2->addText("Diharapkan laporan ini dapat memberikan gambaran yang jelas mengenai pelaksanaan pelatihan, capaian yang telah diperoleh, serta rekomendasi untuk perbaikan di masa mendatang.", $fontstyle, $paragraphstyle);

            $section2->addText("Pekanbaru, $pelatihan->bulan_ttd_lap $pelatihan->tahun,", $fontstyle, ['alignment' => 'both', 'indentation' => ['left' => Converter::cmToTwip(9.75)]]);
            $section2->addText('Ketua', array_merge($fontstyle, ['bold' => true]), ['alignment' => 'both', 'indentation' => ['left' => Converter::cmToTwip(9.75)]]);
            $section2->addTextBreak(3);
            $section2->addText("{$pelatihan->nama_ketua_panitia}", array_merge($fontstyle, ['bold' => true]), ['alignment' => 'both', 'indentation' => ['left' => Converter::cmToTwip(9.75)]]);
            $section2->addText("NIP. {$pelatihan->nip_ketua_panitia}", array_merge($fontstyle, ['bold' => true]), ['alignment' => 'both', 'indentation' => ['left' => Converter::cmToTwip(9.75)]]);
            // Daftar Isi
            $section2->addPageBreak();
            $section2->addTitle("DAFTAR ISI");
            $toc2 = $section2->addTOC($tocFontStyle, $tocStyle);

            // BAB I
            $section2->addPageBreak();
            $section2->setStyle([
                'tabs' => [
                    new Tab('left', 3000),
                    new Tab('left', 3500)
                    ]
            ]);
            $section2->addTitle("BAB I", 1);
            $section2->addTitle("PENDAHULUAN", 1);
            $section2->addTitle("A. Organisasi Diklat", 2);
            $section2->addText("Dalam lanskap pendidikan dan pelatihan keagamaan yang terus berkembang, Kementerian Agama Republik Indonesia mengambil langkah strategis. Berdasarkan Peraturan Menteri Agama Nomor 15 Tahun 2021 tentang Organisasi Dan Tata Kerja Unit Pelaksana Teknis Pendidikan Dan Pelatihan Keagamaan lahirlah Loka Pendidikan dan Pelatihan Keagamaan Pekanbaru. Pada bulan Maret tahun 2022 Loka Pendidikan dan Pelatihan Keagamaan Pekanbaru memulai melaksanakan kegiatan operasional sebagai langkah awal untuk melaksanakan tugas pokok sebagai lembaga Pendidikan dan Pelatihan. Pendirian Loka Pendidikan dan Pelatihan Keagamaan Pekanbaru sejalan dengan komitmen Kementerian Agama untuk meningkatkan kualitas sumber daya manusia (SDM) ASN dan mewujudkan mutu pendidikan serta layanan keagamaan yang lebih baik di wilayah Riau dan Kepulauan Riau. Pendirian Loka Pendidikan dan Pelatihan Keagamaan Pekanbaru merupakan manifestasi nyata dari komitmen Kementerian Agama untuk meningkatkan kualitas sumber daya manusia (SDM) Aparatur Sipil Negara (ASN) di bidang keagamaan, khususnya di wilayah Riau dan Kepulauan Riau. Melalui berbagai program pelatihan, Loka Pendidikan dan Pelatihan Keagamaan Pekanbaru berupaya mencetak ASN yang kompeten, profesional, dan mampu memberikan layanan keagamaan yang berkualitas kepada masyarakat.", $fontstyle, $paragraphstyle);
            $section2->addText("Salah satu program unggulan yang diselenggarakan oleh Loka Pendidikan dan Pelatihan Keagamaan Pekanbaru adalah Pelatihan Jarak Jauh $pelatihan->nama_pelatihan. Pelatihan ini dirancang khusus untuk meningkatkan kemampuan para ASN dalam $materi->tujuan_pelatihan.", $fontstyle, $paragraphstyle);
            
            $section2->addTitle("B. Nama Unit/Satuan Kerja", 2);
            $section2->addText("Nama unit/satuan kerja penyelenggara $pelatihan->nama_kegiatan ini adalah Loka Diklat Keagamaan Pekanbaru, Jl. Yos Sudarso, Rumbai, Pekanbaru.", $fontstyle, $paragraphstyle);
            
            $section2->addTitle("C. Nama Diklat yang Diselenggarakan", 2);
            $section2->addText("Nama Diklat yang diselenggarakan adalah $pelatihan->nama_pelatihan bagi $pelatihan->jabatan_peserta di Loka Pendidikan dan Pelatihan Keagamaan Pekanbaru Tahun 2024.", $fontstyle, $paragraphstyle);
            
            $section2->addTitle("D. Dasar Hukum", 2);
            $section2->addText("Dasar $pelatihan->nama_kegiatan bagi Wilayah Kerja Kementerian Agama $pelatihan->tempat ini adalah : ", $fontstyle, $paragraphstyle);

            $style = generate_list_style($phpword, 'decimal');

            $section2->addListItem("Undang-Undang Nomor 20 Tahun 2023 tentang Aparatur Sipil Negara;", 0, $fontstyle, $style);
            $section2->addListItem("Peraturan Pemerintah Republik Indonesia Nomor 17 Tahun 2020 Tentang Perubahan Atas Peraturan Pemerintah Nomor :1 Tahun 2017 Tentang Manajemen Pegawai Negeri Sipil;", 0, $fontstyle, $style);
            $section2->addListItem("Peraturan Presiden Nomor 12 Tahun 2023 tentang Kementerian Agama (Lembaran Negara Republik Indonesia Tahun 2023 Nomor 21);", 0, $fontstyle, $style);
            $section2->addListItem("Peraturan Menteri Pendayagunaan Aparatur Negara Nomor PER/18/M.PAN/II/2003 tentang Pedoman Organisasi Unit Pelaksana Teknis Kementerian dan Lembaga Pemerintahan Non Kementerian;", 0, $fontstyle, $style);
            $section2->addListItem("Peraturan Menteri Agama Nomor 59 Tahun 2015 tentang Organisasi Tata Kerja Balai Diklat dan Pelatihan Keagamaan;", 0, $fontstyle, $style);
            $section2->addListItem("Peraturan Menteri Agama RI Nomor 42 Tahun 2016 tentang Organisasi dan Tata Kerja Kementerian Agama;", 0, $fontstyle, $style);
            $section2->addListItem("Peraturan Menteri Agama Nomor 19 Tahun 2020 tentang Penyelenggaraan Pelatihan Sumber Daya Manusia pada Kementerian Agama;", 0, $fontstyle, $style);
            $section2->addListItem("Peraturan Menteri Agama Nomor 15 Tahun 2021 tentang Organisasi Dan Tata Kerja Unit Pelaksana Teknis Pendidikan Dan Pelatihan Keagamaan;", 0, $fontstyle, $style);
            $section2->addListItem("DIPA Loka Diklat Keagamaan Pekanbaru Tahun 2024 Nomor: DIPA 025.11.2. 690527/2022, tanggal 30 November 2023.", 0, $fontstyle, $style);

            $section2->addTitle("E. Sumber Pembiayaan", 2);
            $section2->addText("Sumber biaya penyelenggaraan $pelatihan->nama_kegiatan bagi Wilayah Kerja Kementerian Agama $pelatihan->nama_pelatihan Tahun $pelatihan->tahun ini dibebankan pada DIPA Loka Diklat Keagamaan Pekanbaru Nomor : DIPA 025.11.2. 690527/2024, tanggal 30 November 2023.", $fontstyle, $paragraphstyle);
            
            $section2->addTitle("F. Susunan Panitia", 2);
            $section2->addText("Adapun susunan panitia penyelenggara $pelatihan->nama_kegiatan Wilayah Kerja Kementerian Agama $pelatihan->provinsi Tahun $pelatihan->tahun adalah sebegai berikut:", $fontstyle, $paragraphstyle);

            if (isset($pelatihan->nama_ketua_panitia)) {
                $section2->addText("Ketua\t: {$pelatihan->nama_ketua_panitia}", $fontstyle, $paragraphstyle);
            }

            if (isset($pelatihan->nama_akademis)) {
                $section2->addText("Seketaris/Akademis\t: {$pelatihan->nama_akademis}", $fontstyle, $paragraphstyle);
            }

            if (isset($pelatihan->nama_administrasi)) {
                $section2->addText("Anggota/Keuangan\t: {$pelatihan->nama_administrasi}", $fontstyle, $paragraphstyle);
            }

            if (isset($pelatihan->nama_keuangan)) {
                $section2->addText("Anggota/Keuangan\t: {$pelatihan->nama_keuangan}", $fontstyle, $paragraphstyle);
            }


            $section2->addTitle("G. Alamat Penyelenggara", 2);
            $section2->addText("Alamat penyelenggara Pelatihan Jarak Jauh $pelatihan->nama_pelatihan Wilayah Kerja Kementerian Agama Provinsi $pelatihan->tempat Tahun $pelatihan->tahun ini adalah Loka Diklat Keagamaan Pekanbaru, Jl. Yos Sudarso, Rumbai, Pekanbaru. Email: loka_pekanbaru@kemenag.go.id.", $fontstyle, $paragraphstyle);

            // BAB II
            $section2->addPageBreak();
            $section2->addTitle("BAB II", 1);
            $section2->addTitle("PELAKSANAAN DIKLAT", 1);
            $section2->addTitle("A. Tujuan dan Sasaran", 2);
            $section2->addText("$pelatihan->nama_kegiatan di $pelatihan->tempat Tahun $pelatihan->tahun ini memiliki tujuan dan sasaran sebagai berikut :", $fontstyle, $paragraphstyle);
            
            $style = generate_list_style($phpword, 'decimal');

            $section2->addListItem("Tujuan", 0, array_merge($fontstyle, ['size' => 12]), $style);
            $section2->addText("Pelatihan ini bertujuan untuk : ", $fontstyle, $paragraphstyle);
            
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

            $style = generate_list_style($phpword, 'decimal', 2);

            $section2->addListItem("Sasaran", 0, $fontstyle, $style);
            $section2->addText("Adapun sasaran Pelatihan $pelatihan->nama_pelatihan Tahun $pelatihan->tahun ini adalah tersedianya $pelatihan->jumlah_peserta orang $pelatihan->jabatan_peserta yang cakap dan kompeten.", $fontstyle, $paragraphstyle);
            
            $section2->addTitle("B. Kurikulum", 2);
            $section2->addText("Kurikulum $pelatihan->nama_kegiatan Tahun $pelatihan->tahun ini disesuaikan dengan kurikulum $materi->asal_kursil Badan Litbang dan Diklat Kementerian Agama Republik Indonesia.", $fontstyle, $paragraphstyle);
            
            $section2->addTitle("C. Mata Diklat dan Jumlah Jam Pelajaran", 2);
            $section2->addText("Mata $pelatihan->nama_pelatihan Tahun $pelatihan->tahun ini memiliki jumlah jam pelajaran sebanyak $materi->jumlah_jp jam pelajaran dengan jenis mata pelatihan materi kelompok dasar $materi->jp_kel_dasar JP, materi kelompok inti $materi->jp_kel_inti JP, dan kelompok penunjang $materi->jp_kel_penunjang JP.", $fontstyle, $paragraphstyle);
            $section2->addText("Kelompok dasar yaitu kelompok mata pelajaran yang bertujuan untuk menanamkan, memperkuat dan meningkatkan profesionalisme, kesetiaan dan ketaatan peserta sebagai dasar dalam melaksanakan tugas jabatannya sebagai abdi negara dan abdi masyarakat yang meliputi :", $fontstyle, $paragraphstyle);
            
            $style = generate_list_style($phpword, 'decimal');

            if (!empty($pelatihan->materi)) {
                
                foreach ($pelatihan->materi as $materi) {
                    //Akses property yang sudah diparsing
                    if (!empty($materi->kel_dasar_parsed)) {
                        foreach ($materi->kel_dasar_parsed as $item) {
                            $section2->addListItem($item, 0, $fontstyle, $style, ['indentation' => ['left' => 720, 'hanging' => 360]]);
                        }
                    } else {
                        $section2->addText('Materi tidak tersedia', $fontstyle);
                    }
                }
            } else {
                $section2->addText('Tidak ada materi pelatihan', $fontstyle);
            }

            $section2->addText("Kelompok Inti yaitu kelompok mata pelajaran yang bertujuan untuk membekali peserta dengan pengetahuan dibidang tugas pokok yang bersangkutan meliputi :", $fontstyle, $paragraphstyle);

            $style = generate_list_style($phpword, 'decimal');

            if (!empty($pelatihan->materi)) {
                
                foreach ($pelatihan->materi as $materi) {
                    //Akses property yang sudah diparsing
                    if (!empty($materi->kel_inti_parsed)) {
                        foreach ($materi->kel_inti_parsed as $item) {
                            $section2->addListItem($item, 0, $fontstyle, $style, ['indentation' => ['left' => 720, 'hanging' => 360]]);
                        }
                    } else {
                        $section2->addText('Materi tidak tersedia', $fontstyle);
                    }
                }
            } else {
                $section2->addText('Tidak ada materi pelatihan', $fontstyle);
            }

            $section2->addText("Kelompok penunjang adalah kelompok mata pelajaran yang bertujuan untuk memperluas pengetahuan dan wawasan, serta mempertajam pemahaman dan penghayatan peserta terhadap berbagai faktor, termasuk lingkungan. Sebagai penunjang pelaksanaan tugas pokok tersebut terdiri dari :", $fontstyle, $paragraphstyle);

            $style = generate_list_style($phpword, 'decimal');

            if (!empty($pelatihan->materi)) {
                
                foreach ($pelatihan->materi as $materi) {
                    //Akses property yang sudah diparsing
                    if (!empty($materi->kel_penunjang_parsed)) {
                        foreach ($materi->kel_penunjang_parsed as $item) {
                            $section2->addListItem($item, 0, $fontstyle, $style, ['indentation' => ['left' => 720, 'hanging' => 360]]);
                        }
                    } else {
                        $section2->addText('Materi tidak tersedia', $fontstyle);
                    }
                }
            } else {
                $section2->addText('Tidak ada materi pelatihan', $fontstyle);
            }

            $section2->addTitle("D. Jadwal Pelatihan", 2);
            $section2->addText("Adapun Jadwal $pelatihan->nama_pelatihan Tahun $pelatihan->tahun dimuat di lampiran.", $fontstyle, $paragraphstyle);
            
            $section2->addTitle("E. Peserta", 2);
            
            $style = generate_list_style($phpword, 'decimal');
            
            $section2->addListItem("Jumlah dan Asal Peserta", 0, $fontstyle, $style, ['indentation' => ['left' => 720, 'hanging' => 360]]);
            $section2->addText("Peserta $pelatihan->nama_pelatihan berjumlah $pelatihan->jumlah_peserta orang yang terdiri dari $pelatihan->jabatan_peserta yang berasal dari $pelatihan->tempat", $fontstyle, $paragraphstyle);
            
            $section2->addListItem("Status Kepegawaian, Jenis Kelamin, dan Pendidikan Peserta", 0, $fontstyle, $style, ['indentation' => ['left' => 720, 'hanging' => 360]]);
            $section2->addText("Peserta yang merupakan Aparatur Sipil Negara (ASN) berjumlah $pelatihan->jumlah_peserta_asn orang dan $pelatihan->jumlah_peserta_non_asn orang non ASN. Peserta terdiri dari $pelatihan->jumlah_peserta_laki orang laki-laki dan $pelatihan->jumlah_peserta_wanita orang perempuan. Pendidikan terakhir dari peserta adalah sebagai berikut:", $fontstyle, $paragraphstyle);

            $section2->addText("SMA/MA\t: $pelatihan->jumlah_pendidikan_peserta_sma", $fontstyle, $paragraphstyle);
            $section2->addText("D3\t: $pelatihan->jumlah_pendidikan_peserta_d3", $fontstyle, $paragraphstyle);
            $section2->addText("S1\t: $pelatihan->jumlah_pendidikan_peserta_s1", $fontstyle, $paragraphstyle);
            $section2->addText("S2\t: $pelatihan->jumlah_pendidikan_peserta_s2", $fontstyle, $paragraphstyle);
            $section2->addText("S3\t: $pelatihan->jumlah_pendidikan_peserta_s3", $fontstyle, $paragraphstyle);

            $section2->addTitle("F. Widyaiswara/Tenaga Pengajar", 2);
            $section2->addText("Jumlah, asal daerah, dan jenjang akademik Widyaiswara/Tenaga Pengajar $pelatihan->nama_kegiatan bagi $pelatihan->tempat Tahun $pelatihan->tahun ini adalah adalah sebagai berikut :", $fontstyle, $paragraphstyle);
            // New WI list from tbl_pelatihan_pengajar
            $section2->addText("Jumlah dan Asal Widyaiswara/Tenaga Pengajar", $fontstyle, $paragraphstyle);

            $styleDec = generate_list_style($phpword, 'decimal');

            if ($isNonLatsar) {
                // hitung dari mapping baru
                $totalTeachers = count($wiList) + count($pengajarList) + ($wiRapat ? 1 : 0);
                $section2->addText("Widyaiswara/Tenaga Pengajar berjumlah {$totalTeachers} orang, yakni :", $fontstyle, $paragraphstyle);

                foreach ($wiList as $o) {
                    $section2->addListItem($teacherLine($o), 0, $fontstyle, $styleDec, ['indentation' => ['left' => 720, 'hanging' => 360]]);
                }
                if ($wiRapat) {
                    $section2->addListItem($teacherLine($wiRapat) . " (Rapat Kelulusan)", 0, $fontstyle, $styleDec, ['indentation' => ['left' => 720, 'hanging' => 360]]);
                }

                if (!empty($pengajarList)) {
                    // garis pemisah kecil antar kelompok (opsional)
                    // $section2->addTextBreak(1);
                    foreach ($pengajarList as $o) {
                        $section2->addListItem($teacherLine($o), 0, $fontstyle, $styleDec, ['indentation' => ['left' => 720, 'hanging' => 360]]);
                    }
                }
            } else {
                // fallback (Latsar) – tetap pakai field lama
                $section2->addText("Widyaiswara/Tenaga Pengajar berjumlah " . (int)$pelatihan->jumlah_wi_pengajar . " orang, yakni :", $fontstyle, $paragraphstyle);

                if (isset($pelatihan->wi_1)) { $section2->addListItem("{$pelatihan->wi_1->nama} berasal dari {$pelatihan->wi_1->asal_satker}", 0, $fontstyle, $styleDec, ['indentation' => ['left' => 720, 'hanging' => 360]]); }
                if (isset($pelatihan->wi_2)) { $section2->addListItem("{$pelatihan->wi_2->nama} berasal dari {$pelatihan->wi_2->asal_satker}", 0, $fontstyle, $styleDec, ['indentation' => ['left' => 720, 'hanging' => 360]]); }
                if (isset($pelatihan->wi_3)) { $section2->addListItem("{$pelatihan->wi_3->nama} berasal dari {$pelatihan->wi_3->asal_satker}", 0, $fontstyle, $styleDec, ['indentation' => ['left' => 720, 'hanging' => 360]]); }

                if (isset($pelatihan->pengajar_1)) { $section2->addListItem("{$pelatihan->pengajar_1->nama} {$pelatihan->pengajar_1->asal_satker}", 0, $fontstyle, $styleDec, ['indentation' => ['left' => 720, 'hanging' => 360]]); }
                if (isset($pelatihan->pengajar_2)) { $section2->addListItem("{$pelatihan->pengajar_2->nama} {$pelatihan->pengajar_2->asal_satker}", 0, $fontstyle, $styleDec, ['indentation' => ['left' => 720, 'hanging' => 360]]); }
                if (isset($pelatihan->pengajar_3)) { $section2->addListItem("{$pelatihan->pengajar_3->nama} {$pelatihan->pengajar_3->asal_satker}", 0, $fontstyle, $styleDec, ['indentation' => ['left' => 720, 'hanging' => 360]]); }
            }


            $section2->addTitle("G. Jenjang Akademik/Kualifikasi Widyaiswara/Tenaga Pengajar", 2);
            $section2->addText("Jenjang akademik/kualifikasi pendidikan Widyaiswara/Tenaga Pengajar pada Diklat ini adalah:", $fontstyle, $paragraphstyle);

            $section2->addText("Setara D2/D3\t: $pelatihan->jumlah_pendidikan_wi_d2 orang", $fontstyle, $paragraphstyle);
            $section2->addText("S1\t: $pelatihan->jumlah_pendidikan_wi_s1 orang", $fontstyle, $paragraphstyle);
            $section2->addText("S2\t: $pelatihan->jumlah_pendidikan_wi_s2 orang", $fontstyle, $paragraphstyle);
            $section2->addText("S3\t: $pelatihan->jumlah_pendidikan_wi_s3 orang", $fontstyle, $paragraphstyle);
            
            $section2->addTitle("H. Evaluasi", 2);
            $section2->addText("Evaluasi dilakukan terhadap penyelenggara, widyaiswara/Tenaga Pengajar, dan peserta sebagai berikut :", $fontstyle, $paragraphstyle);
            $section2->addText("Hasil Evaluasi Terhadap Penyelenggara (terlampir)", $fontstyle, $paragraphstyle);
            
            $section2->addTitle("I. Hasil Evaluasi Terhadap Widyaiswara/Tenaga Pengajar (terlampir)", 2);
            $section2->addText("Hasil Rekapitulasi Nilai Peserta (terlampir)", $fontstyle, $paragraphstyle);
            
            $section2->addTitle("J. Penyelenggaraan", 2);
            $section2->addText("Pelatihan Jarak Jauh Intensif ini dirancang untuk memberikan pengalaman belajar yang fleksibel dan efektif bagi peserta dari berbagai wilayah. Pelatihan akan berlangsung selama $durasi hari hari dengan kombinasi kegiatan mandiri melalui LMS dan sesi live melalui Zoom.", $fontstyle, $paragraphstyle);
            $section2->addText("Pelatihan ini menggabungkan penggunaan Learning Management System (LMS) https://pjj.kemenag.go.id/ sebagai platform utama pembelajaran dengan aplikasi konferensi video Zoom untuk sesi interaktif secara langsung.", $fontstyle, $paragraphstyle);
            $section2->addText("Platform ini akan menjadi pusat kegiatan belajar peserta. Di sini, peserta akan menemukan:", $fontstyle, $paragraphstyle);
            $section2->addText("Materi Pelatihan berupa modul-modul pembelajaran, presentasi, dan sumber daya lainnya yang disusun secara sistematis.", $fontstyle, $paragraphstyle);
            $section2->addText("Latihan-latihan untuk mengukur pemahaman peserta terhadap materi.", $fontstyle, $paragraphstyle);
            $section2->addText("Forum Diskusi yaitu ruang interaksi bagi peserta untuk berdiskusi, bertukar pikiran, dan bertanya kepada instruktur.", $fontstyle, $paragraphstyle);
            $section2->addText("Pengumpulan tugas yaitu fasilitas untuk mengumpulkan tugas-tugas yang telah diselesaikan.", $fontstyle, $paragraphstyle);
            
            $section2->addTitle("K. Zoom", 2);
            $section2->addText("Sesi live yaitu rtemuan secara langsung dengan instruktur untuk menyampaikan materi, menjawab pertanyaan, dan melakukan diskusi lebih mendalam. Workshop berupa kegiatan praktik langsung dengan bimbingan instruktur. Presentasi peserta merupakan kesempatan bagi peserta untuk mempresentasikan hasil kerja mereka.", $fontstyle, $paragraphstyle);
            
            $section2->addTitle("L. Keuangan", 2);
            
            $style = generate_list_style($phpword, 'decimal');
            
            $section2->addListItem("Rencana", 0, $fontstyle, $style);
            $section2->addText("Rencana keuangan yang digunakan dari DIPA LDK Pekanbaru No-DIPA 025.11.2.690527/2024 tanggal 30 November 2023 sebesar Rp. $pelatihan->rab,- (terlampir).", $fontstyle, $paragraphstyle);
            $section2->addListItem("Realisasi", 0, $fontstyle, $style);
            $section2->addText("Realisasi keuangan yang digunakan dari DIPA LDK Pekanbaru No-  DIPA 025.11.2. 690527/2024 tanggal 30 November 2023 sebesar Rp. $pelatihan->realisasi.- (terlampir).", $fontstyle, $paragraphstyle);
            
            $section2->addTitle("M. Penjaminan Mutu", 2);
            $section2->addText("Dalam rangka penjaminan mutu dilakukan usaha pembelajaran yang paling sedikit 5 JP perhari dan paling banyak 8 JP per hari yang berupa tatap muka synchronous. Durasi PJJ paling sedikit 5 (lima) hari dan paling banyak 10 (sepuluh) hari sesuai kebutuhan pelatihan. Hal ini berdasarkan Petunjuk Teknis Pengelolaan Pelatihan Jarak Jauh (Distance Learning) yang dikeluarkan oleh Badan Litbang dan Diklat Kementerian Agama Republik Indonesia.", $fontstyle, $paragraphstyle);
            
            $section2->addTitle("N. Lain-Lain", 2);
            $section2->addText("Pembukaan", $fontstyle, $paragraphstyle);
            $section2->addText("Pembukaan dilaksanakan pada hari\t: " . $data['tanggal_mulai'], $fontstyle, $paragraphstyle);
            $section2->addText("Waktu Pembukaan\t: pukul $pelatihan->waktu_pembukaan WIB", $fontstyle, $paragraphstyle);
            $section2->addText("Pejabat yang membuka\t:", $fontstyle, $paragraphstyle);
            $section2->addText("Nama\t: {$pelatihan->pejabat_pembuka->nama}", $fontstyle, $paragraphstyle);
            $section2->addText("Jabatan\t: {$pelatihan->pejabat_pembuka->jabatan}", $fontstyle, $paragraphstyle);
            
            $section2->addText("Penutupan", $fontstyle, $paragraphstyle);
            $section2->addText("Penutupan dilaksanakan pada hari\t: " . $data['tanggal_selesai'], $fontstyle, $paragraphstyle);
            $section2->addText("Waktu Penutupan\t: pukul $pelatihan->waktu_penutupan WIB", $fontstyle, $paragraphstyle);
            $section2->addText("Pejabat yang menutup\t:", $fontstyle, $paragraphstyle);
            $section2->addText("Nama\t: {$pelatihan->pejabat_pembuka->nama}", $fontstyle, $paragraphstyle);
            $section2->addText("Jabatan\t: {$pelatihan->pejabat_pembuka->jabatan}", $fontstyle, $paragraphstyle);

            $section2->addTitle("O. Pemberian Surat Keterangan Diklat", 2);
            $section2->addText("Surat Tanda Tamat Pendidikan dan Pelatihan (STTPP) yang diberikan adalah:", $fontstyle, $paragraphstyle);
            $section2->addText("Jenis Surat\t:Sertifikat", $fontstyle, $paragraphstyle);
            $section2->addText("Pemberian Surat\t:Setelah PJJ Ditutup", $fontstyle, $paragraphstyle);

            //BAB III
            $section2->addPageBreak();
            $section2->addTitle("BAB III", 1);
            $section2->addTitle("PENUTUP", 1);
            $section2->addTitle("A. Keimpulan", 2);
            $section2->addText("$pelatihan->nama_kegiatan bagi $pelatihan->tempat Tahun $pelatihan->tahun ini menggunakan Kurikulum $materi->asal_kursil Badan Litbang dan Diklat Kementerian Agama, yaitu  menggunakan pola $materi->jumlah_jp jam pelajaran dan dilaksanakan selama $durasi hari, mulai " . $data['tanggal_mulai'] . ".", $fontstyle, $paragraphstyle);
            $section2->addText("Beberapa peserta menghadapi kendala teknis seperti koneksi internet yang tidak stabil, yang dapat mengganggu pengalaman pelatihan. Keterbatasan waktu dan kesulitan dalam mengaplikasikan materi secara langsung juga menjadi tantangan yang dihadapi oleh sebagian peserta.", $fontstyle, $paragraphstyle);
            $section2->addText("Secara keseluruhan, pelatihan ini  telah berlangsung dengan baik, tertib, dan aman. Hasilnya, seluruh peserta dinyatakan telah menyelesaikan dan berhak memperoleh STTPP (Surat Tanda Tamat Pendidikan dan Pelatihan) berbentuk Sertifikat, setelah menyelesaikan laporan tindak lanjut.", $fontstyle, $paragraphstyle);

            $section2->addTitle("B. Saran", 2);
            $section2->addText("$pelatihan->nama_kegiatan bagi $pelatihan->tempat Tahun $pelatihan->tahun, disarankan sebagai berikut : Menambahkan sesi praktik langsung atau workshop yang memungkinkan peserta untuk mengaplikasikan materi secara langsung.", $fontstyle, $paragraphstyle);
            $section2->addText("Diharapkan Widyaiswara/Tenaga pengajar menambahkan modul tambahan atau referensi untuk topik-topik yang memerlukan pemahaman lebih mendalam.", $fontstyle, $paragraphstyle);
            $section2->addText("Diharapkan kepada panitia agar terus dapat meningkatkan pelayanan, sehingga dicapai tingkat pelayanan yang prima. Disamping itu, panitia diharapkan panduan atau pelatihan singkat tentang penggunaan teknologi yang akan digunakan selama pelatihan.", $fontstyle, $paragraphstyle);
            $section2->addText("Diharapkan kepada Widyaiswara/Tenaga Pengajar, Pengelola serta penyelenggara pelatihan agar melakukan evaluasi rutin terhadap pelatihan untuk mengidentifikasi area perbaikan dan memastikan bahwa pelatihan tetap relevan dengan kebutuhan peserta.", $fontstyle, $paragraphstyle);

            $section2->addText("Pekanbaru, $pelatihan->bulan_ttd_lap $pelatihan->tahun,", $fontstyle, ['alignment' => 'both', 'indentation' => ['left' => Converter::cmToTwip(9.75)]]);
            $section2->addText('Diterima Kepala LDK Pekanbaru', array_merge($fontstyle, ['bold' => true]), ['alignment' => 'both', 'indentation' => ['left' => Converter::cmToTwip(9.75)]]); 
            $section2->addTextBreak(3);
            $section2->addText("H. Aprianto, S.Ag., M.A.", array_merge($fontstyle, ['bold' => true]), ['alignment' => 'both', 'indentation' => ['left' => Converter::cmToTwip(9.75)]]);
            $section2->addText("NIP. 197603012003121004", array_merge($fontstyle, ['bold' => true]), ['alignment' => 'both', 'indentation' => ['left' => Converter::cmToTwip(9.75)]]);

                    $coverSection3 = $phpword->addSection([
            'pageSizeW' => \PhpOffice\PhpWord\Shared\Converter::inchToTwip(8.5),
            'pageSizeH' => \PhpOffice\PhpWord\Shared\Converter::inchToTwip(11),
            'marginTop' => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(1),
            'marginLeft' => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(1),
            'marginRight' => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(1),
            'marginBottom' => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(1),
        ]);

        // Add logo (adjust path and size as needed)
        $coverSection3->addImage(
            'assets/cover/Logo_Kemenag.png',
            [
                'width' => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(0.2),
                'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER,
                'marginTop' => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(1),
            ]
        );

        $coverSection3->addTextBreak(2); // Add space before footer

        // Add "TERM OF REFERENCES" text
        $coverSection3->addText(
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

               // Add logo (adjust path and size as needed)
        $coverSection3->addImage(
            'assets/cover/three_line_cover.png',
            [
                'width' => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(0.1),
                'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER,
                'marginTop' => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(1),
                'spaceAfter' => 600,
            ]
        );

         $coverSection3->addTextBreak(3); // Add space before footer

            $coverSection3->addText(
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
            $section3->addTitle("KATA PENGANTAR", 1);
            $section3->addText("Puji dan syukur kita panjatkan kehadirat Tuhan Yang Maha Esa, karena berkat rahmat serta karunia-Nya Term Of References (TOR) pelaksanaan kegiatan $pelatihan->nama_kegiatan ini dapat disusun.", $fontstyle, $paragraphstyle);
            $section3->addText("TOR pelatihan ini memuat latar belakang, dasar hukum, maksud dan tujuan, kepesertaan, metode pelaksanaan kegiatan, waktu dan tempat pelaksanaan, serta biaya kegiatan. TOR ini diharapkan dapat menjadi acuan tata kelola pelatihan dari tahap persiapan hingga pelaporan.", $fontstyle, $paragraphstyle);
            $section3->addText("Akhir kata, kritik dan saran yang bersifat membangun sangat diharapkan dalam upaya pengembangan TOR ini guna peningkatan dan perbaikan kualitas pelatihan di Loka Diklat Keagamaan Pekanbaru dimasa yang akan datang", $fontstyle, $paragraphstyle);

            $section3->addText("Pekanbaru, $pelatihan->bulan_ttd_lap $pelatihan->tahun,", $fontstyle, ['alignment' => 'both', 'indentation' => ['left' => Converter::cmToTwip(9.75)]]);
            $section3->addText('Diterima Kepala LDK Pekanbaru', array_merge($fontstyle, ['bold' => true]), ['alignment' => 'both', 'indentation' => ['left' => Converter::cmToTwip(9.75)]]); 
            $section3->addTextBreak(3);
            $section3->addText("H. Aprianto, S.Ag., M.A.", array_merge($fontstyle, ['bold' => true]), ['alignment' => 'both', 'indentation' => ['left' => Converter::cmToTwip(9.75)]]);
            $section3->addText("NIP. 197603012003121004", array_merge($fontstyle, ['bold' => true]), ['alignment' => 'both', 'indentation' => ['left' => Converter::cmToTwip(9.75)]]);

            //Term of References
            $section3->addPageBreak();
            $section3->addTitle("TERM OF REFERENCES", 1);
            $section3->addTitle("$pelatihan->nama_kegiatan", 1);
            $section3->addTitle("A. Latar Belakang", 2);
            $section3->addText("Peraturan Menteri Agama RI Nomor 15 Tahun 2021 Tentang Organisasi dan Tata Kerja Unit Pelaksana Teknis Pendidikan dan Pelatihan Keagamaan terkait penyelenggaraan Pendidikan dan Pelatihan Pegawai Negeri Sipil di lingkungan Kantor Kementerian Agama bahwa UPT Pendidikan dan Pelatihan Keagamaan mempunyai tugas melaksanakan pendidikan dan pelatihan tenaga administrasi dan tenaga teknis pendidikan dan keagamaan kepada ASN Kementerian Agama di wilayah kerja masing-masing dengan berpedoman kepada kebijakan Kepala Badan Litbang dan pelatihan Kementerian Agama. Pelatihan Jarak Jauh merupakan pelatihan yang dilaksanakan dalam kelas virtual melalui media online berdasarkan pertimbangan dan tujuan kebutuhan perluasan akses peserta pelatihan. Pelatihan Jarak Jauh adalah pelatihan formal berbasis lembaga yang peserta didik dan instrukturnya berada di lokasi terpisah sehingga memerlukan sistem telekomunikasi yang interaktif untuk dapat terhubung satu dengan lainnya dan berbagai sumber daya yang diperlukan didalamnya. Pada Pelatihan Jarak Jauh peran teknologi sangat dibutuhkan mengingat pembelajaran dilakukan secara daring.", $fontstyle, $paragraphstyle);
            $section3->addText("Berdasarkan hasil Analisis Kebutuhan Pelatihan (AKP) Loka Pelatihan Keagamaan Pekanbaru tahun 2023, jenis pelatihan yang perlu dilaksanakan adalah $pelatihan->nama_kegiatan. Berdasarkan realita tersebut, Loka Pendidikan dan Pelatihan Keagamaan Pekanbaru memandang perlu melaksanakan $pelatihan->nama_kegiatan.", $fontstyle, $paragraphstyle);

            $section3->addTitle("B. Dasar Hukum", 2);

            $style = generate_list_style($phpword, 'decimal');

            $section3->addListItem("Undang-Undang Nomor 20 Tahun 2023 tentang Aparatur Sipil Negara;", 0, $fontstyle, $style);
            $section3->addListItem("Peraturan Pemerintah Republik Indonesia Nomor 17 Tahun 2020 Tentang Perubahan Atas Peraturan Pemerintah Nomor :1 Tahun 2017 Tentang Manajemen Pegawai Negeri Sipil;", 0, $fontstyle, $style);
            $section3->addListItem("Peraturan Presiden Nomor 12 Tahun 2023 tentang Kementerian Agama (Lembaran Negara Republik Indonesia Tahun 2023 Nomor 21);", 0, $fontstyle, $style);
            $section3->addListItem("Peraturan Menteri Pendayagunaan Aparatur Negara Nomor PER/18/M.PAN/II/2003 tentang Pedoman Organisasi Unit Pelaksana Teknis Kementerian dan Lembaga Pemerintahan Nonkementerian;", 0, $fontstyle, $style);
            $section3->addListItem("Peraturan Menteri Agama Nomor 59 Tahun 2015 tentang Organisasi Tata Kerja Balai Diklat dan Pelatihan Keagamaan;", 0, $fontstyle, $style);
            $section3->addListItem("Peraturan Menteri Agama RI Nomor  42 Tahun 2016 tentang Organisasi dan Tata Kerja Kementerian Agama;", 0, $fontstyle, $style);
            $section3->addListItem("Peraturan Menteri Agama Nomor 15 Tahun 2021 tentang Organisasi Dan Tata Kerja Unit Pelaksana Teknis Pendidikan Dan Pelatihan Keagamaan;", 0, $fontstyle, $style);
            $section3->addListItem("DIPA Loka Diklat Keagamaan Pekanbaru Tahun 2024 Nomor: DIPA 025.11.2. 690527/2022, tanggal 30 November 2023.", 0, $fontstyle, $style);

            $section3->addTitle("C. Nama Pelatihan", 2);
            $section3->addText("Sesuai dengan kurikulum yang berlaku pelatihan ini bernama $pelatihan->nama_kegiatan di $pelatihan->tempat.", $fontstyle, $paragraphstyle);
            
            $section3->addTitle("D. Tujuan Pelatihan", 2);
            $section3->addText("Pelatihan ini bertujuan:", $fontstyle, $paragraphstyle);

            $style = generate_list_style($phpword, 'decimal');

            if (!empty($pelatihan->materi)) {
                foreach ($pelatihan->materi as $materi) {
                    if (!empty($materi->parsed_tujuan)) {
                        foreach ($materi->parsed_tujuan as $item) {
                            $section3->addListItem($item['judul'], 0, $fontstyle, $style, ['indentation' => ['left' => 720, 'hanging' => 360]]);
                            if (!empty($item['deskripsi'])) {
                                $section3->addText($item['deskripsi'], $fontstyle, $paragraphstyle2);
                            }
                        }
                    }
                }
            }

            // $section3->addListItem("Peningkatan Pemahaman Tentang Zakat", 0, $fontstyle, $style);
            // $section3->addText("Memahami konsep dasar zakat, jenis-jenis zakat (zakat mal dan zakat fitrah), dan dalil-dalil syariah yang mendasarinya.", $fontstyle, $paragraphstyle);
            // $section3->addListItem("Pengelolaan Dana Zakat yang Efektif", 0, $fontstyle, $style, ['indentation' => ['left' => 720, 'hanging' => 360]]);
            // $section3->addText("Membekali peserta dengan keterampilan teknis untuk mengelola dana zakat, mulai dari pengumpulan, pendistribusian, hingga pelaporan.", $fontstyle, $paragraphstyle2);
            // $section3->addListItem("Peningkatan Kapasitas Lembaga Pengelola Zakat (LPZ)", 0, $fontstyle, $style, ['indentation' => ['left' => 720, 'hanging' => 360]]);
            // $section3->addText("Memperkuat manajemen lembaga zakat agar lebih profesional, transparan, dan akuntabel.", $fontstyle, $paragraphstyle2);
            // $section3->addListItem("Strategi Pengumpulan Zakat", 0, $fontstyle, $style, ['indentation' => ['left' => 720, 'hanging' => 360]]);
            // $section3->addText("Mengajarkan teknik penggalangan dana zakat yang inovatif dan berbasis teknologi untuk menjangkau lebih banyak muzakki (pemberi zakat).", $fontstyle, $paragraphstyle2);
            // $section3->addListItem("Distribusi Zakat yang Tepat Sasaran", 0, $fontstyle, $style, ['indentation' => ['left' => 720, 'hanging' => 360]]);
            // $section3->addText("Memberikan wawasan mengenai metode pendistribusian zakat yang adil, tepat sasaran, dan berdampak besar bagi mustahik (penerima zakat).", $fontstyle, $paragraphstyle2);
            // $section3->addListItem("Peningkatan Kesejahteraan Umat", 0, $fontstyle, $style, ['indentation' => ['left' => 720, 'hanging' => 360]]);
            // $section3->addText("Menanamkan nilai-nilai bahwa zakat adalah instrumen pemberdayaan ekonomi umat, bukan sekadar kewajiban agama.", $fontstyle, $paragraphstyle2);
            // $section3->addListItem("Kepatuhan Syariah", 0, $fontstyle, $style, ['indentation' => ['left' => 720, 'hanging' => 360]]);
            // $section3->addText("Menjamin bahwa pengelolaan zakat dilakukan sesuai dengan kaidah syariah dan hukum positif yang berlaku, seperti Undang-Undang Pengelolaan Zakat.", $fontstyle, $paragraphstyle2);

            $section3->addTitle("E. Peserta Pelatihan", 2);
            $section3->addText("Peserta pelatihan ini berjumlah $pelatihan->jumlah_peserta terdiri dari $pelatihan->jumlah_peserta yang belum pernah mengikuti pelatihan sejenis dan diberi tugas oleh pejabat berwenang untuk mengikuti pelatihan tersebut.", $fontstyle, $paragraphstyle);
            
            $section3->addTitle("F. Pelaksanaan Kegiatan", 2);
            $section3->addText("Pelaksanaan kegiatan ini dimulai dari tahap persiapan pelatihan, tahap pelaksanaan pelatihan dan tahap purna pelatihan.", $fontstyle, $paragraphstyle);
            $section3->addText("Tahap Persiapan Pelatihan", $fontstyle, $paragraphstyle);
            $section3->addText("Persiapan pelatihan dimulai dengan mengadakan rapat persiapan untuk membahas kegiatan pembelajaran, sarana prasarana pendukung dan dukungan teknologi yang digunakan.", $fontstyle, $paragraphstyle);
            $section3->addText("Mempersiapkan jadwal pelatihan dan mempersiapkan dokumen pendukung berupa keputusan penyelenggaran pelatihan dan panduan penyelenggaraan pelatihan.", $fontstyle, $paragraphstyle);
            $section3->addText("Persiapan dan pengajuan pelatihan pada Learning Management System (LMS).", $fontstyle, $paragraphstyle);
            $section3->addText("Menyampaikan pemberitahuan dan pemanggilan peserta melalui penanggung jawab atau PIC masing-masing wilayah.", $fontstyle, $paragraphstyle);
            $section3->addText("Pendaftaran calon peserta : peserta mendaftarkan diri melalui link pendaftaran dan melengkapi syarat pendaftaran. Peserta dibuktikan melalui Surat Tugas dari unit asal peserta.", $fontstyle, $paragraphstyle);
            $section3->addText("Peserta yang memenuhi syarat dimasukkan dalam grup melalui media sosial berdasarkan pelatihan yang didaftarkan.", $fontstyle, $paragraphstyle);

            $section3->addTitle("G. Tahap Pelaksanaan Pelatihan", 2);
            $section3->addText("Peserta diberikan username dan password Learning Management System (LMS).", $fontstyle, $paragraphstyle);
            $section3->addText("Pelaksanaan pelatihan dilaksanakan melalui pembelajaran tatap muka synchronous (video conference, live chat, live diskusi dan kuis) dan asynchronous (belajar mandiri).", $fontstyle, $paragraphstyle);
            $section3->addText("Kegiatan pelatihan meliputi Pembukaan, Membangun Komitmen Belajar (Building Learning Commitment (BLC)), pelaksanaan pembelajaran synchronous dan asynchronous, pengembalian peserta yang tidak berhak melanjutkan pelatihan dan Penutupan.", $fontstyle, $paragraphstyle);
            $section3->addText("Tahap Purna Pelatihan", $fontstyle, $paragraphstyle);
            $section3->addText("Penyampaian Surat Pengembalian Peserta yang telah selesai melakukan kegiatan. Memberikan fasilitas bagi alumni pelatihan berupa media untuk berdiskusi.", $fontstyle, $paragraphstyle);

            // H. Tempat dan Waktu Pelaksanaan
            $section3->addTitle("H. Tempat dan Waktu Pelaksanaan", 2);
            $section3->addText(
                "Pelatihan ini dilaksanakan melalui online (daring) pada " . $data['tanggal_mulai'] . " s.d " . $data['tanggal_selesai'] . " dengan jumlah $materi->jumlah_jp Jam Pelajaran(JP).",
                $fontstyle,
                $paragraphstyle
            );

            $section3->addTitle("I. Panitia dan Tenaga Pengajar", 2);
            $section3->addText(
                "Penyelenggara pelatihan ini adalah Loka Pendidikan dan Pelatihan Keagamaan Pekanbaru , dengan susunan panitia sebagai berikut : ",
                $fontstyle,
                $paragraphstyle
            );

            // Panitia (tetap sama)
            if (isset($pelatihan->penanggung_jawab)) {
                $section3->addText("Penanggung Jawab\t: {$pelatihan->penanggung_jawab->nama}", $fontstyle, $paragraphstyle);
            }
            if (isset($pelatihan->nama_ketua_panitia)) {
                $section3->addText("Ketua Panitia\t: {$pelatihan->nama_ketua_panitia}", $fontstyle, $paragraphstyle);
            }
            if (isset($pelatihan->nama_akademis)) {
                $section3->addText("Bidang Akademis\t: {$pelatihan->nama_akademis}", $fontstyle, $paragraphstyle);
            }
            if (isset($pelatihan->nama_administrasi)) {
                $section3->addText("Bidang Administrasi\t: {$pelatihan->nama_administrasi}", $fontstyle, $paragraphstyle);
            }
            if (isset($pelatihan->nama_keuangan)) {
                $section3->addText("Bidang Keuangan\t: {$pelatihan->nama_keuangan}", $fontstyle, $paragraphstyle);
            }

            // === Tenaga Pengajar dari tabel baru ===
            $styleDec = generate_list_style($phpword, 'decimal');

            // flag PJJ/PDWK spt sebelumnya
            $isNonLatsar = in_array((int)($pelatihan->id_jenis_pelatihan ?? 0), [1, 2], true);
            $wiList       = ($isNonLatsar && isset($pelatihan->wi_list)       && is_array($pelatihan->wi_list))       ? $pelatihan->wi_list       : [];
            $pengajarList = ($isNonLatsar && isset($pelatihan->pengajar_list) && is_array($pelatihan->pengajar_list)) ? $pelatihan->pengajar_list : [];
            $wiRapat      = ($isNonLatsar && isset($pelatihan->wi_rapat)) ? $pelatihan->wi_rapat : null;

            if ($isNonLatsar && (!empty($wiList) || !empty($pengajarList) || $wiRapat)) {
                // Widyaiswara (baru)
                if (!empty($wiList) || $wiRapat) {
                    $section3->addText("Widyaiswara\t:", $fontstyle, $paragraphstyle);
                    $n = 1;
                    foreach ($wiList as $o) {
                        $nm = isset($o->nama) ? $o->nama : '-';
                        $section3->addText("\t{$n}. {$nm}", $fontstyle, $paragraphstyle);
                        $n++;
                    }
                    if ($wiRapat) {
                        $nm = isset($wiRapat->nama) ? $wiRapat->nama : '-';
                        $section3->addText("\t{$n}. {$nm} (Rapat Kelulusan)", $fontstyle, $paragraphstyle);
                    }
                }

                // Tenaga Pengajar (baru)
                if (!empty($pengajarList)) {
                    $section3->addText("Tenaga Pengajar\t:", $fontstyle, $paragraphstyle);
                    $n = 1;
                    foreach ($pengajarList as $o) {
                        $nm = isset($o->nama) ? $o->nama : '-';
                        $section3->addText("\t{$n}. {$nm}", $fontstyle, $paragraphstyle);
                        $n++;
                    }
                }

                // Total (isi angka yang sebelumnya kosong)
                $totalTeachers = count($wiList) + count($pengajarList) + ($wiRapat ? 1 : 0);
                $section3->addText(
                    "Widyaiswara/Tenaga pengajar dalam pelatihan ini berjumlah {$totalTeachers} orang dengan persyaratan:",
                    $fontstyle,
                    $paragraphstyle
                );

            } else {
                // === Fallback: skema lama (untuk Latsar atau data lama) ===
                if (isset($pelatihan->wi_1)) {
                    $section3->addText("Widyaiswara\t: 1. {$pelatihan->wi_1->nama}", $fontstyle, $paragraphstyle);
                }
                if (isset($pelatihan->wi_2)) {
                    $section3->addText("\t: 2. {$pelatihan->wi_2->nama}", $fontstyle, $paragraphstyle);
                }
                if (isset($pelatihan->wi_3)) {
                    $section3->addText("\t: 3. {$pelatihan->wi_3->nama}", $fontstyle, $paragraphstyle);
                }

                if (isset($pelatihan->pengajar_1)) {
                    $section3->addText("Tenaga Pengajar\t: 1. {$pelatihan->pengajar_1->nama}", $fontstyle, $paragraphstyle);
                }
                if (isset($pelatihan->pengajar_2)) {
                    $section3->addText("\t: 2. {$pelatihan->pengajar_2->nama}", $fontstyle, $paragraphstyle);
                }
                if (isset($pelatihan->pengajar_3)) {
                    $section3->addText("\t: 3. {$pelatihan->pengajar_3->nama}", $fontstyle, $paragraphstyle);
                }

                $fallbackTotal = isset($pelatihan->jumlah_wi_pengajar) ? (int)$pelatihan->jumlah_wi_pengajar : 0;
                $section3->addText(
                    "Widyaiswara/Tenaga pengajar dalam pelatihan ini berjumlah {$fallbackTotal} orang dengan persyaratan:",
                    $fontstyle,
                    $paragraphstyle
                );
            }

            $style = generate_list_style($phpword, 'decimal');

            $section3->addListItem("Widyaiswara;", 0, [], $style, ['indentation' => ['left' => 720, 'hanging' => 360]]);
            $section3->addListItem("Pejabat Struktural Loka Pendidikan dan Pelatihan Keagamaan Pekanbaru;", 0, [], $style, ['indentation' => ['left' => 720, 'hanging' => 360]]);
            $section3->addListItem("Pejabat Struktural dari Kementerian Agama;", 0, [], $style, ['indentation' => ['left' => 720, 'hanging' => 360]]);
            $section3->addListItem("Mampu mengisi waktu yang diberikan oleh panitia dengan kegiatan pembelajaran dengan materi yang sesuai ditetapkan dalam jadwal;", 0, [], $style, ['indentation' => ['left' => 720, 'hanging' => 360]]);
            $section3->addListItem("Mempersiapkan bahan yang dibutuhkan untuk setiap rincian kegiatan yang dipersyaratkan panitia;", 0, [], $style, ['indentation' => ['left' => 720, 'hanging' => 360]]);

            $section3->addTitle("J. Materi Pelatihan", 2);
            $section3->addText("Materi pelatihan ini terlampir sesuai dengan Kurikulum dan Silabus Pelatihan.", $fontstyle, $paragraphstyle);
            
            $section3->addTitle("K. Jadwal Tentatif", 2);
            $section3->addText("Jadwal tentatif pelatihan ini terlampir.", $fontstyle, $paragraphstyle);
            
            $section3->addTitle("L. Pembiayaan", 2);
            $section3->addText("Pembiayaan Pelatihan ini dibebankan kepada DIPA Loka Diklat Keagamaan Pekanbaru Tahun 2024 Nomor: DIPA 025.11.2. 690527/2022, tanggal 30 November 2023.", $fontstyle, $paragraphstyle);
            
            $section3->addTitle("M. Penutup", 2);
            $section3->addText("Demikian Term of reference (TOR) ini dibuat dengan harapan dapat menjadi pedoman pelaksanaan pelatihan ini.", $fontstyle, $paragraphstyle);

            $section3->addText("Pekanbaru, $pelatihan->bulan_ttd_lap $pelatihan->tahun,", $fontstyle, ['alignment' => 'both', 'indentation' => ['left' => Converter::cmToTwip(9.75)]]);
            $section3->addText('Diterima Kepala LDK Pekanbaru', array_merge($fontstyle, ['bold' => true]), ['alignment' => 'both', 'indentation' => ['left' => Converter::cmToTwip(9.75)]]); 
            $section3->addTextBreak(3);
            $section3->addText("H. Aprianto, S.Ag., M.A.", array_merge($fontstyle, ['bold' => true]), ['alignment' => 'both', 'indentation' => ['left' => Converter::cmToTwip(9.75)]]);
            $section3->addText("NIP. 197603012003121004", array_merge($fontstyle, ['bold' => true]), ['alignment' => 'both', 'indentation' => ['left' => Converter::cmToTwip(9.75)]]);

            //Berita Acara
            $section3->addPageBreak();
            $section3->addText("BERITA ACARA", ['bold' => true], ['alignment' => Jc::CENTER]);
            $section3->addText("Evaluasi Kelulusan Peserta", ['bold' => true], ['alignment' => Jc::CENTER]);
            $section3->addText("Pelatihan $pelatihan->nama_pelatihan", ['bold' => true], ['alignment' => Jc::CENTER]);
            $section3->addText("Loka Pendidikan dan Pelatihan Keagamaan Pekanbaru", ['bold' => true], ['alignment' => Jc::CENTER]);
            $section3->addText("Tahun 2024", ['bold' => true], ['alignment' => Jc::CENTER]);

            $section3->addText("Pada hari ini " . $data['tanggal_selesai'] . " pukul 13.30 s.d 14.00 WIB bertempat di $pelatihan->tempat serta Widyaiswara secara dalam jaringan telah diadakan Rapat Evaluasi Kelulusan Peserta $pelatihan->nama_pelatihan yang diselenggarakan dari tanggal  " . $data['tanggal_mulai'] . " s.d " . $data['tanggal_selesai'] . ".", $fontstyle, $paragraphstyle);
            $section3->addText("Berdasarkan Rapat Evaluasi Kelulusan maka hasil akhir nilai peserta pelatihan adalah dari 30 orang, peserta dinyatakan lulus sebanyak 22 orang dan tidak lulus 8 orang dengan rekapitulasi kualifikasi nilai peserta sebagaimana terlampir.", $fontstyle, $paragraphstyle);
            $section3->addText("Demikian berita acara ini dibuat sebagaimana mestinya.", $fontstyle, $paragraphstyle);

            $section3->addText("Pekanbaru, $pelatihan->bulan_ttd_lap $pelatihan->tahun,", $fontstyle, ['alignment' => 'both', 'indentation' => ['left' => Converter::cmToTwip(9.75)]]);
            $section3->addText('Diterima Kepala LDK Pekanbaru', array_merge($fontstyle, ['bold' => true]), ['alignment' => 'both', 'indentation' => ['left' => Converter::cmToTwip(9.75)]]); 
            $section3->addTextBreak(3);
            $section3->addText("{$pelatihan->nama_ketua_panitia}", array_merge($fontstyle, ['bold' => true]), ['alignment' => 'both', 'indentation' => ['left' => Converter::cmToTwip(9.75)]]);
            $section3->addText("NIP. {$pelatihan->nip_ketua_panitia}", array_merge($fontstyle, ['bold' => true]), ['alignment' => 'both', 'indentation' => ['left' => Converter::cmToTwip(9.75)]]);

            //Daftar Hadir
            // $section3->addPageBreak();
            // $section3->addText("DAFTAR HADIR RAPAT EVALUASI KELULUSAN PESERTA", ['bold' => true], ['alignment' => Jc::CENTER]);
            // $section3->addText("Evaluasi Kelulusan Peserta", ['bold' => true], ['alignment' => Jc::CENTER]);
            // $section3->addText("Pelatihan Manajemen Zakat Angkatan I", ['bold' => true], ['alignment' => Jc::CENTER]);
            // $section3->addText("Loka Pendidikan dan Pelatihan Keagamaan Pekanbaru", ['bold' => true], ['alignment' => Jc::CENTER]);
            // $section3->addText("Tahun 2024", ['bold' => true], ['alignment' => Jc::CENTER]);

            // $table = $section3->addTable([
            //     'borderSize' => 6,
            //     'borderColor' => '000000',
            //     'cellMargin' => 50,
            // ]);

            //Header
            // $table->addRow();
            // $table->addCell(500)->addText('NO.', ['bold' => true], ['alignment' => Jc::CENTER]);
            // $table->addCell(2500)->addText('NAMA', ['bold' => true], ['alignment' => Jc::CENTER]);
            // $table->addCell(2500)->addText('JABATAN', ['bold' => true], ['alignment' => Jc::CENTER]);
            // $cell = $table->addCell(4000);
            // $cell->getStyle()->setGridSpan(2);
            // $cell->addText('TANDA TANGAN', ['bold' => true], ['alignment' => Jc::CENTER]);

            //Baris 2
            // $table->addRow();
            // $table->addCell(500)->addText('1');
            // $table->addCell(2500)->addText('Eko Oktaviadi, SH');
            // $table->addCell(2500)->addText('Ketua Panitia');
            // $table->addCell(2000, ['borderRightSize' => 0, 'borderRightColor' => 'FFFFFF'])->addText('1......');
            // $table->addCell(2000)->addText('');

            // $section3->addText('Pekanbaru, $ September 2024,', $fontstyle, ['alignment' => 'both', 'indentation' => ['left' => Converter::cmToTwip(9.75)]]);
            // $section3->addText('Panitia Penyelenggara', array_merge($fontstyle, ['bold' => true]), ['alignment' => 'both', 'indentation' => ['left' => Converter::cmToTwip(9.75)]]); 
            // $section3->addText('Ketua,', array_merge($fontstyle, ['bold' => true]), ['alignment' => 'both', 'indentation' => ['left' => Converter::cmToTwip(9.75)]]); 
            // $section3->addTextBreak(3);
            // $section3->addText('H. Aprianto, S.Ag., M.A.', array_merge($fontstyle, ['bold' => true]), ['alignment' => 'both', 'indentation' => ['left' => Converter::cmToTwip(9.75)]]);
            // $section3->addText('NIP. 196702161994031005', array_merge($fontstyle, ['bold' => true]), ['alignment' => 'both', 'indentation' => ['left' => Converter::cmToTwip(9.75)]]);

            
            //$this->generateListSection($phpword, $section, $lists);
            
            // --- Simpan file ---
            $filename = 'Laporan_Pelatihan_' . date('Ymd_His') . '.docx';
            $outputPath = FCPATH . 'downloads/' . $filename;

            if (!is_dir(FCPATH . 'downloads/')) {
                throw new \Exception('Folder downloads/ tidak ditemukan.');
            }
            if (!is_writable(FCPATH . 'downloads/')) {
                throw new \Exception('Folder downloads/ tidak memiliki izin tulis.');
            }

            try {
                $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpword, 'Word2007');
                $objWriter->save($outputPath);
            } catch (\Throwable $e) {
                throw new \Exception('Gagal menyimpan file Word: ' . $e->getMessage());
            }

            if (!file_exists($outputPath)) {
                throw new \Exception('File tidak berhasil dibuat di: ' . $outputPath);
            }

            return basename($outputPath);
        } catch (\Throwable $e) {
            // Lempar error agar bisa ditangkap oleh controller (seperti generateLaporan)
            throw new \Exception('Gagal generate laporan: ' . $e->getMessage());
        }
    }
}
?>