<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PlaceRepository")
 */
class Place implements \JsonSerializable
{
	/**
	 * @ORM\Id()
	 * @ORM\GeneratedValue()
	 * @ORM\Column(type="integer")
	 */
	private $id;
	
	/**
	 * @ORM\Column(type="string", length=255)
	 */
	private $name;
	
	/**
	 * @ORM\Column(type="string", length=255)
	 */
	private $address;
	
	/**
	 * @ORM\Column(type="float", nullable=true)
	 */
	private $longitude;
	
	/**
	 * @ORM\Column(type="float", nullable=true)
	 */
	private $latitude;
	
	/**
	 * @ORM\OneToMany(targetEntity="App\Entity\Event", mappedBy="place")
	 */
	private $events;
	
	/**
	 * @ORM\ManyToOne(targetEntity="App\Entity\City", inversedBy="places")
	 * @ORM\JoinColumn(nullable=false)
	 */
	private $city;
	
	public function __construct()
	{
		$this->events = new ArrayCollection();
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
	
	public function getAddress(): ?string
	{
		return $this->address;
	}
	
	public function setAddress(string $address): self
	{
		$this->address = $address;
		
		return $this;
	}
	
	public function getLongitude(): ?float
	{
		return $this->longitude;
	}
	
	public function setLongitude(?float $longitude): self
	{
		$this->longitude = $longitude;
		
		return $this;
	}
	
	public function getLatitude(): ?float
	{
		return $this->latitude;
	}
	
	public function setLatitude(?float $latitude): self
	{
		$this->latitude = $latitude;
		
		return $this;
	}
	
	/**
	 * @return Collection|Event[]
	 */
	public function getEvents(): Collection
	{
		return $this->events;
	}
	
	public function addEvent(Event $event): self
	{
		if (!$this->events->contains($event)) {
			$this->events[] = $event;
			$event->setPlace($this);
		}
		
		return $this;
	}
	
	public function removeEvent(Event $event): self
	{
		if ($this->events->contains($event)) {
			$this->events->removeElement($event);
			// set the owning side to null (unless already changed)
			if ($event->getPlace() === $this) {
				$event->setPlace(null);
			}
		}
		
		return $this;
	}
	
	public function getCity(): ?City
	{
		return $this->city;
	}
	
	public function setCity(?City $city): self
	{
		$this->city = $city;
		
		return $this;
	}
	
	public function __toString(): string
	{
		return $this->name;
	}
	
	/**
	 * Specify data which should be serialized to JSON
	 * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
	 * @return mixed data which can be serialized by <b>json_encode</b>,
	 * which is a value of any type other than a resource.
	 * @since 5.4.0
	 */
	public function jsonSerialize()
	{
		return [
			"id" => $this->id,
			"name" => $this->name,
			"address" => $this->address,
			"latitude" => $this->latitude,
			"longitude" => $this->longitude,
			"city" => $this->city->getId(),
		];
	}
}
