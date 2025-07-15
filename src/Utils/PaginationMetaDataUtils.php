<?php

namespace Corekit\Utils;

class PaginationMetaDataUtils
{
    /**
     * Generate pagination metadata from a paginated instance.
     *
     * @param mixed $paginator
     * @return array
     */
    public static function extract($paginator): array
    {
        if (
            method_exists($paginator, 'total') &&
            method_exists($paginator, 'currentPage') &&
            method_exists($paginator, 'perPage') &&
            method_exists($paginator, 'lastPage') &&
            method_exists($paginator, 'nextPageUrl') &&
            method_exists($paginator, 'nextPageUrl') &&
            method_exists($paginator, 'previousPageUrl')
        ) {

            return [
                'total_count'  => $paginator->total(),
                'current_page' => $paginator->currentPage(),
                'per_page'     => $paginator->perPage(),
                'last_page'    => $paginator->lastPage(),
                'next_page'    => $paginator->nextPageUrl(),
                'prev_page'    => $paginator->previousPageUrl(),
            ];
        }

        throw new \InvalidArgumentException('Provided instance does not support pagination methods.');
    }
}