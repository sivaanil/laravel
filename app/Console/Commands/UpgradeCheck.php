<?php

namespace Unified\Console\Commands;

use DB;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Log;
use Unified\System\Network\WANConfig;
use Unified\System\Network\WANStatus;
use Unified\Models\UnifiedConfig;


class UpgradeCheck extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'csquared:check-upgrade';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checks DMONS System for updates and updates check-in.';

    protected $client;
    protected $disabled;
    protected $message;
    protected $processUpdate;
    protected $upgradeBranchType;
    protected $currentBranchType;
    protected $upgradeVersion;
    protected $currentVersion;

    protected $mac;
    protected $ip;
    protected $baseUri;

    /**
     * Create a new command instance.
     *
     */
    public function __construct(UnifiedConfig $unified)
    {
        $this->unified = $unified;

        parent::__construct();
        $this->baseUri = config('csquared.dmons.connection.prefix') . "://" .
            config('csquared.dmons.connection.ip') . ":" .
            config('csquared.dmons.connection.port') . "/" .
            config('csquared.dmons.version');

        $this->client = new Client();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(WANStatus $wanStatus)
    {
        $this->setApiVars();
        Log::info('Constructing Upgrade Check with guzzle client', (array) $this->client);
        //$this->client = new Client('https://dmons.csquaredsystems.com/dmons/api/v1/');

        // Need a validator to make sure that the wan statuses
        // are actually displaying the correct eth
        $this->mac = sha1($wanStatus->getMacAddress());

        $response = $this->checkIn();
        $responseBody = $response->json();
        Log::info("Response Body", (array) $responseBody);
        switch ($responseBody['status']) {
            case 'update':
                Log::info("Status Returned = Update");
                $updateResults = $this->getUpdate($response);
                break;
            case 'force':
                Log::info("Status Returned = Force");
                $this->unified->setUnifiedConfig('force_update', 1);
                $updateResults = $this->getUpdate($response);
                $this->call('csquared:backup');
                break;
            case 'rollback':
                Log::info("Status Returned = Rollback");
                $updateResults = $this->rollback();
                break;
            default:
                Log::info("Status Returned =", (array) $responseBody['status']);
                $updateResults = $responseBody;
                break;

        }
        Log::info('Update available returned ', (array) $updateResults);

        return;
    }


    /**
     * @return mixed
     */
    private function checkIn()
    {
        $headers = [
            'Content-Type' => 'application/json'
        ];

        try {
            $request = $this->client->createRequest("GET", $this->baseUri . "update/checkin/$this->mac");
//            $request->setHeaders($headers);
//            $query = $request->getQuery();
//            $query->set('ip_address', $this->ip);
//            $query->set('name', 'sitegate');

            $response = $this->client->send($request);
            $body = $response->json();

            Log::info("CHECK IN RESPONSE:", $body);
            print_r($response->getHeaders());
            print_r($response->getStatusCode());
            print_r($body);

            Log::info('response', (array) $response);

            return $response;
        } catch (RequestException $e) {
            Log::error("RequestException:", (array) $e->getRequest());
            if ($e->hasResponse()) {
                Log::error("RequestException:", (array) $e->getResponse());
            }
            exit;
        }

    }

    /**
     * @param $response
     */
    private function getUpdate($response)
    {
        $body = $response->json();
        if (isset($body['slug'])) {
            $this->updateUnifiedConfig($body);
            $fileName = $body['slug'];
            $filePath = storage_path('app/updates/' . $fileName);
            $ext = $this->baseUri . "update/" . $this->mac . "/$fileName";
            $request = $this->client->createRequest("GET", $ext, [
                'verify'  => false,
                'debug'   => true,
                'save_to' => $filePath,
                'timeout' => 900,
            ]);

            $contentType = "application/x-tar";
            Log::info("CheckUpdate returned an update. Starting processing of file.");
            Log::debug("File Path =$filePath");
            Log::debug(__METHOD__ . "::Filename = $fileName");
            $updateLocation = 'updates/' . $fileName;
            if (file_exists(storage_path('/app/updates/' . $fileName))) {
                Log::debug(__METHOD__ . "::Deleting file from updates");
                Storage::delete($updateLocation);
            }
            $response = $this->client->send($request);
            Log::debug(__METHOD__ . "Response =", (array) $response);
            Log::info('Updating unified_config:upgradeVersion to ' . $fileName);

            $this->unified->setUnifiedConfig("process_update", 1);

            return true;

        } else {
            Log::info('Check Update did not find an update or an error occurred');
            Log::debug('deleting temp file that was created');

            $results = [
                'update'      => false,
                'response'    => $body,
                'headers'     => $response->getHeaders(),
                'http_status' => $response->getStatusCode(),
                'reason'      => $response->getReasonPhrase()
            ];
            $this->updateUnifiedConfig($body);
            Log::info('Check update returned with', $results);


            return $results;

        }
    }

    private function rollback()
    {

    }

    private function setApiVars()
    {
        $this->currentVersion = $this->unified->getUnifiedConfig('current_version');
        $this->upgradeVersion = $this->unified->getUnifiedConfig('upgrade_version');
        $this->currentBranchType = $this->unified->getUnifiedConfig('current_branch_type');
        $this->upgradeBranchType = $this->unified->getUnifiedConfig('upgrade_branch_type');
        $this->processUpdate = $this->unified->getUnifiedConfig('process_update');
        $this->message = $this->unified->getUnifiedConfig('message');
        $this->disabled = $this->unified->getUnifiedConfig('disabled');
    }

    private function updateUnifiedConfig($resArr)
    {
        $versions = str_replace('.tar.gz', '', $resArr['slug']);
        $this->unified->setUnifiedConfig("upgrade_version", $versions);
        $this->unified->setUnifiedConfig('message', $resArr['message']);
        #$this->unified->setUnifiedConfig('command', $resArr['command']);
        $this->unified->setUnifiedConfig('banner_message', $resArr['bannerMsg']);
        $disabled = ($resArr['active'] === 1) ? 0 : 1;
        $this->unified->setUnifiedConfig('disabled', $disabled);
        $this->unified->setUnifiedConfig('upgrade_branch_type', $resArr['branch']);
    }


}
