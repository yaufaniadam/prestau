<?php 
if (!defined('BASEPATH')) exit('No direct script access allowed');  

use PhpOffice\PhpSpreadsheet\Spreadsheet as spreadsheet; // instead PHPExcel
use PhpOffice\PhpSpreadsheet\Writer\Xlsx as xlsx; // Instead PHPExcel_Writer_Excel2007
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing as drawing; // Instead PHPExcel_Worksheet_Drawing
use PhpOffice\PhpSpreadsheet\Style\Alignment as alignment; // Instead alignment
use PhpOffice\PhpSpreadsheet\Style\Fill as fill; // Instead fill
use PhpOffice\PhpSpreadsheet\Style\Border as border; // Instead fill
use PhpOffice\PhpSpreadsheet\Style\Color as color_; //Instead PHPExcel_Style_Color
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup as pagesetup; // Instead PHPExcel_Worksheet_PageSetup


class Excel {

	
    public function __construct() {
     

    }
    public function style($style) {   
        // Buat sebuah variabel untuk menampung pengaturan style dari header tabel
		
		$style_header = [
			'font' => [
				'bold' => true,
				'color' => ['argb' => 'FFFFFFFF'],	
			],
			'alignment' => [
				'horizontal' => alignment::HORIZONTAL_CENTER,
			],
			'borders' => [
				'right' => ['borderStyle'  => border::BORDER_THIN],  // Set border right dengan garis tipis
			  	'bottom' => ['borderStyle'  => border::BORDER_THIN], // Set border bottom dengan garis tipis
			  	'left' => ['borderStyle'  => border::BORDER_THIN] // Set border left dengan garis tipis			  
			],
			'fill' => [
				'fillType' => fill::FILL_GRADIENT_LINEAR,
				'rotation' => 90,
				'startColor' => [
					'argb' => '065139',
				],
				'endColor' => [
					'argb' => '056848',
				],
			],
		];
		

		$style_td = [
			'alignment' => [
				'horizontal' => alignment::HORIZONTAL_LEFT, // Set text jadi di tengah secara vertical (middle)
			  	'vertical' => alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
			],
			'borders' => [
			  'top' => ['borderStyle'  => border::BORDER_THIN], // Set border top dengan garis tipis
			  'right' => ['borderStyle'  => border::BORDER_THIN],  // Set border right dengan garis tipis
			  'bottom' => ['borderStyle'  => border::BORDER_THIN], // Set border bottom dengan garis tipis
			  'left' => ['borderStyle'  => border::BORDER_THIN] // Set border left dengan garis tipis
			  ]
	  ];

	  $style_td_left = [
			'alignment' => [
			  	'horizontal' => alignment::HORIZONTAL_LEFT, // Set text jadi di tengah secara vertical (middle)
				'vertical' => alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
		  ],
	  ];

	  $style_td_right = [
			'alignment' => [
			  	'horizontal' => alignment::HORIZONTAL_RIGHT, // Set text jadi di tengah secara vertical (middle)
				'vertical' => alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
		  ],
	  ];

		  
	  $style_td_bold = [
			'font' => ['bold' => true],
			'fill' => [
				'fillType' => fill::FILL_SOLID,				
				'color' => [
					'argb' => 'ecaa27',
				],				
			],
	  ];

	  $style_td_bold_no_bg = [
			'font' => ['bold' => true],
				'fill' => [
			  'type' => fill::FILL_SOLID,
		  ],
	  ];


	$arr = array('style_header'=>$style_header, 'style_td'=>$style_td, 'style_td_left'=>$style_td_left, 'style_td_right'=>$style_td_right, 'style_td_bold'=>$style_td_bold,'style_td_bold_no_bg'=>$style_td_bold_no_bg );

		return $arr[$style];

	}
	 
}