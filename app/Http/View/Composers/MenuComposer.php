<?php

namespace App\Http\View\Composers;

use App\Models\Category;
use Illuminate\View\View;

class MenuComposer
{
    public function compose(View $view)
    {
        $view->with('menus', Category::with('children')
            ->whereIn('type', [Category::MENU])
            ->where('status', Category::ACTIVE)
            ->orderBy('order', 'asc')
            ->get());
    }
}
