<?php

namespace App\Http\Controllers;

use App\Models\Keyword;
use App\Models\User;
use Illuminate\Http\Request;

class ProjectKeywordController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function store(Request $request)
    {
        $user = auth('sanctum')->user();
        $user=User::find($user->id);
        $keywords = $request->input('keywords');

        foreach ($keywords as $keyword) {
            $keywordModel = Keyword::firstOrCreate(['keyword' => $keyword]);
            $user->keywords()->syncWithoutDetaching($keywordModel->id);
        }

        // $keyword = new Keyword(['keyword' => $request->keyword]);
        // $user->keywords()->save($keyword);
        $keywords = $user->keywords;
        $keywordNames = $keywords->pluck('keyword')->toArray();
        // $user_keywords=UserKeyword::where('user_id',$user->id)->with('keyword')->get();
        $response["success"] = true;
        $response["message"] = "Keywords added successfully ";
        $response["user_keywords"] = $keywordNames;
        $response["user"] = $user;

        return $response;
    }
    public function updateKeywords(Request $request)
    {
        $validatedData = $request->validate([
            'keywords' => 'required|array'
        ]);

        $user = auth('sanctum')->user();
        $user = User::find($user->id);

        $keywords = [];

        foreach ($validatedData['keywords'] as $keywordName) {
            $keyword = Keyword::where('keyword', $keywordName)->first();

            if (!$keyword) {
                $keyword = Keyword::create(['keyword' => $keywordName]);
            }

            $keywords[] = $keyword->id;
        }

        $user->keywords()->sync($keywords);
        $keywords = $user->keywords;
        $keywordNames = $keywords->pluck('keyword')->toArray();
        $response["success"] = true;
        $response["message"] = "Keywords updated successfully";
        $response["user_keywords"] = $keywordNames;
        $response["user"] = $user;
        return $response;
        // return response()->json(['message' => 'Keywords updated successfully']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
