<?php

validate_request($_POST, [
    ["page", "s"],
    ["gameName", "s"],
    ["gameLink", "s"],
    ["gameIcon", "s"],
    ["isPlaying", "s"]
]);

require_once(__DIR__ . "/../../Partials/DatabaseConnector.php");

$status = Data::where("group", "Steam")->where("tag", $_POST['page'])->first();
$result = json_encode([
    "page" => $_POST['page'],
    "gameName" => $_POST['gameName'],
    "gameLink" => $_POST['gameLink'],
    "gameIcon" => $_POST['gameIcon'],
    "isPlaying" => $_POST['isPlaying'] === "true"
]);

if (!$status) {
    Data::create([
        "group" => "Steam",
        "tag" => $_POST['page'],
        "json" => $result
    ]);
} else {
    $status->json = $result;

    $status->save();
}

response_success([
    "refresh" => Token::generate()
]);

?>
