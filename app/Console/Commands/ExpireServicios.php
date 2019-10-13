<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Servicio;

class ExpireServicios extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'servicios:expire';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Desactivar los servicios Expirados';

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
      Servicio::whereNotNull('expiration')
              ->where('expiration', '<', date('Y-m-d H:i:s'))
              ->update(['active' => false]);
    }
}
