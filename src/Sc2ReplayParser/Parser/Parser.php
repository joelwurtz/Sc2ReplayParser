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
use Monolog\Logger;
use Sc2ReplayParser\Entity\Replay;

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
  //1.3.4
  const BUILD_18701 = 18701;
  //1.3.3
  const BUILD_18317 = 18317;
  //1.2.?
  const BUILD_17326 = 17326;
  //1.1
  const BUILD_16561 = 16561;
  const BUILD_LAST = self::BUILD_18701;
  
  protected $streamReader;
  protected $build;
  protected $replay;
  
  private $log;
  
  public function __construct(Replay $replay, StreamReader $streamReader, $build = self::BUILD_LAST, $log = null)
  {
    $this->build = $build;
    $this->replay = $replay;
    $this->streamReader = $streamReader;
    
    if ($log === null || !($log instanceof Logger))
    {
      $log = new Logger('null');
    }
    $this->log = $log;
  }
  
  public function getLog()
  {
    return $this->log;
  }
  
  abstract public function parse();
  
  protected function isInBuild($minBuild, $maxBuild = self::BUILD_LAST)
  {
    return (boolean)($this->build >= $minBuild && $this->build <= $maxBuild);
  }
}