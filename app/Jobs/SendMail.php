<?php

namespace App\Jobs;

use App\Book;
use App\Mail\ActionDetailMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

      protected $details;
      protected $book;
      protected $user;
      protected $action;

    public function __construct($details,$book,$user,$action)
    {
        $this->details = $details;
        $this->book = $book;
        $this->user = $user;
        $this->action = $action;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to($this->details["email"])->send(new ActionDetailMail($this->book,$this->user,$this->action) );
    }
}
