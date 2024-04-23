<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
class ServiceController extends Controller
{
    public function service()
    {
        $services = Service::all();
        return view('admin.service', compact('services'));
    }

    public function edit($id)
    {
        // Retrieve the specific Service section by its ID
        $services = Service::findOrFail($id);
        $sr = Service::all();
    
        // Pass the Service section to the view
        return view('admin.serviceedit', compact('services', 'sr'));
    }
    
    public function update(Request $request, $id)
    {
        // Retrieve the specific Service section by its ID
        $services = Service::findOrFail($id);

        // Validate the request data
        $validatedData = $request->validate([
            'title' => 'required|string',
            'content' => 'required|string',
        ]);

        // Update title and content
        $services->title = $validatedData['title'];
        $services->content = $validatedData['content'];

        // Save the changes to the Service section
        $services->save();

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Service section updated successfully.');
    }

    public function delete(Request $request, $id)
    {
        // Retrieve the specific Service section by its ID
        $services = Service::findOrFail($id);

         // Check if the Service section exists
         if (!$services) {
            // If the Service section does not exist, return a response
            return redirect()->back()->with('error', 'Service section not found.');
        }

        // Save the changes to the Service section
        $services->delete();

        // Redirect back with a success message
        return redirect()->route('adminservice')->with('success', 'Service section deleted successfully.');
        }


    public function store(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'title' => 'required|string',
            'content' => 'required|string',
        ]);
    
            // Create a new Service model instance and save it to the database
            Service::create([
                'title' => $validatedData['title'],
                'content' => $validatedData['content'],
            ]);
    
            // Redirect back or to a specific route after successful submission
            return redirect()->back()->with('success', 'Service section added successfully!');
        
    }
}
