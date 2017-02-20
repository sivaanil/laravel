<?php
namespace Tests\functional;

use Illuminate\Support\Facades\DB;
use \PDO;
use \Illuminate\Database\Connection;
use \GuzzleHttp\Client;

abstract class AbstractDatabaseTestCase extends \PHPUnit_Extensions_Database_TestCase
{
	const DATABASE_CONNECTION_NAME = 'mysql_testing';
	protected $db = null;
	private $ip = null;
	
	/**
	 * getConnection
	 */
	protected function getConnection()
	{
		// load from testing database configuration
		if ($this->db == null) {
			$this->db = DB::connection(self::DATABASE_CONNECTION_NAME);
			
			$this->db->enableQueryLog();
		}

		return $this->db;
	}
	
    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        putenv('DB_DEFAULT=' . self::DATABASE_CONNECTION_NAME);
        putenv('CACHE_DRIVER=file');
        
        $app = require __DIR__.'/../../bootstrap/app.php';
        $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
        return $app;
    }
    
    /**
     * loadFixture
     */
    public function loadFixture($fixtureName)
    {
    	$className = $fixtureName . 'Fixture';
    	
    	include_once(__dir__ . '/fixtures/' . $className . '.php');
    
    	$fixture = new $className();
    	
    	$fixture->run($this->getConnection());
    }
    
    /**
     * 
     */
    protected function login($username = null, $password = null)
    {
    	$data = [
    		'username' => ($username == null) ? 'g8keeper' : $username,
    		'password' => ($password == null) ? '123456' : $password
    	];
    	
    	$responseBody = $this->callPost('/v1/login', null, $data);
    	
    	return 'Bearer: ' . $responseBody['token'];
    }

    /**
     * setUp
     */
    public function setUp()
    {
    	$this->createApplication();
        $this->reset();
    }

    /**
     * tearDown
     */
    public function tearDown()
    {
    	$this->reset();
    }

    /**
     * reset
     */
    protected function reset()
    {
    	$this->resetTables();
    }

    /**
     * resetTables
     */
    protected function resetTables()
    {
    	$connection = $this->getConnection();
    	$pdo = $connection->getPdo();

    	$pdo->exec('set FOREIGN_KEY_CHECKS = 0');
    	$query = $pdo->query('show full tables where Table_Type = "BASE TABLE"'); // excludes views
    	
    	$tables = $query->fetchAll();
    	
    	foreach ($tables as $table) {
    		$pdo->exec('truncate table ' . $table[0]);
    	}
    	
    	$pdo->exec('set FOREIGN_KEY_CHECKS = 1');
    }
    
    /**
     * getHostIp
     */
    private function getHostIp()
    {
    	if ($this->ip == null) {
	    	exec("ifconfig eth0 | grep 'inet' | grep -v '127.0.0.1' | grep -v 'fe' | cut -d: -f2 | awk '{ print $1 }'", $ip);
	    	
	    	$this->ip = (is_array($ip)) ? $ip[0] : $ip;
    	}
    	
    	return $this->ip;
    }

	/**
	 * build url
	 */
	private function buildUrl($path, $data = array())
	{
		$url = 'https://' . $this->getHostIp() . '/api' . $path;
		
    	if ($data !== []) {
    		$url = $url . '?' . http_build_query($data);
    	}

    	return $url;
	}
	
	/**
	 * getDataSet
	 */
	public function getDataSet($fixtures = array())
	{
		if(empty($fixtures) && property_exists($this, 'fixtures')) {
			$fixtures = $this->fixtures;
		}
		
		$fixturePath = __dir__ . '/fixtures';
		
		foreach ($fixtures as $fixture) {
			$this->loadFixture($fixture);
		}
	}
	
	/**
	 * getResponseBodyArray
	 */
	public function getResponseBodyArray($response)
	{
		return json_decode($response->getBody(), true);
	}
	
    /**
     * callGet
     */
    public function callGet($path, $header = [], $data = [])
    {
        $url = $this->buildUrl($path, $data);
    	$client = $this->presetClient($header);
        
        $response = $client->get($url);
        
        return $this->getResponseBodyArray($response);
    }
	
    /**
     * callPost
     */
    public function callPost($path, $header = [], $data = [])
    {
    	$url =  $this->buildUrl($path);
        $client = $this->presetClient($header);
        
        $response = $client->post(
        		$url, [ 'json' => $data ]);
        
        return $this->getResponseBodyArray($response);
    }
	
    /**
     * callPut
     */
    public function callPut($path, $header = [], $data = [])
    {
    	$url =  $this->buildUrl($path);
        $client = $this->presetClient($header);
        
        $response = $client->put(
        		$url, [ 'json' => $data ]);
        
        return $this->getResponseBodyArray($response);
    }
	
    /**
     * callDelete
     */
    public function callDelete($path, $header = [], $data = [])
    {
    	$url =  $this->buildUrl($path);
        $client = $this->presetClient($header);
        
        $response = $client->delete(
        		$url, [ 'json' => $data ]);
        
        return $this->getResponseBodyArray($response);
    }

    /**
     * Create Guzzle client and preset some default values.
     * 
     * @param unknown $header
     * @return \GuzzleHttp\Client
     */
    private function presetClient($header = []) 
    {
        $client = new Client();
        $client->setDefaultOption('verify', false);
        $client->setDefaultOption('exceptions', false);
        
        // flag the api to let it know it is running in test mode
        // ie: use the test database.
    	$header['testing'] = 'test';
        
        $client->setDefaultOption('headers', $header);

        return $client;
    }
	
}