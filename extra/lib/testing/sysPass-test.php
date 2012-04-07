<?php
require '../crypto.php';
echo '<b>New System:</b> ' . Crypto::encryptPassword("Hello World!");
echo '<br/><br/>';
echo '<b>Old System:</b> ' . md5("Hello World!");
