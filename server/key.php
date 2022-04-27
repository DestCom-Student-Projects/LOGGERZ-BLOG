<?php 
    $details = openssl_pkey_get_details(openssl_pkey_new());

    echo $details['key'];
?>