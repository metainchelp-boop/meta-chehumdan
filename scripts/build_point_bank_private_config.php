<?php
/* GitHub Actions secret을 런타임 전용 PHP 설정으로 만든다. 값은 출력하지 않는다. */
if(PHP_SAPI !== 'cli' || !isset($argv[1])) exit(2);

$config = array(
    'token'=>(string)getenv('METACREW_POINT_BANK_TOKEN'),
    'currentKeyId'=>(string)getenv('METACREW_POINT_BANK_HMAC_CURRENT_ID'),
    'currentSecret'=>(string)getenv('METACREW_POINT_BANK_HMAC_CURRENT_SECRET'),
    'previousKeyId'=>(string)getenv('METACREW_POINT_BANK_HMAC_PREVIOUS_ID'),
    'previousSecret'=>(string)getenv('METACREW_POINT_BANK_HMAC_PREVIOUS_SECRET')
);
if(strlen($config['token']) < 32 || strlen($config['currentSecret']) < 32 ||
    !preg_match('/^[A-Za-z0-9._-]{1,64}$/', $config['currentKeyId'])) exit(3);
if(($config['previousKeyId'] === '') xor ($config['previousSecret'] === '')) exit(4);
if($config['previousKeyId'] !== '' &&
    (!preg_match('/^[A-Za-z0-9._-]{1,64}$/', $config['previousKeyId']) ||
     strlen($config['previousSecret']) < 32 || $config['previousKeyId'] === $config['currentKeyId'])) exit(5);

$target = $argv[1];
$directory = dirname($target);
if(!is_dir($directory) && !mkdir($directory, 0700, true)) exit(6);
$content = "<?php\nif(!defined('MC_POINT_BANK_PRIVATE_LOAD')) exit;\nreturn ".var_export($config, true).";\n";
if(file_put_contents($target, $content, LOCK_EX) === false) exit(7);
@chmod($target, 0600);
echo "private config ready\n";
