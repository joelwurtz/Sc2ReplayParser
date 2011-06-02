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
 * ReplayGameEvents use to parse game events for a replay.
 *
 * @package    Sc2ReplayParser
 * @subpackage custom
 * @author     joel.wurtz@gmail.com
 * @version    1.0.0
 */
class ReplayGameEvents extends Parser
{
  const EVENT_GROUP_INITIALIZATION = 0x00;
  const EVENT_GROUP_PLAYER_ACTION = 0x01;
  const EVENT_GROUP_UNKNOW1 = 0x02;
  const EVENT_GROUP_CAMERA = 0x03;
  const EVENT_GROUP_UNKNOW2 = 0x04;
  const EVENT_GROUP_UNKNOW3 = 0x05;
  
  private $eventMapping = array(
    self::EVENT_GROUP_INITIALIZATION => array(
      0x2C => 'decodePlayerEnterEvent',
      0x0C => 'decodePlayerEnterEvent',
      0x0B => 'decodePlayerEnterEvent',
      0x05 => 'decodeGameStartEvent',
    ),
    self::EVENT_GROUP_PLAYER_ACTION => array(
      0x09 => 'decodePlayerLeaveEvent',
      0x0B => 'decodeUnitAbilityEvent',
      0x1B => 'decodeUnitAbilityEvent',
      0x2B => 'decodeUnitAbilityEvent',
      0x3B => 'decodeUnitAbilityEvent',
      0x4B => 'decodeUnitAbilityEvent',
      0x5B => 'decodeUnitAbilityEvent',
      0x6B => 'decodeUnitAbilityEvent',
      0x7B => 'decodeUnitAbilityEvent',
      0x8B => 'decodeUnitAbilityEvent',
      0x9B => 'decodeUnitAbilityEvent',
      0x0C => 'decodeUnitSelectionEvent',
      0x1C => 'decodeUnitSelectionEvent',
      0x2C => 'decodeUnitSelectionEvent',
      0x3C => 'decodeUnitSelectionEvent',
      0x4C => 'decodeUnitSelectionEvent',
      0x5C => 'decodeUnitSelectionEvent',
      0x6C => 'decodeUnitSelectionEvent',
      0x7C => 'decodeUnitSelectionEvent',
      0x8C => 'decodeUnitSelectionEvent',
      0x9C => 'decodeUnitSelectionEvent',
      0xAC => 'decodeUnitSelectionEvent',
      0x0D => 'decodeHotkeyEvent',
      0x1D => 'decodeHotkeyEvent',
      0x2D => 'decodeHotkeyEvent',
      0x3D => 'decodeHotkeyEvent',
      0x4D => 'decodeHotkeyEvent',
      0x5D => 'decodeHotkeyEvent',
      0x6D => 'decodeHotkeyEvent',
      0x7D => 'decodeHotkeyEvent',
      0x8D => 'decodeHotkeyEvent',
      0x9D => 'decodeHotkeyEvent',
      0x1F => 'decodeResourceTransferEvent',
      0x2F => 'decodeResourceTransferEvent',
      0x3F => 'decodeResourceTransferEvent',
      0x4F => 'decodeResourceTransferEvent',
      0x5F => 'decodeResourceTransferEvent',
      0x6F => 'decodeResourceTransferEvent',
      0x7F => 'decodeResourceTransferEvent',
      0x8F => 'decodeResourceTransferEvent',
    ),
    self::EVENT_GROUP_UNKNOW1 => array(
      0x06 => 'decodeUnknowEvent8',
      0x06 => 'decodeUnknowEvent4',
      0x06 => 'decodeUnknowEvent4',
    ),
    self::EVENT_GROUP_CAMERA => array(
      0x87 => 'decodeUnknowEvent8',
      0x08 => 'decodeUnknowEventCamera0x08',
      0x18 => 'decodeUnknowEvent162',
      0x01 => 'decodeUnknowEventCamera0xXF',
      0x11 => 'decodeUnknowEventCamera0xXF',
      0x21 => 'decodeUnknowEventCamera0xXF',
      0x31 => 'decodeUnknowEventCamera0xXF',
      0x41 => 'decodeUnknowEventCamera0xXF',
      0x51 => 'decodeUnknowEventCamera0xXF',
      0x61 => 'decodeUnknowEventCamera0xXF',
      0x71 => 'decodeUnknowEventCamera0xXF',
      0x81 => 'decodeUnknowEventCamera0xXF',
      0x91 => 'decodeUnknowEventCamera0xXF',
      0xA1 => 'decodeUnknowEventCamera0xXF',
      0xB1 => 'decodeUnknowEventCamera0xXF',
      0xC1 => 'decodeUnknowEventCamera0xXF',
      0xD1 => 'decodeUnknowEventCamera0xXF',
      0xE1 => 'decodeUnknowEventCamera0xXF',
      0xF1 => 'decodeUnknowEventCamera0xXF',
    ),
    self::EVENT_GROUP_UNKNOW2 => array(
      0x02 => 'decodeUnknowEvent2',
      0x12 => 'decodeUnknowEvent2',
      0x22 => 'decodeUnknowEvent2',
      0x32 => 'decodeUnknowEvent2',
      0x42 => 'decodeUnknowEvent2',
      0x52 => 'decodeUnknowEvent2',
      0x62 => 'decodeUnknowEvent2',
      0x72 => 'decodeUnknowEvent2',
      0x82 => 'decodeUnknowEvent2',
      0x92 => 'decodeUnknowEvent2',
      0xA2 => 'decodeUnknowEvent2',
      0xB2 => 'decodeUnknowEvent2',
      0xC2 => 'decodeUnknowEvent2',
      0xD2 => 'decodeUnknowEvent2',
      0xE2 => 'decodeUnknowEvent2',
      0xF2 => 'decodeUnknowEvent2',
      0x0C => 'decodeUnknowEvent0',
      0x1C => 'decodeUnknowEvent0',
      0x2C => 'decodeUnknowEvent0',
      0x3C => 'decodeUnknowEvent0',
      0x4C => 'decodeUnknowEvent0',
      0x5C => 'decodeUnknowEvent0',
      0x6C => 'decodeUnknowEvent0',
      0x16 => 'decodeUnknowEvent24',
      0xC6 => 'decodeUnknowEvent16',
      0x18 => 'decodeUnknowEvent4',
      0x87 => 'decodeUnknowEvent4',
      0x00 => 'decodeUnknowEvent10',
    ),
    self::EVENT_GROUP_UNKNOW3 => array(
      0x89 => 'decodeUnknowEvent4'
    ),
  );
  
  public function parse()
  {
    while ($this->streamReader->available() > 0)
    {
      $timestamp = $this->streamReader->readTimestamp();
      $eventInfo = $this->streamReader->readByte();

      //Player Id is one the last 5 bits of event info byte
      $playerId = $eventInfo & 0x1F;
      //Event group is the first 3 bits of event info byte
      $eventGroup = $eventInfo >> 5;
      //Event code next byte
      $eventCode = $this->streamReader->readByte();
      
      $this->decodeEvent($eventGroup, $eventCode, $playerId, $timestamp);
    }
  }
  
  public function decodeEvent($eventGroup, $eventCode, $playerId, $timestamp)
  {
    if (isset($this->eventMapping[$eventGroup][$eventCode]))
    {
      $function = $this->eventMapping[$eventGroup][$eventCode];
      $this->$function($eventGroup, $eventCode, $playerId, $timestamp);
    }
    else
    {
      $this->getLog()->addWarning(sprintf(
        "Unknow Game Event not mapped : Timestamp : %s | Group Ox%02X | Code 0x%02X | Player Id 0x%02X",
        $timestamp,
        $eventGroup,
        $eventCode,
        $playerId
      ));
    }
  }
  
  private function decodePlayerEnterEvent($eventGroup, $eventCode, $playerId, $timestamp)
  {
    //Do Nothing
  }
  
  private function decodeGameStartEvent($eventGroup, $eventCode, $playerId, $timestamp)
  {
    //Do Nothing
  }
  
  private function decodePlayerLeaveEvent($eventGroup, $eventCode, $playerId, $timestamp)
  {
    //Do Nothing
  }
  
  private function decodeUnitAbilityEvent($eventGroup, $eventCode, $playerId, $timestamp)
  {
    
  }
  
  private function decodeUnitSelectionEvent($eventGroup, $eventCode, $playerId, $timestamp)
  {
    
  }
  
  private function decodeHotkeyEvent($eventGroup, $eventCode, $playerId, $timestamp)
  {
    
  }
  
  private function decodeResourceTransferEvent($eventGroup, $eventCode, $playerId, $timestamp)
  {
    
  }
  
  private function decodeUnknowEventCamera0x08($eventGroup, $eventCode, $playerId, $timestamp)
  {
    
  }
  
  private function decodeUnknowEventCamera0xXF($eventGroup, $eventCode, $playerId, $timestamp)
  {
    
  }
  
  private function decodeUnknowEvent8($eventGroup, $eventCode, $playerId, $timestamp)
  {
    $this->streamReader->readBytes(8);
  }
  
  private function decodeUnknowEvent4($eventGroup, $eventCode, $playerId, $timestamp)
  {
    $this->streamReader->readBytes(4);
  }
  
  private function decodeUnknowEvent2($eventGroup, $eventCode, $playerId, $timestamp)
  {
    $this->streamReader->readBytes(2);
  }
  
  private function decodeUnknowEvent10($eventGroup, $eventCode, $playerId, $timestamp)
  {
    $this->streamReader->readBytes(10);
  }
  
  private function decodeUnknowEvent0($eventGroup, $eventCode, $playerId, $timestamp)
  {
    //Read o bytes do nothing
  }
  
  private function decodeUnknowEvent162($eventGroup, $eventCode, $playerId, $timestamp)
  {
    $this->streamReader->readBytes(162);
  }
  
  private function decodeUnknowEvent16($eventGroup, $eventCode, $playerId, $timestamp)
  {
    $this->streamReader->readBytes(16);
  }
  
  private function decodeUnknowEvent24($eventGroup, $eventCode, $playerId, $timestamp)
  {
    $this->streamReader->readBytes(24);
  }
}