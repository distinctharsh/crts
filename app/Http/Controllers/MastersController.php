<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NetworkType;
use App\Models\Section;
use App\Models\Status;
use App\Models\Vertical;
use Illuminate\Support\Str;

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
        try {
            $request->validate(['name' => 'required|string|max:255|unique:network_types,name']);
            NetworkType::create(['name' => $request->name]);
            return redirect()->route('masters.index')->with('success', 'Network Type added successfully.');
        } catch (\Exception $e) {
            return redirect()->route('masters.index')->with('error', 'Network Type add failed: ' . $e->getMessage());
        }
    }

    public function updateNetworkType(Request $request, NetworkType $networkType)
    {
        try {
            $request->validate(['name' => 'required|string|max:255|unique:network_types,name,' . $networkType->id]);
            $networkType->update(['name' => $request->name]);
            return redirect()->route('masters.index')->with('success', 'Network Type updated successfully.');
        } catch (\Exception $e) {
            return redirect()->route('masters.index')->with('error', 'Network Type update failed: ' . $e->getMessage());
        }
    }

    public function destroyNetworkType(NetworkType $networkType)
    {
        try {
            $networkType->delete();
            return redirect()->route('masters.index')->with('success', 'Network Type deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('masters.index')->with('error', 'Network Type delete failed: ' . $e->getMessage());
        }
    }

    public function storeSection(Request $request)
    {
        try {
            $request->validate(['name' => 'required|string|max:255|unique:sections,name']);
            Section::create(['name' => $request->name]);
            return redirect()->route('masters.index')->with('success', 'Section added successfully.');
        } catch (\Exception $e) {
            return redirect()->route('masters.index')->with('error', 'Section add failed: ' . $e->getMessage());
        }
    }

    public function updateSection(Request $request, Section $section)
    {
        try {
            $request->validate(['name' => 'required|string|max:255|unique:sections,name,' . $section->id]);
            $section->update(['name' => $request->name]);
            return redirect()->route('masters.index')->with('success', 'Section updated successfully.');
        } catch (\Exception $e) {
            return redirect()->route('masters.index')->with('error', 'Section update failed: ' . $e->getMessage());
        }
    }

    public function destroySection(Section $section)
    {
        try {
            $section->delete();
            return redirect()->route('masters.index')->with('success', 'Section deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('masters.index')->with('error', 'Section delete failed: ' . $e->getMessage());
        }
    }

    public function storeStatus(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255|unique:statuses,name',
                'color' => 'required|string|max:255',
            ]);
            Status::create([
                'name' => $request->name,
                'color' => $request->color,
                'slug' => Str::slug($request->name),
                'visible_to_user' => $request->has('visible_to_user'),
            ]);
            return redirect()->route('masters.index')->with('success', 'Status added successfully.');
        } catch (\Exception $e) {
            return redirect()->route('masters.index')->with('error', 'Status add failed: ' . $e->getMessage());
        }
    }

    public function updateStatus(Request $request, Status $status)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255|unique:statuses,name,' . $status->id,
                'color' => 'required|string|max:255',
            ]);
            $status->update([
                'name' => $request->name,
                'color' => $request->color,
                'slug' => Str::slug($request->name),
                'visible_to_user' => $request->has('visible_to_user'),
            ]);
            return redirect()->route('masters.index')->with('success', 'Status updated successfully.');
        } catch (\Exception $e) {
            return redirect()->route('masters.index')->with('error', 'Status update failed: ' . $e->getMessage());
        }
    }

    public function destroyStatus(Status $status)
    {
        try {
            $status->delete();
            return redirect()->route('masters.index')->with('success', 'Status deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('masters.index')->with('error', 'Status delete failed: ' . $e->getMessage());
        }
    }

    public function storeVertical(Request $request)
    {
        try {
            $request->validate(['name' => 'required|string|max:255|unique:verticals,name']);
            Vertical::create(['name' => $request->name]);
            return redirect()->route('masters.index')->with('success', 'Vertical added successfully.');
        } catch (\Exception $e) {
            return redirect()->route('masters.index')->with('error', 'Vertical add failed: ' . $e->getMessage());
        }
    }

    public function updateVertical(Request $request, Vertical $vertical)
    {
        try {
            $request->validate(['name' => 'required|string|max:255|unique:verticals,name,' . $vertical->id]);
            $vertical->update(['name' => $request->name]);
            return redirect()->route('masters.index')->with('success', 'Vertical updated successfully.');
        } catch (\Exception $e) {
            return redirect()->route('masters.index')->with('error', 'Vertical update failed: ' . $e->getMessage());
        }
    }

    public function destroyVertical(Vertical $vertical)
    {
        try {
            $vertical->delete();
            return redirect()->route('masters.index')->with('success', 'Vertical deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('masters.index')->with('error', 'Vertical delete failed: ' . $e->getMessage());
        }
    }

} 