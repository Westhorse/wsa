<?php

namespace App\Http\Controllers\Common;

use App\Helpers\JsonResponse;
use App\Models\Media;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Log;
use App\Http\Resources\Common\MediaResource;

class MediaController extends Controller
{

    public function index(Request $request)
    {
        $length = $request->get('length', 24);
        $searchTerm = $request->input('search');
        if ($searchTerm) {
            $media = Media::where('name', 'like', "%$searchTerm%")
                ->latest()
                ->paginate($length);
        } else {
            $media = Media::latest()->paginate($length);
        }

        if ($request->has('ids')) {
            $media = Media::whereIn('id', $request->get('ids', []))->get();
        }

        return MediaResource::collection($media);
    }


    public function showMedia(Request $request)
    {
        $imageIds = $request->input('images', []);
        $media = Media::whereIn('id', $imageIds)->get();
        return MediaResource::collection($media);
    }

    public function getImg($id)
    {

        $media = Media::find($id);
        return new MediaResource($media);
    }

    public function store(Request $request)
    {
        request()->validate([
            'file' => ['required', 'file', 'max:512000']
        ], [
            'max' => 'File cannot be larger than 512MB.'
        ]);

        // dd(request()->all());


        try {

            $media = DB::transaction(function () {

                $file = request()->file('file');
                $media = Media::create([
                    'name' => $file->getClientOriginalName(),
                    'file_name' => $file->getClientOriginalName(),
                    'mime_type' => $file->getMimeType(),
                    'size' => $file->getSize(),
                    'author_id' => auth()->id()
                ]);

                $directory = "media/{$media->created_at->format('Y/m/d')}/{$media->id}";
                $media = Media::find($media->id);
                $media->update([
                    'file_path' => $directory . '/' . $media->file_name
                ]);
                $file->storeAs($directory, $media->file_name, 'public');


                return $media;
            });
        } catch (Exception $exception) {
            Log::error($exception->getMessage());

            return response()->json(['message' => $exception->getMessage()], 422);
        }


        return [
            'id' => $media->id,
            'preview_url' => $media->preview_url,
            'full_url' => $media->full_url,
        ];
    }

    public function destroy()
    {
        request()->validate([
            'mediaIds' => ['required', 'array']
        ]);

        foreach (Media::find(request('mediaIds')) as $media) {
            $media->delete();
            Storage::disk('public')->delete($media->path);
        }

        return redirect()->back();
    }


    public function deleteImage($id)
    {
        try {
            $media = Media::find($id);
            if (!$media) {
                return response()->json(['status' => 'error', 'message' => 'Media not found'], 404);
            }
            $isUsed = DB::table('mediable')
                ->where('media_id', $id)
                ->exists();
            if (!$isUsed) {
                $media->delete();
                return JsonResponse::respondSuccess(trans(JsonResponse::MSG_FORCE_DELETED_SUCCESSFULLY));
            } else {
                return response()->json(['result' => 'error', 'message' => 'Media is still in use', 'status' => 400]);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}
