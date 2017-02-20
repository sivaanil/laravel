<?php

namespace Unified\Services;

use Illuminate\Database\DatabaseManager;
use PDOException;
use Unified\Browser\BrowserManager;

/**
 * Runs various processes on reboot
 *
 * @author ross.keatinge
 */
class Reboot
{

    /**
     * Add anything here that needs to run on reboot
     */
    private function RunOnReboot()
    {

        error_reporting(E_ALL);
        ini_set('display_errors', 'On');
        echo "Running on reboot\n";

        $this->browserManager->DeactivateAllSlots();
        echo "Resetting process flags\n";
        // 2/2/2016 reset process flags on reboot
        $this->browserManager->ResetProcessFlags();
        
    }

    private $db;
    private $browserManager;

    public function __construct(DatabaseManager $db, BrowserManager $browserManager)
    {
        $this->db = $db;
        $this->browserManager = $browserManager;
    }

    /**
     * Run the reboot tasks
     * 
     * @return boolean
     *  true means success. Continue with the request
     *  false means failure. Either the db is not running yet or another process is running the reboot tasks.
     */
    public function Process()
    {
        $flagFile = storage_path('framework/rebooted');
        $lockFile = $flagFile . '.lock';

        $fpLock = fopen($lockFile, 'a');

        if (!flock($fpLock, LOCK_EX | LOCK_NB)) {
            fclose($fpLock);
            return false;
        }

        if (!file_exists($flagFile)) {
            // another process has already processed the reboot between when we saw the flag file and getting the lock
            $this->UnlockAndClose($fpLock);
            return true;
        }

        // we should try to process the reboot

        if (!$this->IsDatabaseRunning()) {
            // don't bother if the database is not running yet
            $this->UnlockAndClose($fpLock);
            return false;
        }

        // We have the lock, the flag file still exists and the database is running.
        // It's all up us.
        $this->RunOnReboot();

        unlink($flagFile);
        $this->UnlockAndClose($fpLock);

        return true;
    }

    private function UnlockAndClose($fp)
    {
        flock($fp, LOCK_UN);
        fclose($fp);
    }

    private function IsDatabaseRunning()
    {
        $running = false;

        try {
            $this->db->statement('SELECT 1');
            $running = true;
        } catch (PDOException $ex) {
            // 2002 means cannot connect to db
            // any other code is a real error
            if ($ex->getCode() != 2002) {
                throw $ex;
            }
        }

        return $running;
    }

}
