<?php
/*
 * This file is part of the Sc2ReplayParser.
 * (c) 2011 joel.wurtz@gmail.com
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sc2ReplayParser\Entity;

/**
 * Player entity.
 *
 * @package    Sc2ReplayParser
 * @subpackage Entity
 * @author     joel.wurtz@gmail.com
 * @version    1.0.0
 */
class Player
{
  const TYPE_HUMAN = "Humn";
  const TYPE_COMPUTER = "Comp";
  
  const RACE_ZERG = "Zerg";
  const RACE_PROTOSS = "Prot";
  const RACE_TERRAN = "Terr";
  const RACE_RANDOM = "RAND";
  
  private $id;
  private $name;
  private $type;
  private $race_choose;
  private $race_play;
  private $color_name;
  private $color_value;
  private $handicap;
  private $difficulty;
  private $team;
  private $position;
  
  public function __construct($id)
  {
    $this->id = $id;
  }
  
  public function getId()
  {
    return $this->id;
  }
  
  public function setName($name)
  {
    
  }
  
  public function setType($type)
  {
    $this->type = $type;
  }
  
  /**
   * Set race of player before starting the game
   * 
   * @param string $race_choose : Race choose before starting the game
   */
  public function setRaceChoose($race_choose)
  {
    $this->race_choose = $race_choose;
  }

  /**
   * Set race of player during the game
   * 
   * @param string $race_play : Race play during the game
   */
  public function setRacePlay($race_play)
  {
    $this->race_play = $race_play;
  }

  /**
   * Set player's color name
   * 
   * @param string $color_name : Color's name of player choose before starting the game
   */
  public function setColorName($color_name)
  {
    $this->color_name = $color_name;
  }
  
  /**
   * Set player's color value representing by an array (alpha, red, green, blue)
   * 
   * @param array $color_value : Color's value pf player
   */
  public function setColorValue($color_value)
  {
    $this->color_value = $color_value;
  }

  /**
   * Set player's handicap => 100 = no handicap
   * 
   * @param int $handicap : Player's handicap in percent
   */
  public function setHandicap($handicap)
  {
    $this->handicap = $handicap;
  }

  /**
   * Player's difficulty
   * 
   * Only useful when player is a computer
   * 
   * @param string $difficulty : Player's difficulty
   */
  public function setDifficulty($difficulty)
  {
    $this->difficulty = $difficulty;
  }

  /**
   * Set player's team
   * 
   * @param int $team : Player's team
   */
  public function setTeam($team)
  {
    $this->team = $team;
  }

  /**
   * Set player's position in team
   * 
   * @param int $position : Player's position in team
   */
  public function setPosition($position)
  {
    $this->position = $position;
  }

  
}