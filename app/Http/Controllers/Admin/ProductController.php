<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Admin\Products\AssignAttributeRequest;
use App\Http\Requests\Admin\Products\CreateProductRequest;
use App\Http\Requests\Admin\Products\updateProductPriceAttributeRequest;
use App\Http\Requests\Admin\Products\updateProductQuantityAttributeRequest;
use App\Http\Requests\Admin\Products\UpdateProductRequest;
use App\Http\Requests\Admin\Products\UploadImagesRequest;
use App\Models\Attribute;
use App\Models\Image;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Repositories\Admin\AttributeRepository;
use App\Repositories\Admin\AttributeValueRepository;
use App\Repositories\Admin\CategoryRepository;
use App\Repositories\Admin\ProductAttributeRepository;
use App\Repositories\Admin\ProductRepository;
use App\Tools\InterventionFilters\ImageFilter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image as ImageIntervention;

class ProductController extends BaseController
{
    /**
     * @var ProductRepository
     */
    private $productRepository;
    /**
     * @var AttributeRepository
     */
    private $attributeRepository;
    /**
     * @var AttributeValueRepository
     */
    private $attributeValueRepository;
    /**
     * @var ProductAttributeRepository
     */
    private $productAttributeRepository;
    /**
     * @var CategoryRepository
     */
    private $categoryRepository;

    /**
     * ProductController constructor.
     *
     * @param ProductRepository $productRepository
     * @param AttributeRepository $attributeRepository
     * @param AttributeValueRepository $attributeValueRepository
     * @param ProductAttributeRepository $productAttribute
     */
    public function __construct(
        ProductRepository $productRepository,
        AttributeRepository $attributeRepository,
        AttributeValueRepository $attributeValueRepository,
        ProductAttributeRepository $productAttributeRepository,
        CategoryRepository $categoryRepository
    ) {
        Cache::forget('featured-product');
        $this->productRepository = $productRepository;
        $this->attributeRepository = $attributeRepository;
        $this->attributeValueRepository = $attributeValueRepository;
        $this->productAttributeRepository = $productAttributeRepository;
        $this->categoryRepository = $categoryRepository;
    }


    public function index(Request $request)
    {
        $searchPhrase = $request->get('search') ?? null;
        $this->setPageTitle(trans('products.name'));
        $this->setSideBar('products');
        $products = $this->productRepository->listProducts(true, $searchPhrase);
        return view('admin.products.index', compact('searchPhrase','products'));
    }

    public function create()
    {
        $this->setPageTitle(trans('products.create_product'));
        $this->setSideBar('products');
        $categories = $this->categoryRepository->list();
        return view('admin.products.create', compact('categories'))->with(['tab' => 'product_attribute']);
    }

    public function store(CreateProductRequest $request)
    {
        $data = $request->validated();
        $product = $this->productRepository->createProduct($data);
        return redirect()->route('admin.products.edit', $product->id)->with('message', trans('products.product_updated_successfully'));
    }

    public function edit(Product $product,Request $request)
    {
        $tab = $request->get('tab',null);
        $this->setPageTitle(trans('products.edit_product'));
        $this->setSideBar('products');
        $productAttributes = $product->attributes()->get();//TODO: use eager load instead of another query
        $categories = $this->categoryRepository->list();
        return view('admin.products.edit', compact('tab','product', 'productAttributes', 'categories'));
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $this->setPageTitle(trans('products.name'));
        $this->setSideBar('products');
        $data = $request->validated();
        $this->productRepository->updateProduct($data, $product);
        return redirect()->route('admin.products.edit', $product->id)
            ->with('message', trans('products.product_updated_successfully'))
            ->with(['tab' => 'product_attribute']);
    }

    public function uploadImages(UploadImagesRequest $request, Product $product)
    {
        $data = $request->file('images');
        $this->productRepository->saveImages(collect($data), $product);
        return redirect()->route('admin.products.edit', ['tab' => 'product_images','product' => $product->id])
            ->with('message', trans('products.product_image_uploaded_successfully'));
    }

    public function deleteImage(Product $product, Image $image)
    {
        $this->productRepository->deleteImage($image);
    }

    public function delete(Product $product)
    {
        $this->productRepository->delete($product);
        return redirect()->route('admin.products.index', $product->id)->with('message', trans('products.product_removed_successfully'));
    }

    public function assignAttributes(Product $product)
    {
        $this->setPageTitle(trans('products.name'));
        $this->setSideBar('products');
        $attributes = Attribute::query()->with('values')->get();
        return view('admin.products.add_attribute', compact('attributes', 'product'));
    }

    public function storeAssignAttributes(AssignAttributeRequest $request, Product $product)
    {
        $this->setPageTitle(trans('products.name'));
        $this->setSideBar('products');
        $data = $request->validated();
        $this->productRepository->AssignAttributes($product, $data);
        return redirect()->route('admin.products.edit', $product->id);
    }


    public function uploadClothImageSizeShow(Request $request)
    {
        $this->setPageTitle('راهنمای سایز لباس');
        $this->setSideBar('uploadClothImageSize');
        $image = Image::query()->where([
            'imageable_id' => 0,
            'imageable_type' => 'cloth_image_size_guidance',
        ])->first();
        return view('admin.products.clothImageSizeGuidance',compact('image'));
    }
    public function deleteClothImageSizeShow(Request $request)
    {
        $this->setPageTitle('راهنمای سایز لباس');
        $this->setSideBar('uploadClothImageSize');
        $image = Image::query()->where([
            'imageable_id' => 0,
            'imageable_type' => 'cloth_image_size_guidance',
        ])->first();
        return view('admin.products.clothImageSizeGuidance',compact('image'));
    }
    public function uploadClothImageSize(Request $request)
    {
        $file = $request->file('image');
        $dir = Config::get('custom_config.files.store_directory');
        $disk = Config::get('custom_config.files.disk');
        if (!File::isDirectory($dir)){
            File::makeDirectory($dir, 0777, true, true);
        }
        $path = $file->hashName($dir);
        $image = ImageIntervention::make($file);


        $isImageSaved = Storage::disk($disk)
            ->put($path, (string)$image->encode());

        if ($isImageSaved) {

            $image = Image::query()->updateOrCreate([
                'imageable_id' => 0,
                'imageable_type' => 'cloth_image_size_guidance',
            ],[
                'path' => $path,
                'imageable_id' => 0,
                'imageable_type' => 'cloth_image_size_guidance',
            ]);
        }

        return redirect()->route('admin.products.upload_cloth_image_size_show')
            ->with('message', trans('products.product_image_uploaded_successfully'));
    }

    /************************************************************************************************************
     *
     *                              API
     *
     *
     *********************************************************************************************************** */

    public function updateProductPriceAttribute(Product $product, ProductAttribute $attribute, updateProductPriceAttributeRequest $request)
    {
        $data = $request->validated();
        $result = tap($attribute)->update([
           'price' => $data['value'] + $attribute->price
        ]);
        return response()->json([
           'data' => $result->toArray()
        ]);
    }

    public function updateProductQuantityAttribute(Product $product, ProductAttribute $attribute, updateProductQuantityAttributeRequest $request)
    {
        $data = $request->validated();
        $result = tap($attribute)->update([
            'quantity' => $data['value'] + $attribute->quantity
        ]);
        return response()->json([
            'data' => $result->toArray()
        ]);
    }
}
