<?php

namespace OZiTAG\Tager\Backend\Files\Operations;

use Ozerich\FileStorage\Storage;
use OZiTAG\Tager\Backend\Core\Jobs\Operation;
use OZiTAG\Tager\Backend\Files\Repositories\UserFileRepository;
use OZiTAG\Tager\Backend\Files\Requests\CreateUserFileRequest;

class CreateUserFileOperation extends Operation
{
    protected CreateUserFileRequest $request;

    public function __construct(CreateUserFileRequest $request)
    {
        $this->request = $request;
    }

    public function handle(UserFileRepository $userFileRepository)
    {
        return $userFileRepository->fillAndSave([
            'file_id' => Storage::fromUUIDtoId($this->request->file)
        ]);
    }
}
