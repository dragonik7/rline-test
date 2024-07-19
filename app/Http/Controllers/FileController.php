<?php

namespace App\Http\Controllers;

use App\Exceptions\BaseException;
use App\Http\Requests\File\FileRenameRequest;
use App\Http\Requests\File\FileUploadRequest;
use App\Http\Resources\FileResource;
use App\Models\File;
use App\Services\FileService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{

    public function __construct(
        protected FileService $filesService
    ) {}

    public function upload(FileUploadRequest $request)
    {
        $request->validated();
        try {
            $files = $this->filesService->uploadFile($request->file('files'), $request->directory);
        } catch (BaseException $exception) {
            return $this->failResponse($exception->getMessage(), $exception->getCode());
        }
        return $this->successResponse(FileResource::collection($files), self::CODE_SUCCESS_CREATE_201);
    }

    public function destroy(int $id)
    {
        try {
            $this->filesService->destroy($id);
        } catch (BaseException $exception){
           return $this->failResponse($exception->getMessage(), $exception->getCode());
        }
        return $this->successResponse([], 204, "File deleted successfully");
    }

    public function rename(int $id, FileRenameRequest $request)
    {
        $request->validated();
        try {
            $file = $this->filesService->rename($request->name, $id);
        } catch (BaseException $exception){
            return $this->failResponse($exception->getMessage(), $exception->getCode());
        }
        return $this->successResponse(new FileResource($file), 200, "File renamed successfully");
    }

    public function show(int $id)
    {
        try {
            $file = $this->filesService->show($id);
        } catch (BaseException $exception){
            return $this->failResponse($exception->getMessage(), $exception->getCode());
        }
        return $this->successResponse(new FileResource($file), 200);
    }

    public function togglePublic($id)
    {
        try {
            $file = $this->filesService->togglePublic($id);
        } catch (BaseException $exception){
            return $this->failResponse($exception->getMessage(), $exception->getCode());
        }
        return $this->successResponse(new FileResource($file), 200, "File visibility toggled successfully");
    }

    public function download(string $uniqueLink)
    {
        $file = File::where('unique_link', $uniqueLink)->firstOrFail();
        return Storage::download($file->path, $file->name);
    }

    public function getCurrentSpace()
    {
        try {
            $totalSize = $this->filesService->getSpace();
        } catch (BaseException $exception){
            return $this->failResponse($exception->getMessage(), $exception->getCode());
        }
        return $this->successResponse(['total_size' => $totalSize], 200);
    }
}
