<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\DB;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('inspire')->everyMinute();

        // Cancela Pedidos em Alteração por mais de 10 minutos
        $schedule->call(function () {
            DB::table('tbl_pedido')
                ->where('id_pedido_status', 7)
                ->where('updated_at', '<=', DB::raw('current_timestamp - (10 ||\' minutes\')::interval'))
                ->update(['id_pedido_status' => 6, 'id_usuario_update' => null]);

            //\Log::debug('Teste Kernel');
        })->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
