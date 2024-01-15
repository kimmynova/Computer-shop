<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductsResource;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Products;
use Illuminate\Database\DBAL\TimestampType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Str;
use IntlChar;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // public function index(Request $request)
    // {
    //     $files = Storage::disk('public')->allFiles('upload');

    //     $products = Products::orderBy('name', 'ASC');
    //     if ($request->has('search') && !empty($request->search)) {
    //         $products = $products->where('name', 'like', '%' . $request->search . '%');
    //     }
    //     $products = $products->paginate(10);

    //     if ($products->isEmpty()) {
    //         return response([
    //             "message" => "Products not found",
    //         ], 404);
    //     }
    //     $response = [
    //         // 'products' => $products->items(), //! i made mistake with add items()
    //         'products' => $products,
    //         'files' => [],
    //     ];
    //     foreach ($files as $file) {
    //         $response['files'][] = [
    //             'name' => basename($file),
    //             'url' => Storage::disk('public')->url($file),
    //             'size' => round(Storage::disk('public')->size($file) / 1024 / 1024, 2) . 'MB',
    //             'created_at' => \Carbon\Carbon::parse(Storage::disk('public')->lastModified($file))->diffForHumans(),
    //             'extension' => pathinfo($file, PATHINFO_EXTENSION),
    //         ];
    //     }

    //     return response()->json($response);
    // }
    public function index(Request $request)
    {
        $files = Storage::disk('public')->allFiles('uploads');

        $products = Products::orderBy('name', 'ASC');

        if ($request->has('search') && !empty($request->search)) {
            $products = $products->where('name', 'like', '%' . $request->search . '%');
        }

        // Assuming you want to filter products where the image_url is not empty
        $products = $products->whereNotNull('image_url')->paginate(10);

        if ($products->isEmpty()) {
            return response([
                "message" => "Products not found",
            ], 404);
        }

        foreach ($products as $product) {
            $imageUrls = [];
            $imageFileNames = explode(',', $product->image_url);

            foreach ($imageFileNames as $imageName) {
                $imageUrl = Storage::disk('public')->url('uploads/' . trim($imageName));
                $imageUrls[] = $imageUrl;
            }

            $product->image_urls = $imageUrls;
        }

        $response = [
            'products' => $products,
            'files' => [],
        ];

        foreach ($files as $file) {
            $response['files'][] = [
                'name' => basename($file),
                'url' => Storage::disk('public')->url($file),
                'size' => round(Storage::disk('public')->size($file) / 1024 / 1024, 2) . 'MB',
                'created_at' => \Carbon\Carbon::parse(Storage::disk('public')->lastModified($file))->diffForHumans(),
                'extension' => pathinfo($file, PATHINFO_EXTENSION),
            ];
        }

        return response()->json($response);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'unit_price' => 'required',
            'cate_id' => 'required',
            'brands_id' => 'required',
            'image_url.*' => 'required|image|mimes:jpg,jpeg,png,csv,txt,xlsx,xls,pdf|max:2048',
        ]);
        // Fetch the name for the specified category ID
        $categoryName = Category::where('id', intval($request->input('cate_id')))->value('name');

        // Fetch the name for the specified brand ID
        $brand_name = Brand::where('id', intval($request->input('brands_id')))->value('brand_name');

        // $fileName = md5($request->file('image_url')->getClientOriginalName() . time()) . "." . $request->file('image_url')->getClientOriginalExtension();
        // $filePath = $request->file('image_url')->storeAs('uploads', $fileName, 'public');
        $images = $request->file('image_url');

    $imageNames =[];
        if ($images) {
            foreach ($images as $image) {
                $newName = rand() . '.' . $image->getClientOriginalExtension();
                $image->move(storage_path('app/public/uploads'), $newName);

                $imageNames[] = $newName;
            }
        }

        $imageNamesString = implode(',', $imageNames);


        if ($images) {

            $productData = [
                'uuid' => Uuid::uuid4()->toString(),
                'prod_code' => 'PCS-' . now()->timestamp,
                'name' => $request->input('name'),
                'description' => $request->input('description'),
                'unit_price' => $request->input('unit_price'),
                'cate_id' => $request->input('cate_id'),
                'category_name' => $categoryName,
                'brands_id' => $request->input('brands_id'),
                'brand_name' => $brand_name,
                'image_url' => $imageNamesString
            ];


            $product = Products::create($productData);
            $productResource = new ProductsResource($product);
//             dd($productResource);  // Dump the variable
// dd($image['image_url']);

            return response()->json([
                'message' => 'Product created successfully', 'product' => $productResource,
            ], 201);
        } else {
            return response()->json(['message' => 'Failed to upload product'], 500);
        }
    }



    // public function create(Request $request)
    // {
    //     $request->validate([
    //         'name' => 'required',
    //         'description' => 'required',
    //         'unit_price' => 'required',
    //         'cate_id' => 'required',
    //         'brands_id' => 'required',
    //         'image_url' => 'required|mimes:jpg,jpeg,png,csv,txt,xlsx,xls,pdf|max:2048',
    //     ]);
    //     // Fetch the name for the specified category ID
    //     $categoryName = Category::where('id', intval($request->input('cate_id')))->value('name');

    //     // Fetch the name for the specified brand ID
    //     $brand_name = Brand::where('id', intval($request->input('brands_id')))->value('brand_name');

    //     $fileName = md5($request->file('image_url')->getClientOriginalName() . time()) . "." . $request->file('image_url')->getClientOriginalExtension();
    //     $filePath = $request->file('image_url')->storeAs('uploads', $fileName, 'public');

    //     if ($filePath) {
    //         $productData = [
    //             'uuid' => Uuid::uuid4()->toString(),
    //             // 'prod_code' => rand(100000000000, 999999999999),
    //             // 'prod_code' => 'psc-' . now()->format('Y-m-d'),
    //             'prod_code' => 'PCS-' . now()->timestamp,
    //             'name' => $request->input('name'),
    //             'description' => $request->input('description'),
    //             'unit_price' => $request->input('unit_price'),
    //             'cate_id' => $request->input('cate_id'),
    //             'category_name' => $categoryName,
    //             'brands_id' => $request->input('brands_id'),
    //             'brand_name' => $brand_name,
    //             'image_url' => [
    //                 'name' => basename($filePath),
    //                 'url' => Storage::disk('public')->url($filePath),
    //                 'size' => round(Storage::disk('public')->size($filePath) / 1024 / 1024, 2) . 'MB',
    //                 'created_at' => \Carbon\Carbon::parse(Storage::disk('public')->lastModified($filePath))->diffForHumans(),
    //                 'extension' => pathinfo($filePath, PATHINFO_EXTENSION),
    //             ],
    //         ];


    //         $product = Products::create($productData);
    //         $productResource = new ProductsResource($product);

    //         return response()->json([
    //             'message' => 'Product created successfully', 'product' => $productResource,
    //         ], 201);
    //     } else {
    //         return response()->json(['message' => 'Failed to upload product'], 500);
    //     }
    // }

    // // if (!Storage::disk('public')->exists('uploads')) {
    // //     Storage::disk('public')->makeDirectory('uploads');
    // // }

    // // $filePaths = [];
    // // foreach ($request->file('image_url') as $image) {
    // //     $fileName = md5($image->getClientOriginalName() . time()) . "." . $image->getClientOriginalExtension();
    // //     $filePath = $image->storeAs('uploads', $fileName, 'public');
    // //     $filePaths[] = $filePath;
    // // }

    // if (!empty($filePaths)) {
    //     $imageInfo = [];



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(Request $request, string $id)
    {
        $product = Products::find($id);

        if (!$product) {
            return response()->json([
                'message' => 'Product not found!',
            ], 404);
        }

        $folderName = 'uploads';
        $product->delete();
        $fileNames = explode(',', $product->image_url);

        foreach ($fileNames as $fileName) {
            $filePath = $folderName . '/' . $fileName;
            if (Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            } else {
                return response()->json([
                    'message' => 'Error File found!',
                ]);
            }
        }

        if (Storage::disk('public')->allFiles($folderName) === []) {
            Storage::disk('public')->deleteDirectory($folderName);
        }

        return response()->json([
            'message' => 'Product and files deleted successfully.',
        ], 200);
    }


    // public function delete(Request $request, string $id)
    // {
    //     $product = Products::find($id);

    //     if (!$product) {
    //         return response()->json([
    //             'message' => 'Product not found!',
    //         ], 404);
    //     }

    //     // Delete the product from the database
    //     $product->delete();

    //     // Check if the file parameter is present in the request
    //     if ($request->has('uploads')) {
    //         $fileName = $request->input('uploads');
    //         $filePath = 'uploads/' . $fileName;

    //         // Check if the file exists
    //         if (Storage::disk('public')->exists($filePath)) {
    //             // Delete the file
    //             Storage::disk('public')->delete($filePath);
    //             return response()->json([
    //                 'message' => 'Product and file deleted successfully.',
    //             ], 200);
    //         } else {
    //             return response()->json([
    //                 'message' => 'File not found!',
    //             ], 404);
    //         }
    //     }

    //     return response()->json([
    //         'message' => 'File parameter is missing.',
    //     ], 400);
    // }
}
