<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ListenerCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:listen {host=0.0.0.0} {port=8080}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $factory = new \React\Datagram\Factory();

        // $factory->createClient($this->argument('host').':'.$this->argument('port'))->then(function (\React\Datagram\Socket $client) {
        //     $client->send('first');

        //     $client->on('message', function($message, $serverAddress, $client) {
        //         $this->info( 'received "' . $message . '" from ' . $serverAddress );
        //     });
        // });

        $factory = new \React\Datagram\Factory();

        $factory->createServer($this->argument('host').':'.$this->argument('port'))->then(function (\React\Datagram\Socket $server) {
            $server->on('message', function($message, $address, $server) {
                // $server->send('hello ' . $address . '! echo: ' . $message, $address);

                // $this->info('client ' . $address . ': ' . $message);

                $pattern = "/^\<([0-9]{1,3})\>([A-Za-z]{3} [0-9]{1,2} \d{2}:\d{2}:\d{2}) ([\S]+) ([\S]+) ([\S\s]+)/";
                $result = preg_split($pattern, $message, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);

                $data = [
                    'priority' => $result[0],
                    'date' => $result[1],
                    'server' => $result[2],
                    'app' => $result[3],
                    'message' => $result[4],
                ];

                $this->info("Priority: {$data['priority']}\nServer: {$data['server']}\nApp: {$data['app']}\nMessage: {$data['message']}");
            });
        });


        $this->info("Started on " . $this->argument('host') . ':' . $this->argument('port'));
    }
}
