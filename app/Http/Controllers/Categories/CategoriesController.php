<?php

namespace App\Http\Controllers\Categories;

use App\Http\Controllers\Controller;
use App\Models\Categories\Categories;
use App\Models\Sections;
use App\Models\Products\ProductsCategories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoriesController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    //
    $categories = Categories::all();
    // return response()->json(
    //   ['status' => 200, 'data' => count($categories)],
    //   200
    // );
    // exit();

    if (count($categories) > 0) {
      foreach ($categories as $x => $v) {
        $new = [];
        $new = ProductsCategories::where(
          "category_id",
          '=',
          $categories[$x]['id']
        )->get();
        if (count($new) > 0) {
          $categories[$x]['products'] = count($new);
        } else {
          $categories[$x]['products'] = 0;
        }
      }
    } else {
      $categories = [];
    }
    return response()->json(['status' => 200, 'data' => $categories], 200);
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
    $section = Sections::find($request->section_id);
    if (!$section) {
      return response()->json(
        [
          'status' => 204,
          'message' =>
            'There is no section available with id =' . $request->section_id,
        ],
        204
      );
    } else {
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
        $categories = Categories::create([
          'name_en' => $request->name_en,
          'name_ar' => $request->name_ar,
          'desc_en' => $request->desc_en,
          'desc_ar' => $request->desc_ar,
          'desc_seo' => $request->desc_seo,
          'user_id' => $request->user_id,
          'created_by' => $request->created_by,
          'section_id' => $request->section_id,
        ]);

        return response()->json(['status' => 201, 'data' => $categories], 201);
      }
    }
  }
  /**
   * Show the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
    $categories = Categories::find($id);
    if ($categories) {
      return response()->json(['status' => 202, 'data' => $categories], 202);
    } else {
      return response()->json(
        [
          'status' => 204,
          'message' => 'There is no category available with id =' . $id,
        ],
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
    $section = Sections::find($request->section_id);
    if (!$section) {
      return response()->json(
        [
          'status' => 204,
          'message' =>
            'There is no section available with id =' . $request->section_id,
        ],
        204
      );
    } else {
      $categories = Categories::find($id);
      if (!$categories) {
        return response()->json(
          ['status' => 204, 'message' => "no categories with id = $id founded"],
          204
        );
      } else {
        $updated = $categories->fill($request->all())->save();

        if ($updated) {
          return response()->json([
            'status' => 201,
            'data' => $categories,
          ]);
        } else {
          return response()->json(
            [
              'success' => 304,
              'message' => 'category could not be updated',
            ],
            304
          );
        }
      }
    }
  }
  public function CatBySectionID($id)
  {
    $categories = Categories::where('section_id', $id)->get();
    if ($categories) {
      return response()->json(['status' => 202, 'data' => $categories], 202);
    } else {
      return response()->json(
        [
          'status' => 204,
          'message' => "there is no category available for section id = $id ",
        ],
        204
      );
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
    $categories = Categories::find($id);
    if (!$categories) {
      return response()->json(
        [
          'status' => 204,
          'message' => "there is no category available with id = $id ",
        ],
        204
      );
    } else {
      $categories->delete();
      return response()->json(
        [
          'status' => 202,
          'message' => 'the category with id = ' . $id . ' was deleted success',
        ],
        202
      );
    }
  }
}
