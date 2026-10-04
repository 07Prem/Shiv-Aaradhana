<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Inquiry;
use App\Services\Inquiries\InquiryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class InquiryController extends Controller
{
    public function __construct(protected InquiryService $inquiryService)
    {
    }

    public function index(Request $request): View
    {
        $filters = [
            'status' => $request->input('status'),
            'type' => $request->input('type'),
            'sort' => $request->input('sort', 'latest'),
            'q' => $request->input('q', $request->input('search')),
        ];

        $inquiries = $this->inquiryService->listInquiries($filters, 15);

        $counts = [
            'all' => Inquiry::count(),
            'general' => Inquiry::where('inquiry_type', Inquiry::TYPE_GENERAL)->count(),
            'quote' => Inquiry::where('inquiry_type', Inquiry::TYPE_QUOTE)->count(),
            'new' => Inquiry::where('status', Inquiry::STATUS_NEW)->count(),
            'in_progress' => Inquiry::where('status', Inquiry::STATUS_IN_PROGRESS)->count(),
            'responded' => Inquiry::where('status', Inquiry::STATUS_RESPONDED)->count(),
            'accepted' => Inquiry::where('status', Inquiry::STATUS_ACCEPTED)->count(),
            'rejected' => Inquiry::where('status', Inquiry::STATUS_REJECTED)->count(),
            'closed' => Inquiry::where('status', Inquiry::STATUS_CLOSED)->count(),
        ];

        return view('admin.inquiries.index', compact('inquiries', 'filters', 'counts'));
    }

    public function show(Inquiry $inquiry): View
    {
        $inquiry->load(['items.product.category', 'product.category', 'activities.user']);

        return view('admin.inquiries.show', compact('inquiry'));
    }

    public function updateStatus(Request $request, Inquiry $inquiry): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in([
                Inquiry::STATUS_NEW,
                Inquiry::STATUS_IN_PROGRESS,
                Inquiry::STATUS_RESPONDED,
                Inquiry::STATUS_ACCEPTED,
                Inquiry::STATUS_REJECTED,
                Inquiry::STATUS_CLOSED,
                Inquiry::STATUS_SPAM,
            ])],
            'status_note' => ['nullable', 'string', 'max:500'],
        ]);

        $this->inquiryService->updateStatus(
            $inquiry,
            $validated['status'],
            $validated['status_note'] ?? null,
            $request->user()
        );

        AuditLog::record('inquiry_status_updated', "Updated inquiry #{$inquiry->reference_no} status to {$validated['status']}", $inquiry);

        return back()->with('success', "Inquiry status updated to '{$validated['status']}'.");
    }

    public function addNote(Request $request, Inquiry $inquiry): RedirectResponse
    {
        $validated = $request->validate([
            'notes' => ['required', 'string', 'max:1000'],
        ]);

        $this->inquiryService->addActivityNote($inquiry, $validated['notes'], $request->user());

        return back()->with('success', 'Internal activity note added.');
    }

    public function exportCsv(): StreamedResponse
    {
        AuditLog::record('inquiries_exported', "Exported inquiries CSV report");

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="inquiries_export_' . date('Y-m-d_H-i') . '.csv"',
        ];

        return response()->stream(function () {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, [
                'Reference No',
                'Type',
                'Buyer Name',
                'Company',
                'Email',
                'Phone',
                'Country',
                'Target Product',
                'Target Quantity',
                'Packaging',
                'Discharge Port',
                'Status',
                'Received At',
            ]);

            Inquiry::with(['items', 'product'])->chunk(100, function ($batch) use ($handle) {
                foreach ($batch as $inq) {
                    $productsStr = $inq->items->count() > 0 
                        ? $inq->items->map(fn($it) => "{$it->product_name} ({$it->quantity})")->implode('; ')
                        : ($inq->product?->name ?? 'General Inquiry');

                    fputcsv($handle, [
                        $inq->reference_no,
                        $inq->inquiry_type,
                        $inq->full_name,
                        $inq->company_name ?? '',
                        $inq->email,
                        $inq->phone,
                        $inq->country,
                        $productsStr,
                        $inq->target_quantity ?? '',
                        $inq->packaging_requirements ?? '',
                        $inq->port_of_destination ?? '',
                        $inq->status,
                        $inq->created_at->format('Y-m-d H:i:s'),
                    ]);
                }
            });

            fclose($handle);
        }, 200, $headers);
    }
}
