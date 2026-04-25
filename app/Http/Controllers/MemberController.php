<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Member;

class MemberController extends Controller
{
    public function index()
    {
        return view('members.index', [
            'members' => Member::all()
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'       => 'required',
            'phone'      => 'nullable',
            'address'    => 'nullable',
            'email'      => 'nullable|email',
            'birth_date' => 'nullable|date',
            'is_active'  => 'required|boolean'
        ]);

        return response()->json(
            Member::create($request->all())
        );
    }

    public function edit(Member $member)
    {
        return response()->json($member);
    }

    public function update(Request $request, Member $member)
    {
        $request->validate([
            'name'       => 'required',
            'phone'      => 'nullable',
            'address'    => 'nullable',
            'email'      => 'nullable|email',
            'birth_date' => 'nullable|date',
            'is_active'  => 'required|boolean'
        ]);

        $member->update($request->all());

        return response()->json($member);
    }

    public function destroy(Member $member)
    {
        $member->delete();

        return response()->json([
            'success' => true
        ]);
    }
}