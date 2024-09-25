<?php

namespace App\Controller;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

abstract class BaseController extends AbstractController
{

      protected $entityManager;
   
    /**
     * BaseController constructor.
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager    = $entityManager;
    }


    /**
     * @param string|null $name
     * @return EntityManagerInterface
     */
    public function getRepository($entity) :EntityRepository
    {
        return $this->entityManager->getRepository($entity);
    }

    /**
     * Saves given object using the default entity manager
     *
     * @param object $object The object to save
     * @return object The saved object, throws otherwise
     * @throws ORMException if an error has occurred
     */
    public function save($object)
    {

        try {
            
            if($object->getId() === null) {
                

                $this->entityManager->persist($object);

            }
           
            $this->entityManager->flush();

            return $object;

        } catch (ORMException $ORMex) {
            throw $ORMex;
        }
    }

    function slugify(string $text): string
    {

        $text = preg_replace('~[^\pL\d]+~u', '-', $text);

        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        $text = strtolower($text);

        $text = preg_replace('~[^-\w]+~', '', $text);

        $text = trim($text, '-');

        $text = preg_replace('~-+~', '-', $text);

        if (empty($text)) {
            return 'n-a';
        }

        return $text;
    }


}
