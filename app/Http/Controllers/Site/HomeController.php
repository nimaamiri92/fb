<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\BaseController;
use App\Models\Category;
use App\Repositories\Admin\CategoryRepository;
use App\Repositories\Site\ProductRepository;
use App\Repositories\Site\SliderRepository;
use Illuminate\Http\Request;

class HomeController extends BaseController
{
    /**
     * @var SliderRepository
     */
    private $sliderRepository;
    /**
     * @var CategoryRepository
     */
    private $categoryRepository;
    /**
     * @var ProductRepository
     */
    private $productRepository;

    public function __construct(
        SliderRepository $sliderRepository,
        CategoryRepository $categoryRepository,
        ProductRepository $productRepository
    ) {
        $this->sliderRepository = $sliderRepository;
        $this->categoryRepository = $categoryRepository;
        $this->productRepository = $productRepository;
    }

    public function index()
    {
        $this->setPageTitle('فروشگاه اینترنتی بی سی سی');
        $this->setCartContent();
        $featuredProducts= $this->productRepository->featuredProducts();
        $brands = $this->categoryRepository->allWith(Category::BRAND, Category::ACTIVE);
        $sliders =  $this->sliderRepository->getSliders();
        return view('home', compact('sliders', 'featuredProducts', 'brands'));
    }

    public function search(Request $request)
    {
        $keyword = $request->get('search',null);
        if (!$keyword){
            return response()->json([], 200);
        }
        $response = $this->productRepository->search($keyword);
        return response()->json(['result' => $response], 200);
    }

    public function aboutUs()
    {
        $this->setPageTitle('فروشگاه اینترنتی بی سی سی');
        $this->setCartContent();
        return view('site.layouts.about-us');
    }


}
