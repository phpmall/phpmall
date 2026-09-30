<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('app:hello')]
#[Description('Command description')]
class Hello extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
    }
}
