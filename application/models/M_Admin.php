<?php if(! defined('BASEPATH')) exit('No direct script acess allowed');

class M_Admin extends CI_Model
{
  function __construct()
  {
	 parent::__construct();
	 //validasi jika user belum login
	 }

  //  Get Data Pelatihan
  //  function dataPelatihan($id_pelatihan){
  //   // Di model M_Admin->dataPelatihan(), ganti dengan:
  //   $this->db->select('
  //       p.*,
  //       d.id_detail_pelatihan,
  //       d.id_penanggung_jawab,
  //       d.id_ketua_panitia,
  //       d.id_akademis,
  //       d.id_keuangan,
  //       d.id_administrasi,
  //       d.id_wi_1,
  //       d.id_wi_2,
  //       d.id_wi_3,
  //       d.id_wi_rapat_kelulusan,
  //       d.id_wi_4,
  //       d.id_pengajar_1,
  //       d.id_pengajar_2,
  //       d.id_pengajar_3,
  //       d.jumlah_wi_pengajar,
  //       d.jumlah_pendidikan_wi_s1,
  //       d.jumlah_pendidikan_wi_s2,
  //       d.jumlah_pendidikan_wi_s3,
  //       d.jumlah_peserta,
  //       d.jumlah_lulus,
  //       d.jumlah_tidak_lulus,
  //       d.jabatan_peserta,
  //       d.jumlah_peserta_asn,
  //       d.jumlah_peserta_non_asn,
  //       d.jumlah_peserta_laki,
  //       d.jumlah_peserta_wanita,
  //       d.jumlah_pendidikan_peserta_sma,
  //       d.jumlah_pendidikan_wi_d2,
  //       d.jumlah_pendidikan_peserta_d3,
  //       d.jumlah_pendidikan_peserta_s1,
  //       d.jumlah_pendidikan_peserta_s2,
  //       d.jumlah_pendidikan_peserta_s3,
  //       d.rab,
  //       d.realisasi,
  //       j.nama_jenis_pelatihan,
  //       pembuka.nama as nama_pejabat_pembuka,
  //       pembuka.NIP as nip_pejabat_pembuka,
  //       pembuka.jabatan as jabatan_pejabat_pembuka,
  //       pembuka.asal_satker as satker_pejabat_pembuka,
  //       penutup.nama as nama_pejabat_penutup,
  //       penutup.NIP as nip_pejabat_penutup,
  //       penutup.jabatan as jabatan_pejabat_penutup,
  //       penutup.asal_satker as satker_pejabat_penutup
  //   ');

  //   $this->db->from('tbl_pelatihan p');
  //   $this->db->join('tbl_detail_pelatihan d', 'p.id_pelatihan = d.id_pelatihan', 'left');
  //   $this->db->join('tbl_jenis_pelatihan j', 'p.id_jenis_pelatihan = j.id_jenis_pelatihan', 'left');
  //   $this->db->join('tbl_pegawai pembuka', 'p.id_pejabat_pembuka = pembuka.id_pegawai', 'left');
  //   $this->db->join('tbl_pegawai penutup', 'p.id_pejabat_penutup = penutup.id_pegawai', 'left');
  //   $this->db->where('p.id_pelatihan', $id_pelatihan);

  //   $pelatihan = $this->db->get()->row();

  //   // $pelatihan->materi->tujuan_parsed = $this->parseTujuanKursil($pelatihan->materi->tujuan_kursil ?? '');

  //   if(!$pelatihan) return null;

  //   $pegawai_ids = [];
  //   $pegawai_fields = [
  //     'id_pejabat_pembuka',
  //     'id_pejabat_penutup',
  //     'id_penanggung_jawab',
  //     'id_ketua_panitia',
  //     'id_akademis',
  //     'id_keuangan',
  //     'id_administrasi',
  //     'id_wi_1',
  //     'id_wi_2',
  //     'id_wi_3',
  //     'id_wi_4',
  //     'id_wi_rapat_kelulusan',
  //     'id_pengajar_1',
  //     'id_pengajar_2',
  //     'id_pengajar_3'
  //   ];

  //   foreach ($pegawai_fields as $field){
  //     if (!empty($pelatihan->{$field})) {
  //       $pegawai_ids[] = $pelatihan->{$field};
  //     }
  //   }

  //   if(!empty($pegawai_ids)) {
  //     $this->db->select('p.*, r.nama_role as jabatan');
  //     $this->db->from('tbl_pegawai p');
  //     $this->db->join('tbl_role r', 'p.jabatan = r.id_role', 'left');
  //     $this->db->where_in('p.id_pegawai', array_unique($pegawai_ids));
  //     $pegawai_data = $this->db->get()->result();

  //     $pegawai_map = array_column($pegawai_data, null, 'id_pegawai');

  //     foreach ($pegawai_fields as $field) {
  //       $key = str_replace('id_', '', $field);
  //       if (!empty($pelatihan->{$field}) && isset($pegawai_map[$pelatihan->{$field}])) {
  //         $pelatihan->{$key} = $pegawai_map[$pelatihan->{$field}];
  //       }
  //     }

  //     //Ambil materi pelatihan
  //    $materi = $this->db->get_where('tbl_materi_pelatihan', ['id_pelatihan' => $id_pelatihan])->result();

  //    //Daftar materi
  //     $kolom_materi = [
  //         'nama_mata_pelatihan_kel_dasar',
  //         'nama_mata_pelatihan_kel_inti',
  //         'nama_mata_pelatihan_kel_penunjang'
  //     ];

  //    foreach ($materi as $m) {
  //     $m->tujuan_kursil_parsed = $this->parseTujuanKursil($m->tujuan_kursil ?? '');
  //     foreach ($kolom_materi as $kolom){
  //       $materi_parsed = str_replace('nama_mata_pelatihan_', '', $kolom) . '_parsed';
  //       $m->{$materi_parsed} = $this->_parse_materi($m->{$kolom} ?? '');
  //     }
  //    }

  //    $pelatihan->materi = $materi;

  //     return $pelatihan;
  //   }
  // }


public function dataPelatihan($id_pelatihan)
{
    // 1) Ambil baris induk + detail + pejabat pembuka/penutup + jenis
    $this->db->select('
        p.*,
        d.*,
        j.nama_jenis_pelatihan,

        pembuka.nama        AS nama_pejabat_pembuka,
        pembuka.NIP         AS nip_pejabat_pembuka,
        pembuka.jabatan     AS jabatan_pejabat_pembuka,
        pembuka.asal_satker AS satker_pejabat_pembuka,

        penutup.nama        AS nama_pejabat_penutup,
        penutup.NIP         AS nip_pejabat_penutup,
        penutup.jabatan     AS jabatan_pejabat_penutup,
        penutup.asal_satker AS satker_pejabat_penutup,

        wi1.nama AS nama_wi_1,
        wi2.nama AS nama_wi_2,
        wi3.nama AS nama_wi_3,
        wi4.nama AS nama_wi_4,
        wirapat.nama AS nama_wi_rapat_kelulusan,

        peng1.nama AS nama_pengajar_1,
        peng2.nama AS nama_pengajar_2,
        peng3.nama AS nama_pengajar_3,

        kp_user.nama AS nama_ketua_panitia,
        kp_user.nip  AS nip_ketua_panitia,
        kp_user.level AS jabatan_ketua_panitia,

        ak_user.nama AS nama_akademis,
        ak_user.nip  AS nip_akademis,
        ak_user.level AS jabatan_akademis,

        ke_user.nama AS nama_keuangan,
        ke_user.nip  AS nip_keuangan,
        ke_user.level AS jabatan_keuangan,

        ad_user.nama AS nama_administrasi,
        ad_user.nip  AS nip_administrasi,
        ad_user.level AS jabatan_administrasi,
        pic_user.nama AS nama_pic_smartbangkom,
        pic_user.nip  AS nip_pic_smartbangkom

    ');

    $this->db->from('tbl_pelatihan p');
    $this->db->join('tbl_detail_pelatihan d', 'p.id_pelatihan = d.id_pelatihan', 'left');
    $this->db->join('tbl_jenis_pelatihan j', 'p.id_jenis_pelatihan = j.id_jenis_pelatihan', 'left');
    $this->db->join('tbl_pegawai pembuka', 'p.id_pejabat_pembuka = pembuka.id_pegawai', 'left');
    $this->db->join('tbl_pegawai penutup', 'p.id_pejabat_penutup = penutup.id_pegawai', 'left');

    $this->db->join('tbl_pegawai wi1', 'wi1.id_pegawai = d.id_wi_1', 'left');
    $this->db->join('tbl_pegawai wi2', 'wi2.id_pegawai = d.id_wi_2', 'left');
    $this->db->join('tbl_pegawai wi3', 'wi3.id_pegawai = d.id_wi_3', 'left');
    $this->db->join('tbl_pegawai wi4', 'wi4.id_pegawai = d.id_wi_4', 'left');
    $this->db->join('tbl_pegawai wirapat', 'wirapat.id_pegawai = d.id_wi_rapat_kelulusan', 'left');

    $this->db->join('tbl_pegawai peng1', 'peng1.id_pegawai = d.id_pengajar_1', 'left');
    $this->db->join('tbl_pegawai peng2', 'peng2.id_pegawai = d.id_pengajar_2', 'left');
    $this->db->join('tbl_pegawai peng3', 'peng3.id_pegawai = d.id_pengajar_3', 'left');

    $this->db->join('tbl_panitia_pelatihan pp_ketua', 'pp_ketua.id = d.id_ketua_panitia', 'left');
    $this->db->join('tbl_login kp_user', 'kp_user.id_login = pp_ketua.panitia_id', 'left');

    $this->db->join('tbl_panitia_pelatihan pp_akademis', 'pp_akademis.id = d.id_akademis', 'left');
    $this->db->join('tbl_login ak_user', 'ak_user.id_login = pp_akademis.panitia_id', 'left');

    $this->db->join('tbl_panitia_pelatihan pp_keuangan', 'pp_keuangan.id = d.id_keuangan', 'left');
    $this->db->join('tbl_login ke_user', 'ke_user.id_login = pp_keuangan.panitia_id', 'left');

    $this->db->join('tbl_panitia_pelatihan pp_administrasi', 'pp_administrasi.id = d.id_administrasi', 'left');
    $this->db->join('tbl_login ad_user', 'ad_user.id_login = pp_administrasi.panitia_id', 'left');

    $this->db->join('tbl_panitia_pelatihan pp_pic', 'pp_pic.id = d.pic_smartbangkom', 'left');
    $this->db->join('tbl_login pic_user', 'pic_user.id_login = pp_pic.panitia_id', 'left');


    $this->db->where('p.id_pelatihan', (int)$id_pelatihan);   

    // $this->db->select('
    //     p.*,
    //     d.*,
    //     j.nama_jenis_pelatihan,

    //     d.id_detail_pelatihan,
    //     d.id_penanggung_jawab,
    //     d.id_ketua_panitia,
    //     d.id_akademis,
    //     d.id_keuangan,
    //     d.id_administrasi,
    //     d.id_wi_1,
    //     d.id_wi_2,
    //     d.id_wi_3,
    //     d.id_wi_rapat_kelulusan,
    //     d.id_wi_4,
    //     d.id_pengajar_1,
    //     d.id_pengajar_2,
    //     d.id_pengajar_3,
    //     d.jumlah_wi_pengajar,
    //     d.jumlah_pendidikan_wi_s1,
    //     d.jumlah_pendidikan_wi_s2,
    //     d.jumlah_pendidikan_wi_s3,
    //     d.jumlah_peserta,
    //     d.jumlah_lulus,
    //     d.jumlah_tidak_lulus,
    //     d.jabatan_peserta,
    //     d.jumlah_peserta_asn,
    //     d.jumlah_peserta_non_asn,
    //     d.jumlah_peserta_laki,
    //     d.jumlah_peserta_wanita,
    //     d.jumlah_pendidikan_peserta_sma,
    //     d.jumlah_pendidikan_wi_d2,
    //     d.jumlah_pendidikan_peserta_d3,
    //     d.jumlah_pendidikan_peserta_s1,
    //     d.jumlah_pendidikan_peserta_s2,
    //     d.jumlah_pendidikan_peserta_s3,
    //     d.rab,
    //     d.realisasi,
    //     d.pic_smartbangkom,
    //     d.jml_peserta_nilai_sm,
    //     d.jml_peserta_nilai_m,
    //     d.jml_peserta_nilai_cm,
    //     d.jml_peserta_nilai_dl,
    //     d.jml_peserta_tm,
    //     d.peserta_peringkat_1,
    //     d.peserta_peringkat_2,
    //     d.peserta_peringkat_3,

    //     j.nama_jenis_pelatihan,

    //     pembuka.nama         AS nama_pejabat_pembuka,
    //     pembuka.NIP          AS nip_pejabat_pembuka,
    //     pembuka.jabatan      AS jabatan_pejabat_pembuka,
    //     pembuka.asal_satker  AS satker_pejabat_pembuka,

    //     penutup.nama         AS nama_pejabat_penutup,
    //     penutup.NIP          AS nip_pejabat_penutup,
    //     penutup.jabatan      AS jabatan_pejabat_penutup,
    //     penutup.asal_satker  AS satker_pejabat_penutup
    // ');
    // $this->db->from('tbl_pelatihan p');
    // $this->db->join('tbl_detail_pelatihan d', 'p.id_pelatihan = d.id_pelatihan', 'left');
    // $this->db->join('tbl_jenis_pelatihan j', 'p.id_jenis_pelatihan = j.id_jenis_pelatihan', 'left');
    // $this->db->join('tbl_pegawai pembuka', 'p.id_pejabat_pembuka = pembuka.id_pegawai', 'left');
    // $this->db->join('tbl_pegawai penutup', 'p.id_pejabat_penutup = penutup.id_pegawai', 'left');
    // $this->db->where('p.id_pelatihan', (int)$id_pelatihan);

    $pelatihan = $this->db->get()->row();
    if (!$pelatihan) return null;

    // Pastikan tipe numerik aman untuk percabangan
    $pelatihan->id_jenis_pelatihan = (int)$pelatihan->id_jenis_pelatihan;

    // 2) PETAKAN pegawai untuk semua field id_pegawai (termasuk pic_smartbangkom)
    $pegawai_fields = [
        'id_pejabat_pembuka','id_pejabat_penutup',
        'id_penanggung_jawab',
        'id_wi_1','id_wi_2','id_wi_3','id_wi_4','id_wi_rapat_kelulusan',
        'id_pengajar_1','id_pengajar_2','id_pengajar_3',
        'pic_smartbangkom'
    ];
    $pegawai_ids = [];
    foreach ($pegawai_fields as $f) if (!empty($pelatihan->{$f})) $pegawai_ids[] = (int)$pelatihan->{$f};
    $pegawai_map = [];
    if ($pegawai_ids) {
        $this->db->select('p.*, r.nama_role as jabatan');
        $this->db->from('tbl_pegawai p');
        $this->db->join('tbl_role r', 'p.jabatan = r.id_role', 'left');
        $this->db->where_in('p.id_pegawai', array_unique($pegawai_ids));
        $pegawai_data = $this->db->get()->result();
        $pegawai_map  = array_column($pegawai_data, null, 'id_pegawai');

        foreach ($pegawai_fields as $f) {
            $key = str_replace('id_', '', $f); // id_ketua_panitia -> ketua_panitia; pic_smartbangkom -> pic_smartbangkom
            if (!empty($pelatihan->{$f}) && isset($pegawai_map[$pelatihan->{$f}])) {
                $pelatihan->{$key} = $pegawai_map[$pelatihan->{$f}];
            }
        }
    }

    // --- NEW: attach WI/Pengajar for non-Latsar from tbl_pelatihan_pengajar
    if (in_array((int)$pelatihan->id_jenis_pelatihan, [1,2], true)) { // 1=PJJ, 2=PDWK
        $asgn = $this->_get_pengajar_assignments((int)$pelatihan->id_pelatihan);

        // full objects (id, nama, NIP, asal_satker, jabatan)
        $pelatihan->wi_list       = $asgn['wi_list'];
        $pelatihan->pengajar_list = $asgn['pengajar_list'];
        $pelatihan->wi_rapat      = $asgn['wi_rapat'];

        // simple name arrays for views
        $pelatihan->wi_names        = $asgn['wi_names'];
        $pelatihan->pengajar_names  = $asgn['pengajar_names'];
        $pelatihan->wi_rapat_name   = $asgn['wi_rapat_name'];

        // computed headcount (read-only)
        $pelatihan->jumlah_wi_pengajar_auto =
            count($asgn['wi_list']) + count($asgn['pengajar_list']) + ($asgn['wi_rapat'] ? 1 : 0);
    }


    // 3) MATERI (tetap seperti sebelumnya)
    $materi = $this->db->get_where('tbl_materi_pelatihan', ['id_pelatihan' => (int)$id_pelatihan])->result();
    $kolom_materi = [
        'nama_mata_pelatihan_kel_dasar',
        'nama_mata_pelatihan_kel_inti',
        'nama_mata_pelatihan_kel_penunjang'
    ];
    foreach ($materi as $m) {
        $m->tujuan_kursil_parsed = $this->parseTujuanKursil($m->tujuan_kursil ?? '');
        foreach ($kolom_materi as $k) {
            $parsed_key = str_replace('nama_mata_pelatihan_', '', $k) . '_parsed';
            $m->{$parsed_key} = $this->_parse_materi($m->{$k} ?? '');
        }
    }
    $pelatihan->materi = $materi;

    // 4) PESERTA (tbl_peserta) + buat peta untuk resolusi peringkat
    $this->db->select('
        id_peserta,
        nama_peserta  AS nama,
        nip,
        pangkatgol    AS golru,
        jabatan,
        unit_kerja
    ');
    $this->db->from('tbl_peserta_pelatihan');
    $this->db->where('id_pelatihan', (int)$id_pelatihan);
    // $this->db->where('deleted_at IS NULL', null, false);
    $this->db->order_by('nama_peserta', 'asc');
    $peserta = $this->db->get()->result();
    $pelatihan->peserta = $peserta;

    $peserta_map = [];
    if ($peserta) {
        foreach ($peserta as $ps) {
            $peserta_map[(int)$ps->id_peserta] = $ps;
        }
    }

    // 5) Peringkat 1-3 (resolve ke objek peserta)
    $pelatihan->peringkat_1 = !empty($pelatihan->peserta_peringkat_1) && isset($peserta_map[$pelatihan->peserta_peringkat_1])
        ? $peserta_map[$pelatihan->peserta_peringkat_1] : null;
    $pelatihan->peringkat_2 = !empty($pelatihan->peserta_peringkat_2) && isset($peserta_map[$pelatihan->peserta_peringkat_2])
        ? $peserta_map[$pelatihan->peserta_peringkat_2] : null;
    $pelatihan->peringkat_3 = !empty($pelatihan->peserta_peringkat_3) && isset($peserta_map[$pelatihan->peserta_peringkat_3])
        ? $peserta_map[$pelatihan->peserta_peringkat_3] : null;


        // 6) AGENDA + TOPIK + GRUP (skema BARU: via tbl_pelatihan_agenda & tbl_grup_agenda)
/*
   Prinsip:
   - Ambil daftar agenda yang MEMANG di-assign ke pelatihan ini (tbl_pelatihan_agenda).
   - Ambil topik per agenda (untuk sum JP dan tampilan rinci).
   - Ambil grup pengajar via tbl_grup_agenda yang now berelasi ke pelatihan_agenda_id.
   - Ambil semua pengajar (main/grup) sekali query (join role utk label jabatan).
*/
$pa_rows = $this->db
    ->select('pa.pelatihan_agenda_id, pa.id_pelatihan, pa.agenda_id, pa.main_teacher_id, a.agenda_title')
    ->from('tbl_pelatihan_agenda pa')
    ->join('tbl_agenda a', 'a.agenda_id = pa.agenda_id', 'inner')
    ->where('pa.id_pelatihan', (int)$id_pelatihan)
    ->order_by('a.agenda_id', 'asc')
    ->get()->result();

$agenda_ids  = [];
$pa_ids      = [];
$teacher_ids = [];
foreach ($pa_rows as $r) {
    $agenda_ids[] = (int)$r->agenda_id;
    $pa_ids[]     = (int)$r->pelatihan_agenda_id;
    if (!empty($r->main_teacher_id)) $teacher_ids[] = (int)$r->main_teacher_id;
}
$agenda_ids = array_values(array_unique($agenda_ids));
$pa_ids     = array_values(array_unique($pa_ids));

// -- Topik per agenda + ringkasan JP
$topics_by_agenda = [];
$sum_by_agenda    = []; // [agenda_id] => ['sum_jp_async'=>X, 'sum_jp_sync'=>Y, 'total_topics'=>Z]
if (!empty($agenda_ids)) {
    $topik_rows = $this->db
        ->select('topic_id, agenda_id, topic_no, topic_title, jp_async, jp_sync')
        ->from('tbl_topik')
        ->where_in('agenda_id', $agenda_ids)
        ->order_by('agenda_id ASC, topic_no ASC')
        ->get()->result();
    foreach ($topik_rows as $t) {
        $aid = (int)$t->agenda_id;
        $topics_by_agenda[$aid][] = $t;
        if (!isset($sum_by_agenda[$aid])) {
            $sum_by_agenda[$aid] = ['sum_jp_async'=>0,'sum_jp_sync'=>0,'total_topics'=>0];
        }
        $sum_by_agenda[$aid]['sum_jp_async'] += (int)($t->jp_async ?? 0);
        $sum_by_agenda[$aid]['sum_jp_sync']  += (int)($t->jp_sync  ?? 0);
        $sum_by_agenda[$aid]['total_topics']++;
    }
}

// -- Grup per pelatihan_agenda (bukan lagi per agenda)
$groups_by_pa = []; // [pelatihan_agenda_id] => [ {agenda_group_id, group_no, teacher_id}, ... ]
if (!empty($pa_ids)) {
    $gr_rows = $this->db
        ->select('agenda_group_id, pelatihan_agenda_id, group_no, teacher_id')
        ->from('tbl_grup_agenda')
        ->where_in('pelatihan_agenda_id', $pa_ids)
        ->order_by('pelatihan_agenda_id ASC, group_no ASC')
        ->get()->result();
    foreach ($gr_rows as $g) {
        $groups_by_pa[(int)$g->pelatihan_agenda_id][] = $g;
        if (!empty($g->teacher_id)) $teacher_ids[] = (int)$g->teacher_id;
    }
}

// -- Peta data pengajar (once)
$teacher_map = [];
$teacher_ids = array_values(array_unique(array_filter($teacher_ids)));
if (!empty($teacher_ids)) {
    $trows = $this->db
        ->select('p.*, r.nama_role as jabatan')
        ->from('tbl_pegawai p')
        ->join('tbl_role r', 'p.jabatan = r.id_role', 'left')
        ->where_in('p.id_pegawai', $teacher_ids)
        ->get()->result();
    $teacher_map = array_column($trows, null, 'id_pegawai');
}

// sort grup per pelatihan_agenda_id tanpa closure
foreach ($groups_by_pa as $k => $arr) {
    if (!is_array($arr)) { $arr = [$arr]; }
    $ord = [];
    foreach ($arr as $i => $g) { $ord[$i] = (int)($g->group_no ?? 0); }
    array_multisort($ord, SORT_ASC, $arr);
    $groups_by_pa[$k] = array_values($arr);
}


// --- Rakit struktur final per agenda (unchanged) ---
$agenda_final = [];
foreach ($pa_rows as $row) {
    $aid   = (int)$row->agenda_id;
    $pa_id = (int)$row->pelatihan_agenda_id;

    $sumA = (int)($sum_by_agenda[$aid]['sum_jp_async'] ?? 0);
    $sumS = (int)($sum_by_agenda[$aid]['sum_jp_sync']  ?? 0);

    $obj = (object)[
        'agenda_id'     => $aid,
        'agenda_title'  => $row->agenda_title,
        'sum_jp_async'  => $sumA,
        'sum_jp_sync'   => $sumS,
        'total_topics'  => (int)($sum_by_agenda[$aid]['total_topics'] ?? 0),
        'main_teacher'  => (!empty($row->main_teacher_id) && isset($teacher_map[$row->main_teacher_id])) ? $teacher_map[$row->main_teacher_id] : null,
        'topik'         => $topics_by_agenda[$aid] ?? [],
        'grup'          => []
    ];

    if (!empty($groups_by_pa[$pa_id])) {
        foreach ($groups_by_pa[$pa_id] as $g) {
            $obj->grup[] = (object)[
                'agenda_group_id' => (int)$g->agenda_group_id,
                'group_no'        => (int)$g->group_no,
                'teacher'         => (!empty($g->teacher_id) && isset($teacher_map[$g->teacher_id])) ? $teacher_map[$g->teacher_id] : null,
            ];
        }
    }

    $agenda_final[] = $obj;
}
$pelatihan->agenda = $agenda_final;


// 7) Flatten opsional (tetap boleh dipakai bagian lain), namun TIDAK dipakai untuk tabel B
$tenaga_pengajar = [];
foreach ($pelatihan->agenda as $ag) {
    $rows = $ag->grup;
    if (empty($rows)) {
        $tenaga_pengajar[] = (object)[
            'agenda'        => $ag->agenda_title,
            'jp_async'      => (int)$ag->sum_jp_async,
            'jp_sync'       => (int)$ag->sum_jp_sync,
            'kel'           => null,
            'nama_pengajar' => $ag->main_teacher->nama ?? '-'
        ];
    } else {
        foreach ($rows as $gr) {
            $tenaga_pengajar[] = (object)[
                'agenda'        => $ag->agenda_title,
                'jp_async'      => (int)$ag->sum_jp_async,
                'jp_sync'       => (int)$ag->sum_jp_sync,
                'kel'           => (int)$gr->group_no,
                'nama_pengajar' => $gr->teacher->nama ?? ($ag->main_teacher->nama ?? '-'),
            ];
        }
    }
}
$pelatihan->tenaga_pengajar = $tenaga_pengajar;

    // Susun tim_penyelenggara dari pegawai yang sudah dipetakan
$pelatihan->ketua_panitia = (!empty($pelatihan->nama_ketua_panitia))
    ? (object)[
        'nama' => $pelatihan->nama_ketua_panitia,
        'NIP'  => $pelatihan->nip_ketua_panitia,
      ]
    : null;

$pelatihan->akademis = (!empty($pelatihan->nama_akademis))
    ? (object)[
        'nama' => $pelatihan->nama_akademis,
        'NIP'  => $pelatihan->nip_akademis,
      ]
    : null;

$pelatihan->administrasi = (!empty($pelatihan->nama_administrasi))
    ? (object)[
        'nama' => $pelatihan->nama_administrasi,
        'NIP'  => $pelatihan->nip_administrasi,
      ]
    : null;

    $pelatihan->keuangan = (!empty($pelatihan->nama_keuangan))
    ? (object)[
        'nama' => $pelatihan->nama_keuangan,
        'NIP'  => $pelatihan->nip_keuangan,
      ]
    : null;

$pelatihan->pic_smartbangkom = (!empty($pelatihan->nama_pic_smartbangkom))
? (object)[
    'nama' => $pelatihan->nama_pic_smartbangkom,
    'NIP'  => $pelatihan->nip_pic_smartbangkom,
    ]
: null;


$tim = [];
$slot_to_label = [
    'penanggung_jawab' => 'Penanggung Jawab',
    'ketua_panitia'    => 'Ketua Panitia',
    'akademis'         => 'Koordinator Akademis',
    'administrasi'     => 'Koordinator Administrasi',
    'keuangan'     => 'Koordinator Keuangan',
    'pic_smartbangkom'         => 'PIC Smartbangkom',
];

foreach ($slot_to_label as $slot => $label) {
    if (isset($pelatihan->{$slot}) && is_object($pelatihan->{$slot})) {
        $tim[] = (object)[
            'nama'    => $pelatihan->{$slot}->nama ?? '-',
            'nip'     => $pelatihan->{$slot}->NIP  ?? '-',
            'jabatan' => $label,
        ];
    }
}
$pelatihan->tim_penyelenggara = $tim;

// Default-kan agar aman dipakai di generator
if (!isset($pelatihan->peserta) || !is_array($pelatihan->peserta)) {
    $pelatihan->peserta = [];
}
if (!isset($pelatihan->tenaga_pengajar) || !is_array($pelatihan->tenaga_pengajar)) {
    $pelatihan->tenaga_pengajar = [];
}

// Total peserta riil (hanya yang belum di-soft delete)
$pelatihan->jumlah_peserta_riil = (int)$this->db
    ->where('id_pelatihan', (int)$id_pelatihan)
    ->count_all_results('tbl_peserta_pelatihan');


    return $pelatihan;
}


// --- NEW: collect WI/Pengajar mapping for PJJ/PDWK from tbl_pelatihan_pengajar
private function _get_pengajar_assignments($id_pelatihan)
{
    $rows = $this->db
        ->select('pp.id_pegawai, pp.tipe_peran, pg.nama, pg.NIP, pg.asal_satker, r.nama_role AS jabatan')
        ->from('tbl_pelatihan_pengajar pp')
        ->join('tbl_pegawai pg', 'pg.id_pegawai = pp.id_pegawai', 'left')
        ->join('tbl_role r', 'pg.jabatan = r.id_role', 'left')
        ->where('pp.id_pelatihan', (int)$id_pelatihan)
        ->where('pp.deleted_at IS NULL', null, false)
        // order: WI → WI Rapat → Pengajar, then by name
        ->order_by('FIELD(pp.tipe_peran,"Widyaiswara","Widyaiswara Rapat Kelulusan","Pengajar")', '', false)
        ->order_by('pg.nama', 'asc')
        ->get()->result();

    $wi_list = [];
    $pengajar_list = [];
    $wi_rapat = null;

    foreach ($rows as $r) {
        if ($r->tipe_peran === 'Widyaiswara') {
            $wi_list[] = $r;
        } elseif ($r->tipe_peran === 'Pengajar') {
            $pengajar_list[] = $r;
        } elseif ($r->tipe_peran === 'Widyaiswara Rapat Kelulusan' && !$wi_rapat) {
            $wi_rapat = $r;
        }
    }

    return [
        'wi_list'        => $wi_list,
        'pengajar_list'  => $pengajar_list,
        'wi_rapat'       => $wi_rapat,
        'wi_names'       => array_map(function($o){ return $o->nama; }, $wi_list),
        'pengajar_names' => array_map(function($o){ return $o->nama; }, $pengajar_list),
        'wi_rapat_name'  => $wi_rapat ? $wi_rapat->nama : null,
    ];
}


  private function _parse_materi($text){
    if (empty(trim($text))) return ["-"];

    return array_values(array_filter(
      array_map('trim', preg_split("/\r\n|\n|\r/", $text)),
      function($item){
        return !empty($item);
      }
    ));
  }

  public function get_ketua_loka() {
    return $this->db->get_where('tbl_pegawai', ['id_pegawai' => 71996])->row();
  }

  public function parseTujuanKursil($text) {
    if (empty($text)) return [];

    // Normalisasi newline dan pecah menjadi array
    $items = array_filter(
        explode("\n", str_replace(["\r\n", "\r"], "\n", $text)),
        function($line) {
            return !empty(trim($line));
        }
    );

    // Kelompokkan judul dan deskripsi berpasangan
    $result = [];
    for ($i = 0; $i < count($items); $i += 2) {
        $result[] = [
            'judul' => trim($items[$i] ?? ''),
            'deskripsi' => trim($items[$i+1] ?? '')
        ];
    }

    return $result;
  }

   public function get_durasi_pelatihan($id_pelatihan){
    //Ambil data tanggal
    $this->db->select('tanggal_mulai_pelatihan, tanggal_selesai_pelatihan');
    $this->db->where('id_pelatihan', $id_pelatihan);
    $pelatihan = $this->db->get('tbl_pelatihan')->row();

    if(!$pelatihan) return 0;

    //Hitung durasi
    $mulai = new DateTime($pelatihan->tanggal_mulai_pelatihan);
    $selesai = new DateTime($pelatihan->tanggal_selesai_pelatihan);
    $interval = $mulai->diff($selesai);

    //Return jumlah hari + 1
    return $interval->days;
   }

  //  Get Jenis Pelatihan
  function getJenisPelatihan() {
    return $this->db
      ->where('deleted_at IS NULL', null, false)
      ->order_by('id_jenis_pelatihan', 'DESC')
      ->get('tbl_jenis_pelatihan')
      ->result_array();
  }

// function getJenisPelatihan() {
//     return $this->db
//         ->where('deleted_at IS NULL', null, false) // existing condition
//         ->where('id_jenis_pelatihan', 1)          // new condition
//         ->order_by('id_jenis_pelatihan', 'DESC')
//         ->get('tbl_jenis_pelatihan')
//         ->result_array();
// }


  function get_pelatihan_by_jenis($id_jenis = null) {
    $this->db->select('pel.*, j.nama_jenis_pelatihan AS nama_jenis_pelatihan');
    $this->db->from('tbl_pelatihan pel');
    $this->db->join('tbl_jenis_pelatihan j', 'pel.id_jenis_pelatihan = j.id_jenis_pelatihan', 'left');
    $this->db->where('pel.deleted_at IS NULL', null, false);

    if ($id_jenis !== NULL) {
        $this->db->where('pel.id_jenis_pelatihan', $id_jenis);
    }

    $this->db->order_by('pel.id_pelatihan', 'DESC');
    return $this->db->get();
}


  public function get_pelatihan_by_panitia($id_jenis, $panitia_id) {
    // PERBAIKAN: pp.peran dibungkus GROUP_CONCAT supaya kompatibel dengan
    // ONLY_FULL_GROUP_BY (default di MariaDB 10.2+/MySQL 5.7+) saat dipakai
    // bareng GROUP BY di bawah. Sekaligus lebih informatif: kalau satu
    // panitia menjabat lebih dari satu peran di pelatihan yang sama,
    // semua perannya ikut tampil (dipisah koma), bukan cuma salah satu.
    // MIN() dipakai (bukan cuma nama_jenis_pelatihan biasa) supaya aman
    // dari mode ONLY_FULL_GROUP_BY di semua versi MySQL/MariaDB — nilainya
    // tetap sama saja karena satu id_jenis_pelatihan hanya punya satu nama.
    $this->db->select('pel.*, MIN(j.nama_jenis_pelatihan) AS nama_jenis_pelatihan, GROUP_CONCAT(DISTINCT pp.peran SEPARATOR ", ") AS peran', FALSE);
    $this->db->from('tbl_pelatihan pel');
    $this->db->join('tbl_jenis_pelatihan j', 'j.id_jenis_pelatihan = pel.id_jenis_pelatihan', 'left');
    $this->db->join('tbl_panitia_pelatihan pp', 'pp.pelatihan_id = pel.id_pelatihan');
    $this->db->where('pp.panitia_id', $panitia_id);

    if ($id_jenis != null) {
        $this->db->where('pel.id_jenis_pelatihan', $id_jenis);
    }

    // PERBAIKAN BUG: satu panitia bisa punya lebih dari satu baris yang
    // cocok di tbl_panitia_pelatihan untuk pelatihan yang sama (baik
    // karena memang menjabat beberapa peran sekaligus, maupun karena data
    // lama yang ke-insert dobel). Tanpa GROUP BY, JOIN ini menghasilkan
    // satu baris per kecocokan -> pelatihan yang sama tampil berkali-kali
    // di halaman daftar (data?jenis=...). GROUP BY memastikan satu
    // pelatihan hanya muncul sekali di listing.
    $this->db->group_by('pel.id_pelatihan');

    $this->db->order_by('pel.tanggal_mulai_pelatihan', 'DESC');
    return $this->db->get();
}


  function tambahJenisPelatihan($data)
   {
     $this->db->insert('tbl_jenis_pelatihan',$data)->result_array();
     return $this->db->insert_id();
   }

   function get_table($table_name)
   {
     $get_user = $this->db->get($table_name);
     return $get_user->result_array();
   }

   function get_tableid($table_name,$where,$id)
   {
     $this->db->where($where,$id);
     $edit = $this->db->get($table_name);
     return $edit->result_array();
   }

   function get_tableid_edit($table_name,$where,$id)
   {
     $this->db->where($where,$id);
     $edit = $this->db->get($table_name);
     return $edit->row();
   }

   function add_multiple($table,$data = array())
  {
      $total_array = count($data);

      if($total_array != 0)
      {
      $this->db->insert_batch($table, $data);
      }
  }

   function insertTable($table_name,$data)
   {
     $tambah = $this->db->insert($table_name,$data);
     return $tambah;
   }

   function LastinsertId($table_name,$data)
   {
     $this->db->insert($table_name,$data);
     $insert_id = $this->db->insert_id();
     return $insert_id;
   }

   function update_table($table_name,$where,$id,$data)
   {
     $this->db->where($where,$id);
     $update = $this->db->update($table_name,$data);
     return $update;
   }

   function delete_table($table_name,$where,$id)
   {
     $this->db->where($where,$id);
     $hapus = $this->db->delete($table_name);
     return $hapus;
   }

   function delete_table_multiple($table_name,$where,$id)
   {
      if (!empty($id)) {
         $this->db->where_in($where,$id);
         $hapus = $this->db->delete($table_name);
         return $hapus;
      }
   }

   function edit_table($table_name,$where,$id)
   {
     $this->db->where($where,$id);
     $edit = $this->db->get($table_name);
     return $edit->row();
   }

   function CountTable($table_name)
   {
     $Count = $this->db->get($table_name);
     return $Count->num_rows();
   }

   function CountTableId($table_name,$where,$id)
   {
     $this->db->where($where,$id);
     $Count = $this->db->get($table_name);
     return $Count->num_rows();
   }

   function SelectTable($table_name,$query,$id,$orderby)
   {
       $this->db->select($query, FALSE); // select('RIGHT(user.id_odojers,4) as kode', FALSE);
       $this->db->order_by($id,$orderby);
       $query = $this->db->get($table_name); // cek dulu apakah ada sudah ada kode di tabel.
       return $query;
   }

   function SelectTableSQL($query)
   {
       $row = $this->db->query($query);
       return $row;
   }

  function get_user($user)
  {
    $this->db->where('id_login',$user);
    $get_user = $this->db->get('tbl_login');
    return $get_user->row();
	}
	
	function rp($angka){
			$hasil_rupiah = "Rp" . number_format($angka,0,',','.'). ',-';
			return $hasil_rupiah;
	}

 
	public function buat_kode($table_name,$kodeawal,$idkode,$orderbylimit)
  {
      $query = $this->db->query("select * from $table_name $orderbylimit"); // cek dulu apakah ada sudah ada kode di tabel.
      
		  if($query->num_rows() > 0){
        //jika kode ternyata sudah ada.
        $hasil = $query->row();
        $kd = $hasil->$idkode;
        $cd = $kd;
        $nomor = $query->num_rows();
        $kode = $cd + 1;
        $kodejadi = $kodeawal."00".$kode;    // hasilnya CUS-0001 dst.
        $kdj = $kodejadi;
		  }else {
        //jika kode belum ada
        $kode = 0+1;
        $kodejadi = $kodeawal."00".$kode;    // hasilnya CUS-0001 dst.
        $kdj = $kodejadi;
      }
		  return $kdj;
  }

  public function buat_kode_join($table_name,$kodeawal,$idkode)
  {
      $query = $this->db->query($table_name); // cek dulu apakah ada sudah ada kode di tabel.
		  if($query->num_rows() > 0){
        //jika kode ternyata sudah ada.
        $hasil = $query->row();
        $kd = $hasil->$idkode;
        $cd = $kd;
        $kode = $cd + 1;
        $kodejadi = $kodeawal."00".$kode;    // hasilnya CUS-0001 dst.
        $kdj = $kodejadi;
		  }else {
        //jika kode belum ada
        $kode = 0+1;
        $kodejadi = $kodeawal."00".$kode;    // hasilnya CUS-0001 dst.
        $kdj = $kodejadi;
      }
		  return $kdj;
  }
  
  function acak($panjang)
  {
      $karakter= 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz123456789';
      $string = '';
      for ($i = 0; $i < $panjang; $i++) {
        $pos = rand(0, strlen($karakter)-1);
        $string .= $karakter[$pos];
      }
      return $string;
  }
}
?>
