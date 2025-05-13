<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\LibraryUser;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Auth;

class LibraryUserController extends Controller
{
    /**
     * Display a listing of the resource.
     */

     public function index()
     {
         $users = LibraryUser::with('permissions')->get();
         $subscriptions = Subscription::where('id', Auth::user()->library_type)->get();
        $permissions= $subscriptions->flatMap(function ($subscription) {
            return $subscription->permissions->pluck('name','id');
        })->unique();
         $branches=Branch::where('library_id',Auth::user()->id)->get();
         foreach ($users as $user) {
            $user->branch_names = Branch::whereIn('id', $user->branch_id)->pluck('name')->toArray();
        }
         return view('library_users.index', compact('users', 'permissions','branches'));
     }
     
     public function store(Request $request)
     {
         $request->validate([
           'name' => 'required|string|max:255',
            'email' => 'required|email|unique:library_users,email,' . $request->id,
            'password' => $request->id ? 'nullable|min:6' : 'required|min:6',
            'branch_id' => 'required|array|min:1',
            'status' => 'required|in:0,1',
            'permissions' => 'required|array|min:1',
         ]);
     
         $data = $request->only('name', 'email', 'mobile', 'status');
         
         // Store the branch_id as a JSON array (since it's a JSON column)
         if ($request->filled('branch_id')) {
             $data['branch_id'] = $request->branch_id;  // Pass the array directly
         }
     
         // Add the library_id (assuming it's coming from the authenticated library user)
         $data['library_id'] = auth()->guard('library')->id();
     
         // Handle password update if provided
         if ($request->filled('password')) {
             $data['password'] = bcrypt($request->password);
         }
     
         // Update or create the user
         $user = LibraryUser::updateOrCreate(['id' => $request->id], $data);
     
         // Sync permissions if provided
        //  $user->syncPermissions($request->permissions ?? []);
     
         return response()->json(['message' => 'User saved successfully.']);
     }
     

     
     
     public function toggleStatus($id)
     {
         $user = LibraryUser::findOrFail($id);
         $user->status = !$user->status;
         $user->save();
     
         return response()->json(['message' => 'Status updated.']);
     }
     

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LibraryUser $libraryUser)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LibraryUser $libraryUser)
    {
        //
    }
}
