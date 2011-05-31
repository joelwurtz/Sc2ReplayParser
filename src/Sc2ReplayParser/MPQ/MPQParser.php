<?php
/*
 * This file is part of the Sc2ReplayParser.
 * (c) 2011 joel.wurtz@gmail.com
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * MPQParser use to parse mpqfile.
 *
 * @package    Sc2ReplayParser
 * @subpackage MPQ
 * @author     joel.wurtz@gmail.com
 * @version    1.0.0
 */
class MPQParser
{
  private $streamReader = null;
  private $version = null;
  
  public function __construct($file)
  {
    $this->streamReader = new MPQStreamReader(new FileInputStream($file));
    
    $this->checkFile();
  }
  
  private function checkFile()
  {
    $info = $this->streamReader->readBytes(4);
    if ($info[1] != 0x4D || $info[2] != 0x50 || $info[3] != 0x51 || $info[4] != 0x1B)
    {
      throw new Exception("File is not a SC2Replay");
    }
  }
  
  public function extract()
  {
    //First parsing user data block
    $userDataMaxSize = $this->streamReader->readUInt32();
    $headerOffset = $this->streamReader->readUInt32();
    $userDataSize = $this->streamReader->readUInt32();
    
    //Mark current offset as start of user data
    $this->streamReader->mark("userDataStart");
    $userData = $this->streamReader->readSerializedData();
    
    $this->streamReader->offset($headerOffset);
    
    
    
    print_r($userData);
  }
}