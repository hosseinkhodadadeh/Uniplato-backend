<?php


namespace Tests\Unit;


use App\Lib\Response;
use PHPUnit\Framework\TestCase;

class ResponseTest extends TestCase
{
    public function testResponse()
    {
        $notFound = Response::createNotFound();
        $this->assertObjectHasAttribute('headers' , $notFound);
        $this->assertObjectHasAttribute('statusCode' , $notFound);
        $this->assertObjectHasAttribute('headers' , $notFound);
    }

}