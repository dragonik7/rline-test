<?php

namespace App\Services;

use App\Exceptions\BaseException;
use App\Models\Directory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DirectoryService
{

    public function create(array $dataDirectory): Directory
    {
        return Directory::create([
            'name'      => $dataDirectory['name'],
            'user_id'   => Auth::id(),
            'parent_id' => $dataDirectory['parent_id']??= null,
        ]);
    }

    /**
     * @throws BaseException
     */
    public function destroy(int $id): void
    {
        $directory = Directory::with(['files'])->findOrFail($id);

        if ($directory->user_id !== Auth::id()) {
            throw new BaseException("Unauthorized", 403);
        }

        foreach ($directory->files as $file){
            Storage::delete($file->path);
            $file->delete();
        }
        $directory->delete();
    }

    /**
     * @throws BaseException
     */
    public function rename(int $id, string $name): Directory
    {
        $directory = Directory::findOrFail($id);

        if ($directory->user_id !== Auth::id()) {
            throw new BaseException("Unauthorized", 403);
        }

        $directory->name = $name;
        $directory->save();
        return $directory;
    }
}