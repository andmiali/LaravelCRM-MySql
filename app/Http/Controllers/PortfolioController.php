<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Portfolio;
use Illuminate\Support\Facades\Storage;
class PortfolioController extends Controller
{
    public function portfolio()
    {
        $ports = Portfolio::all();
        return view('admin.portfolio', compact('ports'));
    }

    public function edit($id)
    {
        // Retrieve the specific Portfolio section by its ID
        $ports = Portfolio::findOrFail($id);
        $prt = Portfolio::all();
    
        // Pass the Portfolio section to the view
        return view('admin.portfolioedit', compact('ports', 'prt'));
    }
    
    public function update(Request $request, $id)
    {
        // Retrieve the specific Portfolio section by its ID
        $ports = Portfolio::findOrFail($id);

        // Validate the request data
        $validatedData = $request->validate([
            'title' => 'required|string',
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Adjust the validation rules as needed
        ]);

        // Update title and content
        $ports->title = $validatedData['title'];

        // Check if a new image has been uploaded
        if ($request->hasFile('image')) {
            // Delete the old image if it exists
            if ($ports->image) {
                $imagePath = public_path($ports->image);
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }

            // Move the new image to the public/images directory
            $imageName = time() . '.' . $request->image->getClientOriginalExtension();
            $request->image->move(public_path('images'), $imageName);
            $ports->image = 'images/'.$imageName;
        }

        // Save the changes to the Portfolio section
        $ports->save();

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Portfolio section updated successfully.');
    }

    public function delete(Request $request, $id)
    {
        // Retrieve the specific Portfolio section by its ID
        $ports = Portfolio::findOrFail($id);

         // Check if the Portfolio section exists
         if (!$ports) {
            // If the Portfolio section does not exist, return a response
            return redirect()->back()->with('error', 'Portfolio section not found.');
        }

        if ($ports->image) {
                $imagePath = public_path($ports->image);
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
        }


        // Save the changes to the Portfolio section
        $ports->delete();

        // Redirect back with a success message
        return redirect()->route('adminportfolio')->with('success', 'Portfolio section deleted successfully.');
        }


    public function store(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'title' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Adjust file type and size as needed
        ]);
    
        // Check if the file was uploaded successfully
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            // Get the original file name
            $originalFileName = $request->file('image')->getClientOriginalName();
    
            // Move the uploaded image to the public/images directory
            $request->file('image')->move(public_path('images'), $originalFileName);
    
            // Create a new Portfolio model instance and save it to the database
            Portfolio::create([
                'title' => $validatedData['title'],
                'image' => 'images/' . $originalFileName, // Save the path to the image in the database
            ]);
    
            // Redirect back or to a specific route after successful submission
            return redirect()->back()->with('success', 'Portfolio section added successfully!');
        }
    
        // If the file upload fails, redirect back with an error message
        return redirect()->back()->with('error', 'Failed to upload image. Please try again.');
    }
}
