<?php

namespace BaseTest\Base\Base;

use \PHPUnit_Framework_TestCase;
use Dms\Coding\Zlib\Zlib;

class ZlibTest extends PHPUnit_Framework_TestCase
{
    public function testCanEncode()
    {
    	$in = 'stirng test';
    	$in_encoded = gzcompress($in);
    	
        $coding = new Zlib();
        $out_encoded = $coding->encode($in);
        
        $this->assertEquals($in_encoded,$out_encoded);
    }
    
    public function testCanDecode()
    {
    	$in = 'stirng test';
    	$in_encoded = gzcompress($in);
    	
    	$coding = new Zlib();
    	$out_decoded = $coding->decode($in_encoded);
    
    	$this->assertEquals($in,$out_decoded);
    }
    
    public function testCanGetNameCoding()
    {
    	$coding = new Zlib();
    	$name = $coding->getCoding();
    
    	$this->assertEquals($name,Zlib::CODING_ZLIB_STR);
    }
}
