<?php

namespace Enraiged\Contracts;

use Illuminate\Http\Request;

interface ProvidesTabs
{
    /**
     *  Create and return a tabbed template system against this model.
     *
     *  @param  \Illuminate\Http\Request  $request
     *  @return array
     */
    public function tabs(Request $request): array;
}
