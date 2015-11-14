<?php

namespace App\Resolvers;

use App\Core\EntityInterface;

interface EntityResolverInterface
{
    public function setEntity(EntityInterface $entity);

    /**
     * @return EntityInterface
     */
    public function getEntity();
}