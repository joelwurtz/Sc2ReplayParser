<?php
/*
 * This file is part of the Sc2ReplayParser.
 * (c) 2011 joel.wurtz@gmail.com
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sc2ReplayParser\Parser;

/**
 * ReplayAttributesEvents use to parse attributes for a replay.
 *
 * @package    Sc2ReplayParser
 * @subpackage custom
 * @author     joel.wurtz@gmail.com
 * @version    1.0.0
 */
class ReplayAttributesEvents extends Parser
{
  private $colorMapping = array(
    'tc01' => 'Red',
    'tc02' => 'Blue',
    'tc03' => 'Teal',
    'tc04' => 'Purple',
    'tc05' => 'Yellow',
    'tc06' => 'Orange',
    'tc07' => 'Green',
    'tc08' => 'Light Pink',
    'tc09' => 'Violet',
    'tc10' => 'Light Grey',
    'tc11' => 'Dark Green',
    'tc12' => 'Brown',
    'tc13' => 'Light Green',
    'tc14' => 'Dark Grey',
    'tc15' => 'Pink',
  );
  
  private $attributeDecodeMapping = array(
    0x01F4 => 'decodePlayerType',
    0x07D1 => 'decodeGameType',
    0x0BB8 => 'decodeGameSpeed',
    0x0BB9 => 'decodePlayerRace',
    0x0BBA => 'decodePlayerColor',
    0xBBB => 'decodeHandicap',
    0xBBC => 'decodeDifficulty',
    0x0BC1 => 'decodeGameCategory',
    0x07D2 => 'decodeTeam1v1',
    0x07D3 => 'decodeTeam2v2',
    0x07D4 => 'decodeTeam3v3',
    0x07D5 => 'decodeTeam4v4',
    0x07D6 => 'decodeTeamFFA',
    0x07D7 => 'decodeTeam5v5',
    0x07D8 => 'decodeTeam6v6',
    0x0BBF => 'decodeUnknowAttribute',
    0x0BC0 => 'decodeUnknowAttribute',
    0x07DB => 'decodeUnknowAttribute',
    0x07DC => 'decodeUnknowAttribute',
    0x07DD => 'decodeUnknowAttribute',
    0x07DE => 'decodeUnknowAttribute',
    0x07DF => 'decodeUnknowAttribute',
    0x07E0 => 'decodeUnknowAttribute',
    0x07E1 => 'decodeUnknowAttribute',
    0x07E2 => 'decodeUnknowAttribute',
    0x03E8 => 'decodeUnknowAttribute',
    0x0BC2 => 'decodeUnknowAttribute',
    0x07D0 => 'decodeUnknowAttribute',
    0x0BBE => 'decodeUnknowAttribute',
    0X03E9 => 'decodeUnknowAttribute',
  );
  
  public function parse()
  {
    if ($this->build >= Parser::BUILD_17326)
    {
      $header = $this->streamReader->readBytes(5);
    }
    else
    {
      $header = $this->streamReader->readBytes(4);
    }
    $attributesCount = $this->streamReader->readUInt32();
    
    while($this->streamReader->available() > 0)
    {
      $attributeHeader = $this->streamReader->readUInt32();
      $attributeId = $this->streamReader->readUInt32();
      
      $playerId = $this->streamReader->readByte();
      
      $value = trim(strrev($this->streamReader->readString(4)));
      
      $this->decodeAttribute($attributeHeader, $attributeId, $playerId, $value);
      
      $attributesCount--;
    }
  }
  
  private function decodeAttribute($attributeHeader, $attributeId, $playerId, $value)
  {
    if (isset($this->attributeDecodeMapping[$attributeId]))
    {
      $function = $this->attributeDecodeMapping[$attributeId];
      $this->$function($attributeId, $playerId, $value);
    }
    else
    {
      $this->getLog()->addWarning(sprintf(
        "Attribute not mapped : Header : 0x%08X | Attribute Id : 0x%08X | Player Id : 0x%02X | Value : %s", 
        $attributeHeader,  
        $attributeId, 
        $playerId, 
        $value
      ));
    }
  }
  
  private function decodePlayerType($attributeId, $playerId, $value)
  {
    $this->replay->getPlayer($playerId)->setType($value);
  }
  
  private function decodeGameType($attributeId, $playerId, $value)
  {
    
  }
  
  private function decodeGameSpeed($attributeId, $playerId, $value)
  {
    
  }
  
  private function decodePlayerRace($attributeId, $playerId, $value)
  {
    $this->replay->getPlayer($playerId)->setRaceChoose($value);
  }
  
  private function decodePlayerColor($attributeId, $playerId, $value)
  {
    $this->replay->getPlayer($playerId)->setColorName($this->colorMapping[$value]);
  }
  
  private function decodeHandicap($attributeId, $playerId, $value)
  {
    $this->replay->getPlayer($playerId)->setHandicap((int)$value);
  }
  
  private function decodeDifficulty($attributeId, $playerId, $value)
  {
    $this->replay->getPlayer($playerId)->setDifficulty($value);
  }
  
  private function decodeGameCategory($attributeId, $playerId, $value)
  {
    
  }
  
  private function decodeTeam1v1($attributeId, $playerId, $value)
  {
    $this->replay->getPlayer($playerId)->setTeam((int)substr($value, 1));
  }
  
  private function decodeTeam2v2($attributeId, $playerId, $value)
  {
    $this->replay->getPlayer($playerId)->setTeam((int)substr($value, 1));
  }
  
  private function decodeTeam3v3($attributeId, $playerId, $value)
  {
    $this->replay->getPlayer($playerId)->setTeam((int)substr($value, 1));
  }
  
  private function decodeTeam4v4($attributeId, $playerId, $value)
  {
    $this->replay->getPlayer($playerId)->setTeam((int)substr($value, 1));
  }
  
  private function decodeTeamFFA($attributeId, $playerId, $value)
  {
    $this->replay->getPlayer($playerId)->setTeam((int)substr($value, 1));
  }
  
  private function decodeTeam5v5($attributeId, $playerId, $value)
  {
    $this->replay->getPlayer($playerId)->setTeam((int)substr($value, 1));
  }
  
  private function decodeTeam6v6($attributeId, $playerId, $value)
  {
    $this->replay->getPlayer($playerId)->setTeam((int)substr($value, 1));
  }
  
  private function decodeUnknowAttribute($attributeId, $playerId, $value)
  {
    $this->getLog()->addInfo(sprintf(
      "Attribute unknow : Attribute Id : 0x%08X | Player Id : 0x%02X | Value : %s",  
      $attributeId, 
      $playerId, 
      $value
    ));
  }
}