<?php
/*
 * This file is part of the Sc2ReplayParser.
 * (c) 2011 joel.wurtz@gmail.com
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sc2ReplayParser\IO;

/**
 * MPQStreamReader use to read data from mpq file.
 *
 * @package    Sc2ReplayParser
 * @subpackage IO
 * @author     joel.wurtz@gmail.com
 * @version    1.0.0
 */
class MPQStreamReader extends LittleEndianStreamReader
{
  const SERIAL_TYPE_BINARY = 0x02;
  const SERIAL_TYPE_ARRAY_NUM = 0x04;
  const SERIAL_TYPE_ARRAY_ASSOC = 0x05;
  const SERIAL_TYPE_INT8 = 0x06;
  const SERIAL_TYPE_INT32 = 0x07;
  const SERIAL_TYPE_VLF_NUMBER = 0x09;

  public function readSerializedData()
  {
    $byteDataType = $this->readByte();

    switch($byteDataType)
    {
    case self::SERIAL_TYPE_BINARY:
      $byteLen = $this->readVLFNumber();
      return $this->readString($byteLen);
      break;

    case self::SERIAL_TYPE_ARRAY_NUM:
      $array = array();
      $this->skip(2);
      $countElem = $this->readVLFNumber();
      while ($countElem > 0)
      {
        $array[] = $this->readSerializedData();
        $countElem--;
      }
      return $array;
      break;

    case self::SERIAL_TYPE_ARRAY_ASSOC:
      $array = array();
      $countElem = $this->readVLFNumber();
      while ($countElem > 0)
      {
        $index = $this->readVLFNumber();
        $array[$index] = $this->readSerializedData();
        $countElem--;
      }
      return $array;
      break;

    case self::SERIAL_TYPE_INT8:
      return $this->readByte();
      break;

    case self::SERIAL_TYPE_INT32:
      return $this->readUInt32();
      break;

    case self::SERIAL_TYPE_VLF_NUMBER:
      return $this->readVLFNumber();
      break;

    default:
      throw new Exception(sprintf("Undefined data type 0x%X", $byteDataType));
      break;
    }
  }

  public function readVLFNumber()
  {
    $multi = 1;
    $bytes = 0;
    $number = 0;
    $first = true;
    $byte = 0;

    while ($first || $byte >= 0x80)
    {
      $byte = $this->readByte();
      $number += ($byte & 0x7F) * pow(2, $bytes * 7);

      if ($first)
      {
        $first = false;

        if ($byte & 0x01)
        {
          $multi = -1;
          $number--;
        }
      }

      $bytes++;
    }

    $number *= $multi;
		$number /= 2;

		return $number;
  }
  
  public function readTimestamp()
  {
    $firstByte = $this->readByte();
    $bytesLeft = $firstByte & 0x03;
    $timestamp = $firstByte >> $bytesLeft;
    while($bytesLeft > 0)
    {
      $byte = $this->readByte();
      $timestamp = $timestamp << 8;
      $timestamp += $byte;
      $bytesLeft--;
    }
    
    return $timestamp;
  }
}