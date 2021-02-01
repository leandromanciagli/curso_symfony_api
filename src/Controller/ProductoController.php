<?php
namespace App\Controller;

use App\Entity\Producto;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ProductoController
 * @package App\Controller
 *
 * @Route(path="/api")
 */
class ProductoController extends AbstractController
{
    /**
     * @Route("/producto", name="add_producto", methods={"POST"})
     */
    public function add(Request $request): JsonResponse
    {
        $nombre = $request->get('nombre');
        $precio = $request->get('precio');
        $descripcion = $request->get('descripcion');

        if(empty($nombre) || empty($precio))
        {
            $error = [ 
                "mensaje" => "El nombre y el precio son obligatorios!",
            ];
            return new JsonResponse(['error' => $error], 400);
        }

        try
        {
            $producto = new Producto($nombre, $precio, $descripcion);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($producto);
            $entityManager->flush();
        }
        catch (\Exception $e)
        {
            $error = [ 
                "mensaje" => "Se produjo un error al intentar crear el producto: ".$e->getMessage(),
            ];
            return new JsonResponse(['error' => $error], 500);
        }

        return new JsonResponse(['mensaje' => 'Producto guardado!'], Response::HTTP_CREATED);
    }

    /**
     * @Route("/producto/{id}", name="get_one_producto", methods={"GET"})
     */
    public function get($id): JsonResponse
    {
        if(empty($id))
        {
            $error = [ 
                "mensaje" => "Debe ingresar el id del producto a buscar",
            ];
            return new JsonResponse(['error' => $error], 400);
        }
        else
        {
            $producto = $this->getDoctrine()->getRepository(Producto::class)->findOneBy(["id" => $id]);
            if(!$producto)
            {
                $error = [ 
                    "mensaje" => "No se encontró un producto con id ".$id,
                ];
                return new JsonResponse(['error' => $error], 400);
            }
            else
            {
                $data = [
                    'id' => $producto->getId(),
                    'nombre' => $producto->getNombre(),
                    'precio' => $producto->getPrecio(),
                    'descripcion' => $producto->getDescripcion(),
                ];
            }
            return new JsonResponse($data, Response::HTTP_OK);
        }
    }

    /**
     * @Route("/productos", name="get_all_productos", methods={"GET"})
     */
    public function getAll(): JsonResponse
    {
        $productos = $this->getDoctrine()->getRepository(Producto::class)->findAll();
        $data = [];
        foreach ($productos as $producto) 
        {
            $data[] = [
                'id' => $producto->getId(),
                'nombre' => $producto->getNombre(),
                'precio' => $producto->getPrecio(),
                'descripcion' => $producto->getDescripcion(),
            ];
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route("/producto/{id}", name="update_producto", methods={"POST"})
     */
    public function update($id, Request $request): JsonResponse
    {
        
        $nombre = $request->get('nombre');
        $precio = $request->get('precio');
        $descripcion = $request->get('descripcion');
        
        if(empty($nombre) || empty($precio))
        {
            $error = [ 
                "mensaje" => "El nombre y el precio son obligatorios!",
            ];
            return new JsonResponse(['error' => $error], 400);
        }

        try
        {
            $producto = $this->getDoctrine()->getRepository(Producto::class)->findOneBy(['id' => $id]);
            $producto->setNombre($nombre);
            $producto->setPrecio($precio);
            $producto->setDescripcion($descripcion);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($producto);
            $entityManager->flush();
        }
        catch (\Exception $e)
        {
            $error = [ 
                "mensaje" => "Se produjo un error al intentar actualizar el producto: ".$e->getMessage(),
            ];
            return new JsonResponse(['error' => $error], 500);
        }

		return new JsonResponse(['mensaje' => 'Producto actualizado!'], Response::HTTP_OK);
    }

    /**
     * @Route("/producto/{id}", name="delete_producto", methods={"DELETE"})
     */
    public function delete($id): JsonResponse
    {
        $producto = $this->getDoctrine()->getRepository(Producto::class)->findOneBy(['id' => $id]);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($producto);
        $entityManager->flush();
        return new JsonResponse(['mensaje' => 'El producto fue eliminado con éxito.'], Response::HTTP_OK);
    }
}

?>