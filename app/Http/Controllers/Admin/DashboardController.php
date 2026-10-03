<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Category;
use App\Models\Inquiry;
use App\Models\Product;
use App\Models\ProductType;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $metrics = [
            'total_categories' => Category::count(),
            'total_product_types' => ProductType::count(),
            'published_products' => Product::where('status', Product::STATUS_PUBLISHED)->count(),
            'draft_products' => Product::where('status', Product::STATUS_DRAFT)->count(),
            'featured_products' => Product::where('is_featured', true)->count(),
            'total_inquiries' => Inquiry::count(),
            'new_inquiries' => Inquiry::where('status', Inquiry::STATUS_NEW)->count(),
            'in_progress_inquiries' => Inquiry::where('status', Inquiry::STATUS_IN_PROGRESS)->count(),
            'responded_inquiries' => Inquiry::where('status', Inquiry::STATUS_RESPONDED)->count(),
        ];

        $recentInquiries = Inquiry::with('product')->latest()->take(6)->get();
        $recentAuditLogs = AuditLog::with('user')->latest()->take(8)->get();

        return view('admin.dashboard', compact('metrics', 'recentInquiries', 'recentAuditLogs'));
    }
}
