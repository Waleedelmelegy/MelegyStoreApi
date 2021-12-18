<?php

namespace App\Http\Controllers\Categories;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Categories\CategoriesAttributes;

class CategoriesAttributesController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    //
    $attributes = CategoriesAttributes::all();

    return response()->json(['status' => 200, 'data' => $attributes], 200);
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
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    //
    $validator = Validator::make($request->all(), [
      'name_en' => 'required|max:55',
      'name_ar' => 'required|max:55',
      'desc_en' => 'required|max:55',
      'desc_ar' => 'required|max:55',
      'desc_seo' => 'required|max:135',
      'user_id' => 'required',
      'created_by' => 'required|max:100',
      'section_id' => 'required',
    ]);

    if ($validator->fails()) {
      return response()->json(
        ['status' => 203, 'message' => $validator->messages()],
        203
      );
    } else {
      $attributes = CategoriesAttributes::create([
        'key_ar' => $request->key_ar,
        'key_en' => $request->key_en,
        'categorie_id' => $request->categorie_id,
        'icon' => $request->icon,
      ]);

      return response()->json(['status' => 201, 'data' => $attributes], 201);
    }
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
