<?php


namespace Tests\Unit;


use App\Lib\Request;
use PHPUnit\Framework\TestCase;

class RequestTest extends TestCase
{
    public function testGet()
    {
        $req = new Request(['first' => 1]);
        $this->assertEquals(1, $req->get('first'));
        $this->assertNull($req->get('seconds'));
    }

    public function testPOST()
    {
        $req = new Request([] , ['first' => 1]);
        $this->assertEquals(1, $req->post('first'));
        $this->assertNull($req->get('seconds'));
    }

    public function testServer()
    {
        $req = new Request([] , [] , ['first' => 1]);
        $this->assertEquals(1, $req->server('first'));
        $this->assertNull($req->get('seconds'));
    }


}