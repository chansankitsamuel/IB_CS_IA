<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Pager extends BaseConfig
{
    /**
     * --------------------------------------------------------------------------
     * Templates
     * --------------------------------------------------------------------------
     *
     * Pagination links are rendered out using views to configure their
     * appearance. This array contains aliases and the view names to
     * use when rendering the links.
     *
     * Within each view, the Pager object will be available as $pager,
     * and the desired group as $pagerGroup;
     *
     * @var array<string, string>
     */
    public array $templates = [
        'default_full'   => 'CodeIgniter\Pager\Views\default_full',
        'default_simple' => 'CodeIgniter\Pager\Views\default_simple',
        'default_head'   => 'CodeIgniter\Pager\Views\default_head',
        'custom_pager'    => 'App\Views\Pager\custom_pager',
        
        'group1' => [
            'wrapper'     => '<nav aria-label="Page navigation">{pagerList}</nav>',
            'first'       => '<li class="page-item"><a class="page-link" href="{url}">{page}</a></li>',
            'previous'    => '<li class="page-item"><a class="page-link" href="{url}">&laquo;</a></li>',
            'next'        => '<li class="page-item"><a class="page-link" href="{url}">&raquo;</a></li>',
            'last'        => '<li class="page-item"><a class="page-link" href="{url}">{page}</a></li>',
            'current'     => '<li class="page-item active"><a class="page-link" href="{url}">{page}</a></li>',
            'num_links'   => '<li class="page-item"><a class="page-link" href="{url}">{page}</a></li>',
            'start_links' => '<li class="page-item"><a class="page-link" href="{url}">{page}</a></li>',
            'end_links'   => '<li class="page-item"><a class="page-link" href="{url}">{page}</a></li>',
        ],
    ];
    

    /**
     * --------------------------------------------------------------------------
     * Items Per Page
     * --------------------------------------------------------------------------
     *
     * The default number of results shown in a single page.
     */
    public int $perPage = 20;
}
