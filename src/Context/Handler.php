<?php

namespace rohsyl\LaravelAcl\Context;

use Illuminate\Database\Eloquent\Model;

abstract class Handler
{

    public abstract function active(): bool;

    public abstract function model(): Model;
}