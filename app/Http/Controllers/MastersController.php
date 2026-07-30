<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NetworkType;
use App\Models\Section;
use App\Models\Status;
use App\Models\RequestType;
use App\Models\Vertical;
use App\Models\User;
use App\Models\SubCategory;
use Illuminate\Support\Str;

class MastersController extends Controller
{
    public function index()
    {
        $networkTypes = NetworkType::withTrashed()->orderBy('name')->get();
        $sections = Section::withTrashed()->orderBy('name')->get();
        $statuses = Status::withTrashed()
            ->where('name', '!=', 'assign_to_me')
            ->ordered()
            ->get();

        $verticals = Vertical::withTrashed()
            ->whereNull('parent_id')
            ->with(['children' => function($query) {
                $query->withTrashed()->orderBy('name');
            }])
            ->orderBy('name')
            ->get();

        $allVerticals = Vertical::withTrashed()->with('parent')
            ->orderBy('name')
            ->get();
        $requestTypes = RequestType::withTrashed()->orderBy('name')->get();

        $assignableUsers = User::whereHas('role', function($q) {
            $q->whereIn('slug', ['manager', 'vm', 'nfo']);
        })->orderBy('full_name')->get();

        return view('masters.index', compact('networkTypes', 'sections', 'statuses', 'verticals', 'allVerticals', 'requestTypes', 'assignableUsers'));
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

    public function restoreNetworkType($id)
    {
        try {
            $networkType = NetworkType::withTrashed()->findOrFail($id);
            $networkType->restore();

            return redirect()->route('masters.index')->with('success', 'Network Type restored successfully.');
        } catch (\Exception $e) {
            return redirect()->route('masters.index')->with('error', 'Network Type restore failed: ' . $e->getMessage());
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

    public function restoreSection($id)
    {
        try {
            $section = Section::withTrashed()->findOrFail($id);
            $section->restore();

            return redirect()->route('masters.index')->with('success', 'Section restored successfully.');
        } catch (\Exception $e) {
            return redirect()->route('masters.index')->with('error', 'Section restore failed: ' . $e->getMessage());
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

    public function restoreStatus($id)
    {
        try {
            $status = Status::withTrashed()->findOrFail($id);
            $status->restore();

            return redirect()->route('masters.index')->with('success', 'Status restored successfully.');
        } catch (\Exception $e) {
            return redirect()->route('masters.index')->with('error', 'Status restore failed: ' . $e->getMessage());
        }
    }

    public function storeVertical(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255|unique:verticals,name',
                'parent_id' => 'nullable',
                'send_email' => 'nullable|boolean',
                'user_ids'   => 'required|array|min:1',
                'user_ids.*' => 'exists:users,id',
            ], [
                'user_ids.required' => 'At least one user must be assigned to this category.'
            ]);

            $vertical = Vertical::create([
                'name' => $request->name,
                'parent_id' => $request['parent_id'] ? $request['parent_id'] : null,
                'send_email' => $request->has('send_email'),
            ]);

            $vertical->users()->sync($request->input('user_ids', []));

            return redirect()->route('masters.index')->with('success', 'Category added and users assigned successfully.');
        } catch (\Exception $e) {
            return redirect()->route('masters.index')->with('error', 'Category add failed: ' . $e->getMessage());
        }
    }

    public function updateVertical(Request $request, Vertical $vertical)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255|unique:verticals,name,' . $vertical->id,
                'parent_id' => 'nullable|exists:verticals,id', 
                'send_email' => 'nullable|boolean',
                'user_ids'   => 'required|array|min:1',
                'user_ids.*' => 'exists:users,id',
            ]);

            $vertical->update([
                'name' => $request->name,
                'parent_id' => $request->has('parent_id') ? ($request->parent_id ?: null) : $vertical->parent_id,
                'send_email' => $request->has('send_email'),
            ]);

            $vertical->users()->sync($request->input('user_ids', []));

            return redirect()->route('masters.index')->with('success', 'Category updated successfully.');
        } catch (\Exception $e) {
            return redirect()->route('masters.index')->with('error', 'Category update failed: ' . $e->getMessage());
        }
    }

    public function destroyVertical(Vertical $vertical)
    {
        try {
            \DB::transaction(function () use ($vertical) {
                $deleteCascade = function ($item) use (&$deleteCascade) {
                    foreach ($item->children()->withTrashed()->get() as $child) {
                        $deleteCascade($child);
                        $child->delete();
                    }
                };

                $deleteCascade($vertical);
                $vertical->delete();
            });

            return redirect()->route('masters.index')->with('success', 'Category and all its nested sub-categories deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('masters.index')->with('error', 'Category delete failed: ' . $e->getMessage());
        }
    }

    public function restoreVertical($id)
    {
        try {
            $vertical = Vertical::withTrashed()->findOrFail($id);
            
            if ($vertical->parent_id && $vertical->parent()->withTrashed()->first()->trashed()) {
                return redirect()->route('masters.index')->with('error', 'Cannot restore sub-category. Please restore its parent category first.');
            }

            \DB::transaction(function () use ($vertical) {
                $vertical->restore();

                $restoreCascade = function ($item) use (&$restoreCascade) {
                    foreach ($item->children()->withTrashed()->get() as $child) {
                        $child->restore();
                        $restoreCascade($child); 
                    }
                };

                $restoreCascade($vertical);
            });

            return redirect()->route('masters.index')->with('success', 'Category and all its nested sub-categories restored successfully.');
        } catch (\Exception $e) {
            return redirect()->route('masters.index')->with('error', 'Category restore failed: ' . $e->getMessage());
        }
    }

    public function storeSubCategory(Request $request)
    {
        try {
            $request->validate([
                'vertical_id' => 'required|exists:verticals,id',
                'name' => 'required|string|max:255',
            ]);

            SubCategory::create([
                'vertical_id' => $request->vertical_id,
                'name' => $request->name,
            ]);

            return redirect()->route('masters.index')->with('success', 'Sub Category added successfully.');
        } catch (\Exception $e) {
            return redirect()->route('masters.index')->with('error', 'Sub Category add failed: ' . $e->getMessage());
        }
    }

    public function updateSubCategory(Request $request, SubCategory $subCategory)
    {
        try {
            $request->validate([
                'vertical_id' => 'required|exists:verticals,id',
                'name' => 'required|string|max:255',
            ]);

            $subCategory->update([
                'vertical_id' => $request->vertical_id,
                'name' => $request->name,
            ]);

            return redirect()->route('masters.index')->with('success', 'Sub Category updated successfully.');
        } catch (\Exception $e) {
            return redirect()->route('masters.index')->with('error', 'Sub Category update failed: ' . $e->getMessage());
        }
    }

    public function destroySubCategory(SubCategory $subCategory)
    {
        try {
            $subCategory->delete();
            return redirect()->route('masters.index')->with('success', 'Sub Category deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('masters.index')->with('error', 'Sub Category delete failed: ' . $e->getMessage());
        }
    }

    public function storeRequestType(Request $request)
    {
        try {
            $request->validate(['name' => 'required|string|max:255|unique:request_types,name']);
            RequestType::create([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
            ]);
            return redirect()->route('masters.index')->with('success', 'Request Type added successfully.');
        } catch (\Exception $e) {
            return redirect()->route('masters.index')->with('error', 'Request Type add failed: ' . $e->getMessage());
        }
    }

    public function updateRequestType(Request $request, RequestType $requestType)
    {
        try {
            $request->validate(['name' => 'required|string|max:255|unique:request_types,name,' . $requestType->id]);
            $requestType->update([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
            ]);
            return redirect()->route('masters.index')->with('success', 'Request Type updated successfully.');
        } catch (\Exception $e) {
            return redirect()->route('masters.index')->with('error', 'Request Type update failed: ' . $e->getMessage());
        }
    }

    public function destroyRequestType(RequestType $requestType)
    {
        try {
            $requestType->delete();
            return redirect()->route('masters.index')->with('success', 'Request Type deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('masters.index')->with('error', 'Request Type delete failed: ' . $e->getMessage());
        }
    }

    public function restoreRequestType($id)
    {
        try {
            $requestType = RequestType::withTrashed()->findOrFail($id);
            $requestType->restore();

            return redirect()->route('masters.index')->with('success', 'Request Type restored successfully.');
        } catch (\Exception $e) {
            return redirect()->route('masters.index')->with('error', 'Request Type restore failed: ' . $e->getMessage());
        }
    }
    
}