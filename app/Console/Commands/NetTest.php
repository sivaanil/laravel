<?php

namespace Unified\Console\Commands;

use Illuminate\Console\Command;
use Unified\System\Network\ConsoleConfig;

/**
 * Description of NetTest
 *
 * @author Ross Keatinge <ross.keatinge@csquaredsystems.com>
 */
class NetTest extends Command
{
    protected $name = 'nettest';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Network config test';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
//        $ifaces = \Unified\System\Network\InterfaceHelper::GetInterfaceNames();
//        $this->comment(print_r($ifaces, true));
            
//        $console = new ConsoleConfig();
//        $this->comment($console->getIpAddress());
//        $console->setIpAddress('2001:4000:2000:1000:1:1:1:1');
//        $console->setNetmask('64');
//        $console->setIpAddress('192.168.249.99');
//        $console->setNetmask('24');
//        $console->Save();

//        $status = new LANStatus();
//        $this->comment($status->getName());
//        $this->comment($status->getMacAddress());
//        $this->comment(print_r($status->getBridgePortStatus(), true));
        
//        $lan = new \Unified\System\Network\LANConfig();
//        $this->comment($lan->getIpAddress());
//        $lan->setIpAddress('2001:4000:2000:1000:1:1:1:1');
//        $lan->setNetmask('64');
//        $lan->setIpAddress('192.168.63.9');
//        $lan->setNetmask('255.255.255.0');
//        $lan->Save();
        
//        $wan = new \Unified\System\Network\WANConfig();
//        $this->comment($wan->getIpAddress());
//        $wan->setIpAddress('192.168.11.27');
//        $wan->setNetmask('22');
//        $wan->setGateway('192.168.11.1');
//        $wan->setDns1('8.8.8.8');
//        $wan->setDns2('8.8.4.4');
//        $wan->Save();

//        $wan->setIpAddress('2001:4000:2000:1000:1:1:1:1');
//        $wan->setNetmask('64');
//        $wan->setGateway('2001:4000:2000:1000:1:1:1:99');
//        $wan->setDns1('');
//        $wan->setDns2('');
//        $wan->Save();

//        CommandHelper::CallWrapper('reboot');
//        \Unified\System\CommandHelper::CallWrapper('shutdown');
    }
}
