<?php
/*
Copyright 2002-2012 MarkLogic Corporation.  All Rights Reserved.

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

     http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.
*/
namespace MarkLogic\MLPHP\Test;

use MarkLogic\MLPHP;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

/**
 * @package MLPHP\Test
 * @author Eric Bloch <eric.bloch@gmail.com>
 * @author Mike Wooldridge <mike.wooldridge@marklogic.com>
 */
abstract class TestBase extends \PHPUnit_Framework_TestCase
{
    protected static $logger;
    private static $api;
    protected static $client;
    private static $config = array(
        'host' => '127.0.0.1',
        'port' => 8234,
        'db' => 'mlphp-test',
        'user' => 'admin',
        'pass' => 'admin',
        'mgmt_port' => 8002
    );

    // Runs before each test class
    // https://phpunit.de/manual/current/en/fixtures.html#fixtures.variations
    public static function setUpBeforeClass()
    {
        // Create a logger for tests
        self::$logger = new Logger('test');
        self::$logger->pushHandler(new StreamHandler('php://stderr', Logger::DEBUG));

        // Create a REST API for tests
        self::$api = new MLPHP\RESTClient(
            self::$config['host'],
            self::$config['mgmt_port'],
            '',
            'v1',
            self::$config['user'],
            self::$config['pass'],
            'digest',
            self::$logger
        );

        $params = array();
        $headers = array('Content-type' => 'application/json');
        $body = '
            {
                "rest-api": {
                    "name": "test-mlphp-rest-api",
                    "database": "' . self::$config['db'] . '",
                    "modules-database": "' . self::$config['db'] . '-modules",
                    "port": "' . self::$config['port'] . '"
                }
            }
        ';
        $request = new MLPHP\RESTRequest('POST', 'rest-apis', array(), $body, $headers);
        self::$api->post($request); // POST to set up REST API

        // Create a REST client for tests
        self::$client = new MLPHP\RESTClient(
            self::$config['host'],
            self::$config['port'],
            '',
            'v1',
            self::$config['user'],
            self::$config['pass'],
            'digest',
            self::$logger
        );

        // Clear the REST API database
        $db = new MLPHP\Database(self::$client);
        $db->clear();

    }

    // Runs after each test class
    // https://phpunit.de/manual/current/en/fixtures.html#fixtures.variations
    public static function tearDownAfterClass()
    {
        $params = array();
        $body = null;
        $headers = array();
        $request = new MLPHP\RESTRequest(
            'DELETE', 'rest-apis/test-mlphp-rest-api', $params, $body, $headers
        );

        self::$api->delete($request);

        // Wait for server reboot
        sleep(5); // increase time if "no connection" exceptions
    }

}

