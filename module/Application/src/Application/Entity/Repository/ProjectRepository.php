<?php

namespace Application\Entity\Repository;

use Doctrine\ORM\EntityRepository;


/**
 * UserRepository
 *
 * Repository class to extend Doctrine ORM functions with your own
 * using DQL language. More here http://mackstar.com/blog/2010/10/04/using-repositories-doctrine-2
 *
 */
class ProjectRepository extends EntityRepository
{
    public function youtCustomDQLFunction($number = 30)
    {

    }

}
