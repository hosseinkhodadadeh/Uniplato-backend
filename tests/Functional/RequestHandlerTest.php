<?php


namespace Tests\Functional;


use App\Lib\RequestHandler;
use App\Lib\Response;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Request;
use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;

class RequestHandlerTest extends TestCase
{

    private RequestHandler$handler;
    private static $server;
    private Client $client;

    protected function setUp(): void
    {
        parent::setUp();
        $this->handler = new RequestHandler();
        $this->client = new Client([
            'base_uri' => 'http://localhost:8080/index.php/',
            'timeout' => 2.0,
            'http_errors' => false
        ]);

        getDb()->exec('truncate category ');

        getDb()->exec('DROP TABLE IF EXISTS `category`');
        getDb()->exec('CREATE TABLE `category`  (
    `latitude` int NULL DEFAULT NULL,
  `longitude` int NULL DEFAULT NULL,
  `category` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `counter` int NULL DEFAULT NULL,
  `id` int NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact');

        getDb()->exec('INSERT INTO `category` VALUES (3, 1, \'77\', 250, 1)
                                    , (3, 1, \'76\', 160, 2)
                                    , (3, 1, \'75\', 200, 3)');

    }

    public static function setUpBeforeClass(): void
    {
        define('ROOT', __DIR__ . '/../..');
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {

            $spec = [
                1 => ['file', 'null', 'w'],
                2 => ['file', 'null', 'w'],
            ];
        } else {

            $spec = [
                1 => ['file', '/dev/null', 'w'],
                2 => ['file', '/dev/null', 'w'],
            ];
        }

        if (!self::$server = @proc_open('php -S localhost:8080', $spec, $pipes, dirname(dirname(__DIR__)))) {

            self::markTestSkipped('PHP server unable to start.');
        }

    }

    public static function tearDownAfterClass(): void
    {
        if (self::$server) {
            proc_terminate(self::$server);
            proc_close(self::$server);
        }
    }

    public function testNotFound()
    {
        $response = $this->client->request('GET', 'notfound');
        $this->assertEquals(Response::HTTP_STATUS_NOT_Found, $response->getStatusCode());
    }

    public function testCategoryList()
    {
        $response = $this->client->request('GET', 'categories/list');
        $this->assertEquals(Response::HTTP_STATUS_OK, $response->getStatusCode());
        $actualString = $response->getBody()->getContents();
        $this->assertJson($actualString);

        $arrayData = json_decode($actualString, true);
        $this->assertIsArray($arrayData);
        $this->assertArrayHasKey('categories', $arrayData);
        $this->assertArrayHasKey('status', $arrayData);
        $this->assertIsArray($arrayData['categories']);
        $this->assertEquals(3, count($arrayData['categories']));
        $this->assertArrayHasKey('id', $arrayData['categories'][0]);
        $this->assertArrayHasKey('category', $arrayData['categories'][0]);
        $this->assertArrayHasKey('counter', $arrayData['categories'][0]);

    }

    public function testCategoryUpdate()
    {
        $testData = array(
            'id' => 2,
            'counter' => 165
        );

        $response = $this->client->request('POST', 'categories/save', [
            'form_params' => $testData
        ]);
        $actualData = getDb()->select(
            'select counter from category where id=?',
            [$testData['id']]
        );


        $this->assertEquals(Response::HTTP_STATUS_OK, $response->getStatusCode());
        $this->assertEquals($testData['counter'], $actualData[0]['counter']);


        $actualString = $response->getBody()->getContents();
        $this->assertJson($actualString);

        $arrayData = json_decode($actualString, true);
        $this->assertIsArray($arrayData);

        $this->assertArrayHasKey('status', $arrayData);
        $this->assertEquals(1, count($arrayData));


    }

}