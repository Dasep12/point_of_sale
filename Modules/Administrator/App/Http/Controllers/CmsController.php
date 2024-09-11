<?php

namespace Modules\Administrator\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class CmsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sql = DB::table('tbl_mst_cms')->first();
        return view('administrator::cms/index', ['data' => $sql]);
    }


    public function updateCms(Request $req)
    {
        // Validate the file input
        $req->validate([
            'logo' => 'required|mimes:jpg,png,jpeg|max:5048', // restrict file type and size (max 5MB)
        ]);
        $filename = "";
        $filePath = "";
        // Handle the file upload
        if ($req->hasFile('logo')) {
            $file = $req->file('logo');
            $filename = time() . '-' . $file->getClientOriginalName(); // create a unique filename
            $destinationPath = public_path('assets/images');
            // Move the file to the public/assets/images directory
            $file->move($destinationPath, $filename);
        }
        $data = [
            'name_company' => $req->name_company,
            'logo' => $filename,
            'address' => $req->address,
            'phone' => $req->phone,
        ];
        DB::beginTransaction();
        DB::table('tbl_mst_cms')->where('id', $req->id)->update($data);
        try {
            DB::commit();
            return back()->with('success', 'Data Update !');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Please select a file.');
        }
    }
}
