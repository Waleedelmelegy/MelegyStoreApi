<?php

namespace App\Http\Controllers;

use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ImageController extends Controller
{
  /**
   * Fetch images
   * @param NA
   * @return JSON response
   */
  public function index()
  {
    $images = Image::all();
    return response()->json(
      [
        "status" => 200,
        "count" => count($images),
        "data" => $images,
      ],
      200
    );
  }

  /**
   * Upload Image
   * @param $request
   * @return JSON response
   */
  public function upload(Request $request)
  {
    $imagesName = [];
    $response = [];

    $validator = Validator::make($request->all(), [
      'images' => 'required',
      'images.*' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    ]);

    if ($validator->fails()) {
      return response()->json([
        "status" => "failed",
        "message" => "Validation error",
        "errors" => $validator->errors(),
      ]);
    }

    if ($request->has('images')) {
      foreach ($request->file('images') as $image) {
        // $imgInfo = getimagesize($image);
        // $mime = $imgInfo['mime'];
        // return $image;
        // exit();
        $filenameOriginal =
          time() . rand(5, 15) . '.' . $image->getClientOriginalExtension();

        $image->move('uploads/', $filenameOriginal);

        Image::create([
          'image_large' => $filenameOriginal,
        ]);

        // $imgInfo = getimagesize($image);
        // $mime = $imgInfo['mime'];
        // switch ($mime) {
        //   case 'image/jpeg':
        //     $img = imagecreatefromjpeg($image);
        //     imagejpeg($image, 'uploads/' . '-jpg-' . $filenameOriginal, 75);
        //     break;
        //   case 'image/png':
        //     $img = imagecreatefrompng($image);
        //     imagepng($image, 'uploads/' . '-png-' . $filenameOriginal, 75);
        //     break;
        //   case 'image/gif':
        //     $img = imagecreatefromgif($image);
        //     imagegif($image, 'uploads/' . '-gif-' . $filenameOriginal, 75);
        //     break;
        //   default:
        //     $img = imagecreatefromjpeg($image);
        //     imagejpeg($image, 'uploads/' . '-jpeg-' . $filenameOriginal, 75);
        // }
      }

      $response["status"] = 200;
      $response["message_en"] = "Success! image(s) uploaded";
      $response['message_ar'] = "تم إضافة الصورة بنجاح";
    } else {
      $response["status"] = "failed";
      $response["message_en"] = "Failed! image(s) not uploaded";
      $response['message_ar'] = "لم يتم إضافة الصورة بنجاح";
    }
    return response()->json($response);
  }

  public function destroy($id)
  {
    $image = Image::find($id);

    $link = file_exists('uploads/' . $image->image_large);

    if ($link) {
      $link = unlink('uploads/' . $image->image_large);
    }
    $image->delete();

    return response()->json(
      [
        'status' => 202,
        'message' => 'the image with id = ' . $id . ' was deleted success',
      ],
      202
    );
  }
}
