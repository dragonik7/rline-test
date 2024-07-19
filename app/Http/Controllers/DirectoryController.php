<?php

namespace App\Http\Controllers;

use App\Exceptions\BaseException;
use App\Http\Requests\Directory\DirectoryRenameRequest;
use App\Http\Requests\Directory\DirectoryStoreRequest;
use App\Models\Directory;
use App\Services\DirectoryService;

class DirectoryController extends Controller
{

    public function __construct(
        protected DirectoryService $directoryService
    ) {}

    public function store(DirectoryStoreRequest $request)
    {
        $data = $request->validated();
        try {
            $directory = $this->directoryService->create($data);
        } catch (BaseException $exception) {
            return $this->failResponse($exception->getMessage(), $exception->getCode());
        }
        return response()->json($directory, 201);
    }

    // Удаление директории
    public function destroy(int $id)
    {
        try {
            $this->directoryService->destroy($id);
        } catch (BaseException $exception) {
            return $this->failResponse($exception->getMessage(), $exception->getCode());
        }
        return $this->successResponse([],204, 'Directory deleted successfully');
    }

    // Переименование директории
    public function rename(int $id, DirectoryRenameRequest $request)
    {
        $request->validated();
        try {
            $this->directoryService->rename($id, $request->name);
        } catch (BaseException $exception) {
            return $this->failResponse($exception->getMessage(), $exception->getCode());
        }
        return $this->successResponse([],200, 'Directory renamed successfully');
    }

}
