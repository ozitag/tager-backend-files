<?php

namespace OZiTAG\Tager\Backend\Files\Enums;

enum FilesScope: string
{
    case FilesView = 'files.view';
    case FilesCreate = 'files.create';
    case FilesDelete = 'files.delete';
}
