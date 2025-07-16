<?php

namespace Core\View;

/**
 * Class Paginator
 *
 * @author Gabriel Amorim <https://github.com/amorim778>
 * @package Core\View\Paginator
 */
class Paginator
{
    /** @var int */
    private $page;

    /** @var int */
    private $pages;

    /** @var int */
    private $rows;

    /** @var int */
    private $limit;

    /** @var int */
    private $offset;

    /** @var int */
    private $range;

    /** @var string */
    private $link;

    /** @var string */
    private $title;

    /** @var string */
    private $class;

    /** @var string */
    private $hash;

    /** @var array */
    private $first;

    /** @var array */
    private $last;

    /** @var string */
    private $params;

    /** @var string */
    private $onclick;

    /**
     * Paginator constructor.
     * @param string|null $link
     * @param string|null $title
     * @param array|null $first
     * @param array|null $last
     */
    public function __construct(
        string $link = null,
        string $title = null,
        array $first = null,
        array $last = null,
        string $onclick = ''
    ) {
        $this->link = ($link ?? "?page=");
        $this->title = ($title ?? "Página");
        $this->first = ($first ?? ["Primeira página", "<<"]);
        $this->last = ($last ?? ["Última página", ">>"]);
        $this->onclick = $onclick;
    }

    /**
     * @param int $rows
     * @param int $limit
     * @param int|null $page
     * @param int $range
     * @param string|null $hash
     * @param array $params
     */
    public function pager(
        int $rows,
        int $limit = 10,
        int $page = null,
        int $range = 3,
        string $hash = null,
        array $params = []
    ): void {
        $this->rows = $this->toPositive($rows);
        $this->limit = $this->toPositive($limit);
        $this->range = $this->toPositive($range);
        $this->pages = (int)ceil($this->rows / $this->limit);
        $this->page = ($page <= $this->pages ? $this->toPositive($page) : $this->pages);

        $this->offset = (($this->page * $this->limit) - $this->limit >= 0 ?
            ($this->page * $this->limit) - $this->limit :
            0);
        $this->hash = (!empty($hash) ? "#{$hash}" : null);

        $this->addGetParams($params);

        if ($this->rows && $this->offset >= $this->rows) {
            header("Location: {$this->link}" . ceil($this->rows / $this->limit));
            exit;
        }
    }

    /**
     * @return int
     */
    public function limit(): int
    {
        return $this->limit;
    }

    /**
     * @return int
     */
    public function offset(): int
    {
        return $this->offset;
    }

    /**
     * @return int
     */
    public function page()
    {
        return $this->page;
    }

    /**
     * @return int
     */
    public function pages()
    {
        return $this->pages;
    }

    /**
     * @param string $cssClass
     * @param bool $fixedFirstAndLastPage
     * @return null|string
     */
    public function render(string $cssClass = null, bool $fixedFirstAndLastPage = false): ?string
    {
        $this->class = $cssClass ?? "paginator";
        $current = "<li class=\"page-item active\"><a href=\"javascript:;\" class=\"page-link\">{$this->page}</a></li>";
        if ($this->rows > $this->limit) :
            $paginator = "<ul class=\"pagination\">";
            $paginator .= $this->firstPage($fixedFirstAndLastPage);
            $paginator .= $this->beforePages();
            $paginator .= $current;
            $paginator .= $this->afterPages();
            $paginator .= $this->lastPage($fixedFirstAndLastPage);
            $paginator .= "</ul>";
            return $paginator;
        endif;

        return null;
    }

    /**
     * @return null|string
     */
    private function beforePages(): ?string
    {
        $before = null;
        for ($iPag = $this->page - $this->range; $iPag <= $this->page - 1; $iPag++) :
            if ($iPag >= 1) :
                $redirect = $this->onclick ?
                    "onclick=\"{$this->onclick}({$iPag})\" href=\"javascript:;\"" :
                    "href=\"{$this->link}{$iPag}{$this->hash}{$this->params}\"";
                $before .= "<li class=\"page-item\"><a {$redirect} class=\"page-link\">{$iPag}</a></li>";
            endif;
        endfor;

        return $before;
    }

    /**
     * @return string|null
     */
    private function afterPages(): ?string
    {
        $after = null;
        for ($dPag = $this->page + 1; $dPag <= $this->page + $this->range; $dPag++) :
            if ($dPag <= $this->pages) :
                $redirect = $this->onclick ?
                    "onclick=\"{$this->onclick}({$dPag})\" href=\"javascript:;\"" :
                    "href=\"{$this->link}{$dPag}{$this->hash}{$this->params}\"";
                $after .= "<li class=\"page-item\"><a {$redirect} class=\"page-link\">{$dPag}</a></li>";
            endif;
        endfor;

        return $after;
    }

    /**
     * @param bool $fixedFirstAndLastPage
     * @return string|null
     */
    public function firstPage(bool $fixedFirstAndLastPage = true): ?string
    {
        if ($fixedFirstAndLastPage || $this->page != 1) {
            $redirect = $this->onclick ?
                "onclick=\"{$this->onclick}(1)\" href=\"javascript:;\"" :
                "href=\"{$this->link}1{$this->hash}{$this->params}\"";
            return "
            <li class=\"page-item previous \">
                <a {$redirect} class=\"page-link\">
                <i class=\"previous\"></i>
                </a>
            </li>
          ";
        }
        return '<li class="page-item previous disabled"><a href="#" class="page-link"><i class="previous"></i></a></li>';
    }

    /**
     * @param bool $fixedFirstAndLastPage
     * @return string|null
     */
    public function lastPage(bool $fixedFirstAndLastPage = true): ?string
    {
        if ($fixedFirstAndLastPage || $this->page != $this->pages) {
            $redirect = $this->onclick ?
                "onclick=\"{$this->onclick}({$this->pages})\" href=\"javascript:;\"" :
                "href=\"{$this->link}{$this->pages}{$this->hash}{$this->params}\"";
            return "
                <li class=\"page-item next \">
                    <a {$redirect} class=\"page-link\">
                    <i class=\"next\"></i>
                    </a>
                </li>
            ";
        }
        return '<li class="page-item next disabled"><a href="#" class="page-link"><i class="next"></i></a></li>';
    }

    /**
     * @param $number
     * @return int
     */
    private function toPositive($number): int
    {
        return ($number >= 1 ? $number : 1);
    }

    /**
     * Add get parameters
     * @param array $params
     * @return null
     */
    private function addGetParams(array $params)
    {
        $this->params = '';

        if (count($params) > 0) {
            if (isset($params['page'])) {
                unset($params['page']);
            }

            $this->params  = '&';
            $this->params .= http_build_query($params);
        }

        return $this;
    }
}
