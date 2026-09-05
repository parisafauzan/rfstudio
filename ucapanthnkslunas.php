<!DOCTYPE html>
<html lang="en">
    <body>
        <?php
$bytes = random_bytes(3);
$result = (bin2hex($bytes));
var_dump($result);
?>
    </body>
</html>