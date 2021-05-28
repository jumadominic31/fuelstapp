<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

use App\Txn;
use App\Memloyaltytxn;

class dailymemloyalty extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:dailymemloyalty';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This commands populates the total daily transaction volume for members for the previous day';

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
        $yesterday_date = date('Y-m-d', strtotime('-1 days'));
        // $yesterday_date = '2021-05-05';
        $txns = Txn::select('ownerid', DB::raw('sum(volume) as amount'), DB::raw('date(created_at) as date'))->where('companyid', '=', '3')->where('ownerid', '!=', '0')->where(DB::raw('date(created_at)'), '=', $yesterday_date)->groupBy('ownerid')->get()->toArray();
        $ins_success = Memloyaltytxn::insert($txns);
        dd($ins_success);

    }
}
