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
  const GAME_FRAME_PER_SECOND = 32;
  
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
      0x0D => 'decodeHotkeyEvent', //Hot key on group 0
      0x1D => 'decodeHotkeyEvent', //Hot key on group 1
      0x2D => 'decodeHotkeyEvent', //Etc ....
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
      0x01 => 'decodeUnknowEventCamera0xX1',
      0x11 => 'decodeUnknowEventCamera0xX1',
      0x21 => 'decodeUnknowEventCamera0xX1',
      0x31 => 'decodeUnknowEventCamera0xX1',
      0x41 => 'decodeUnknowEventCamera0xX1',
      0x51 => 'decodeUnknowEventCamera0xX1',
      0x61 => 'decodeUnknowEventCamera0xX1',
      0x71 => 'decodeUnknowEventCamera0xX1',
      0x81 => 'decodeUnknowEventCamera0xX1',
      0x91 => 'decodeUnknowEventCamera0xX1',
      0xA1 => 'decodeUnknowEventCamera0xX1',
      0xB1 => 'decodeUnknowEventCamera0xX1',
      0xC1 => 'decodeUnknowEventCamera0xX1',
      0xD1 => 'decodeUnknowEventCamera0xX1',
      0xE1 => 'decodeUnknowEventCamera0xX1',
      0xF1 => 'decodeUnknowEventCamera0xX1',
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
    $timestamp = 0;
    while ($this->streamReader->available() > 0 && $timestamp < 20)
    {
      $timestamp += $this->streamReader->readTimestamp() / self::GAME_FRAME_PER_SECOND;
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
      $this->streamReader->beginLog("logEvent");
      $this->$function($eventGroup, $eventCode, $playerId, $timestamp);
      $listHexa = $this->streamReader->endLog("logEvent");
      echo sprintf("Calling %s with Timestamp : %s | Group Ox%02X | Code 0x%02X | Player Id 0x%02X\n", $function, $timestamp, $eventGroup, $eventCode, $playerId);
      $i = 0;
      foreach ($listHexa as $hexa)
      {
        $i++;
        echo $hexa."\t";
        if ($i % 8 == 0)
        {
          echo "\n";
        }
      }
      echo "\n";
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
    //echo sprintf("Player %s entering the game\n", $playerId, $timestamp);
  }
  
  private function decodeGameStartEvent($eventGroup, $eventCode, $playerId, $timestamp)
  {
    //Do Nothing
    
    //echo sprintf("Game starting\n");
  }
  
  private function decodePlayerLeaveEvent($eventGroup, $eventCode, $playerId, $timestamp)
  {
    //Do Nothing
    //echo sprintf("Player %s leaving the game\n", $playerId);
  }
  
  private function decodeUnitAbilityEvent($eventGroup, $eventCode, $playerId, $timestamp)
  {
    if ($this->isInBuild(self::BUILD_18317))
    {
      $firstByte = $this->streamReader->readByte();
      $temp = $this->streamReader->readByte();
      if ($firstByte & 0x0c && !($firstByte & 1)) 
      {
        if ($temp & 8) 
        {
          if ($temp & 0x80)
          {
            $this->streamReader->skip(8);
          }
          $this->streamReader->skip(10);
          $ability = 0;
        }
        else
        {
          $abilityBytes = $this->streamReader->readBytes(3);
          $ability = $abilityBytes[1] << 16 | $abilityBytes[2] << 8 | $abilityBytes[3];
          if (($temp & 0x60) == 0x60)
          {
            $this->streamReader->skip(4);
          }
          else
          {
            $flagtemp = $ability & 0xF0;
            if ($flagtemp & 0x20)
            {
              $this->streamReader->skip(9);
              if ($firstByte & 8)
              {
                $this->streamReader->skip(9);
              }
            }
            elseif ($flagtemp & 0x10)
            {
              $this->streamReader->skip(9);
            }
            elseif ($flagtemp & 0x40)
            {
              $this->streamReader->skip(18);
            }
          }
          $ability = $ability & 0xFFFF0F;
        }
      }
    }
    elseif ($this->isInBuild(self::BUILD_16561))
    {
      $firstByte = $this->streamReader->readByte();
      $temp = $this->streamReader->readByte();
      $abilityBytes = $this->streamReader->readBytes(3);
      $ability = ($abilityBytes[1] << 16) | ($abilityBytes[2] << 8) | ($abilityBytes[3] & 0x3F);
      
      if ($temp == 0x20 || $temp == 0x22) 
      {
        $nByte = $ability & 0xFF;
        if ($nByte > 0x07) 
        {
          if ($firstByte == 0x29 || $firstByte == 0x19)
          {
            $this->streamReader->skip(4);
          }
          else
          {
            $this->streamReader->skip(9);
            if ($nByte & 0x20)
            {
              $this->streamReader->skip(8);
              $nByte = $this->streamReader->readByte();
              if ($nByte & 8)
              {
                $this->streamReader->skip(4);
              }
            }
          }
        }
      }
      elseif ($temp == 0x48 || $temp == 0x4A)
      {
        $this->streamReader->skip(7);
      }
      elseif ($temp == 0x88 || $temp == 0x8A)
      {
        $this->streamReader->skip(8);
      }
    }
    else
    {
      
    }
  }
  
  private function decodeUnitSelectionEvent($eventGroup, $eventCode, $playerId, $timestamp)
  {
    if ($this->isInBuild(self::BUILD_16561))
    {
      $this->streamReader->skip(1);
      $deselectFlags = $this->streamReader->readByte();
      if (($deselectFlags & 3) == 1)
      {
        $nextByte = $this->streamReader->readByte();
        $deselectionBits = ($deselectFlags & 0xFC) | ($nextByte & 3);
        while ($deselectionBits > 6) 
        {
          $nextByte = $this->streamReader->readByte();
          $deselectionBits -= 8;
        }
        $deselectionBits += 2;
        $deselectionBits %= 8;
        $bitMask = pow(2, $deselectionBits) - 1;
      }
      elseif ($deselectFlags & 3)
      {
        $nextByte = $this->streamReader->readByte();
        $deselectionBits = ($deselectFlags & 0xFC) | ($nextByte & 3);
        while ($deselectionBits > 0) 
        {
          $nextByte = $this->streamReader->readByte();
          $deselectionBits--;
        }
        $bitMask = 3;
      }
      else
      {
        $bitMask = 3;
        $nextByte = $deselectFlags;
      }
      $uType = array();
      $unitIDs = array();
      $prevByte = $nextByte;
      $nextByte = $this->streamReader->readByte();
      if ($bitMask > 0)
      {
        $numUnitTypeIDs = ($prevByte & (0xFF - $bitMask)) | ($nextByte & $bitMask);
      }
      else
      {
        $numUnitTypeIDs = $nextByte;
      }
      for ($i = 0;$i < $numUnitTypeIDs;$i++) 
      {
        $unitTypeID = 0;
        for ($j = 0;$j < 3;$j++) 
        {
          $prevByte = $nextByte;
          $nextByte = $this->streamReader->readByte();
          if ($bitMask > 0)
          {
            $byte = ($prevByte & (0xFF - $bitMask)) | ($nextByte & $bitMask);
          }
          else
          {
            $byte = $nextByte;
          }
          $unitTypeID = $byte << ((2 - $j )* 8) | $unitTypeID;
        }
        $prevByte = $nextByte;
        $nextByte = $this->streamReader->readByte();
        if ($bitMask > 0)
        {
          $unitTypeCount = ($prevByte & (0xFF - $bitMask)) | ($nextByte & $bitMask);
        }
        else
        {
          $unitTypeCount = $nextByte;
        }
        $uType[$i + 1]['count'] = $unitTypeCount;
        $uType[$i + 1]['id'] = $unitTypeID;
      }
      
      $prevByte = $nextByte;
      $nextByte = $this->streamReader->readByte();
      if ($bitMask > 0)
      {
        $numUnits = ($prevByte & (0xFF - $bitMask)) | ($nextByte & $bitMask);
      }
      else
      {
        $numUnits = $nextByte;
      }

      for ($i = 0;$i < $numUnits;$i++) {
        $unitID = 0;
        for ($j = 0;$j < 4;$j++) {
          $prevByte = $nextByte;
          $nextByte = $this->streamReader->readByte();
          if ($bitMask > 0)
          {
            $byte = ($prevByte & (0xFF - $bitMask)) | ($nextByte & $bitMask);
          }
          else
          {
            $byte = $nextByte;
          } 
          if ($j < 2)
          {
            $unitID = ($byte << ((1 - $j )* 8)) | $unitID;
          }
        }
        $unitIDs[] = $unitID;
      }
    }
    else
    {
      
    }
  }
  
  private function decodeHotkeyEvent($eventGroup, $eventCode, $playerId, $timestamp)
  {
    $firstByte = $this->streamReader->readByte();
    if ($firstByte > 3)
    {
      if ($this->streamReader->available() > 0)
      {
        $this->streamReader->mark();
        $byte2 = $this->streamReader->readByte();
        $this->streamReader->reset();
      }
      if ($this->isInBuild(self::BUILD_16561))
      {
        if ($firstByte & 8)
        {
          $skipByte = $this->streamReader->readByte() & 0x0F;
          $this->streamReader->skip($skipByte);
          return;
        }
        $extraBytes = floor($firstByte / 8);
        $this->streamReader->skip($extraBytes);
        $tmp = $this->streamReader->readByte();
        if ($extraBytes == 0)
        {
          if (($byte2 & 7) > 4)
          {
            $this->streamReader->skip(1);
          }
          if ($byte2 & 8)
          {
            $this->streamReader->skip(1);
          }
        }
        else
        {
          if (($firstByte & 4) && ($byte2 & 7) > 4)
          {
            $this->streamReader->skip(1);
          }
          if (($firstByte & 4) && ($byte2 & 8))
          {
            $this->streamReader->skip(1);
          }
        }
      }
      else
      {
        
      }
    }
  }
  
  private function decodeResourceTransferEvent($eventGroup, $eventCode, $playerId, $timestamp)
  {
    
  }
  
  private function decodeUnknowEventCamera0x08($eventGroup, $eventCode, $playerId, $timestamp)
  {
    
  }
  
  private function decodeUnknowEventCamera0xX1($eventGroup, $eventCode, $playerId, $timestamp)
  {
    $this->streamReader->skip(3);
    $flag = $this->streamReader->readByte();
    if (($flag & 0x10) != 0)
    {
      $this->streamReader->skip(1);
      $flag = $this->streamReader->readByte();
    }

    if (($flag & 0x20) != 0)
    {
      $this->streamReader->skip(1);
      $flag = $this->streamReader->readByte();
    }

    if (($flag & 0x40) != 0)
    {
      $this->streamReader->skip(2);
    }
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