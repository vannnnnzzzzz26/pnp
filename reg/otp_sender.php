<?php
    require_once( 'vendor/autoload.php' );

function sendOTP($phone_number, $otp) {
    $ch = curl_init();
    $parameters = array(
        'apikey' => '0a1c1d98b58a36653a8b7c1486b78786', // Replace with actual API key
        'number' => $phone_number, 
        'message' => "Your One Time Password  code is: $otp",
        'sendername' => 'Copwatch'
    );

    curl_setopt($ch, CURLOPT_URL, 'https://semaphore.co/api/v4/messages');
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($parameters));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $output = curl_exec($ch);
    curl_close($ch);

    return $output;
}
?>
