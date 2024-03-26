<?php

namespace rohsyl\LaravelAcl\Test\Context;

use Illuminate\Database\Eloquent\Model;
use rohsyl\LaravelAcl\Context\Handler;
use rohsyl\LaravelAcl\Test\Models\Group;

class GroupContextHandler extends Handler
{

    public function active(): bool
    {
        return session()->has('group_id');
    }

    public function model(): Model
    {
        return Group::find(session('group_id'));
    }
}