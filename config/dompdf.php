<?php

return [
    /*
    |--------------------------------------------------------------------------
    | DOMPDF Options
    |--------------------------------------------------------------------------
    |
    | These are the options that will be passed to the Dompdf instance.
    |
    | For a full list of options, see:
    | https://github.com/dompdf/dompdf/wiki/Usage#options
    |
    */

    'fontDir' => public_path('fonts/'), // Path to the fonts directory

    'fontCache' => storage_path('app/dompdf'), // Path to the font cache directory

    'defaultFont' => 'Arial', // The default font for the PDF

    'fontHeightRatio' => 0.9, // The font height-to-width ratio

    'isRemoteEnabled' => true, // Enable or disable remote file access

    'isPhpEnabled' => true, // Enable or disable evaluation of PHP code

    /*
    |--------------------------------------------------------------------------
    | Font Families
    |--------------------------------------------------------------------------
    |
    | You can define font families and their corresponding TTF font files here.
    | These fonts will be used in the PDF generation process.
    |
    */

    'fontFamily' => 'Arial, sans-serif', // Default font family

    'fontFamily' => 'BanglaFont', // Add your Bangla font name here

    /*
    |--------------------------------------------------------------------------
    | Additional Options
    |--------------------------------------------------------------------------
    |
    | Add any additional options that you want to pass to the Dompdf instance.
    | For a full list of options, see the link provided above.
    |
    */

    // 'some_option' => 'some_value',
];
