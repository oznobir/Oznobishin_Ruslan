<?php

namespace core\base\models;

use core\base\controllers\Singleton;

class Crypt
{
    use Singleton;

    private string $cryptMethod = 'aes-128-cbc';
    private string $hashAlgo = 'sha256';
    private int $hashLength = 32;

    public function encrypt($str): false|string
    {
        $iv_length = openssl_cipher_iv_length($this->cryptMethod);
        $iv = openssl_random_pseudo_bytes($iv_length);
        $ciphertext = openssl_encrypt($str, $this->cryptMethod, bin2hex(CRYPT_KEY), OPENSSL_RAW_DATA, $iv);
        $hmac = hash_hmac($this->hashAlgo, $ciphertext, bin2hex(CRYPT_KEY), true);
        return base64_encode( $iv.$hmac.$ciphertext);

    }

    public function decrypt($ciphertext): false|string
    {
        $iv_length = openssl_cipher_iv_length($this->cryptMethod);
        $c = base64_decode($ciphertext);
        $iv = substr($c, 0, $iv_length);
        $hmac = substr($c, $iv_length, $this->hashLength);
        $ciphertext_raw = substr($c, $iv_length + $this->hashLength);
        $original_plaintext = openssl_decrypt($ciphertext_raw, $this->cryptMethod, bin2hex(CRYPT_KEY), OPENSSL_RAW_DATA, $iv);
        $calMac = hash_hmac($this->hashAlgo, $ciphertext_raw, bin2hex(CRYPT_KEY), true);
        if (hash_equals($hmac, $calMac))
            return $original_plaintext;
        else return false;
    }
}