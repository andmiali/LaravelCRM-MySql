<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\About;
use Illuminate\Support\Facades\Storage;

class AboutController extends Controller
{
    public function about()
    {
        $abouts = About::all();
        return view('admin.about', compact('abouts'));
    }

    public function edit($id)
    {
        // Retrieve the specific about section by its ID
        $abouts = About::findOrFail($id);
        $abt = About::all();
    
        // Pass the about section to the view
        return view('admin.aboutedit', compact('abouts', 'abt'));
    }
    
    public function update(Request $request, $id)
    {
        // Retrieve the specific about section by its ID
        $about = About::findOrFail($id);

        // Validate the request data
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Adjust the validation rules as needed
        ]);

        // Update title and content
        $about->title = $validatedData['title'];
        $about->content = $validatedData['content'];

        // Check if a new image has been uploaded
        if ($request->hasFile('image')) {
            // Delete the old image if it exists
            if ($about->image) {
                $imagePath = public_path($about->image);
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }

            // Move the new image to the public/images directory
            $imageName = time() . '.' . $request->image->getClientOriginalExtension();
            $request->image->move(public_path('images'), $imageName);
            $about->image = 'images/'.$imageName;
        }

        // Save the changes to the about section
        $about->save();

        // Redirect back with a success message
        return redirect()->back()->with('success', 'About section updated successfully.');
    }

    public function delete(Request $request, $id)
    {
        // Retrieve the specific about section by its ID
        $about = About::findOrFail($id);

         // Check if the about section exists
         if (!$about) {
            // If the about section does not exist, return a response
            return redirect()->back()->with('error', 'About section not found.');
        }

        if ($about->image) {
                $imagePath = public_path($about->image);
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
        }


        // Save the changes to the about section
        $about->delete();

        // Redirect back with a success message
        return redirect()->route('adminabout')->with('success', 'About section deleted successfully.');
        }


    public function store(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Adjust file type and size as needed
        ]);
    
        // Check if the file was uploaded successfully
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            // Get the original file name
            $originalFileName = $request->file('image')->getClientOriginalName();
    
            // Move the uploaded image to the public/images directory
            $request->file('image')->move(public_path('images'), $originalFileName);
    
            // Create a new About model instance and save it to the database
            About::create([
                'title' => $validatedData['title'],
                'content' => $validatedData['content'],
                'image' => 'images/' . $originalFileName, // Save the path to the image in the database
            ]);
    
            // Redirect back or to a specific route after successful submission
            return redirect()->back()->with('success', 'About section added successfully!');
        }
    
        // If the file upload fails, redirect back with an error message
        return redirect()->back()->with('error', 'Failed to upload image. Please try again.');
    }
    
    
}
