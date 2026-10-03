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
        $inquiry = DB::transaction(function () use ($data, $ip, $userAgent) {
            // Generate unique reference number: SA-YYYY-XXXXXX
            $refNumber = 'SA-' . date('Y') . '-' . strtoupper(Str::random(6));

            // Determine line items
            $itemsData = [];
            if (! empty($data['items']) && is_array($data['items'])) {
                $itemsData = $data['items'];
            } elseif (! empty($data['product_id'])) {
                $itemsData[] = [
                    'product_id' => $data['product_id'],
                    'quantity' => $data['target_quantity'] ?? '1 Standard Consignment',
                    'notes' => $data['packaging_requirements'] ?? null,
                ];
            }

            // Primary product ID for backward compatibility
            $primaryProductId = $data['product_id'] ?? (! empty($itemsData[0]['product_id']) ? $itemsData[0]['product_id'] : null);
            $targetQuantity = $data['target_quantity'] ?? null;
            if (empty($targetQuantity) && ! empty($itemsData)) {
                $qtyParts = [];
                foreach ($itemsData as $item) {
                    if (! empty($item['quantity'])) {
                        $qtyParts[] = $item['quantity'];
                    }
                }
                $targetQuantity = ! empty($qtyParts) ? implode(', ', array_slice($qtyParts, 0, 3)) : null;
            }

            $inquiry = Inquiry::create([
                'reference_no' => $refNumber,
                'inquiry_type' => $data['inquiry_type'] ?? (count($itemsData) > 0 ? Inquiry::TYPE_QUOTE : Inquiry::TYPE_GENERAL),
                'product_id' => $primaryProductId,
                'full_name' => $data['full_name'],
                'company_name' => $data['company_name'] ?? null,
                'email' => $data['email'],
                'phone' => $data['phone'],
                'country' => $data['country'],
                'target_quantity' => $targetQuantity,
                'packaging_requirements' => $data['packaging_requirements'] ?? null,
                'port_of_destination' => $data['port_of_destination'] ?? null,
                'subject' => $data['subject'] ?? null,
                'message' => $data['message'] ?? 'Quotation requested via online RFQ system.',
                'status' => Inquiry::STATUS_NEW,
                'ip_address' => $ip,
                'user_agent' => $userAgent,
            ]);

            // Persist each line item with authoritative product details from database
            foreach ($itemsData as $item) {
                $prod = null;
                if (! empty($item['product_id'])) {
                    $prod = \App\Models\Product::find($item['product_id']);
                }

                \App\Models\InquiryItem::create([
                    'inquiry_id' => $inquiry->id,
                    'product_id' => $prod?->id,
                    'product_name' => $prod?->name ?? ($item['product_name'] ?? 'Custom Product Request'),
                    'product_slug' => $prod?->slug,
                    'hs_code' => $prod?->hs_code,
                    'quantity' => $item['quantity'] ?? '1 Consignment',
                    'notes' => $item['notes'] ?? null,
                ]);
            }

            $productCount = count($itemsData);
            $summaryNote = $productCount > 0 
                ? "Quotation inquiry received with {$productCount} product line item(s). Reference #{$inquiry->reference_no}"
                : "General trade inquiry received with Reference #{$inquiry->reference_no}";

            InquiryActivity::create([
                'inquiry_id' => $inquiry->id,
                'action' => 'inquiry_created',
                'notes' => $summaryNote,
            ]);

            return $inquiry;
        });

        // Clear session RFQ items if present
        if (session()->has('rfq_items')) {
            session()->forget('rfq_items');
        }

        // Dispatch notification safely outside the database transaction
        try {
            dispatch(new SendInquiryNotificationJob($inquiry));
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning("Notification dispatch failed for inquiry #{$inquiry->reference_no}: " . $e->getMessage());
        }

        return $inquiry->load(['items.product', 'product', 'activities']);
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
        $query = Inquiry::query()->with(['items.product', 'product', 'activities'])->latest();

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
                  ->orWhere('phone', 'like', "%{$term}%")
                  ->orWhere('country', 'like', "%{$term}%")
                  ->orWhere('message', 'like', "%{$term}%")
                  ->orWhereHas('items', function ($itemQ) use ($term) {
                      $itemQ->where('product_name', 'like', "%{$term}%")
                            ->orWhere('hs_code', 'like', "%{$term}%");
                  })
                  ->orWhereHas('product', function ($prodQ) use ($term) {
                      $prodQ->where('name', 'like', "%{$term}%");
                  });
            });
        }

        return $query->paginate($perPage)->withQueryString();
    }
}
