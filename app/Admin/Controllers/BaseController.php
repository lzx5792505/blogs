<?php

namespace App\Admin\Controllers;

use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Controllers\HasResourceActions;

class BaseController extends AdminController
{
    use HasResourceActions;
}
