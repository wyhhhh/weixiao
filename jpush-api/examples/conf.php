<?php
require __DIR__ . '/../autoload.php';

use JPush\Client as JPush;

$app_key = '20fb09122825e1f47b46c97e';
$master_secret = '4a50a7f776e17ce48a4b7207';
//$registration_id = 'registration_id';

$client = new JPush($app_key, $master_secret);
