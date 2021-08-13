<?php
// src/Usuario.php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="mensajes")
 */
class Mensaje
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @var int
     */
    protected $idMensaje;

     /**
     * @ORM\Column(type="string")
     * @var string
     */
    protected $texto;

     /**
    * @ORM\ManyToOne(targetEntity="Clan", inversedBy="mensajes")
    * @ORM\JoinColumn(name="idclan", referencedColumnName="idclan")
    */
    protected $clan0;

    /**
     * @ORM\ManyToOne(targetEntity="Usuario", inversedBy="mensajes")
     * @ORM\JoinColumn(name="id_usuario", referencedColumnName="id")
     */
    protected $usuario0;

    public function getIdChat()
    {
        return $this->idChat;
    }

    public function getTexto()
    {
        return $this->texto;
    }

    public function setTexto($texto)
    {
        $this->texto = $texto;
    }

   

// Relacion de clan
    
    public function setClan(Clan $clan)
    {
        $this->clan0 = $clan;
	$clan->registrarMensaje($this);
    }

    public function getClan()
    {
        return $this->clan0;
    }

// Relacion con Usuarios

    public function setUsuario(Usuario $usuario){
	$this->usuario0 = $usuario;
	$usuario->addMensaje($this);
    }
    public function getUsuario(){
	return $this->usuario0;
    }

}
