<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Faker\Guesser\Name;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $category = Category::orderby('name', 'ASC');
        if ($request->has('search') && !empty($request->search)) {
            $category = $category->where('name', 'like', '%' . $request->search . '%');
        }
        $category = $category->paginate(10);
        if ($category->isEmpty()) {
            return response()->json([
                "message" => "Category not found",
            ], 404);
        }
        return response()->json(['category' => $category], 201);
    }

    /**
     * Show the form for creating a new resource.
     */
    // public function create(Request $request)
    // {
    //     $data = $request->validate([
    //         'name' => 'required|string|max:255',
    //         'description' => 'required|string',
    //     ]);
    //     $category = Category::create($data);
    //     return response(new CategoryResource($category), 201);
    // }
    public function create(Request $request)
    {
        $data = $request->validate([
            '*.name' => 'required|string|max:255|unique:' . Category::class, //!wildcard
            '*.description' => 'required|string',
        ]);

        // $categories = [];

        foreach ($data as $categoryData) {
            $categories[] = Category::create($categoryData);
        }
        return response(CategoryResource::collection($categories), 201);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $category = Category::find($id);
        return response([
            'category' => $category,
        ]);
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
        $data = $request->validate([
            'name' => 'required|string|unique:' . Category::class,
            'description' => 'required|string',
        ]);

        if (isset($data)) {
            $category = Category::find($id);
            $category->update($data);
            if (!$category) {
                return response()->json([
                    'message' => 'Category not found'
                ], 404);
            } else {
                return response()->json([
                    'message' => 'Category have been updated successfully',
                    'data' => new CategoryResource($category)
                ], 201);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(string $id, Category $category)
    {
        $category = $category->find($id);
        if (!$category) {
            return response()->json([
                'message' => 'Category not found',
            ]);
        }
        $category->delete();
        return response()->json([
            'message' => 'Category has been deleted',
        ]);
    }
}
