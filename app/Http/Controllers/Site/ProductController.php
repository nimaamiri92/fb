<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Site\Product\productListRequest;
use App\Models\Image;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\Slider;
use App\Repositories\Admin\AttributeRepository;
use App\Repositories\Admin\CategoryRepository;
use App\Repositories\Site\ProductRepository;
use App\Services\Site\ProductService;
use Symfony\Component\VarDumper\Cloner\Data;
use Symfony\Component\VarDumper\Cloner\VarCloner;
use Symfony\Component\VarDumper\Dumper\CliDumper;
use Symfony\Component\VarDumper\Dumper\HtmlDumper;
use Symfony\Component\VarDumper\Server\DumpServer;
use Symfony\Component\VarDumper\VarDumper;

class ProductController extends BaseController
{
    /**
     * @var ProductRepository
     */
    private $productRepository;
    /**
     * @var ProductService
     */
    private $productService;
    /**
     * @var AttributeRepository
     */
    private $attributeRepository;
    /**
     * @var CategoryRepository
     */
    private $categoryRepository;

    public function __construct(
        ProductRepository $productRepository,
        ProductService $productService,
        AttributeRepository $attributeRepository,
        CategoryRepository $categoryRepository
    ) {
        $this->productRepository = $productRepository;
        $this->productService = $productService;
        $this->attributeRepository = $attributeRepository;
        $this->categoryRepository = $categoryRepository;
    }

    public function index(productListRequest $request)
    {
        $filters['gender'] = [];
        $filters['category'] = [];
        $filters['size'] = [];
        $filters['brand'] = [];
        $filters['page'] = $request->get('page');

        $filters = array_merge($filters, $request->validated());
        $this->setPageTitle('محصولات');
        $this->setCartContent();

        //get list products with filters
        $products = $this->productRepository->listOfProduct($filters);

        //list of product also can return json response
        if ($request->has('api')) {
            return response()->json([
                'data' => $products,
                'status' => 200
            ]);
        }

        $categories = $this->categoryRepository->getPluckAllCategory();
        $brands = $this->categoryRepository->getPluckAllBrand();
        $attributes = $this->attributeRepository->getActiveListOfAttributesWithValues();

        return view('site.products.index', compact('filters', 'products', 'attributes', 'categories', 'brands'));
    }

    public function show(Product $product)
    {
        $this->setPageTitle($product->product_name);
        $this->setCartContent();
        $product = $this->productRepository->show($product);
        $similarProducts = $this->productRepository->getSimilarProductBaseOnCategory($product);
        $imageSizeGuide = Image::query()->where([
            'imageable_id' => 0,
            'imageable_type' => 'cloth_image_size_guidance',
        ])->first();
        return view('site.products.show', compact('product', 'similarProducts','imageSizeGuide'));
    }


    /******************************************************************************************************
     *                                           API
     ******************************************************************************************************
     *
     */

    public function getProductPriceByProductAttribute(ProductAttribute $productAttribute)
    {
        $result = $this->productService->getProductPriceByProductAttribute($productAttribute);
        return response()->json(['data' => $result], 200);
    }
}
