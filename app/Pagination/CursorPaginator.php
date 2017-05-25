<?php

namespace App\Pagination;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Str;

class CursorPaginator extends Paginator
{

    /**
     * @var
     */
    private $beforeName;

    /**
     * @var null
     */
    private $before;

    /**
     * @var
     */
    private $afterName;

    /**
     * @var null
     */
    private $after;
    /**
     * @var int|null
     */
    private $isFirstPage;
    /**
     * @var array
     */
    private $isLastPage;

    /**
     * CursorPaginator constructor.
     * @param mixed $items
     * @param int $perPage
     * @param int|null $isFirstPage
     * @param array $before
     * @param $after
     * @param string $beforeName
     * @param string $afterName
     */
    public function __construct($items, $perPage, $isFirstPage, $isLastPage, $before, $after, $beforeName = 'before', $afterName = 'after')
    {
        parent::__construct($items, $perPage);

        $this->path = Paginator::resolveCurrentPath();

        $this->currentPage = null;
        $this->before = $before;
        $this->after = $after;
        $this->beforeName = $beforeName;
        $this->afterName = $afterName;
        $this->isFirstPage = $isFirstPage;
        $this->isLastPage = $isLastPage;

        if (!$this->isLastPage) {
            $this->hasMore = true;
        }

    }

    public function nextPageUrl()
    {
        if ($this->hasMorePages()) {
            return $this->cursorUrl($this->afterName, $this->after);
        }
    }

    public function previousPageUrl()
    {
        if (!$this->onFirstPage()) {
            return $this->cursorUrl($this->beforeName, $this->before);
        }
    }

    public function onFirstPage()
    {
        return $this->isFirstPage;
    }

    private function cursorUrl($cursor, $value)
    {

        if ($cursor == $this->afterName) {
            $parameters = [$this->afterName => $value];
        } else if ($cursor == $this->beforeName) {
            $parameters = [$this->beforeName => $value];
        }

        if (count($this->query) > 0) {
            $parameters = array_merge($this->query, $parameters);
        }

        return $this->path
            . (Str::contains($this->path, '?') ? '&' : '?')
            . http_build_query($parameters, '', '&')
            . $this->buildFragment();
    }

    public function hasPages()
    {
        return ( $this->isFirstPage != $this->isLastPage ) || $this->hasMorePages();
    }
}