<?php
function login_attempt_rate_limit($maxAttempts, $timeFrameInSeconds, $timeoutDuration) {
    $ip = $_SERVER['REMOTE_ADDR'];
    $key = 'login_attempts_' . $ip;

    // Check if the session variable is set
    if (!isset($_SESSION[$key])) {
        $_SESSION[$key] = 1;
        $_SESSION[$key . '_time'] = time(); // Set the initial attempt time
    } else {
        // Increment the counter
        $_SESSION[$key]++;

        // Check if the number of attempts exceeds the limit
        if ($_SESSION[$key] > $maxAttempts) {
            // Check if the time-related session variable is set
            if (!isset($_SESSION[$key . '_time'])) {
                $_SESSION[$key . '_time'] = time(); // Set the initial attempt time
            }

            // Check the time since the first attempt
            $firstAttemptTime = $_SESSION[$key . '_time'];
            $currentTime = time();

            if (($currentTime - $firstAttemptTime) < $timeFrameInSeconds) {
                // Log or take action for a potential brute force attempt
                // $sec = $timeoutDuration;
                die("Rate limit exceeded. Please try again later. goodluck :) .");

            } else {
                // Reset the attempts counter and update the time
                $_SESSION[$key] = 1;
                $_SESSION[$key . '_time'] = $currentTime;
            }
        }
    }
}

// Set your rate limiting parameters
$maxAttempts = 3;  // Maximum number of attempts allowed
$timeFrameInSeconds = 60;  // Time frame in seconds (e.g., 60 seconds for 1 minute)
$timeoutDuration = 10;


// Call the rate limiting function before processing login attempts
login_attempt_rate_limit($maxAttempts, $timeFrameInSeconds, $timeoutDuration);

// Proceed with your regular login processing here
// ...

// blockIP(ip){
    //nambahin disini daftar ip yang keblock kedalem sebuah txt file
//}

?>
