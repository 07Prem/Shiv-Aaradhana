<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RfqCartController extends Controller
{
    /**
     * Get all items currently in customer's quotation list.
     */
    public function index(Request $request): JsonResponse
    {
        $items = session()->get('rfq_items', []);

        return response()->json([
            'success' => true,
            'items' => array_values($items),
            'count' => count($items),
        ]);
    }

    /**
     * Add a product to the quotation list.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['nullable', 'max:100'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        if (isset($validated['quantity']) && is_numeric($validated['quantity']) && (float)$validated['quantity'] <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'The quantity must be greater than zero.',
                'errors' => ['quantity' => ['The quantity must be greater than zero.']],
            ], 422);
        }

        $product = Product::published()->find($validated['product_id']);
        if (! $product) {
            return response()->json([
                'success' => false,
                'message' => 'The selected product is not available for quotation.',
                'errors' => ['product_id' => ['The selected product is not available for quotation.']],
            ], 422);
        }

        $items = session()->get('rfq_items', []);
        $productId = (int) $product->id;

        $inputQty = filled($validated['quantity'] ?? null)
            ? (string) $validated['quantity']
            : ($product->minimum_order_qty ?? '1 x 20ft FCL');

        if (isset($items[$productId])) {
            // Already in cart - increment if numeric, otherwise update
            if (filled($validated['quantity'] ?? null)) {
                if (is_numeric($items[$productId]['quantity']) && is_numeric($validated['quantity'])) {
                    $items[$productId]['quantity'] = (string) ((float) $items[$productId]['quantity'] + (float) $validated['quantity']);
                } else {
                    $items[$productId]['quantity'] = (string) $validated['quantity'];
                }
            }
            if (isset($validated['notes'])) {
                $items[$productId]['notes'] = $validated['notes'];
            }
            $message = "Updated '{$product->name}' in your quotation list.";
        } else {
            $items[$productId] = [
                'product_id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'hs_code' => $product->hs_code,
                'origin' => $product->origin,
                'primary_image' => $product->primary_image,
                'quantity' => $inputQty,
                'notes' => $validated['notes'] ?? '',
            ];
            $message = "Added '{$product->name}' to your quotation request.";
        }

        session()->put('rfq_items', $items);

        return response()->json([
            'success' => true,
            'message' => $message,
            'items' => array_values($items),
            'count' => count($items),
            'added_product' => $items[$productId],
        ]);
    }

    /**
     * Update quantity and specifications for a product in the quotation list.
     */
    public function update(Request $request, int $productId): JsonResponse
    {
        $validated = $request->validate([
            'quantity' => ['required', 'max:100'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        if (is_numeric($validated['quantity']) && (float)$validated['quantity'] <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'The quantity must be greater than zero.',
                'errors' => ['quantity' => ['The quantity must be greater than zero.']],
            ], 422);
        }

        $items = session()->get('rfq_items', []);

        if (! isset($items[$productId])) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found in your quotation list.',
            ], 404);
        }

        $items[$productId]['quantity'] = (string) $validated['quantity'];
        if (array_key_exists('notes', $validated)) {
            $items[$productId]['notes'] = $validated['notes'];
        }

        session()->put('rfq_items', $items);

        return response()->json([
            'success' => true,
            'message' => "Quotation requirements updated for '{$items[$productId]['name']}'.",
            'items' => array_values($items),
            'count' => count($items),
        ]);
    }

    /**
     * Remove a product from the quotation list.
     */
    public function destroy(int $productId): JsonResponse
    {
        $items = session()->get('rfq_items', []);

        $removedName = $items[$productId]['name'] ?? 'Product';
        unset($items[$productId]);

        session()->put('rfq_items', $items);

        return response()->json([
            'success' => true,
            'message' => "Removed '{$removedName}' from quotation request.",
            'items' => array_values($items),
            'count' => count($items),
        ]);
    }

    /**
     * Clear all products from the quotation list.
     */
    public function clear(): JsonResponse
    {
        session()->forget('rfq_items');

        return response()->json([
            'success' => true,
            'message' => 'Quotation request list cleared.',
            'items' => [],
            'count' => 0,
        ]);
    }
}
