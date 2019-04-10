<?php
/**
 * SMM Panel - EasyGrowfast.Com
 * Domain: https://www.easygrowfast.com/
 *  
 */
namespace App\Console\Commands;

use App\Mail\AdminMasterReport;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendAdminMasterReportEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send-status-report';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Master report to admin';

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
        Mail::to(getOption('notify_email'))->send(new AdminMasterReport());
    }
}
