<?php

namespace App\Domain\Apis;

class AtributoApi
{
    public string $columnName;
    public bool $isPrimaryKey;
    public array $rules;
    public string $apiColumnName;
    public bool $hasCustomColumnName;
    public mixed $callback;
    public bool $hasCallback;

    public function __construct(
        bool $isPrimaryKey,
        string $columnName,
        array $rules,
        string $apiColumnName = null,
        \Closure $callback = null,
    ) {
        $this->isPrimaryKey = $isPrimaryKey;
        $this->columnName = $columnName;
        $this->hasCustomColumnName = (bool) isset($apiColumnName);
        $this->apiColumnName = $this->hasCustomColumnName ? $apiColumnName : $columnName;
        $this->rules = ['*.' . $this->apiColumnName => $rules];
        $this->hasCallback = (bool) isset($callback);
        $this->callback = $callback;
    }

    public function callClosure()
    {
        return ($this->callback)();
    }
}
