<?php

namespace DoctrineExtensions\Sluggable;

interface Sluggable
{
    /**
     * Retrieves the Entity slug field name
     *
     * @return string
     */
    function getSlugFieldName();

    /**
     * Retrieves the Entity fields used to generate the slug value
     *
     * @return array
     */
    function getSlugGeneratorFields();
}