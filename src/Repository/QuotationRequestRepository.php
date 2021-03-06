<?php

namespace App\Repository;

use App\Entity\QuotationRequest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use \Doctrine\ORM\Tools\Pagination\Paginator;
use \Symfony\Component\HttpFoundation\Request;
use \Symfony\Component\HttpFoundation\Session\Session;
use \Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @method QuotationRequest|null find($id, $lockMode = null, $lockVersion = null)
 * @method QuotationRequest|null findOneBy(array $criteria, array $orderBy = null)
 * @method QuotationRequest[]    findAll()
 * @method QuotationRequest[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuotationRequestRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, QuotationRequest::class);
    }
    
    /**
     * @return [] Returns an array of QuotationRequest objects
     */
    public function searchBack(Request $request, Session $session, array $data, string &$page)
    {
        if ((int) $page < 1) {
            throw new \InvalidArgumentException(sprintf("The page argument can not be less than 1 (value : %s)", $page));
        }
        $firstResult = ($page - 1) * $data['number_by_page'];
        $query = $this->getBackQuery($data);
        $query->setFirstResult($firstResult)->setMaxResults($data['number_by_page'])->addOrderBy('q.id', 'DESC');
        $paginator = new Paginator($query);
        if ($paginator->count() <= $firstResult && $page != 1) {
            if (!$request->get('page')) {
                $session->set('back_quotation_request_page', --$page);
                return $this->search($request, $session, $data, $page);
            } else {
                throw new NotFoundHttpException();
            }
        }
        return $paginator;
    }

    /**
     * @return \Doctrine\DBAL\Query\QueryBuilder
     */
    public function getBackQuery(array $data)
    {
        $query = $this->createQueryBuilder('q')
            ->leftJoin('q.agent', 'a');
        if (null !== ($data['search'] ?? null)) {
            $exprOrX = $query->expr()->orX();
            $exprOrX->add($query->expr()->like('q.deceasedFirstname', ':search'))->add($query->expr()->like('q.deceasedLastname', ':search'))->add($query->expr()->like('q.deceasedAddress', ':search'))->add($query->expr()->like('q.deathPlace', ':search'))->add($query->expr()->like('q.funeralType', ':search'))->add($query->expr()->like('q.ashesDestination', ':search'))->add($query->expr()->like('q.burialDestination', ':search'))->add($query->expr()->like('q.contemplation', ':search'));
            $query->where($exprOrX)->setParameter('search', '%' . $data['search'] . '%');
        }
        if (null !== ($data['agent'] ?? null)) {
            $query
            ->andWhere('a = :agent')
            ->setParameter('agent', $data['agent']);
        } else {
            $query
            ->andWhere('a IS NULL');
        }
        return $query;
    }
}
