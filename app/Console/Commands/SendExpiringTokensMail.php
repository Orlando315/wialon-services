<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Servicio;

class SendExpiringTokensMail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tokens:expire';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notificar al User que el Token de Wialon esta por expirar';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
      $today = date('Y-m-d H:i:s');
      $todayPlus5 = date('Y-m-d H:i:s', strtotime('+5 days'));

      $servicios = Servicio::whereNotNull('wialon')
                      ->whereNotNull('wialon_expiration')
                      ->whereBetween('wialon_expiration', [$today, $todayPlus5])
                      ->where('active', true)
                      ->get();

      $this->info('Servicios por expirar: '. count($servicios));

      foreach ($servicios as $servicio) {
        $servicio->sendWialonTokenExpirationEmail();
      }
    }
}
