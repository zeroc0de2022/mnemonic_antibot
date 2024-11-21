<?php
declare(strict_types = 1);

use class\MneMonica;

require __DIR__ . '/class/MneMonica.php';

if (isset($_POST['submit'],$_POST['token'])) {
    echo (MneMonica::checkHash($_POST['token']))
        ? '<h1>Human</h1>'
        : '<h1>Bot</h1>';
}

?>
<form action="" method="post">
    <input type="text" name="token" value="<?=MneMonica::getHash()?>" />
    <input type="submit" name="submit" value="send">
</form>
