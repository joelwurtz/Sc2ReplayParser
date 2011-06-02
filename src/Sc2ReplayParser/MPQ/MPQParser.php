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
  private static $cryptTable;

  private $streamReader = null;
  private $version = null;

  private $hashTableSize;
  private $headerOffset;
  private $hashTable;
  private $blockTable;
  private $sectorSize;

  const MPQ_HASH_TABLE_OFFSET = 0;
  const MPQ_HASH_NAME_A = 1;
  const MPQ_HASH_NAME_B = 2;
  const MPQ_HASH_FILE_KEY = 3;
  const MPQ_HASH_ENTRY_EMPTY = -1;
  const MPQ_HASH_ENTRY_DELETED = -2;

  const MPQ_FLAG_FILE = 0x80000000;
  const MPQ_FLAG_CHECKSUM = 0x04000000;
  const MPQ_FLAG_DELETED = 0x02000000;
  const MPQ_FLAG_SINGLEUNIT = 0x01000000;
  const MPQ_FLAG_H_ENCRYPTED = 0x00020000;
  const MPQ_FLAG_ENCRYPTED = 0x00010000;
  const MPQ_FLAG_COMPRESSED = 0x00000200;
  const MPQ_FLAG_IMPLODED = 0x00000100;

  const MPQ_COMPRESS_DEFLATE = 0x02;
  const MPQ_COMPRESS_BZIP2 = 0x10;

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
    $this->headerOffset = $headerOffset = $this->streamReader->readUInt32();
    $userDataSize = $this->streamReader->readUInt32();

    //Mark current offset as start of user data
    $this->streamReader->mark("userDataStart");
    $userData = $this->streamReader->readSerializedData();

    $this->streamReader->offset($headerOffset);

    //Header of mpq
    $this->streamReader->readBytes(4);

    $headerSize = $this->streamReader->readUInt32();
    $archiveSize = $this->streamReader->readUInt32();
    $formatVersion = $this->streamReader->readUInt16();
    $sectorSizeShift = $this->streamReader->readByte();

    $this->streamReader->skip(1);

    $hashTableOffset = $this->streamReader->readUInt32() + $headerOffset;
    $blockTableOffset = $this->streamReader->readUInt32() + $headerOffset;
    $this->hashTableSize = $hashTableEntries = $this->streamReader->readUInt32();
    $blockTableEntries = $this->streamReader->readUInt32();

    $this->sectorSize = 512 * pow(2, $sectorSizeShift);

    //Decode hash table
    $this->streamReader->offset($hashTableOffset);
    $hashSize = $hashTableEntries * 4;
    $hashArray = array();

    while ($hashSize > 0)
    {
      $hashArray[] = $this->streamReader->readUInt32();
      $hashSize--;
    }

    $this->hashTable = $this->decryptData($hashArray,$this->hashString("(hash table)", self::MPQ_HASH_FILE_KEY));

    //Decode block table
    $this->streamReader->offset($blockTableOffset);
    $blockSize = $blockTableEntries * 4;
    $blockArray = array();

    while ($blockSize > 0)
    {
      $blockArray[] = $this->streamReader->readUInt32();
      $blockSize--;
    }

    $this->blockTable = $this->decryptData($blockArray,$this->hashString("(block table)", self::MPQ_HASH_FILE_KEY));

  }

  public function getInputStream($filename)
  {
    $hashA = $this->hashString($filename, self::MPQ_HASH_NAME_A);
    $hashB = $this->hashString($filename, self::MPQ_HASH_NAME_B);

    $hashStart = $this->hashString($filename, self::MPQ_HASH_TABLE_OFFSET) & ($this->hashTableSize - 1);

    $keyFile = array_search($hashA, $this->hashTable);

    if ($keyFile === false || ($keyFile % 4) != 0 || $this->hashTable[$keyFile + 1] != $hashB)
    {
      throw new Exception(sprintf("File %s not found in mpq archive", $filename));
    }

    $blockIndex = $this->hashTable[$keyFile + 3] * 4;

    $dataOffset = $this->blockTable[$blockIndex] + $this->headerOffset;
    $compressedSize = $this->blockTable[$blockIndex + 1];
    $fileSize = $this->blockTable[$blockIndex + 2];
    $fileFlags = $this->blockTable[$blockIndex + 3];

    if (!$this->hasFlag($fileFlags, self::MPQ_FLAG_FILE))
    {
      throw new Exception(sprintf("File %s does not exist", $filename));
    }

    $this->streamReader->offset($dataOffset);

    $sectorsOffset = array();

    //If file is not in an single unit then it have sector
    if (!$this->hasFlag($fileFlags, self::MPQ_FLAG_SINGLEUNIT))
    {
      //A file with length A And a Sector With Length B will have A % B + 1 Sector And A sector which contains size of total for easy streaming
      $nbSector = ($fileSize % $this->sectorSize) + 1 + 1;

      while ($nbSector > 0)
      {
        $sectorsOffset[] = $this->streamReader->readUInt32();
        $compressedSize -= 4;
      }

      //If checksum flag is on an additional sector is present
      if ($this->hasFlag($fileFlags, self::MPQ_FLAG_CHECKSUM))
      {
        $sectorsOffset[] = $this->streamReader->readUInt32();
        $compressedSize -= 4;
      }
    }
    else
    {
      $nbSector = 2;
      $sectorsOffset[] = 0;
      $sectorsOffset[] = $compressedSize;
    }

    $stringStream = "";

    for ($i=0; $i<($nbSector - 1); $i++)
    {
      $compressedSectorSize = $sectorsOffset[$i + 1] - $sectorsOffset[$i];
      $this->streamReader->offset($dataOffset + $sectorsOffset[$i]);
      //If compressed data are not equal to sector and flag compressed data flag then we need decompressed (if compressed size is equal or superior sector is not compress even flag is set)
      if ($compressedSectorSize != $this->sectorSize && $this->hasFlag($fileFlags, self::MPQ_FLAG_COMPRESSED))
      {
        //Compressed
        $compressionMask = $this->streamReader->readByte();
        $compressedData = $this->streamReader->readString($compressedSectorSize - 1);

        //@TODO ADD Compression mode
        switch ($compressionMask)
        {
        case self::MPQ_COMPRESS_BZIP2:
          $unCompressedData = $this->decompressBZip2($compressedData);
          break;

        case self::MPQ_COMPRESS_DEFLATE:
          $unCompressedData = $this->decompressDeflate($compressedData);
          break;

        default:
          throw new Exception(sprintf("Can't decompress file %s, compression mode 0x%02X not supported", $filename, $compressionMask));
          break;
        }

        $stringStream .= $unCompressedData;
      }
      else
      {
        //UnCompressed
        $stringStream .= $this->streamReader->readString($compressedSectorSize);
      }
    }

    //@TODO CHECKSUM VERIF

    return new LittleEndianStreamReader(new StringInputStream($stringStream));
  }

  public static function getCryptValue($key)
  {
    if (self::$cryptTable === null)
    {
      self::$cryptTable = array();
      $seed = 0x00100001;
      $index1 = 0;
      $index2 = 0;

      for ($index1 = 0; $index1 < 0x100; $index1++)
      {
        for ($index2 = $index1, $i = 0; $i < 5; $i++, $index2 += 0x100)
        {
          $seed = (Math::uPlus($seed * 125, 3)) % 0x2AAAAB;
          $temp1 = ($seed & 0xFFFF) << 0x10;

          $seed = (Math::uPlus($seed * 125, 3)) % 0x2AAAAB;
          $temp2 = ($seed & 0xFFFF);

          self::$cryptTable[$index2] = ($temp1 | $temp2);
        }
      }
    }

    return self::$cryptTable[$key];
  }

  public function decryptData($data, $key)
  {
    $seed = ((0xEEEE << 16) | 0xEEEE);
    $datalen = count($data);

    for ($i = 0;$i < $datalen;$i++)
    {
      $seed = Math::uPlus($seed,self::getCryptValue(0x400 + ($key & 0xFF)));
      $ch = $data[$i] ^ (Math::uPlus($key, $seed));

      $key = (Math::uPlus(((~$key) << 0x15), 0x11111111)) | (Math::rShift($key, 0x0B));
      $seed = Math::uPlus(Math::uPlus(Math::uPlus($ch, $seed), ($seed << 5)), 3);
      $data[$i] = $ch & ((0xFFFF << 16) | 0xFFFF);
    }

    return $data;
  }

  public function hashString($string, $hashType)
  {
    $seed1 = 0x7FED7FED;
    $seed2 = ((0xEEEE << 16) | 0xEEEE);
    $strLen = strlen($string);

    for ($i = 0; $i < $strLen; $i++)
    {
      $next = ord(strtoupper(substr($string, $i, 1)));

      $seed1 = self::getCryptValue(($hashType << 8) + $next) ^ (Math::uPlus($seed1, $seed2));
      $seed2 = Math::uPlus(Math::uPlus(Math::uPlus(Math::uPlus($next, $seed1), $seed2), $seed2 << 5), 3);
    }

    return $seed1;
  }

  public function getFileList()
  {
    $fileListStream = $this->getInputStream("(listfile)");
    $arrayListFile = array();

    while ($fileListStream->available() > 0)
    {
      $arrayListFile[] = trim($fileListStream->readLine());
    }

    return $arrayListFile;
  }

  private function decompressDeflate($data)
  {
    if (!function_exists("gzinflate"))
    {
      throw new Exception("You need to install gzlib on your system");
    }

    return gzinflate(substr($data,2,strlen($data) - 2));
  }

  private function decompressBZip2($data)
  {
    if (!function_exists("bzdecompress"))
    {
      throw new Exception("You need to install bzip2 lib on your system");
    }

    $tmp = bzdecompress($data);

    if (is_numeric($tmp))
    {
      throw new Exception(sprintf("Bzip2 returned error code: %d",$tmp));
    }

    return $tmp;
  }

  private function hasFlag($data, $flag)
  {
    return (boolean)($data & $flag);
  }
}