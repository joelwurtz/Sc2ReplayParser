<?php
/*
 * This file is part of the Sc2ReplayParser.
 * (c) 2011 joel.wurtz@gmail.com
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sc2ReplayParser\Entity;

use Sc2ReplayParser\MPQ\MPQParser;
use Sc2ReplayParser\Parser\ReplayAttributesEvents;
use Sc2ReplayParser\Parser\ReplayGameEvents;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

/**
 * Replay use to store replay data.
 *
 * @package    Sc2ReplayParser
 * @subpackage Entity
 * @author     joel.wurtz@gmail.com
 * @version    1.0.0
 */
class Replay
{
  private $players = array();
  private $game = null;
  
  public function __construct($file)
  {
    $this->game = new Game();
    
    $mpqArchive = new MPQParser($file);
    $mpqArchive->extract();
    
    $log = new Logger('Replay ['.$file.']');
    $log->pushHandler(new StreamHandler('debug.log', Logger::WARNING));

    $is = $mpqArchive->getInputStream('replay.details');
    $data = $is->readSerializedData();

    $rae = new ReplayAttributesEvents($this, $mpqArchive->getInputStream('replay.attributes.events'), $mpqArchive->getBuild(), $log);
    $rae->parse();
    
    $rge = new ReplayGameEvents($this, $mpqArchive->getInputStream('replay.game.events'), $mpqArchive->getBuild(), $log);
    $rge->parse();
  }
  
  public function addPlayer($player)
  {
    $this->players[$player->getId()] = $player;
  }
  
  public function getPlayer($id)
  {
    if (!isset($this->players[$id]))
    {
      $this->players[$id] = new Player($id);
    }
    return $this->players[$id];
  }
  
  public function getGame()
  {
    return $this->game;
  }
  
  
}