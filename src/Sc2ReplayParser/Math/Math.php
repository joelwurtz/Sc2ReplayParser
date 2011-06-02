<?php
/*
 * This file is part of the Sc2ReplayParser.
 * (c) 2011 joel.wurtz@gmail.com
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sc2ReplayParser\Math;

/**
 * Math use to define some math functions.
 *
 * @package    Sc2ReplayParser
 * @subpackage Math
 * @author     joel.wurtz@gmail.com
 * @version    1.0.0
 */
class Math
{
  public static function uPlus($o1, $o2)
  {
    $o1h = ($o1 >> 16) & 0xFFFF;
  	$o1l = $o1 & 0xFFFF;

  	$o2h = ($o2 >> 16) & 0xFFFF;
  	$o2l = $o2 & 0xFFFF;	

  	$ol = $o1l + $o2l;
  	$oh = $o1h + $o2h;
  	if ($ol > 0xFFFF) { $oh += (($ol >> 16) & 0xFFFF); }
  	return ((($oh << 16) & (0xFFFF << 16)) | ($ol & 0xFFFF));
  }
  
  public static function rShift($num,$bits) 
  {
  	return (($num >> 1) & 0x7FFFFFFF) >> ($bits - 1);
  }
}