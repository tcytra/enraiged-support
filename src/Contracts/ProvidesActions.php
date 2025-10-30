<?php

namespace Enraiged\Contracts;

use Enraiged\Collections\ActionsCollection;

interface ProvidesActions
{
    /**
     *  Assemble and return the available actions.
     *
     *  @param  array|string   $items
     *  @return \Enraiged\Collections\ActionsCollection
     */
    public function actions(array|string $items): ActionsCollection;
}
