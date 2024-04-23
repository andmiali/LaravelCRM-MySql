<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\First;
use Illuminate\Support\Facades\Storage;
class FirstController extends Controller
{
    public function first()
    {
        $first = First::all();
        return view('admin.first', compact('first'));
    }

    public function edit($id)
    {
        // Retrieve the specific first section by its ID
        $first = First::findOrFail($id);
        $frt = First::all();
    
        // Pass the first section to the view
        return view('admin.firstedit', compact('first', 'frt'));
    }
    
    public function update(Request $request, $id)
    {
        // Retrieve the specific first section by its ID
        $first = First::findOrFail($id);

        // Validate the request data
        $validatedData = $request->validate([
            'content' => 'required|string',
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Adjust the validation rules as needed
        ]);

        // Update title and content
        $first->content = $validatedData['content'];

        // Check if a new image has been uploaded
        if ($request->hasFile('image')) {
            // Delete the old image if it exists
            if ($first->image) {
                $imagePath = public_path($first->image);
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }

            // Move the new image to the public/images directory
            $imageName = time() . '.' . $request->image->getClientOriginalExtension();
            $request->image->move(public_path('images'), $imageName);
            $first->image = 'images/'.$imageName;
        }

        // Save the changes to the first section
        $first->save();

        // Redirect back with a success message
        return redirect()->back()->with('success', 'First section updated successfully.');
    }

    public function delete(Request $request, $id)
    {
        // Retrieve the specific first section by its ID
        $first = First::findOrFail($id);

         // Check if the first section exists
         if (!$first) {
            // If the first section does not exist, return a response
            return redirect()->back()->with('error', 'First section not found.');
        }

        if ($first->image) {
                $imagePath = public_path($first->image);
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
        }


        // Save the changes to the first section
        $first->delete();

        // Redirect back with a success message
        return redirect()->route('adminfirst')->with('success', 'First section deleted successfully.');
        }


    public function store(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'content' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Adjust file type and size as needed
        ]);
    
        // Check if the file was uploaded successfully
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            // Get the original file name
            $originalFileName = $request->file('image')->getClientOriginalName();
    
            // Move the uploaded image to the public/images directory
            $request->file('image')->move(public_path('images'), $originalFileName);
    
            // Create a new first model instance and save it to the database
            First::create([
                'content' => $validatedData['content'],
                'image' => 'images/' . $originalFileName, // Save the path to the image in the database
            ]);
    
            // Redirect back or to a specific route after successful submission
            return redirect()->back()->with('success', 'first section added successfully!');
        }
    
        // If the file upload fails, redirect back with an error message
        return redirect()->back()->with('error', 'Failed to upload image. Please try again.');
    }
    
}
