<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreGeneralInquiryRequest;
use App\Http\Requests\StoreProductQuoteRequest;
use App\Models\Inquiry;
use App\Services\Inquiries\InquiryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class InquiryController extends Controller
{
    public function __construct(protected InquiryService $inquiryService)
    {
    }

    public function storeGeneral(StoreGeneralInquiryRequest $request): JsonResponse|RedirectResponse
    {
        $data = $request->validated();
        $data['inquiry_type'] = Inquiry::TYPE_GENERAL;

        $inquiry = $this->inquiryService->createInquiry(
            $data,
            $request->ip(),
            $request->userAgent()
        );

        $message = "Your inquiry has been successfully registered under Reference #{$inquiry->reference_no}. Our international trade desk will review your requirements and respond promptly.";

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'reference_no' => $inquiry->reference_no,
            ]);
        }

        return redirect()->back()->with('success_inquiry', [
            'reference_no' => $inquiry->reference_no,
            'message' => $message,
        ]);
    }

    public function storeQuote(StoreProductQuoteRequest $request): JsonResponse|RedirectResponse
    {
        $data = $request->validated();
        $data['inquiry_type'] = Inquiry::TYPE_QUOTE;

        $inquiry = $this->inquiryService->createInquiry(
            $data,
            $request->ip(),
            $request->userAgent()
        );

        $message = "Your formal quotation request for {$inquiry->product?->name} has been received under Reference #{$inquiry->reference_no}. Our export team will prepare FOB/CIF rate indications and specifications.";

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'reference_no' => $inquiry->reference_no,
            ]);
        }

        return redirect()->back()->with('success_quote', [
            'reference_no' => $inquiry->reference_no,
            'message' => $message,
            'product_name' => $inquiry->product?->name,
        ]);
    }
}
