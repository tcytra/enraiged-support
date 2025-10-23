<?php

namespace Enraiged\Collections;

use Enraiged\Builders\Secure\AssertSecure;
use Enraiged\Collections\RequestCollection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;

class ActionsCollection extends Collection
{
    use AssertSecure;

    /** @var  Model  The actionable model. */
    protected $model;

    /** @var  array  The action parameters. */
    protected $parameters;

    /** @var  string  The routing prefix for the actions. */
    protected string $prefix;

    /** @var  string  The template json file path. */
    protected string $template;

    /**
     *  Create a new actions collection.
     *
     *  @param  \Illuminate\Contracts\Support\Arrayable<TKey, TValue>|iterable<TKey, TValue>|null  $items
     */
    public function __construct($items = [])
    {
        $this->prefix = preg_replace('/\.$/', '', $this->prefix);

        if ($this->template) {
            $this->items = $this->getArrayableItems(
                $this->assembleActionsFromTemplate($items)
            );
        }
    }

    /**
     *  Configure the routing for the collected actions.
     *
     *  @param  \Enraiged\Collections\RequestCollection  $request
     *  @param  \Illuminate\Database\Eloquent\Model|null  $model
     *  @param  string|null  $prefix = null
     *  @return \Enraiged\Collections\ActionsCollection
     */
    public function asRoutableActions(RequestCollection $request, ?Model $model = null, ?string $prefix = null)
    {
        $actions = [];

        $model = $model ?: $this->model;

        $prefix = $prefix ?: $this->prefix;

        $routeParameters = $request->route()->hasParameters()
            ? [...$request->route()->parameters(), $this->parameters]
            : $this->parameters;

        foreach ($this->items as $action => $parameters) {
            $parameters = $this->populateActionRoute($action, $parameters, $prefix);

            $permission = $this->assertSecure($parameters, $model)
                && $request->user()->can($action, $model);

            $route = $parameters['route']['name'];

            if ($permission && Route::has($route)) {
                $route = Route::getRoutes()->getByName($route);

                $parameters['route']['uri'] = $route->uri;

                preg_match('/\{[a-z]+\}/', $route->uri, $matches);

                if (count($matches)) {
                    $route->parameterNames = preg_replace('/[\{\}]/', '', $matches);
                    $route->parameters = collect($routeParameters)
                        ->only($route->parameterNames)
                        ->toArray();

                    $parameters['route']['params'] = $route->parameters;
                }

                if (!key_exists('method', $parameters['route'])) {
                    $parameters['route']['method'] = 'get';
                }

                if (!key_exists('url', $parameters['route'])) {
                    $parameters['route']['url'] = route(
                        $parameters['route']['name'],
                        $route->parameters,
                        config('enraiged.app.absolute_uris')
                    );
                }

                if (key_exists('disabled', $parameters) && gettype($parameters['disabled']) === 'array') {
                    $parameters['disabled'] = $this->assertDisabledAction($parameters['disabled'], $model);
                }

                $actions[$action] = $parameters;
            }
        }

        $this->items = $this->getArrayableItems($actions);

        return $this;
    }

    /**
     *  Assemble the actions from a filesystem template.
     *
     *  @param  array   $items
     *  @return array
     */
    public function assembleActionsFromTemplate($items): array
    {
        foreach ($items as $action => $properties) {
            if (!is_array($properties)) {
                $items[$action] = ['class' => $properties];
            }
        }

        $keys = array_keys($items);
        $template = json_decode(file_get_contents($this->template), true);

        $actions = array_filter($template, fn ($item) => in_array($item, $keys), ARRAY_FILTER_USE_KEY);

        foreach ($items as $action => $properties) {
            $items[$action] = [
                ...$actions[$action],
                ...$properties,
            ];
        }

        return $items;
    }

    /**
     *  Determine whether or not an action should be disabled.
     *
     *  @param  array|object  $assertion
     *  @param  object|string  $model
     *  @return bool
     */
    protected function assertDisabledAction($assertion, $model)
    {
        $assertion = (object) $assertion;

        if (property_exists($assertion, 'method')) {
            $method = preg_match('/^assert/', $assertion->method)
                ? $assertion->method
                : Str::camel("assert_{$assertion->method}");

            return method_exists($this, $method)
                ? $this->{$method}($assertion, $model)
                : false;
        }
    }

    /**
     *  Run a filter over each of the items.
     *
     *  Note: This overload exists because the collection is renewed by default
     *  and we lose all instance context.
     *
     *  @param  (callable(TValue, TKey): bool)|null  $callback
     *  @return $this
     */
    public function filter(?callable $callback = null)
    {
        $this->items = collect($this->items)
            ->filter($callback)
            ->toArray();

        return $this;
    }

    /**
     *  Set or get the actionable model.
     *
     *  @param  \Illuminate\Database\Eloquent\Model  $model = null
     *  @param  array|null  $parameters
     *  @return \Illuminate\Database\Eloquent\Model|$this
     */
    public function model(?Model $model = null, ?array $parameters = null)
    {
        if ($model) {
            $this->model = $model;
            $this->parameters = $parameters;

            return $this;
        }

        return $this->model;
    }

    /**
     *  Set or get the actionable model.
     *
     *  @param  array|null  $parameters
     *  @return array|$this
     */
    public function parameters(?array $parameters = null)
    {
        if ($parameters) {
            $this->parameters = $parameters;

            return $this;
        }

        return $this->parameters;
    }

    /**
     *  
     *  @param  string  $action
     *  @param  array   $parameters
     *  @param  string|null  $prefix = null
     *  @return array
     */
    protected function populateActionRoute($action, $parameters, $prefix = null): array
    {
        $prefix = $prefix ?: $this->prefix;

        if (!key_exists('route', $parameters)) {
            $parameters['route'] = ['name' => preg_replace('/\.+/', '.', "{$prefix}.{$action}")];

        } else if (gettype($parameters['route']) === 'string') {
            $parameters['route'] = ['name' => $parameters['route'],];

        } else if (key_exists('action', $parameters['route'])) {
            $parameters['route']['name'] = preg_replace('/\.+/', '.', "{$prefix}.{$parameters['route']['action']}");

        } else if (!key_exists('name', $parameters['route'])) {
            $parameters['route']['name'] = preg_replace('/\.+/', '.', "{$prefix}.{$action}");
        }

        return $parameters;
    }
}
