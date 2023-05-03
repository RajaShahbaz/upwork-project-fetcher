<?php

namespace App\Http\Controllers;

use App\Models\Proposal;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ProposalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     */
    public function index()
    {
        $user=auth('sanctum')->user();
        $user=User::where('id',$user->id)->with('proposals')->first();
        // $proposals=Proposal::where('user_id',$user->id)->get();
        // // $keywords = $user->keywords;
        // $user_proposals = $proposals->pluck('proposal')->toArray();
        $response["success"] = true;
        $response["message"] = "Keywords updated successfully";
        // $response["user_proposals"] = $user;
        $response["data"] = $user;
        return $response;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function store(Request $request)
    {
        try {
            // Validate the incoming request data
            $validatedData = $request->validate([
                'proposal' => 'required',
            ]);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
        $user=auth('sanctum')->user();
        $proposal=new Proposal();
        $proposal->user_id=$user->id;
        $proposal->proposal=$validatedData['proposal'];;
        $proposal->save();
        $response["success"] = true;
        $response["message"] = "proposal added successfully";
        $response["data"] = $proposal;
        return $response;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     */
    public function show($id)
    {
        $proposal=Proposal::find($id);
        $response["success"] = true;
        $response["message"] = "proposal added successfully";
        $response["data"] = $proposal;
        return $response;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     */
    public function update(Request $request, $id)
    {
        $proposal=Proposal::find($id);
        $proposal->proposal=$request->proposal ?? $proposal->proposal;
        $proposal->save();
        $response["success"] = true;
        $response["message"] = "proposal updated successfully";
        $response["data"] = $proposal;
        return $response;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
