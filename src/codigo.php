<?php
function generate_string() {
    
    $strength = 5;
    $input = '0123456789';
    $input_length = strlen($input);
    $random_string = 'COD - ';
    for($i = 0; $i < $strength; $i++) {
        $random_character = $input[mt_rand(0, $input_length - 1)];
        $random_string .= $random_character;
    }
    return $random_string;
}
echo generate_string();
?>