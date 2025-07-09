<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NetworkType;
use App\Models\Section;
use App\Models\Status;
use App\Models\Vertical;

class MastersController extends Controller
{
    public function index()
    {
        $networkTypes = \App\Models\NetworkType::orderBy('name')->get();
        $sections = \App\Models\Section::orderBy('name')->get();
        $statuses = \App\Models\Status::where('name', '!=', 'assign_to_me')
        ->ordered()
        ->get();
    
        $verticals = \App\Models\Vertical::orderBy('name')->get();
        return view('masters.index', compact('networkTypes', 'sections', 'statuses', 'verticals'));
    }

    public function storeNetworkType(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255|unique:network_types,name']);
        NetworkType::create(['name' => $request->name]);
        return redirect()->route('masters.index')->with('success', 'Network Type added successfully.');
    }

    public function updateNetworkType(Request $request, NetworkType $networkType)
    {
        $request->validate(['name' => 'required|string|max:255|unique:network_types,name,' . $networkType->id]);
        $networkType->update(['name' => $request->name]);
        return redirect()->route('masters.index')->with('success', 'Network Type updated successfully.');
    }

    public function destroyNetworkType(NetworkType $networkType)
    {
        $networkType->delete();
        return redirect()->route('masters.index')->with('success', 'Network Type deleted successfully.');
    }

    public function storeSection(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255|unique:sections,name']);
        Section::create(['name' => $request->name]);
        return redirect()->route('masters.index')->with('success', 'Section added successfully.');
    }

    public function updateSection(Request $request, Section $section)
    {
        $request->validate(['name' => 'required|string|max:255|unique:sections,name,' . $section->id]);
        $section->update(['name' => $request->name]);
        return redirect()->route('masters.index')->with('success', 'Section updated successfully.');
    }

    public function destroySection(Section $section)
    {
        $section->delete();
        return redirect()->route('masters.index')->with('success', 'Section deleted successfully.');
    }

    public function storeStatus(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:statuses,name',
        ]);
    }
} 