<?php

namespace App\Services;

use App\Exceptions\BaseException;
use App\Models\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileService
{

    public function uploadFile(UploadedFile|array|null $files, int $directoryId = null): Collection
    {
        $savedFiles = collect();
        foreach ($files as $file) {
            $path = $file->store('files');
            $savedFiles->add(File::create([
                'name'         => $file->getClientOriginalName(),
                'directory_id' => $directoryId,
                'user_id'      => Auth::id(),
                'size'         => $file->getSize(),
                'uploaded_at'  => now(),
                'is_public'    => false,
                'unique_link'  => Str::random(16),
                'path'         => $path,
            ]));
        }
        return $savedFiles;
    }

    /**
     * @throws BaseException
     */
    public function destroy(int $id): void
    {
        $file = File::findOrFail($id);

        if ($file->user_id !== Auth::id()) {
            throw new BaseException("Unauthorized", 403);
        }

        Storage::delete($file->path);
        $file->delete();
    }

    /**
     * @throws BaseException
     */
    public function rename(string $name, int $id): File
    {
        $file = File::findOrFail($id);

        if ($file->user_id !== Auth::id()) {
            throw new BaseException("Unauthorized", 403);
        }

        $file->name = $name;
        $file->save();
        return $file;
    }

    /**
     * @throws BaseException
     */
    public function show(int $id): File
    {
        $file = File::findOrFail($id);

        if ($file->user_id !== Auth::id() && !$file->is_public) {
            throw new BaseException("Unauthorized", 403);
        }
        return $file;
    }

    /**
     * @throws BaseException
     */
    public function togglePublic(int $id): File
    {
        $file = File::findOrFail($id);

        if ($file->user_id !== Auth::id()) {
            throw new BaseException("Unauthorized", 403);
        }

        $file->is_public = !$file->is_public;
        $file->save();
        return $file;
    }

    public function getSpace(): int
    {
        return (int)File::where('user_id', Auth::id())->sum('size');
    }
}