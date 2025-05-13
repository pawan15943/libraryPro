<?php

namespace App\Http\Controllers;

use App\Models\Library;
use App\Models\LibraryUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class BranchController extends Controller
{
    public function switch(Request $req)
    {
        $req->validate([
            'branch_id' => 'required|integer|min:0'
        ]);

        $id = $req->branch_id;
      
        if ($id > 0 && ! auth()->user()->branches->contains('id', $id)) {
            abort(403);
        }
        if (Auth::guard('library')->check()){
            Library::where('id',Auth::user()->id)->update([
                'current_branch' => $id,
            ]);
        }elseif(Auth::guard('library_users')->check()){
            LibraryUser::where('id',Auth::user()->id)->update([
                'current_branch' => $id,
            ]);
        }
       
    

        return back();
    }
}
