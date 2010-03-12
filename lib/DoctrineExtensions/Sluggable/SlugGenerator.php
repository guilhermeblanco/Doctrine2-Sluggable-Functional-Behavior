<?php

namespace DoctrineExtensions\Sluggable;

use Doctrine\ORM\EntityManager;

class SlugGenerator
{
    /**
     * @var EntityManager
     */
    private $_em;

    /**
     * Constructor
     *
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->_em = $em;
    }

    /**
     * Process an entity and generate a unique slug in the desierd field.
     *
     * @todo The way we generate the slug is not optimal. It must be needed to deal with concurrency.
     * @param Sluggable $entity Entity to be processed (implements Sluggable interface)
     */
    public function process(Sluggable $entity)
    {
        // Retrieving ClassMetadata
        $class = $this->_em->getClassMetadata(get_class($entity));

        // Generate slug candidate
        $slugCandidate = $this->_getSlugCandidate($class, $entity);

        // Inspect storage for an already existent slug
        $qb = $this->_em->createQueryBuilder()
            ->select($qb->expr()->count('c.' . $entity->getSlugFieldName()))
            ->from($class->name, 'c')
            ->where($qb->expr()->like(
                'c.' . $entity->getSlugFieldName(),
                $qb->expr()->quote($slugCandidate . '%')
            ));
        $count = $qb->getQuery()->getSingleScalarResult();

        // If slug exists, append the counter (foo-2, for example)
        if (intval($count) > 0) {
            $slugCandidate .= '-' . $count;
        }

        // Assign slug value into entity
        $class->setFieldValue($entity, $entity->getSlugFieldName(), $value);
    }

    /**
     * Generate a slug candidate given its ClassMetadata and an Entity to extract information.
     *
     * @param ClassMetadata $class Related Entity ClassMetadata
     * @param Sluggable $entity Entity to have information extracted
     * @return <type>
     */
    private function _getSlugCandidate(ClassMetadata $class, Sluggable $entity)
    {
        $generatorFields = $this->_entity->getSlugGeneratorFields();
        $slugCandidate = '';

        if ($generatorFields) {
            $slugCandidate = array();

            // Loop through all fields defined
            foreach ($generatorFields as $fieldName) {
                $slugCandidate[] = $class->getReflectionProperty($fieldName)->getValue($this->_entity);
            }

            $slugCandidate = implode(' ', $slugCandidate);
        } else {
            // TODO We do not have any field to be considered, create a unique hash using a URL shortener technique
            // Good reference (pt_BR): http://manoellemos.com/2009/11/23/zapt-in-entendendo-e-brincando-com-os-encurtadores-de-url/
        }

        $normalizer = new SlugNormalizer(implode(' ', $slugCandidate));

        return $normalizer->normalize();
    }
}
