<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Product;
use Psy\Readline\Hoa\Console;
use App\Models\Image;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::all();
        return view('products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('products.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'=>'required',
            'purchase_price'=>'required',
            'sales_price'=>'required',
        ]);

        $color = json_encode($request->color);
        $size = json_encode($request->size);

        if($request->has('images')){
            foreach($request->file('images')as $image){
                $imageName = $image->getClientOriginalName();
                $image->move(public_path('images'),$imageName);
                $uploadimage[] = $imageName;
            }

            $totalImages = json_encode($uploadimage);

            Product::create($data + ['color'=>$color, 'size'=>$size,'images'=>$totalImages] );

        } else {
            echo "No images found";
        }
    return response()->json(['success' => true, 'message' => 'Product created successfully']);
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
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        return view('products.edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $product->name = $request->name;
        $product->purchase_price = $request->purchase_price;
        $product->sales_price = $request->sales_price;
        $product->color = json_encode($request->color);
        $product->size = json_encode($request->size);
    
        // Handle existing images
        $existingImages = $request->input('existing_images', []);
        $existingImages = json_decode($product->images, true) ?? [];
    
        // Remove the images that were marked for deletion
        $removedImages = json_decode($request->input('removed_images', '[]'), true) ?? [];
        if ($removedImages) {
            foreach ($removedImages as $imagePath) {
                $imagePath = public_path('images/' . $imagePath);
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }
    
            // Remove the images from the existing images array
            $existingImages = array_diff($existingImages, $removedImages);
    
            // Update the images attribute of the $product object
            $product->images = json_encode(array_values($existingImages));
        }
    
        // Handle new images
        $newImages = $request->file('images');
        if ($newImages) {
            $uploadImages = [];
            foreach ($newImages as $image) {
                $imageName = $image->getClientOriginalName();
                $image->move(public_path('images'), $imageName);
                $uploadImages[] = $imageName;
            }
            $allImages = array_merge($existingImages, $uploadImages);
            $product->images = json_encode($allImages);
        }
    
        $product->save();
    
        return response()->json(['success' => true, 'message' => 'Product updated successfully']);
    }
    
    /**
     * Remove the specified resource from storage.
     */
    public function delete($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['success' => true, 'message' => 'Product deleted successfully.']);
        }

        $imagePaths = json_decode($product->images);
        if ($imagePaths) {
            foreach ($imagePaths as $imagePath) {
                $imagePath = public_path('images/' . $imagePath);
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }
        }

        $product->delete();

        return response()->json(['success' => true, 'message' => 'Product deleted successfully.']);

    }
}