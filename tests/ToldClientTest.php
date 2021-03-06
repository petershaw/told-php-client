<?php

namespace petershaw\told;

require './src/petershaw/told/ToldClient.php';

/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.1 on 2013-10-16 at 23:24:02.
 */
class ToldClientTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var ToldClient
     */
    protected $object;

    /**
     * @covers petershaw\told\ToldClient::getHost
     * @todo   Implement testGetHost().
     */
    public function testConfigureObject() {
        $conf = Array();
        $conf['host'] = "test://example.com";
        $this->object = new ToldClient($conf);

        $this->assertEquals($conf['host'], $this->object->getHost());
    }

    public function testEmptyDefaultTags() {
        $conf = Array();
        $conf['host'] = "test://example.com";
        $this->object = new ToldClient($conf);
        $msg = $this->object->tell("test");
        $this->assertEquals(0, count($msg->tags));
    }

    public function testFilledDefaultTags() {
        $conf = Array();
        $conf['host'] = "test://example.com";
        $conf['defaulttags'] = Array("php", "unittest");
        $this->object = new ToldClient($conf);
        $msg = $this->object->tell("test");
        $this->assertEquals(2, count($msg->tags));
        $this->assertTrue(in_array('php', $msg->tags));
    }

    public function testStringTags() {
        $this->object = new ToldClient();
        $this->object->setHost("test://example.com");
        $msg = $this->object->tell("test", "type", "Honigkuchen");
        $this->assertEquals(1, count($msg->tags));
        $this->assertTrue(in_array('Honigkuchen', $msg->tags));
    }

    public function testArrayTags() {
        $this->object = new ToldClient();
        $this->object->setHost("test://example.com");
        $msg = $this->object->tell("test", "type", Array("Honigkuchen", "Zuckerschlecken"));
        $this->assertEquals(2, count($msg->tags));
        $this->assertTrue(in_array('Honigkuchen', $msg->tags));
        $this->assertTrue(in_array('Zuckerschlecken', $msg->tags));
    }

    public function testOverwriteDefaultTags() {
        $conf = Array();
        $conf['host'] = "test://example.com";
        $conf['defaultags'] = Array("php", "unittest");
        $this->object = new ToldClient($conf);
        $msg = $this->object->tell("test", "type", Array("foo"));
        $this->assertEquals(1, count($msg->tags));
        $this->assertTrue(in_array('foo', $msg->tags));
    }

    public function testFilledDefaultType() {
        $conf = Array();
        $conf['host'] = "test://example.com";
        $conf['type'] = "unitttest";
        $this->object = new ToldClient($conf);
        $msg = $this->object->tell("test");
        $this->assertEquals("unitttest", $msg->etype);
    }
    
    public function testSetType() {
        $conf = Array();
        $conf['host'] = "test://example.com";
        $this->object = new ToldClient($conf);
        $this->object->setType("unitttest");
        $msg = $this->object->tell("test");
        $this->assertEquals("unitttest", $msg->etype);
    }

    public function testSimpleMessageString() {
        $this->object = new ToldClient();
        $this->object->setHost("test://example.com");
        $msg = $this->object->tell("I am a string.");
        $this->assertTrue(is_object($msg->message) || is_string($msg->message));
        //$this->assertEquals("I am a string.", $msg->message->said);
    }

     /**
     * @expectedException Exception
     */
    public function testThowExeptionOnMissingHost() {
         $this->object = new ToldClient();
         $this->object->tell("fail without host.");
    }
    
    /**
      public function testRealSend(){
      $conf['host'] = "http://localhost:3000";
      $conf['debug'] = true;

      $this->object = new ToldClient($conf);

      $msg = $this->object->tell("I am a string.", "unitttest", "php,unitttest,client","0.0.1");

      }
     */
}
