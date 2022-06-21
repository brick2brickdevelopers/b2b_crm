<?php

namespace App\Console\Commands;

use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class fixsip extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fixsip';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
     * @return int
     */
    public function handle()
    {
        $users = User::whereNotNull('sip_user')->get();
        foreach ($users as $user) {
            sip_api($user->sip_user, $user->sip_pass, 'delete');
            $u = User::find($user->id);
            $u->sip_user = $user->id + 1000;
            $u->sip_pass = $user->sip_pass;
            $u->save();
            sip_api($u->sip_user, $u->sip_pass, 'add');
            Log::info(array('user' => $u->id, 'sip_id' => $u->sip_user));
        }
        return 0;
    }
}
