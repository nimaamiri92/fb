<?php

namespace App\Http\View\Composers;

use App\Models\Category;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class MenuComposer
{
    public function compose(View $view)
    {
        if (Cache::has('menu')) {
            return Cache::get('menu');
        }


        Cache::remember('menu', 3600, function () use ($view) {
            $view->with('menus', Category::with('children')
                ->whereIn('type', [Category::MENU])
                ->where('status', Category::ACTIVE)
                ->orderBy('order', 'asc')
                ->get());
        });

        return Cache::get('menu');
    }
}
