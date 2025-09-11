<?php

namespace App\Services;

use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Knp\Component\Pager\Pagination\PaginationInterface;

class PaginatorService
{

    private PaginatorInterface $paginator;
    private RequestStack $requestStack;

    public function __construct(
        PaginatorInterface $paginator,
        RequestStack $requestStack
    )
    {
        $this->paginator = $paginator;
        $this->requestStack = $requestStack;
    }

    public function paginate($target, int $page = 1, ?int $limit = null, array $options = []): PaginationInterface
    {
        if(!$limit) {
            $limit = $this
                        ->requestStack
                        ->getCurrentRequest()
                        ->query
                        ->get('limit', 10);
        }
        if(!count($options)) {
            $options = ['wrap-queries' => true];
        }
        return $this->paginator->paginate(
            $target,
            $page,
            $limit,
            $options
        );
    }

}