<?php

namespace App\Services\Inquiries;

use App\Jobs\SendInquiryNotificationJob;
use App\Models\Inquiry;
use App\Models\InquiryActivity;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class InquiryService
{
    /**
     * Create and durably persist a business inquiry or quotation request.
     *
     * @param array<string, mixed> $data
     */
    public function createInquiry(array $data, ?string $ip = null, ?string $userAgent = null): Inquiry
    {
        return DB::transaction(function () use ($data, $ip, $userAgent) {
            // Generate unique reference number: SA-2026-XXXXX
            $refNumber = 'SA-' . date('Y') . '-' . strtoupper(Str::random(6));

            $inquiry = Inquiry::create([
                'reference_no' => $refNumber,
                'inquiry_type' => $data['inquiry_type'] ?? Inquiry::TYPE_GENERAL,
                'product_id' => $data['product_id'] ?? null,
                'full_name' => $data['full_name'],
                'company_name' => $data['company_name'] ?? null,
                'email' => $data['email'],
                'phone' => $data['phone'],
                'country' => $data['country'],
                'target_quantity' => $data['target_quantity'] ?? null,
                'packaging_requirements' => $data['packaging_requirements'] ?? null,
                'port_of_destination' => $data['port_of_destination'] ?? null,
                'subject' => $data['subject'] ?? null,
                'message' => $data['message'],
                'status' => Inquiry::STATUS_NEW,
                'ip_address' => $ip,
                'user_agent' => $userAgent,
            ]);

            InquiryActivity::create([
                'inquiry_id' => $inquiry->id,
                'action' => 'inquiry_created',
                'notes' => "Inquiry received and registered with Reference #{$inquiry->reference_no}",
            ]);

            // Dispatch background queued notification job
            dispatch(new SendInquiryNotificationJob($inquiry));

            return $inquiry;
        });
    }

    /**
     * Update inquiry status and record audit log.
     */
    public function updateStatus(Inquiry $inquiry, string $newStatus, ?string $note = null, ?User $actor = null): Inquiry
    {
        return DB::transaction(function () use ($inquiry, $newStatus, $note, $actor) {
            $oldStatus = $inquiry->status;
            $inquiry->update(['status' => $newStatus]);

            $description = "Status updated from '{$oldStatus}' to '{$newStatus}'";
            if ($note) {
                $description .= ". Note: {$note}";
            }

            InquiryActivity::create([
                'inquiry_id' => $inquiry->id,
                'user_id' => $actor?->id,
                'action' => 'status_updated',
                'notes' => $description,
            ]);

            return $inquiry;
        });
    }

    /**
     * Add internal follow-up activity note.
     */
    public function addActivityNote(Inquiry $inquiry, string $note, ?User $actor = null): InquiryActivity
    {
        return InquiryActivity::create([
            'inquiry_id' => $inquiry->id,
            'user_id' => $actor?->id,
            'action' => 'internal_note',
            'notes' => $note,
        ]);
    }

    /**
     * List inquiries with filter and search.
     *
     * @param array<string, mixed> $filters
     */
    public function listInquiries(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = Inquiry::query()->with(['product', 'activities'])->latest();

        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (! empty($filters['type'])) {
            $query->where('inquiry_type', $filters['type']);
        }

        if (! empty($filters['q'])) {
            $term = trim($filters['q']);
            $query->where(function ($q) use ($term) {
                $q->where('reference_no', 'like', "%{$term}%")
                  ->orWhere('full_name', 'like', "%{$term}%")
                  ->orWhere('company_name', 'like', "%{$term}%")
                  ->orWhere('email', 'like', "%{$term}%")
                  ->orWhere('country', 'like', "%{$term}%");
            });
        }

        return $query->paginate($perPage)->withQueryString();
    }
}
