<?php

namespace OZiTAG\Tager\Backend\Files\Enums;

use OZiTAG\Tager\Backend\Core\Enums\Enum;

final class FilesScope extends Enum
{
    const FilesView = 'files.view';
    const FilesCreate = 'files.create';
    const FilesDelete = 'files.delete';
}
