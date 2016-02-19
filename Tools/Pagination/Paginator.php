<?php

/*
 * This file is part of the Jirro package.
 *
 * (c) Rendy Eko Prastiyo <rendyekoprastiyo@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jirro\Component\ORM\Tools\Pagination;

use Doctrine\ORM\Tools\Pagination\Paginator as BasePaginator;
use Doctrine\ORM\Query;
use Symfony\Component\HttpFoundation\Request;

class Paginator extends BasePaginator
{
    protected $currentPage;

    public function __construct(Query $query, $currentPage = 1)
    {
        parent::__construct($query);

        $this->currentPage = (int) $currentPage;
    }

    public function getCurrentPage()
    {
        return $this->currentPage;
    }

    public function getTotalPages()
    {
        $itemsPerPage = $this->getQuery()->getMaxResults();
        $itemCount    = $this->count();

        return ceil($itemCount / $itemsPerPage);
    }

    public function hasPreviousPage()
    {
        return ($this->currentPage > 1);
    }

    public function getPreviousPage()
    {
        $currentPage = $this->currentPage;

        if ($currentPage === 1) {
            return $currentPage;
        }

        return ($currentPage - 1);
    }

    public function hasNextPage()
    {
        return ($this->currentPage < $this->getTotalPages());
    }

    public function getNextPage()
    {
        $currentPage = $this->currentPage;
        $totalPages  = $this->getTotalPages();
        if ($currentPage >= $totalPages) {
            return $currentPage;
        }

        return ($currentPage + 1);
    }

    public function getPaginationNumbers()
    {
        $currentPage  = $this->currentPage;
        $totalPages   = $this->getTotalPages();

        $firstNumber = $currentPage - 2;
        if ($firstNumber < 1) {
            $firstNumber = 1;
        }

        $lastNumber = $currentPage + 2;
        if ($lastNumber > $totalPages) {
            $lastNumber = $totalPages;

            $firstNumber -= (2 - ($lastNumber - $currentPage));
            if ($firstNumber < 1) {
                $firstNumber = 1;
            }
        }

        return [
            'firstNumber'   => $firstNumber,
            'currentNumber' => $currentPage,
            'lastNumber'    => $lastNumber,
        ];
    }
}
