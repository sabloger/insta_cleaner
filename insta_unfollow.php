<?php
require __DIR__ . '/vendor/autoload.php';

$ig = new \InstagramAPI\Instagram();

$user = 'user';
$pass = 'pass';

$ig->login($user, $pass);


$rankToken = \InstagramAPI\Signatures::generateUUID();
echo "Rank Token: " . $rankToken . "\n";

$res = $ig->people->getSelfFollowing($rankToken);

$whiteList = [
    'physicsfun',
    'bbcpersian',
    'dr_holakuee',
    'mehradhiddenofficial',
    'ghomayshi',
    'dafdaf_production',
    'reportage_ads',
    'picxagram',
    'iran_verifiedbadge',
    'virlan.com_news',
    'n.moein',
    'radio.ghermez',
];

$uFile = fopen('unfollowed.log', 'a');
$nFile = fopen('still_followed.log', 'a');

fwrite($uFile, "\n\n\n" . date(DATE_ATOM) . "\n");
fwrite($nFile, "\n\n\n" . date(DATE_ATOM) . "\n");

//echo json_encode($res->getUsers(),JSON_PRETTY_PRINT);
//die();
foreach ($res->getUsers() as $user) {
    echo $user->getUsername() . "\n" . $user->getPk() . "\n";
    $ff = $ig->people->getFollowing($user->getPk(), $rankToken, $user);

    echo count($ff->getUsers()) . "\n";

    if (count($ff->getUsers()) > 0 || array_search($user->getUsername(), $whiteList) !== false) { // follow backed
        fwrite($nFile, $user->getUsername() . " - " . $user->getPk() . "\n");
    } else {
        $ig->people->unfollow($user->getPk());
        fwrite($uFile, $user->getUsername() . " - " . $user->getPk() . "\n");
    }

    sleep(1);
}
echo "Finish!";
fclose($uFile);
fclose($nFile);