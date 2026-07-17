<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

abstract class Controller
{
    protected function tablePerPage(Request $request): int
    {
        $value = (int) $request->input('per_page', 10);
        return in_array($value, [10, 25, 50, 100], true) ? $value : 10;
    }

    protected function tableSort(Request $request, array $allowed, string $default = 'created_at'): array
    {
        $sort = $request->input('sort', $default);
        $direction = $request->input('direction', 'desc');
        return [in_array($sort, $allowed, true) ? $sort : $default, in_array($direction, ['asc', 'desc'], true) ? $direction : 'desc'];
    }
}
