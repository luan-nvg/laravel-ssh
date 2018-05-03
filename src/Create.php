<?php 
namespace MNP\Tunneler;

use Illuminate\Console\Command;

class Create
{

    /**
     * The command for creating the tunnel
     * @var string
     */
    protected $sshCommand;

    /**
     * Simple place to keep all output.
     * @var array
     */
    protected $output = [];

    public function __construct()
    {
        $this->sshCommand = sprintf('%s %s -i %s -L %s:%s:%s -p %s %s@%s',
            config('tunneler.ssh_path'),
            config('tunneler.ssh_options'),
            config('tunneler.identity_file'),
            config('tunneler.bind_port'),
            config('tunneler.db_host'),
            config('tunneler.bind_port'),
            config('tunneler.port'),
            config('tunneler.user'),
            config('tunneler.hostname')
        );
    }

    public function handle(): int
    {

        if ($this->run()) {
            return 2;
        }

        throw new \ErrorException(sprintf("Could Not Create SSH Tunnel with command:\n\t%s\nCheck your configuration.",
            $this->sshCommand));
    }

    /**
     * Verifies whether the tunnel is active or not.
     * @return bool
     */

    /**
     * Runs a command and converts the exit code to a boolean
     * @param $command
     * @return bool
     */
    public function run()
    {
        $return_var           = 1;
        $commandCheckOpenDoor = 'lsof -i :' . config('tunneler.local_port');
        $timer_tunneling      = intval(config('tunneler.timemout_tunnel'));
        $checkPort            = exec($commandCheckOpenDoor);
        $commandClosePort     = 'kill $(lsof -t -i:' . config('tunneler.local_port') . ')';

        if (!$checkPort) {
            passthru($this->commandTunneling(), $return_var);
            // sleep($timer_tunneling);
            echo 'Conecting SSH tunnel...';
            while (!$checkPort){
                echo '.';
                $checkPort = exec($commandCheckOpenDoor);
            }
            echo PHP_EOL;
            echo 'SSH tunnel are connected, you can close the extra terminal'.PHP_EOL;

        } else {
            //LISTEN
            //CLOSE_WAIT
            $checkPortStatus = strpos(collect($checkPort)->first(), '(CLOSE_WAIT)');

            if ($checkPortStatus) {
                echo 'Door closed, establishing new connection...';
                exec($commandClosePort);
                $this->run();
            } else {
                echo 'tunneling: The ' . config('tunneler.local_port') . ' port is already being used, if it does not work check if it is already being used by another program.';
            }

        }

        return (bool) ($checkPort == true);
    }

    private function commandTunneling()
    {
       return 'x-terminal-emulator -e "' . $this->sshCommand .'" > /dev/null &';
    }

}