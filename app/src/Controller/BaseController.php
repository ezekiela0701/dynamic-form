<?php

namespace App\Controller;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Vich\UploaderBundle\Mapping\PropertyMappingFactory;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

abstract class BaseController extends AbstractController
{

      protected $parameter;
      protected $serializer;
      protected $entityManager;
   
    /**
     * BaseController constructor.
     * @param SerializerInterface $serializer
     * @param PropertyMappingFactory $propertyMappingFactory
     */
    public function __construct(
        SerializerInterface $serializer,
        EntityManagerInterface $entityManager, 
        ParameterBagInterface $parameter
    )
    {
        $this->serializer       = $serializer;
        $this->parameter        = $parameter;
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
     * Gets the parameter value for the given name from Container
     *
     * @param string $name The parameter
     * 
     * @return mixed The parameter value
     * 
     * @throws InvalidArgumentException if the parameter is not defined
     */
    public function getingParameter(string $name)
    {
        return $this->parameter->get($name);
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

    public function persist($object)
    {

        try {

            $this->entityManager->persist($object);

        } catch (ORMException $ORMex) {
            throw $ORMex;
        }
    }

    public function flush()
    {

        $this->entityManager->flush();

    }

    /**
     * @param mixed $data
     * @param string $type
     * @return string
     */
    public function serialize($data, string $type, $context = [])
    {
        return $this->serializer->serialize($data, $type , $context);
    }


    public function jsonResponseNotFound($message)
    {
        # code...
        return new JsonResponse([

            "data"      => [],
            "code"      => Response::HTTP_NOT_FOUND,
            "success"   => false,
            "message"   => $message

        ]);
    }
    
    /**
     * Removes given object using the default entity manager
     *
     * @param object $object The object to save
     * @return bool True if object was successfuly removed, throws otherwise
     * @throws ORMException if an error has occurred
     */
    public function remove($object)
    {

        try {
            $this->entityManager->remove($object);
            
            $this->entityManager->flush();

            return true;

        } catch (ORMException $ORMex) {
            throw $ORMex;
        }
    }


    public function flashRedirect($type , $message , $route)
    {
        $this->addFlash($type, $message);
            
        return $this->redirectToRoute($route , []) ;
    }

    public function getUrlServer(){
        if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
            $url = "https"; 
        }
        else{
            $url = "http"; 
        }

        $url .= "://"; 
        $url .= $_SERVER['HTTP_HOST']; 
        // $url .= $_SERVER['REQUEST_URI']; 

        return $url ; 

        // $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    }

    public function uploadFile($files , $parameter , $path)
    {
        $datas = [] ;
        if ($files != null ) {
            $file       = explode('.', $files->getClientOriginalName());
            $datas['filename']   = $file[0] . '' . uniqid() . '.' . $files->guessExtension();
            $datas['path']       = $this->getUrlServer().$path."/".$datas['filename'] ;
            
            $files->move($this->getParameter($parameter), $datas['filename'] );
        }
        
        return $datas ;
    }

    public function removeFile($files , $parameter)
    {
        # code...
        $filesystem = new Filesystem();

        $filename = $this->getParameter($parameter).'/'.$files ;
                    
        return $filesystem->remove($filename);
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
