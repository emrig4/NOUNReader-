<?php

namespace App\Modules\Resource\Http\Controllers\Admin;

use App\Modules\Resource\Models\ResourceSubField;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class SubfieldAdminController extends AdminController
{
    /**
     * Display list of all subfields
     */
    public function index(Request $request)
    {
        try {
            $search = $request->get('search', '');
            $parentField = $request->get('parent_field', '');
            
            $query = ResourceSubField::query();
            
            // Apply filters
            if (!empty($search)) {
                $query->where(function($q) use ($search) {
                    $q->where('title', 'LIKE', "%{$search}%")
                      ->orWhere('slug', 'LIKE', "%{$search}%");
                });
            }
            
            if (!empty($parentField)) {
                $query->where('parent_field', $parentField);
            }
            
            $subfields = $query->orderBy('parent_field')->orderBy('title')->paginate(20);
            
            // Get unique parent fields for filter dropdown
            $parentFields = ResourceSubField::select('parent_field')
                ->distinct()
                ->orderBy('parent_field')
                ->pluck('parent_field');
            
            return view('admin.subfields.index', compact(
                'subfields', 'parentFields', 'search', 'parentField'
            ));
            
        } catch (\Exception $e) {
            Log::error('Error loading subfields index', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->with('error', 'Failed to load subfields: ' . $e->getMessage());
        }
    }
    
    /**
     * Show edit form for individual subfield
     */
    public function edit($id)
    {
        try {
            $subfield = ResourceSubField::findOrFail($id);
            
            // Get unique parent fields for dropdown
            $parentFields = ResourceSubField::select('parent_field')
                ->distinct()
                ->orderBy('parent_field')
                ->pluck('parent_field');
            
            return response()->json([
                'success' => true,
                'subfield' => [
                    'id' => $subfield->id,
                    'title' => $subfield->title,
                    'slug' => $subfield->slug,
                    'parent_field' => $subfield->parent_field
                ],
                'parent_fields' => $parentFields
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error loading subfield for edit', [
                'subfield_id' => $id,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to load subfield for editing.'
            ]);
        }
    }
    
    /**
     * Update individual subfield
     */
    public function update(Request $request, $id)
    {
        try {
            $subfield = ResourceSubField::findOrFail($id);
            
            // Validate input
            $validator = Validator::make($request->all(), [
                'title' => 'required|string|max:255|unique:resource_sub_fields,title,' . $id,
                'slug' => 'required|string|max:255|unique:resource_sub_fields,slug,' . $id,
                'parent_field' => 'required|string|max:255'
            ]);
            
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed.',
                    'errors' => $validator->errors()
                ]);
            }
            
            // Store old values for logging
            $oldValues = [
                'title' => $subfield->title,
                'slug' => $subfield->slug,
                'parent_field' => $subfield->parent_field
            ];
            
            // Begin transaction
            DB::beginTransaction();
            
            // Update subfield
            $subfield->update([
                'title' => $request->input('title'),
                'slug' => $request->input('slug'),
                'parent_field' => $request->input('parent_field')
            ]);
            
            // If slug changed, update legacy references in resources table
            if ($oldValues['slug'] !== $request->input('slug')) {
                DB::table('resources')
                    ->where('sub_fields', 'LIKE', "%{$oldValues['slug']}%")
                    ->update(['sub_fields' => DB::raw("REPLACE(sub_fields, '{$oldValues['slug']}', '{$request->input('slug')}')")]);
            }
            
            DB::commit();
            
            Log::info('Subfield updated successfully', [
                'subfield_id' => $id,
                'old_values' => $oldValues,
                'new_values' => [
                    'title' => $request->input('title'),
                    'slug' => $request->input('slug'),
                    'parent_field' => $request->input('parent_field')
                ],
                'updated_by' => auth()->user()->id ?? 'system'
            ]);
            
            return response()->json([
                'success' => true,
                'message' => "Subfield '{$subfield->title}' has been updated successfully."
            ]);
            
        } catch (\Exception $e) {
            DB::rollback();
            
            Log::error('Error updating subfield', [
                'subfield_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to update subfield: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * Delete individual subfield
     */
    public function destroy(Request $request, $id)
    {
        try {
            $subfield = ResourceSubField::findOrFail($id);
            
            // Check if subfield is in use
            $usageCount = DB::table('resource_sub_field_relations')
                ->where('subfield_id', $id)
                ->count();
            
            // Also check in resources table (old format)
            $resourcesUsingSubfield = DB::table('resources')
                ->where('sub_fields', 'LIKE', "%{$subfield->slug}%")
                ->count();
            
            $totalUsage = $usageCount + $resourcesUsingSubfield;
            
            if ($totalUsage > 0) {
                return response()->json([
                    'success' => false,
                    'message' => "Cannot delete subfield '{$subfield->title}'. It is currently assigned to {$totalUsage} resource(s). Please remove these assignments first."
                ]);
            }
            
            // Begin transaction
            DB::beginTransaction();
            
            // Clear any old references
            DB::table('resources')
                ->where('sub_fields', 'LIKE', "%{$subfield->slug}%")
                ->update(['sub_fields' => null]);
            
            // Delete the subfield
            $subfield->delete();
            
            DB::commit();
            
            Log::info('Subfield deleted successfully', [
                'deleted_subfield_id' => $id,
                'deleted_subfield_slug' => $subfield->slug,
                'deleted_by' => auth()->user()->id ?? 'system'
            ]);
            
            return response()->json([
                'success' => true,
                'message' => "Subfield '{$subfield->title}' has been deleted successfully."
            ]);
            
        } catch (\Exception $e) {
            DB::rollback();
            
            Log::error('Error deleting subfield', [
                'subfield_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete subfield: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * Bulk delete subfields
     */
    public function bulkDelete(Request $request)
    {
        try {
            // Validate input
            $validator = Validator::make($request->all(), [
                'subfield_ids' => 'required|array|min:1',
                'subfield_ids.*' => 'integer|exists:resource_sub_fields,id'
            ]);
            
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid subfield IDs provided.'
                ]);
            }
            
            $subfieldIds = $request->input('subfield_ids');
            
            // Get subfields with their info
            $subfields = ResourceSubField::whereIn('id', $subfieldIds)->get();
            
            if ($subfields->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No valid subfields found for deletion.'
                ]);
            }
            
            // Check usage for each subfield
            $inUseSubfields = [];
            $safeToDeleteSubfields = [];
            
            foreach ($subfields as $subfield) {
                $usageCount = DB::table('resource_sub_field_relations')
                    ->where('subfield_id', $subfield->id)
                    ->count();
                
                $resourcesUsingSubfield = DB::table('resources')
                    ->where('sub_fields', 'LIKE', "%{$subfield->slug}%")
                    ->count();
                
                $totalUsage = $usageCount + $resourcesUsingSubfield;
                
                if ($totalUsage > 0) {
                    $inUseSubfields[] = [
                        'id' => $subfield->id,
                        'title' => $subfield->title,
                        'usage' => $totalUsage
                    ];
                } else {
                    $safeToDeleteSubfields[] = $subfield;
                }
            }
            
            // If there are subfields that can't be deleted, return the info
            if (!empty($inUseSubfields)) {
                $usageMessages = [];
                foreach ($inUseSubfields as $inUse) {
                    $usageMessages[] = "'{$inUse['title']}' ({$inUse['usage']} resources)";
                }
                
                return response()->json([
                    'success' => false,
                    'message' => 'Some subfields cannot be deleted because they are still in use: ' . implode(', ', $usageMessages),
                    'in_use' => $inUseSubfields,
                    'safe_to_delete' => $safeToDeleteSubfields->pluck('id')->toArray()
                ]);
            }
            
            // Begin transaction
            DB::beginTransaction();
            
            $deletedCount = 0;
            
            foreach ($safeToDeleteSubfields as $subfield) {
                // Clear any old references
                DB::table('resources')
                    ->where('sub_fields', 'LIKE', "%{$subfield->slug}%")
                    ->update(['sub_fields' => null]);
                
                // Delete the subfield
                $subfield->delete();
                $deletedCount++;
            }
            
            DB::commit();
            
            Log::info('Bulk subfield deletion completed', [
                'deleted_count' => $deletedCount,
                'deleted_subfields' => $subfields->pluck('title')->toArray(),
                'deleted_by' => auth()->user()->id ?? 'system'
            ]);
            
            return response()->json([
                'success' => true,
                'message' => "Successfully deleted {$deletedCount} subfield(s).",
                'deleted_count' => $deletedCount
            ]);
            
        } catch (\Exception $e) {
            DB::rollback();
            
            Log::error('Error during bulk subfield deletion', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'requested_ids' => $request->input('subfield_ids', [])
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete subfields: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * Get subfield usage information
     */
    public function usage($id)
    {
        try {
            $subfield = ResourceSubField::findOrFail($id);
            
            // Check new format usage
            $newUsage = DB::table('resource_sub_field_relations')
                ->join('resources', 'resource_sub_field_relations.resource_id', '=', 'resources.id')
                ->where('resource_sub_field_relations.subfield_id', $id)
                ->select('resources.id', 'resources.title')
                ->get();
            
            // Check old format usage
            $oldUsage = DB::table('resources')
                ->where('sub_fields', 'LIKE', "%{$subfield->slug}%")
                ->select('id', 'title')
                ->get();
            
            return response()->json([
                'success' => true,
                'subfield' => [
                    'id' => $subfield->id,
                    'title' => $subfield->title,
                    'slug' => $subfield->slug,
                    'parent_field' => $subfield->parent_field
                ],
                'usage' => [
                    'new_format' => $newUsage,
                    'old_format' => $oldUsage,
                    'total' => $newUsage->count() + $oldUsage->count()
                ]
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error getting subfield usage', [
                'subfield_id' => $id,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to get subfield usage information.'
            ]);
        }
    }
    
    /**
     * Clear all subfields (emergency function)
     */
    public function clearAll(Request $request)
    {
        try {
            // Verify this is a POST request with confirmation
            if (!$request->isMethod('post') || !$request->has('confirm_clear')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid request. Confirmation required.'
                ]);
            }
            
            // Begin transaction
            DB::beginTransaction();
            
            // Clear subfield references from resources
            $updatedResources = DB::table('resources')
                ->whereNotNull('sub_fields')
                ->update(['sub_fields' => null]);
            
            // Delete all subfields
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
            $deletedSubfields = DB::table('resource_sub_fields')->delete();
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
            
            DB::commit();
            
            Log::info('All subfields cleared', [
                'deleted_subfields' => $deletedSubfields,
                'updated_resources' => $updatedResources,
                'cleared_by' => auth()->user()->id ?? 'system'
            ]);
            
            return response()->json([
                'success' => true,
                'message' => "Successfully cleared {$deletedSubfields} subfields and updated {$updatedResources} resources.",
                'deleted_count' => $deletedSubfields,
                'updated_resources' => $updatedResources
            ]);
            
        } catch (\Exception $e) {
            DB::rollback();
            
            Log::error('Error clearing all subfields', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to clear subfields: ' . $e->getMessage()
            ]);
        }
    }
}