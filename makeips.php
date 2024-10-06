<?php

function generateValidIPs() {
    $file = fopen('valid_ips.txt', 'w');

    for ($a = 000; $a <= 255; $a++) {
        for ($b = 000; $b <= 255; $b++) {
            for ($c = 000; $c <= 255; $c++) {
                for ($d = 000; $d <= 255; $d++) {
                    $ip = "$a.$b.$c.$d";
                    fwrite($file, $ip . PHP_EOL);
                }
            }
        }
    }

    fclose($file);
    echo "Valid IP list generated successfully.";
}

generateValidIPs();

?>