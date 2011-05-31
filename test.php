<?php

require_once "src/Sc2ReplayParser/IO/InputStream.php";
require_once "src/Sc2ReplayParser/IO/FileInputStream.php";
require_once "src/Sc2ReplayParser/IO/StreamReader.php";
require_once "src/Sc2ReplayParser/IO/LittleEndianStreamReader.php";
require_once "src/Sc2ReplayParser/IO/MPQStreamReader.php";
require_once "src/Sc2ReplayParser/IO/StringInputStream.php";
require_once "src/Sc2ReplayParser/Math/Math.php";
require_once "src/Sc2ReplayParser/MPQ/MPQParser.php";

$mpq = new MPQParser("tests/Replay.sc2replay");

$mpq->extract();

$file = $mpq->getFileList();

print_r($file);