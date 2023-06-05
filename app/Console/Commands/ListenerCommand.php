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
    protected $signature = 'app:listen';

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
        $this->info("Started");
        $factory = new \React\Datagram\Factory();

        $factory->createClient('localhost:1234')->then(function (\React\Datagram\Socket $client) {
            $client->send('first');

            $client->on('message', function($message, $serverAddress, $client) {
                $this->info( 'received "' . $message . '" from ' . $serverAddress );
            });
        });
    }
}
