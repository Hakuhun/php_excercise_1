<?php
/**
 * Created by PhpStorm.
 * User: Haku
 * Date: 2018. 10. 08.
 * Time: 17:47
 */

namespace App\Controller;


use App\Entity\Pokemon;
use phpDocumentor\Reflection\Types\Boolean;
use phpDocumentor\Reflection\Types\Integer;
use PhpParser\Node\Expr\Isset_;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class PokeController
 * @package App\Controller
 * @Route(path="/pokemon")
 */
class PokeController extends Controller
{
    /**
     * @var string
     */
    private $filename ="../templates/poke/pokedata.txt";

    /**
     * @var array
     */
    private $pokemons;

    /**
     * @Route(path="/", name="pokeIndex")
     * @param Request $request
     * @return Response
     */
    public function pokeIndexAction(Request $request) : Response{
        $this->pokemons = $this->getPokemonsFromFile();
        if (isset($this->pokemons)){
            $powersum = $this->getSummedAttackValues();
            $countoftypes = $this->getCountOfTypes("normal");
            $istheretype = $this->isThereTypeOfPokemon("fire");
            $number = $this->getNumberOfPokemonByName("pikachu");
            $bestattack = $this->getBestAttackPowerPokemon();
            $randompokemons = $this->randomizePokemons();
            //var_dump($randompokemons);
            $returnaray=array(
                "pokemons" => $this->pokemons,
                "attackpower"=>$powersum,
                "numberoftypes" =>$countoftypes,
                "istheretype" => $istheretype,
                "numberinrow" => $number,
                "bestattack" => $bestattack,
                "randoms" => $randompokemons
            );
            //return $this->render('poke/pokelist.html.twig',array("pokemons" => $this->pokemons, "attackpower"=>$powersum));
            return $this->render('poke/pokelist.html.twig',$returnaray);
        }
        die("HUPSZ");
    }

    private function getPokemonsFromFile() : array {
        if (file_exists($this->filename)){
            $rows = file($this->filename, FILE_IGNORE_NEW_LINES);
            foreach ($rows as $row){
                $entity = explode("@", $row);
                $poke = new Pokemon();
                $poke->setName($entity[0]);
                $poke->setType($entity[1]);
                $poke->setAbilitytype($entity[2]);
                $poke->setWeight(doubleval($entity[3]));
                $poke ->setAtck(intval($entity[4]));
                $poke->setDef(intval($entity[5]));
                $returnarray[] = $poke;
            }
        }
        return $returnarray;
    }

    private function randomizePokemons(): array{
        $types = array("fire", "fairy", "normal", "grass", "psyho", "dark", "light");

        for ($i = 0; $i<20; $i++){
            $poke = new Pokemon();
            $poke->setName("PokeName #".$i);
            $poke->setType("ratata");
            $poke->setAbilityType($types[rand(0,sizeof($types)-1)]);
            $poke->setAtck(rand(0,100));
            $poke->setDef(rand(0,100));
            $poke->setWeight(rand(0,20));
            $pokearray[] = $poke;
        }
        return $pokearray;
    }

    private function getSummedAttackValues():int{
        $sum = 0;
        foreach ($this->pokemons as $pokemon){
            $sum+=$pokemon->getAtck();
        }
        return $sum;
    }

    private function getCountOfTypes($type){
        foreach ($this->pokemons as $pokemon){
            $db = 0;
            if($pokemon->getAbilityType() ==  $type){
                $db++;
            }
        }
        return $db;
    }

    private function isThereTypeOfPokemon($type) : bool {
        $i = 0; $n = sizeof($this->pokemons);
        while ($i < $n && $this->pokemons[$i]->getAbilityType() != $type){
            $i++;
        }
        if ($i<$n)
            return true;
        else
            return false;
    }

    /**
     *
     * @param $name
     * @return int
     */
    private function getNumberOfPokemonByName($name) : int {
        $i = 0; $n = sizeof($this->pokemons);
        while ($i<$n && $this->pokemons[$i]->getType() != $name ){
             $i++;
        }

        if($i<$n){
            return $i+1;
        }

        return $i;
    }

    /**
     * Maximumkiválasztás
     * @return Pokemon
     */
    private function getBestAttackPowerPokemon() : Pokemon{
        $max = 0; $index = 0;
        for($i = 0; $i < sizeof($this->pokemons); $i++){
            if ($max < $this->pokemons[$i]->getAtck()){
                $index = $i;
                $max = $this->pokemons[$i]->getAtck();
            }
        }
        return $this->pokemons[$index];
    }
}