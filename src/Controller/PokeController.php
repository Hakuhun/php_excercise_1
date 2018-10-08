<?php
/**
 * Created by PhpStorm.
 * User: Haku
 * Date: 2018. 10. 08.
 * Time: 17:47
 */

namespace App\Controller;


use App\Entity\Pokemon;
use phpDocumentor\Reflection\Types\Array_;
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
            $unionofpokes = $this->getPokemonUnion($this->pokemons, $randompokemons);
            $filtered = $this->FilteringOfDuplicates($this->pokemons);
            $section = $this->SectionOfArrays($this->pokemons, $randompokemons)["pokes"];
            //var_dump($randompokemons);
            $returnaray=array(
                "pokemons" => $this->pokemons,
                "attackpower"=>$powersum,
                "numberoftypes" =>$countoftypes,
                "istheretype" => $istheretype,
                "numberinrow" => $number,
                "bestattack" => $bestattack,
                "randoms" => $randompokemons,
                "union"=>$unionofpokes,
                "filtered"=>$filtered,
                "section"=>$section
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
        $pokearray[] = $this->pokemons[0];
        return $pokearray;
    }

    /**
     * ÖSSZEGZÉS
     * @return int
     */
    private function getSummedAttackValues():int{
        $sum = 0;
        foreach ($this->pokemons as $pokemon){
            $sum+=$pokemon->getAtck();
        }
        return $sum;
    }

    private function getCountOfTypes($type) : int{
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

    /**
     *Union of 2 arrays
     * @param $array1
     * @param $array2
     * @return array
     */
    function getPokemonUnion($array1, $array2) : array {
        $n1 = sizeof($array1); $n2 = sizeof($array2);

        for($i = 0; $i<$n1; $i++){
            $returnarray[] = $array1[$i];
        }
        $db = $n1;
        for($j = 0; $j<$n2; $j++){
            $i++;
            while (($i<$n1) && ($array1[$i] != $array2[$i])){
                $i++;
            }
            if($i > $n1){
                $db++;
                $returnarray[] = $array2[$j];
            }
        }
        return $returnarray;
    }

    /**
     * ISMÉTLŐDÉSEK KISZŰRÉSE (NIK-es diákból)
     * @param $array1
     * @return array
     */
    private function FilteringOfDuplicates($array1) : int {
        $db = 0;
        for($i = 1; $i<sizeof($array1); $i++){
            $j =0;
            while ($j <= $db && ($array1[$i] != $array1[$j])){
                $j++;
            }
            if($j > $db){
                $db++;
                $array1[$db] = $array1[$i];
            }
        }return $db;
    }

    /**
     * METSZET (nIK-es diából)
     * @param $array1
     * @param $array2
     * @return array
     */
    private function SectionOfArrays($array1, $array2) : array{
        $db = 0;
        $retarray = array("pokes");
        $n1 = sizeof($array1);
        $n2 = sizeof($array2);

        for ($i = 0; $i<$n1; $i++){
            $j =0;
            while (($j<$n2) && $array1[$i] != $array2[$j]){
                $j++;
            }
            if ($j < $n2){
                //$db++;
                $retarray["pokes"][] = $array1[$i];
            }
        }
        //var_dump($retarray);
        return $retarray;

    }
}