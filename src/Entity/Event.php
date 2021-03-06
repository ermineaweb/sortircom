<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EventRepository")
 */
class Event
{
	/**
	 * @ORM\Id()
	 * @ORM\GeneratedValue()
	 * @ORM\Column(type="integer")
	 */
	private $id;
	
	/**
	 * @Assert\NotBlank(message="Vous devez renseigner un nom de sortie")
	 * @Assert\Length(
	 *      max="50",
	 *      maxMessage="Un nom de sortie de plus de 50 caractères ?? Utilisez le champ description pour renseigner les détails"
	 * )
	 * @ORM\Column(type="string", length=255)
	 */
	private $name;
	
	/**
	 * @Assert\NotBlank(message="Vous devez renseigner une date de début de la sortie")
	 * @ORM\Column(type="datetime")
	 */
	private $start;
	
	/**
	 * @Assert\NotBlank(message="Vous devez renseigner une date de fin de la sortie")
	 * @ORM\Column(type="datetime")
	 */
	private $end;
	
	/**
	 * @Assert\NotBlank(message="Vous devez renseigner une date limite d'inscription")
	 * @ORM\Column(type="date")
	 */
	private $limitdate;
	
	/**
	 * @Assert\NotBlank(message="Vous devez renseigner un nombre maximum de participants")
	 * @ORM\Column(type="integer", nullable=true)
	 */
	private $maxsize;
	
	/**
	 * @ORM\Column(type="text", nullable=true)
	 */
	private $description;
	
	/**
	 * @ORM\ManyToMany(targetEntity="App\Entity\User", mappedBy="events")
	 */
	private $users;
	
	/**
	 * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="eventsCreated")
	 * @ORM\JoinColumn(nullable=false)
	 */
	private $creator;
	
	/**
	 * @ORM\Column(type="integer", nullable=false)
	 */
	private $status;
	
	/**
	 * @ORM\ManyToOne(targetEntity="App\Entity\Place", inversedBy="events")
	 */
	private $place;
	
	/**
	 * @ORM\Column(type="text", nullable=true)
	 */
	private $cancel;
	
	public function __construct()
	{
		$this->users = new ArrayCollection();
		$this->start = new \DateTime();
		$this->end = new \DateTime();
		$this->limitdate = new \DateTime();
	}
	
	/*
	 * La durée est un attribut calculée non stocké en BDD
	 */
	public function getDuration(): String
	{
		return $this->start->diff($this->end, true)->format("%a Jour(s)");
	}
	
	/*
	 * Le nombre de personne inscrite à l'event
	 */
	public function getActualSize(): int
	{
		return $this->users->count();
	}
	
	/*
	 * retourne le temps restant pour s'inscrire
	 */
	public function getTimeRemaining(): array
	{
		$now = new \DateTime();
		$time = $now->diff($this->limitdate)->format("%R%a jour(s)");
		$style = "primary";
		if ($time == 0) {
			$time = "Aujourd'hui dernier jour";
			$style = "info";
		} elseif ($time < 0) {
			$time = "Date dépassée";
			$style = "danger";
		}
		return [
			"time" => $time,
			"style" => $style,
		];
	}
	
	public function getId(): ?int
	{
		return $this->id;
	}
	
	public function getName(): ?string
	{
		return $this->name;
	}
	
	public function setName(string $name): self
	{
		$this->name = $name;
		
		return $this;
	}
	
	public function getStart(): ?\DateTimeInterface
	{
		return $this->start;
	}
	
	public function setStart(\DateTimeInterface $start): self
	{
		$this->start = $start;
		
		return $this;
	}
	
	public function getEnd(): ?\DateTimeInterface
	{
		return $this->end;
	}
	
	public function setEnd(\DateTimeInterface $end): self
	{
		$this->end = $end;
		
		return $this;
	}
	
	public function getLimitdate(): ?\DateTimeInterface
	{
		return $this->limitdate;
	}
	
	public function setLimitdate(\DateTimeInterface $limitdate): self
	{
		$this->limitdate = $limitdate;
		
		return $this;
	}
	
	public function getMaxsize(): ?int
	{
		return $this->maxsize;
	}
	
	public function setMaxsize(?int $maxsize): self
	{
		$this->maxsize = $maxsize;
		
		return $this;
	}
	
	public function getDescription(): ?string
	{
		return $this->description;
	}
	
	public function setDescription(?string $description): self
	{
		$this->description = $description;
		
		return $this;
	}
	
	/**
	 * @return Collection|User[]
	 */
	public function getUsers(): Collection
	{
		return $this->users;
	}
	
	public function addUser(User $user): self
	{
		if (!$this->users->contains($user)) {
			$this->users[] = $user;
			$user->addEvent($this);
		}
		
		return $this;
	}
	
	public function removeUser(User $user): self
	{
		if ($this->users->contains($user)) {
			$this->users->removeElement($user);
			$user->removeEvent($this);
		}
		
		return $this;
	}
	
	public function getCreator(): ?User
	{
		return $this->creator;
	}
	
	public function setCreator(?User $creator): self
	{
		$this->creator = $creator;
		
		return $this;
	}
	
	public function getStatus(): ?int
	{
		return $this->status;
	}
	
	public function setStatus(?int $status): self
	{
		$this->status = $status;
		
		return $this;
	}
	
	public function getPlace(): ?Place
	{
		return $this->place;
	}
	
	public function setPlace(?Place $place): self
	{
		$this->place = $place;
		
		return $this;
	}
	
	public function getCancel(): ?string
	{
		return $this->cancel;
	}
	
	public function setCancel(?string $cancel): self
	{
		$this->cancel = $cancel;
		
		return $this;
	}
}
