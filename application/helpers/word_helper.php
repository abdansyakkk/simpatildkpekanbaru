<?php 
    if (!defined('BASEPATH')) exit('No direct script access allowed');

    if (!function_exists('generate_list_style')){
        /**
         * @param \PhpOffice\PhpWord\PhpWord $phpWord
         * @param int $index
         * @return string
        */

        function generate_list_style($phpword, $formatType = 'decimal', $start = 1)
        {
            static $counter = 1;
        
            $styleName = 'orderedList' . $counter;
            $phpword->addNumberingStyle($styleName, [
                'type' => 'multilevel',
                'levels' => [
                    [
                        'format' => $formatType,
                        'text' => '%1.',
                        'left' => 360,
                        'hanging' => 360,
                        'tabPos' => 360,
                        'start' => $start,
                        // 'font' => ['bold' => true]
                    ]
                ]
            ]);
            $counter++;
            return $styleName;
        }
        
    }
?>