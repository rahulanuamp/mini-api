<?php

header("Content-Type: application/json");

date_default_timezone_set("Asia/Kolkata");

try {

    $name = isset($_GET["name"]) ? trim($_GET["name"]) : "User";

    if ($name == "") {
        $name = "User";
    }

    $hour = date("H");

    if ($hour < 12) {

        $greeting = "Good Morning";

    } elseif ($hour < 18) {

        $greeting = "Good Afternoon";

    } else {

        $greeting = "Good Evening";

    }

    echo json_encode(

        [

            "status" => true,

            "message" => $greeting . " " . htmlspecialchars($name) . "!",

            "date" => date("d M Y"),

            "time" => date("h:i:s A"),

            "timezone" => "Asia/Kolkata",

            "api_status" => "Active"

        ],

        JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE

    );

} catch (Exception $e) {

    echo json_encode(

        [

            "status" => false,

            "message" => $e->getMessage()

        ]

    );

}

?>