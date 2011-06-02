<?php
/*
 * This file is part of the Sc2ReplayParser.
 * (c) 2011 joel.wurtz@gmail.com
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sc2ReplayParser\Parser;

use Sc2ReplayParser\IO\StreamReader;

/**
 * Parser use to parse file.
 *
 * @package    Sc2ReplayParser
 * @subpackage Parser
 * @author     joel.wurtz@gmail.com
 * @version    1.0.0
 */
abstract class Parser
{
  const BUILD_17326 = 17326;
  const BUILD_LAST = self::BUILD_17326;
  
  protected $streamReader;
  protected $build;
  
  public function __construct(StreamReader $streamReader, $build = self::BUILD_LAST)
  {
    $this->build = $build;
    $this->streamReader = $streamReader;
  }
  
  abstract public function parse();
}