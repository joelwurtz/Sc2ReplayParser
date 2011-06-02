<?php

require_once "src/Sc2ReplayParser/IO/InputStream.php";
require_once "src/Sc2ReplayParser/IO/FileInputStream.php";
require_once "src/Sc2ReplayParser/IO/StreamReader.php";
require_once "src/Sc2ReplayParser/IO/LittleEndianStreamReader.php";
require_once "src/Sc2ReplayParser/IO/MPQStreamReader.php";
require_once "src/Sc2ReplayParser/IO/StringInputStream.php";
require_once "src/Sc2ReplayParser/Math/Math.php";
require_once "src/Sc2ReplayParser/MPQ/MPQParser.php";

$mpq = new MPQParser("tests/Replay22.2.replay");

$mpq->extract();

$file_list = $mpq->getFileList();

try
{
  $is = $mpq->getInputStream('replay.details');

  while($is->available() > 0)
  {
    echo "Data : \n";
    $data = $is->readSerializedData();
    print_r($data);
    echo "\n\n";
  }
}
catch(Exception $e)
{
 echo sprintf("Erreur sur le fichier %s : %s\n", $f, $e->getMessage());
}
