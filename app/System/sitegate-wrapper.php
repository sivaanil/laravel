<?php

/**
 * This is called from the C wrapper called sitegate-wrapper.
 * It must be copied to /usr/local/bin
 * chown root:root
 * chmod 400
 */
$ok = $argc == 2;

if ($ok) {
    $parts = explode('-', $argv[1]);

    $cmd = $parts[0];

    $args = count($parts) > 1 ? array_slice($parts, 1) : array();

    switch ($cmd) {
        case 'copywan':
            CopyInterfaceFile('wan');
            break;

        case 'copylan':
            CopyInterfaceFile('lan');
            break;

        case 'copyconsole':
            CopyInterfaceFile('console');
            break;

        case 'restartiface':
            RestartInterface($args);
            break;

        case 'resetguest':
            ResetGuest();
            break;

        case 'reboot':
            Reboot();
            break;

        case 'shutdown':
            Shutdown();
            break;

        case 'addroute':
            AddRoute($args);
            break;

        case 'delroute':
            DeleteRoute($args);
            break;

        default:
            $ok = false;
            break;
    }
}

if (!$ok) {
    echo "Bad command\n";
    exit;
}

function CopyInterfaceFile($name)
{
    $source = "/tmp/{$name}.conf";

    if (!ValidateInterfaceFile($source)) {
        echo "Error saving interfaces file: File contains data not permitted\n";
        exit;
    }

    $dest = "/etc/network/interfaces.d/{$name}.conf";

    copy($source, $dest);
    unlink($source);
}

/**
 * Check the interface file for anything unsafe.
 * We don't want to allow anything that runs a command.
 * @param string $filePath
 * @return boolean true means file is safe.
 */
function ValidateInterfaceFile($filePath)
{
    $lines = explode("\n", file_get_contents($filePath));
    $ok = true;

    foreach ($lines as $line) {
        if (!isPermittedLine($line)) {
            $ok = false;
            break;
        }
    }

    return $ok;
}

/**
 * Check a line in the interface file for anything illegal
 * @param string $line
 * @return boolean true means line is permitted
 */
function isPermittedLine($line)
{

    $line = trim($line);

    // Blank lines are okay
    if (empty($line)) {
        return true;
    }

    // All lines must start with one of these.
    $okLines = [
        'auto',
        'iface',
        'address',
        'netmask',
        'gateway',
        'dns-nameservers',
        'bridge_ports'
    ];

    $ok = false;

    foreach ($okLines as $okline) {
        if (strpos($line, $okline) === 0) {
            $ok = true;
            break;
        }
    }

    return $ok;
}

// All commands must use absolute paths and writable only by root.
// This prevents someone subsituting their own command.

function RestartInterface($args)
{
    if (count($args) < 1) {
        echo "Interface name is required.\n";
        exit;
    }

    $name = escapeshellarg($args[0]);
    exec('/sbin/ifdown ' . $name . ' && sleep 5 && /sbin/ifup ' . $name);
}

function Reboot()
{
    exec('/sbin/reboot');
}

function Shutdown()
{
    exec('/sbin/shutdown -h now');
}

function ResetGuest()
{
    exec('/home/c2-maintenance/sites/unified/app/System/reset-guest.sh');
}

function AddRoute($args)
{
    if (count($args) < 2) {
        echo "Usage: sitegate-wrapper addroute-target-route\n";
        exit;
    }

    $target = escapeshellarg($args[0]);
    $route = escapeshellarg($args[1]);

    exec("/sbin/ip route add $target via $route");
}

function DeleteRoute($args)
{
    if (count($args) < 1) {
        echo "Usage: sitegate-wrapper addroute-target-route\n";
        exit;
    }

    $target = escapeshellarg($args[0]);

    exec("/sbin/ip route delete $target");
}
