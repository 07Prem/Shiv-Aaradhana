<?php

namespace App\Jobs;

use App\Mail\InquiryReceivedNotification;
use App\Models\Inquiry;
use App\Models\InquiryActivity;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class SendInquiryNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $backoff = 30; // seconds

    public function __construct(public Inquiry $inquiry)
    {
    }

    public function handle(): void
    {
        $adminEmail = config('mail.from.address', 'info.shivaaradhana@gmail.com');

        try {
            Mail::to($adminEmail)->send(new InquiryReceivedNotification($this->inquiry));

            InquiryActivity::create([
                'inquiry_id' => $this->inquiry->id,
                'action' => 'email_notification_sent',
                'notes' => "Notification successfully dispatched to {$adminEmail}",
            ]);
        } catch (Throwable $e) {
            Log::error("Failed to deliver inquiry notification email for ref {$this->inquiry->reference_no}: " . $e->getMessage());
            
            InquiryActivity::create([
                'inquiry_id' => $this->inquiry->id,
                'action' => 'email_notification_failed',
                'notes' => "Email delivery error: " . substr($e->getMessage(), 0, 250),
            ]);

            throw $e;
        }
    }
}
