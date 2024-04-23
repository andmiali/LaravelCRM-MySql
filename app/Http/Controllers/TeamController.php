<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Team;

class TeamController extends Controller
{
    public function team()
    {
        $teams = Team::all();
        return view('admin.team', compact('teams'));
    }

    public function edit($id)
    {
        // Retrieve the specific Team section by its ID
        $teams = Team::findOrFail($id);
        $tm = Team::all();
    
        // Pass the Team section to the view
        return view('admin.teamedit', compact('teams', 'tm'));
    }
    
    public function update(Request $request, $id)
    {
        // Retrieve the specific Team section by its ID
        $teams = Team::findOrFail($id);

        // Validate the request data
        $validatedData = $request->validate([
            'ism' => 'required|string',
            'lavozim' => 'required|string',
            'linkedin' => 'required|string',
            'facebook' => 'required|string',
            'instagram' => 'required|string',
            'twitter' => 'required|string',
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Adjust the validation rules as needed
        ]);

        // Update title and content
        $teams->ism = $validatedData['ism'];
        $teams->lavozim = $validatedData['lavozim'];
        $teams->linkedin = $validatedData['linkedin'];
        $teams->facebook = $validatedData['facebook'];
        $teams->instagram = $validatedData['instagram'];
        $teams->twitter = $validatedData['twitter'];

        // Check if a new image has been uploaded
        if ($request->hasFile('image')) {
            // Delete the old image if it exists
            if ($teams->image) {
                $imagePath = public_path($teams->image);
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }

            // Move the new image to the public/images directory
            $imageName = time() . '.' . $request->image->getClientOriginalExtension();
            $request->image->move(public_path('images'), $imageName);
            $teams->image = 'images/'.$imageName;
        }

        // Save the changes to the Team section
        $teams->save();

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Team section updated successfully.');
    }

    public function delete(Request $request, $id)
    {
        // Retrieve the specific Team section by its ID
        $teams = Team::findOrFail($id);

         // Check if the Team section exists
         if (!$teams) {
            // If the Team section does not exist, return a response
            return redirect()->back()->with('error', 'Team section not found.');
        }

        if ($teams->image) {
                $imagePath = public_path($teams->image);
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
        }


        // Save the changes to the Team section
        $teams->delete();

        // Redirect back with a success message
        return redirect()->route('adminteam')->with('success', 'Team section deleted successfully.');
        }


    public function store(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'ism' => 'required|string',
            'lavozim' => 'required|string',
            'linkedin' => 'required|string',
            'facebook' => 'required|string',
            'instagram' => 'required|string',
            'twitter' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Adjust file type and size as needed
        ]);
    
        // Check if the file was uploaded successfully
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            // Get the original file name
            $originalFileName = $request->file('image')->getClientOriginalName();
    
            // Move the uploaded image to the public/images directory
            $request->file('image')->move(public_path('images'), $originalFileName);
    
            // Create a new Team model instance and save it to the database
            Team::create([
                'ism' => $validatedData['ism'],
                'lavozim' => $validatedData['lavozim'],
                'linkedin' => $validatedData['linkedin'],
                'facebook' => $validatedData['facebook'],
                'instagram' => $validatedData['instagram'],
                'twitter' => $validatedData['twitter'],
                'image' => 'images/' . $originalFileName, // Save the path to the image in the database
            ]);
    
            // Redirect back or to a specific route after successful submission
            return redirect()->back()->with('success', 'Team section added successfully!');
        }
    
        // If the file upload fails, redirect back with an error message
        return redirect()->back()->with('error', 'Failed to upload image. Please try again.');
    }
}
