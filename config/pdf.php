<?php

return [
    'font_path' => public_path('fonts/'),
    'font_data' => [
        'nikosh' => [ // must be lowercase and snake_case
            'R'  => 'Nikosh.ttf',
            'B'  => 'Nikosh.ttf',
            'I'  => 'Nikosh.ttf',
            'BI' => 'Nikosh.ttf',
            'useOTL' => 0xFF,
        ],
        'kalpurush' => [ // must be lowercase and snake_case
            'R'  => 'Kalpurush.ttf',    // regular font
            'useOTL' => 0xFF,
            'useKashida' => 0,
        ]
    ],
	'mode'                  => 'utf-8',
	'format'                => 'A4',
	'author'                => '',
	'subject'               => '',
	'keywords'              => '',
	'creator'               => 'Laravel Pdf',
	'display_mode'          => 'fullpage',
	'tempDir'               => base_path('storage/temp/'),
	'pdf_a'                 => false,
	'pdf_a_auto'            => false,
	'icc_profile_path'      => '',
    'defaultCssFile'        => false,
    'pdfWrapper'            => 'misterspelik\LaravelPdf\Wrapper\PdfWrapper',
];
