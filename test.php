<?php
require_once "autoload.php";

use Sc2ReplayParser\MPQ\MPQParser;
use Sc2ReplayParser\Parser\ReplayAttributesEvents;

$mpq = new MPQParser("tests/FFA2.SC2Replay");
$mpq->extract();

try 
{
  $is = $mpq->getInputStream('replay.details');
  $data = $is->readSerializedData();
  
  $rae = new ReplayAttributesEvents($mpq->getInputStream('replay.attributes.events'), $mpq->getBuild());
  
  $rae->parse();
  
  //print_r($data);
}
catch(Exception $e)
{
 echo sprintf("Erreur sur le fichier %s : %s\n", $f, $e->getMessage());
}
