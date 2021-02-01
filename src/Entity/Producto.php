<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Producto
 *
 * @ORM\Table(name="producto")
 * @ORM\Entity
 */
class Producto
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=255, nullable=false)
     */
    private $nombre;

    /**
     * @var int
     *
     * @ORM\Column(name="precio", type="integer", nullable=false)
     */
    private $precio;

    /**
     * @var string
     *
     * @ORM\Column(name="descripcion", type="string", length=255, nullable=false)
     */
    private $descripcion;

    public function __construct($nombre, $precio, $descripcion)
    {
        $this->nombre = $nombre;
        $this->precio = $precio;
        $this->descripcion = $descripcion;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function getPrecio(): ?int
    {
        return $this->precio;
    }

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setNombre($nombre): ?self
    {
        $this->nombre = $nombre;
        return $this;
    }

    public function setPrecio($precio): ?self
    {
        $this->precio = $precio;
        return $this;
    }

    public function setDescripcion($descripcion): ?self
    {
        $this->descripcion = $descripcion;
        return $this;
    }
}
