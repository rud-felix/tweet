<?php

namespace Brd4\MessageBundle;

use Brd4\MessageBundle\Entity\Message;
use FOS\ElasticaBundle\Finder\TransformedFinder;
use Knp\Component\Pager\Paginator;
use Symfony\Component\Form\FormInterface;

class Search
{
    private $finder;

    private $paginator;

    private $count;

    public function __construct(TransformedFinder $finder, Paginator $paginator, $count)
    {
        $this->finder = $finder;
        $this->paginator = $paginator;
        $this->count = $count;
    }

    /**
     * @param string $text
     * @param integer $page
     * @return \Knp\Component\Pager\Pagination\PaginationInterface
     */
    public function search($text, $page)
    {
        // TODO: refactoring
        return $this->paginator->paginate(
            $this->finder->createPaginatorAdapter('*' . $text . '*'),
            $page,
            $this->count
        );
    }
}