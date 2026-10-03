<?php

namespace App\Services\Search;

use Illuminate\Pagination\LengthAwarePaginator;

interface SearchServiceInterface
{
    /**
     * Search products with query, filters, sorting, and pagination.
     *
     * @param array<string, mixed> $criteria
     */
    public function searchProducts(array $criteria, int $perPage = 12): LengthAwarePaginator;
}
