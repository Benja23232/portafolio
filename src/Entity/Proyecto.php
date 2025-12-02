<?php

namespace App\Entity;

use App\Repository\ProyectoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProyectoRepository::class)]
class Proyecto
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $titulo = null;

    #[ORM\Column(length: 255)]
    private ?string $descripcion = null;

    #[ORM\Column(length: 255)]
    private ?string $tecnologias = null;

    #[ORM\Column(length: 255)]
    private ?string $enlace = null;


    /**
     * @var Collection<int, Imagen>
     */
    #[ORM\OneToMany(targetEntity: Imagen::class, mappedBy: 'proyecto',  cascade: ['persist', 'remove'])]
    private Collection $imagens;

    public function __construct()
    {
        $this->imagens = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitulo(): ?string
    {
        return $this->titulo;
    }

    public function setTitulo(string $titulo): static
    {
        $this->titulo = $titulo;


        return $this;
    }

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(string $descripcion): static
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    public function getTecnologias(): ?string
    {
        return $this->tecnologias;
    }

    public function setTecnologias(string $tecnologias): static
    {
        $this->tecnologias = $tecnologias;

        return $this;
    }

    public function getEnlace(): ?string
    {
        return $this->enlace;
    }

    public function setEnlace(string $enlace): static
    {
        $this->enlace = $enlace;

        return $this;
    }


    /**
     * @return Collection<int, Imagen>
     */
    public function getImagens(): Collection
    {
        return $this->imagens;
    }

    public function addImagen(Imagen $imagen): static
    {
        if (!$this->imagens->contains($imagen)) {
            $this->imagens->add($imagen);
            $imagen->setProyecto($this);
        }

        return $this;
    }

    public function removeImagen(Imagen $imagen): static
    {
        if ($this->imagens->removeElement($imagen)) {
            // set the owning side to null (unless already changed)
            if ($imagen->getProyecto() === $this) {
                $imagen->setProyecto(null);
            }
        }

        return $this;
    }
}
