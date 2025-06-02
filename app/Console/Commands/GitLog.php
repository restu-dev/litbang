<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GitLog extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'git:log';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Git log with beautiful output';

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
        exec('git log --pretty=format:"%aD %an: %n%s %n" --shortstat > public/pesan_commits');
        $this->info('File log tersimpan di /public/pesan_commits');
    }
}
