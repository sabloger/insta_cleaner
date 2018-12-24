<?php
require __DIR__ . '/vendor/autoload.php';

$ig = new \InstagramAPI\Instagram();

$user = 'user';
$pass = 'pass';

$ig->login($user, $pass);



$rankToken = \InstagramAPI\Signatures::generateUUID();
echo "Rank Token: " . $rankToken . "\n";

$res = $ig->people->getSelfFollowers($rankToken);


$uFile = fopen('followers_not_followed.log', 'a');
$nFile = fopen('followers_followed.log', 'a');

fwrite($uFile, "\n\n\n" . date(DATE_ATOM) . "\n");
fwrite($nFile, "\n\n\n" . date(DATE_ATOM) . "\n");

//echo json_encode($res->getUsers(),JSON_PRETTY_PRINT);
//die();
foreach ($res->getUsers() as $user) {
    echo $user->getUsername() . "\n" . $user->getPk() . "\n";


    $ff = $ig->people->getFollowers($user->getPk(), $rankToken, $user);

    echo count($ff->getUsers()) . "\n";

    if (count($ff->getUsers()) > 0 ) { // follow backed
        fwrite($nFile, $user->getUsername() . " - " . $user->getPk() . "\n");
    } else {
        fwrite($uFile, $user->getUsername() . " - " . $user->getPk() . "\n");
    }

    sleep(1);
}
echo "Finish!";
fclose($uFile);
fclose($nFile);