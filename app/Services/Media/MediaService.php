<?php

namespace App\Services\Media;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use InvalidArgumentException;

class MediaService
{
    protected array $allowedMimes = [
        'image/jpeg',
        'image/png',
        'image/webp',
    ];

    protected array $disallowedExtensions = [
        'php', 'phtml', 'phar', 'exe', 'sh', 'bat', 'cmd', 'js', 'html', 'htm', 'cgi', 'pl'
    ];

    /**
     * Store and associate a product gallery image.
     */
    public function uploadProductImage(Product $product, UploadedFile $file, bool $isPrimary = false, ?string $alt = null): ProductImage
    {
        $this->validateFile($file);

        $extension = strtolower($file->getClientOriginalExtension());
        $filename = Str::uuid() . '.' . $extension;
        $path = $file->storeAs("products/{$product->id}", $filename, 'public');

        if ($isPrimary) {
            // Unset previous primary images for this product
            ProductImage::where('product_id', $product->id)->update(['is_primary' => false]);
            $product->update(['primary_image' => Storage::url($path)]);
        }

        $highestSort = ProductImage::where('product_id', $product->id)->max('sort_order') ?? 0;

        return ProductImage::create([
            'product_id' => $product->id,
            'file_path' => Storage::url($path),
            'alt_text' => $alt ?? ($product->name . ' - Shiv Aaradhana Export Quality'),
            'sort_order' => $highestSort + 1,
            'is_primary' => $isPrimary,
        ]);
    }

    /**
     * Remove a product image and purge from disk.
     */
    public function deleteProductImage(ProductImage $image): void
    {
        $filePath = str_replace('/storage/', '', $image->file_path);
        if (Storage::disk('public')->exists($filePath)) {
            Storage::disk('public')->delete($filePath);
        }

        $productId = $image->product_id;
        $wasPrimary = $image->is_primary;

        $image->delete();

        // If primary was deleted, assign the next available image as primary
        if ($wasPrimary) {
            $nextImage = ProductImage::where('product_id', $productId)->first();
            if ($nextImage) {
                $nextImage->update(['is_primary' => true]);
                Product::where('id', $productId)->update(['primary_image' => $nextImage->file_path]);
            } else {
                Product::where('id', $productId)->update(['primary_image' => null]);
            }
        }
    }

    /**
     * Validate upload for strict MIME, extension, and integrity.
     */
    protected function validateFile(UploadedFile $file): void
    {
        $extension = strtolower($file->getClientOriginalExtension());
        if (in_array($extension, $this->disallowedExtensions, true)) {
            throw new InvalidArgumentException("Illegal or unsafe file extension: .{$extension}");
        }

        $mime = $file->getMimeType();
        if (! in_array($mime, $this->allowedMimes, true)) {
            throw new InvalidArgumentException("Unsupported media type: {$mime}. Only JPG, PNG, and WebP are accepted.");
        }

        // 5MB limit
        if ($file->getSize() > 5 * 1024 * 1024) {
            throw new InvalidArgumentException("File size exceeds maximum permitted limit of 5MB.");
        }
    }
}
