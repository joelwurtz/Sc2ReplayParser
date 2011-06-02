<?php
/*
 * This file is part of the Sc2ReplayParser.
 * (c) 2011 joel.wurtz@gmail.com
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * LittleEndianStreamReader use to read data from an input stream by using little endian order.
 *
 * @package    Sc2ReplayParser
 * @subpackage custom
 * @author     joel.wurtz@gmail.com
 * @version    1.0.0
 */
class LittleEndianStreamReader extends StreamReader
{
  public function readUInt16()
  {
    $bytes = $this->stream->read(2);
    $tmp = unpack("v", $bytes);

    return $tmp[1];
  }

  public function readUInt32()
  {
    $bytes = $this->stream->read(4);
    $tmp = unpack("V", $bytes);

    return $tmp[1];
  }
}