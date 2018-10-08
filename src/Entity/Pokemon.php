<?php
/**
 * Created by PhpStorm.
 * User: Haku
 * Date: 2018. 10. 08.
 * Time: 17:16
 */

namespace App\Entity;


class Pokemon
{
    /***
     * @var strin
     */
    private $name;

    /**
     * @var string
     */
    private $type;

    /**
     * @var double
     */
    private $weight;

    /**
     * @var string
     */
    private $abilitytype;

    /**
     * @var int
     */
    private $atck;

    /**
     * @var int
     */
    private $def;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return float
     */
    public function getWeight(): float
    {
        return $this->weight;
    }

    /**
     * @param float $weight
     */
    public function setWeight(float $weight): void
    {
        $this->weight = $weight;
    }

    /**
     * @param string $type
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getAbilitytype(): string
    {
        return $this->abilitytype;
    }

    /**
     * @param string $abilitytype
     */
    public function setAbilitytype(string $abilitytype): void
    {
        $this->abilitytype = $abilitytype;
    }

    /**
     * @return int
     */
    public function getAtck(): int
    {
        return $this->atck;
    }

    /**
     * @param int $atck
     */
    public function setAtck(int $atck): void
    {
        $this->atck = $atck;
    }

    /**
     * @return int
     */
    public function getDef(): int
    {
        return $this->def;
    }

    /**
     * @param int $def
     */
    public function setDef(int $def): void
    {
        $this->def = $def;
    }



}