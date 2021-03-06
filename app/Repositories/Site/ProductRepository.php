<?php


namespace App\Repositories\Site;

use App\Models\Product;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ProductRepository extends BaseRepository
{
    public function __construct(Product $product)
    {
        parent::__construct($product);
        $this->model = $product;
    }

    public function show(Product $product)
    {
        return $this->model->newQuery()
            ->with(['attributes'=> function ($query) {
                $query->where('quantity', '>', 0);
                $query->with('attributesValues');
            }])
            ->with(['categories', 'discount'])
            ->whereHas('categories')
            ->whereHas('attributes.attributesValues')
            ->findOrFail($product->id);
    }

    public function getSimilarProductBaseOnCategory($product)
    {
        $productCategories = $product->categories->pluck('id');

        $similarProducts = $product
            ->whereHas('categories', function ($query) use ($productCategories) {
                $query->whereIn('categories.id', $productCategories);
            })->with('images', 'discount')->limit(10)->get();

        return $similarProducts;
    }

    public function listOfProduct(array $filters)
    {
        $products = $this->model->newQuery()->limit(50);
        $products->with(['attributes']);
        $products->with(['categories']);
        $products->with(['image']);


        if (!empty($filters['category']) || !empty($filters['brand'])) {
            $products->whereHas('categories', function ($query) use ($filters) {
                if (!empty($filters['category'])) {
                    $query->where('categories.id', $filters['category']);
                }
            });

            $products->whereHas('categories', function ($query) use ($filters) {
                if (!empty($filters['brand'])) {
                    $query->WhereIn('categories.id', $filters['brand']);
                }
            });
        }


        if (!empty($filters['size'])) {
            $products->whereHas('attributes', function ($query) use ($filters) {
                $query->where('quantity', '>', 1);
                $query->whereHas('attributesValues', function ($query) use ($filters) {
                    $query->whereIn('id', $filters['size']);
                });
            });
        }

        if (!empty($filters['gender'])) {
            $products->whereIn('gender', $filters['gender']);
        }

        $products = $products->get()->sortByDesc(function ($products) {
            return $products->attributes->sum('quantity');
        });


        $hasNotQuantity = $products->where('has_quantity', false);
        $hasQuantity = $products->where('has_quantity', true);
        $products = $hasQuantity->concat($hasNotQuantity);
        return $products->paginate(15, $products->count(), $filters['page'] ?? 1);
    }

    public function featuredProducts()
    {
        if (!Cache::has('featured-product')){
            $result = $this->model->newQuery()
                ->where('status', Product::ACTIVE)
                ->where('featured', Product::ACTIVE)
                ->with(['attributes'])
                ->whereHas('attributes', function (Builder $query) {
                    $query->where('quantity', '>', 0);
                })
                ->limit(12)->get();
            Cache::forever('featured-product',$result);
        }

        return Cache::get('featured-product');
    }

    public function search($keyword)
    {
        return $this->model
            ->with('image')
            ->where('product_name', 'like', "%$keyword%")
            ->inRandomOrder()
            ->simplePaginate(5)->items();
    }
}
