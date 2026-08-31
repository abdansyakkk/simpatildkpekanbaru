<?php 
    if (!function_exists('format_tanggal_indonesia')) {
        function format_tanggal_indonesia($dateString) {
            if (empty($dateString) || $dateString == '0000-00-00') return '-';
            
            $date = new DateTime($dateString);
            
            $hari = [
                'Monday'    => 'Senin',
                'Tuesday'   => 'Selasa',
                'Wednesday' => 'Rabu',
                'Thursday'  => 'Kamis',
                'Friday'    => 'Jumat',
                'Saturday'  => 'Sabtu',
                'Sunday'    => 'Minggu'
            ];
            
            $bulan = [
                'January'   => 'Januari',
                'February'  => 'Februari',
                'March'     => 'Maret',
                'April'     => 'April',
                'May'       => 'Mei',
                'June'      => 'Juni',
                'July'      => 'Juli',
                'August'    => 'Agustus',
                'September' => 'September',
                'October'   => 'Oktober',
                'November'  => 'November',
                'December'  => 'Desember'
            ];
            
            $namaHari = $hari[$date->format('l')];
            $namaBulan = $bulan[$date->format('F')];
            
            return $namaHari . ', ' . $date->format('d') . ' ' . $namaBulan . ' ' . $date->format('Y');
        }
    }
?>