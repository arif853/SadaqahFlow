<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Http\Request;

class LogLogin
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function handle(Login $event)
    {
        $user = $event->user;
        
        $user->loginLogs()->create([
            'last_login_ip' => $this->request->ip(),
            'last_login_at' => now()
        ]);
    }
}
