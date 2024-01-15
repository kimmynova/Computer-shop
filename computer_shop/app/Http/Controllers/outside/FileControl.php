<?php

namespace App\Http\Controllers\outside;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;

class FileControl extends Controller
{
    // public function create(Request $request){
    //     $request->validate([
    //         'name' => 'required',
    //         'files.*' => 'required|image|mimes:jpg,jpeg,png|max:2048',
    //     ]);

    //     if ($request->hasFile('files')) {
    //         $images = [];

    //         foreach ($request->file('files') as $key => $image) {
    //             $imageName = $request->input('name') . '-image-' . time() . $key . '.' . $image->extension();
    //             $image->move(public_path('product_images'), $imageName);
    //             $images[] = $imageName;
    //         }

    //         return response()->json(['message' => 'Images uploaded successfully', 'images' => $images], 200);
    //     }

    //     return response()->json(['message' => 'No images uploaded'], 400);

    // }
//     public function create(Request $request)
// {
//     $request->validate([
//         'name' => 'required',
//         'files.*' => 'required|image|mimes:jpg,jpeg,png|max:2048',
//     ]);

//     if ($request->hasFile('files')) {
//         $images = [];

//         foreach ($request->file('files') as $key => $image) {
//             $imageName = $request->input('name') . '-image-' . time() . $key . '.' . $image->extension();

//             // Move the uploaded file to the 'uploads' directory within the 'storage' folder
//             $image->storeAs('uploads', $imageName, 'public');

//             // Generate the public URL for the uploaded image
//             $imageUrl = url('storage/uploads/' . $imageName);

//             $images[] = $imageUrl;
//         }

//         return response()->json(['message' => 'Images uploaded successfully',

//         'images' => $images], 200);
//     }

//     return response()->json(['message' => 'No images uploaded'], 400);
// }
public function create(Request $request)
{
    $images = $request->file('images');
    $imageNames = [];

    if ($images) {
        foreach ($images as $image) {
            $newName = rand() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('/uploads/images'), $newName);
            $imageNames[] = $newName;
        }
    }
    $imageNamesString = implode(',', $imageNames);

    return response()->json(['images' => $imageNamesString]);
}


}
