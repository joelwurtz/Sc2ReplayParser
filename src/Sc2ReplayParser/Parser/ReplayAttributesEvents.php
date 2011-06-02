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
  private $attributeDecodeMapping = array(
    0x01F4 => 'decodePlayerType'
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
      
      $value = strrev($this->streamReader->readString(4));
      
      echo sprintf("Header : 0x%08X | Attribute Id : 0x%08X | Player Id : 0x%02X | Value : %s\n", $attributeHeader,  
      $attributeId, $playerId, $value);
      $attributesCount--;
    }
  }
  
  private function decodeAttribute($attributeHeader, $attributeId, $playerId, $value)
  {
    
  }
}