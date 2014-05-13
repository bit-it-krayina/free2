<?php
namespace Application\Service;


use Doctrine\ORM\EntityManager;

interface EntityManagerAwareInterface
{
    public function setEntityManager(EntityManager $em);

    public function getEntityManager();
    
}