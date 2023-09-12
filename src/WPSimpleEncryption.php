<?php

namespace Jeffreyvr\WPSimpleEncryption;

use Defuse\Crypto\Key;
use Defuse\Crypto\Crypto;

class WPSimpleEncryption
{
    public string $secret_key_constant;

    public function __construct($secret_key_constant)
    {
        $this->secret_key_constant = $secret_key_constant;
    }

    public function is_setup_valid()
    {
        if (! defined($this->secret_key_constant)) {
            return false;
        }

        return true;
    }

    public function get_secret_key_constant()
    {
        // If it's not defined, try to write it to wp-config.php.
        if ($this->is_setup_valid() === false) {
            $this->setup_secret_key();
        }

        if ($this->is_setup_valid() === false) {
            throw new \Exception('Secret key constant is not defined.');
        }

        $key = constant($this->secret_key_constant);

        return Key::loadFromAsciiSafeString($key);
    }

    public function encrypt($string)
    {
        return Crypto::encrypt($string, $this->get_secret_key_constant());
    }

    public function decrypt($string)
    {
        return Crypto::decrypt($string, $this->get_secret_key_constant());
    }

    public function setup_secret_key()
    {
        $config_path = ABSPATH . 'wp-config.php';

        if (!file_exists($config_path) || !is_writable($config_path)) {
            return false;
        }

        $random_key = Key::createNewRandomKey()
            ->saveToAsciiSafeString();

        $define_str = "define('{$this->secret_key_constant}', '{$random_key}');";

        $config_content = file_get_contents($config_path);

        if (strpos($config_content, $this->secret_key_constant) !== false) {
            return false;
        }

        $insert_pos = strpos($config_content, "/* That's all, stop editing!");

        if ($insert_pos !== false) {
            $config_content = substr_replace($config_content, $define_str . PHP_EOL, $insert_pos, 0);
        } else {
            $config_content .= PHP_EOL . $define_str;
        }

        $written = file_put_contents($config_path, $config_content);

        if ($written === false) {
            return false;
        }

        define($this->secret_key_constant, $random_key);

        return $random_key;
    }
}
