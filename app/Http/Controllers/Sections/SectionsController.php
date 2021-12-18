<?php

namespace App\Http\Controllers\Sections;

use App\Http\Controllers\Controller;
use App\Models\Sections;

use App\Models\Categories\Categories;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SectionsController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    //
    $sections = Sections::all();
    $data = [];
    foreach ($sections as $key => $value) {
      $item = [];
      $item = $sections[$key];
      $item['categories'] = Categories::where(
        'section_id',
        '=',
        $sections[$key]->id
      )
        ->get()
        ->toArray();

      array_push($data, $item);
    }
    return response()->json(['status' => 200, 'data' => $data], 200);
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
    ]);

    if ($validator->fails()) {
      return response()->json(
        ['status' => 203, 'message' => $validator->messages()],
        203
      );
    } else {
      $section = Sections::create([
        'name_en' => $request->name_en,
        'name_ar' => $request->name_ar,
        'desc_en' => $request->desc_en,
        'desc_ar' => $request->desc_ar,
        'desc_seo' => $request->desc_seo,
        'user_id' => $request->user_id,
        'created_by' => $request->created_by,
      ]);

      return response()->json(['status' => 201, 'data' => $section], 201);
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
    $section = Sections::find($id);
    if ($section) {
      return response()->json(['status' => 202, 'data' => $section], 202);
    } else {
      return response()->json(
        ['status' => 204, 'message' => 'there is no section with id = ' . $id],
        204
      );
    }
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
    $section = Sections::find($id);
    if (!$section) {
      return response()->json(
        ['status' => 204, 'message' => "no section with id = $id founded"],
        204
      );
    } else {
      $updated = $section->fill($request->all())->save();

      if ($updated) {
        return response()->json(
          [
            'status' => 201,
            'data' => $section,
          ],
          202
        );
      } else {
        return response()->json(
          [
            'status' => 304,
            'message' => 'user could not be updated',
          ],
          304
        );
      }
    }
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
    $section = Sections::find($id);
    if (!$section) {
      return response()->json(
        [
          'status' => 204,
          'data' => "there is no section available with id = $id ",
        ],
        204
      );
    } else {
      $section->delete();
      return response()->json(
        [
          'status' => 202,
          'message' => 'the section with id = ' . $id . ' was deleted success',
        ],
        202
      );
    }
  }
}
