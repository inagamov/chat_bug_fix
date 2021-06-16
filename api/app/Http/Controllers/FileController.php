<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

# Models
use App\Models\UsersInfo;

class FileController extends Controller
{
    public function uploadFile (Request $request) {
        # Processing base64
        $exploded = explode(',', $request->image_data);
        $decoded = base64_decode($exploded[1]);

        # Configuring filename
        $extension = strtok(explode('/', $exploded[0])[1], ';');
        $file_name = $request->file_name.'.'.$extension;

        # Defining the path
        $path = public_path().'/../../web/src/assets/img/uploads/'.$file_name;

        # Uploading file to the storage
        file_put_contents($path, $decoded);

        # Save user profile image filename
        if ($request->image_prefix == 'profile_image') {
            UsersInfo::where('id', $request->user()->id)->update(['profile_image' => $file_name]);

        # Save user profile background filename
        } else {
            UsersInfo::where('id', $request->user()->id)->update(['profile_background' => $file_name]);
        }
    }

    public function removeFile (Request $request) {
        # Define the whole path
        $path = public_path().$request->path;

        # If file exists
        if (File::exists($path)) {
            # Remove file from the server
            unlink($path);

            # Remove user profile image filename
            if ($request->image_prefix == 'profile_image') {
                UsersInfo::where('id', $request->user()->id)->update(['profile_image' => null]);

            # Remove user profile background filename
            } else {
                UsersInfo::where('id', $request->user()->id)->update(['profile_background' => null]);
            }
        }
    }
}