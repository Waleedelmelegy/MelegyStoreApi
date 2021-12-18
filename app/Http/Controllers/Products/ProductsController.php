<?php

namespace App\Http\Controllers\Products;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Categories\Categories;
use App\Models\Products\Products;
use App\Models\Products\ProductsCategories;
use App\Models\Products\ProductsVariations;
use App\Models\Products\ProductsImages;

use Illuminate\Support\Facades\Validator;

class ProductsController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    //
    $products = Products::all();

    $data = [];

    $item = [];
    foreach ($products as $key => $value) {
      $item = $products[$key];
      $categories = [];
      $variations = [];
      $images = [];
      $realCategories = [];
      $realVariations = [];

      $categories = ProductsCategories::where(
        'product_id',
        '=',
        $products[$key]->id
      )->get();
      $variations = ProductsVariations::where(
        'product_id',
        '=',
        $products[$key]->id
      )->get();

      if (count($categories) > 0) {
        foreach ($categories as $x => $v) {
          $new = [];
          $new = Categories::where(
            "id",
            '=',
            $categories[$x]->category_id
          )->get();
          array_push($realCategories, $new[0]);
        }
        if (count($realCategories) > 0) {
          $item["categories"] = $realCategories;
        } else {
          $item["categories"] = [];
        }
      } else {
        $item["categories"] = [];
      }
      if (count($variations) > 0) {
        foreach ($variations as $x => $v) {
          $new = [];
          $new = Products::where(
            "id",
            '=',
            $variations[$x]->variation_id
          )->get();
          array_push($realVariations, $new[0]);
        }
        if (count($realVariations) > 0) {
          $item["variations"] = $realVariations;
        } else {
          $item['variations'] = [];
        }
      } else {
        $item['variations'] = [];
      }

      $images = ProductsImages::where(
        'product_id',
        '=',
        $products[$key]->id
      )->get();

      if (count($images) > 0) {
        $products[$key]['gallary'] = $images;
      } else {
        $products[$key]['gallary'] = [];
      }

      // $item['variations'] = ProductsVariations::where('product_id', '=', $products[$key]->id)->get()->toArray();
      array_push($data, $item);
    }

    // return response()->json(['status' => 200, 'data' => $data], 200);
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
      'status' => 'required',
      'title' => 'required|max:55',
      'desc' => 'required',
      'price' => 'required',
      'color' => 'required',
      'size' => 'required',
      'weight' => 'required',
      'qty' => 'required',
      'user_id' => 'required',
      'created_by' => 'required|max:100',
    ]);

    if ($validator->fails()) {
      return response()->json(
        ['status' => 203, 'message' => $validator->messages()],
        203
      );
    } else {
      $item = Products::create([
        'status' => $request->status,
        'title' => $request->title,
        'desc' => $request->desc,
        'title_seo' => $request->title_seo,
        'desc_seo' => $request->desc_seo,
        'price' => $request->price,
        'color' => $request->color,
        'size' => $request->size,
        'weight' => $request->weight,
        'qty' => $request->qty,
        'user_id' => $request->user_id,
        'created_by' => $request->created_by,
      ]);
      //add products to categories
      foreach ($request->categories as $category) {
        ProductsCategories::create([
          'product_id' => $item->id,
          'category_id' => $category['id'],
        ]);
      }
      foreach ($request->variations as $variation) {
        ProductsVariations::create([
          'product_id' => $item->id,
          'variation_id' => $variation['id'],
        ]);
      }

      // return the product with created categories
      $item['categories'] = ProductsCategories::where(
        'product_id',
        '=',
        $item->id
      )
        ->get()
        ->toArray();
      $item['variations'] = ProductsVariations::where(
        'product_id',
        '=',
        $item->id
      )
        ->get()
        ->toArray();

      return response()->json(['status' => 201, 'data' => $item], 201);
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
    $product = Products::find($id);
    $categories = [];
    $variations = [];
    $realCategories = [];
    $realVariations = [];

    if ($product) {
      $updated = $product->fill(['views' => $product->views + 1])->save();
      if ($updated) {
        $categories = ProductsCategories::where('product_id', '=', $product->id)
          ->get()
          ->toArray();
        //get product categories

        if (count($categories) > 0) {
          foreach ($categories as $x => $v) {
            $new = [];
            $new = Categories::where(
              "id",
              '=',
              $categories[$x]['category_id']
            )->get();

            array_push($realCategories, $new[0]);
          }
          if (count($realCategories) > 0) {
            $product["categories"] = $realCategories;
          } else {
            $product["categories"] = [];
          }
        } else {
          $product["categories"] = [];
        }
        //get product variations
        $variations = ProductsVariations::where('product_id', '=', $product->id)
          ->get()
          ->toArray();

        if (count($variations) > 0) {
          foreach ($variations as $x => $v) {
            $new = [];
            $new = Products::where(
              "id",
              '=',
              $variations[$x]['variation_id']
            )->get();

            array_push($realVariations, $new[0]);
          }
          if (count($realVariations) > 0) {
            $product["variations"] = $realVariations;
          } else {
            $product["variations"] = [];
          }
        } else {
          $product["variations"] = [];
        }

        $images = ProductsImages::where('product_id', '=', $product->id)->get([
          'image_id AS id',
          'image_large',
          'product_id',
        ]);

        if (count($images) > 0) {
          $product['gallary'] = $images;
        } else {
          $product['gallary'] = [];
        }
        return response()->json(['status' => 202, 'data' => $product], 202);
      }
    } else {
      return response()->json(
        ['status' => 200, 'message' => 'there is no product with id = ' . $id],
        200
      );
    }
  }

  public function ProductByAuthUserID()
  {
    $product = auth()
      ->user()
      ->products()
      ->get();
    if (!$product) {
      return response()->json(
        [
          'success' => 200,
          'message' => 'Product with id ' . $id . ' not found',
        ],
        200
      );
    }
    $data = [];
    foreach ($product as $key => $value) {
      $item = [];
      $item = $product[$key];
      $item['categories'] = ProductsCategories::where(
        'product_id',
        '=',
        $product[$key]->id
      )
        ->get()
        ->toArray();
      array_push($data, $item);
    }
    return response()->json(
      [
        'success' => 200,
        'data' => $data,
      ],
      200
    );
  }

  public function ProductByUserID($id)
  {
    $user = User::find($id);
    if (!$user) {
      return response()->json(
        [
          'success' => 200,
          'message' => 'User with id ' . $id . ' not found',
        ],
        200
      );
    }
    $product = User::find($id)
      ->products()
      ->get();
    if (!$product) {
      return response()->json(
        [
          'success' => 202,
          'message' => 'Product with id ' . $id . ' not found',
        ],
        200
      );
    }
    $data = [];
    foreach ($product as $key => $value) {
      $item = [];
      $item = $product[$key];
      $item['categories'] = ProductsCategories::where(
        'product_id',
        '=',
        $product[$key]->id
      )
        ->get()
        ->toArray();
      $item['variations'] = ProductsVariations::where(
        'product_id',
        '=',
        $product[$key]->id
      )
        ->get()
        ->toArray();
      array_push($data, $item);
    }
    return response()->json(
      [
        'success' => 200,
        'data' => $data,
      ],
      200
    );
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
    $product = Products::find($id);
    if (!$product) {
      return response()->json(
        ['status' => 401, 'message' => "no product with id = $id founded"],
        200
      );
    } else {
      //delete old categories first
      $productCat = ProductsCategories::where('product_id', '=', $id)->get();

      if (count($productCat) > 0) {
        foreach ($productCat as $key => $value) {
          ProductsCategories::destroy([
            'product_id' => $productCat[$key]->id,
          ]);
        }
      }
      //insert new categories
      if (count($request->categories) > 0) {
        foreach ($request->categories as $category) {
          ProductsCategories::create([
            'product_id' => $product->id,
            'category_id' => $category['id'],
          ]);
        }
      }

      //delete old variations first
      $productVar = ProductsVariations::where('product_id', '=', $id)->get();

      if (count($productVar) > 0) {
        foreach ($productVar as $key => $value) {
          ProductsVariations::destroy([
            'product_id' => $productVar[$key]->id,
          ]);
        }
      }

      //insert new variations
      if (count($request->variations) > 0) {
        foreach ($request->variations as $variation) {
          ProductsVariations::create([
            'product_id' => $product->id,
            'variation_id' => $variation['id'],
          ]);
        }
      }

      //delete old gallary first
      $productGallary = ProductsImages::where('product_id', '=', $id)->get();
      if (count($productGallary) > 0) {
        foreach ($productGallary as $key => $value) {
          ProductsImages::destroy([
            'product_id' => $productGallary[$key]->id,
          ]);
        }
      }

      //insert new gallary
      if ($request->gallary && count($request->gallary) > 0) {
        foreach ($request->gallary as $image) {
          ProductsImages::create([
            'product_id' => $product->id,
            'image_large' => $image['image_large'],
            'image_id' => $image['id'],
          ]);
        }
      }

      //update product details
      $updated = $product->fill($request->all())->save();
      if ($updated) {
        $product['categories'] = ProductsCategories::where(
          'product_id',
          '=',
          $product->id
        )
          ->get()
          ->toArray();
        $product['variations'] = $request->variations;
        return response()->json(
          [
            'status' => 200,
            'data' => $product,
          ],
          202
        );
      } else {
        return response()->json(
          [
            'status' => 304,
            'message' => 'product could not be updated',
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
    $product = Products::find($id);

    // print_r($product->id);exit;
    if (!$product) {
      return response()->json(
        [
          'status' => 200,
          'message' => "there is no products available with id = $id ",
        ],
        200
      );
    } else {
      //delete product categories
      $productCat = ProductsCategories::where('product_id', '=', $id)->get();
      foreach ($productCat as $key => $value) {
        ProductsCategories::destroy([
          'product_id' => $productCat[$key]->id,
        ]);
      }
      //delete product variations
      $productVar = ProductsVariations::where('product_id', '=', $id)->get();
      foreach ($productVar as $key => $value) {
        ProductsVariations::destroy([
          'product_id' => $productVar[$key]->id,
        ]);
      }
      $singlVariation = ProductsVariations::where('variation_id', '=', $id)
        ->get()
        ->first();
      if ($singlVariation) {
        $singlVariation->delete();
      }
      //delete product categories
      $productImages = ProductsImages::where('product_id', '=', $id)->get();
      foreach ($productImages as $key => $value) {
        ProductsImages::destroy([
          'product_id' => $productImages[$key]->id,
        ]);
      }
      $product->delete();
      return response()->json(
        [
          'status' => 202,
          'message' => 'the product with id = ' . $id . ' was deleted success',
        ],
        202
      );
    }
  }
}
