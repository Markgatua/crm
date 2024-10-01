<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\DocumentType;
use Illuminate\Http\Request;

class DocumentTypesController extends Controller
{
    public function index(){
        $data = DocumentType::all();
        return Inertia::render('Settings/DocumentType/index',[
            'documentType' => $data
        ]);
    }

    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'name'=>'required|string|min:2|max:100'
        ]);
        $role= new DocumentType();
        $role->document=$request->input('name');
        $role->saveOrFail();
        return back()->with('success','Document Type Added Successfully');
    }

    public function update(Request $request, DocumentType $documentType): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'name'=>'required|string|min:2|max:100'
        ]);
        $documentType->document=$request->input('name');
        $documentType->updateOrFail();
        return back()->with('success','Document Type Edited Successfully!');
    }


    public function destroy(DocumentType $documentType): \Illuminate\Http\RedirectResponse
    {
        $documentType->deleteOrFail();
        return back()->with('success','Document Type Deleted Successfully!');
    }
}
