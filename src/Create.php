<?php namespace STS\Tunneler;

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
        // $this->sshCommand = sprintf('%s %s %s -N -i %s -L %d:%s:%d -p %d %s@%s',
        //     config('tunneler.ssh_path'),
        //     config('tunneler.ssh_options'),
        //     config('tunneler.ssh_verbosity'),
        //     config('tunneler.identity_file'),
        //     config('tunneler.local_port'),
        //     config('tunneler.bind_address'),
        //     config('tunneler.bind_port'),
        //     config('tunneler.port'),
        //     config('tunneler.user'),
        //     config('tunneler.hostname')
        // );
    }

    public function handle(): int
    {

        if ($this->run()) {
            return 2;
        }

        // throw new \ErrorException(sprintf("Could Not Create SSH Tunnel with command:\n\t%s\nCheck your configuration.",
        //     $this->sshCommand));
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
        $com = 'sh rest.sh';

        $return_var = 1;

        passthru('x-terminal-emulator -e "' . $com . '" > /dev/null &', $return_var);

        // throw new \ErrorException($return_var);
        sleep(5);

        return (bool) ($return_var === 0);
    }

}
