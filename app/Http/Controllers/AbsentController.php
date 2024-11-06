<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Absent;
use App\Models\Personnel;
use App\Models\Activite;
use App\Models\Planning;
use Carbon\Carbon;

class AbsentController extends Controller
{
    //pointer absent
    public function pointer_absent(Request $request){
        $message = "";
        try {

            //validation des donne du champs
            $validatedData = $request->validate([
                'id_personnel' => 'required',
                'date_absent' => 'required',
            ]);

            //extraction des donnes dans un variable
            $id_personnel = $validatedData['id_personnel'];
            $date_absent = $validatedData['date_absent'];
            $date_absent_carbon = Carbon::parse($date_absent);//transformer la date en type carbon
            $jour = $date_absent_carbon->locale('fr')->dayName;//capter le nom du jour de la date d'absent
            $planning = Planning::where('id_personnel', $id_personnel)->first();//chercher le planning du personnel en question
            if($planning){ // si le planning existe
               $colonne =$jour."_deb"; //formant le nom du jour
               $type_planning = $planning->$colonne; //selecter le date de debut du planning dans le jour en question
               if($type_planning != "off"){ // si different de off

                    try {
                        //ajout dans la base de donner
                        Absent::create([
                            "id_personnel"=>$id_personnel,
                            "date_absent"=>$date_absent,
                            "jour"=>$jour,
                        ]);

                        //algorithme pour augmenter le nombre d'absence
                        $personnel_en_question = Personnel::find($id_personnel);//chercher le personnel qui va etre pointer
                        $nb_absent_a_jour = $personnel_en_question->nb_absent + 1;
                        $personnel_en_question->nb_absent = $nb_absent_a_jour;
                        $personnel_en_question->save();

                        //creation d'activité correspondant
                        $titre = "Pointage Absent";
                        $description = "Le personnel ".$personnel_en_question->identifiant." est absent" ;
                        Activite::create([
                        "titre_activite"=>$titre,
                        "description"=>$description,
                        "status"=>"Absent",
                        ]);

                        $message="C'est fait avec succès !";
                    } catch (QueryException $e) {
                        $message = "Une erreur est survenue";
                    }


               }else{ // si le personnel est off sur le jour en question
                $message = "Ce personnel est OFF le ".$jour;
               }

            }else{ // si le planning n'existe pas
                $message ="planning n'existe pas";
            }




        }catch (\Exception $e) { //si les champs a des probleme : comme vide
            $message = "Validation des champs incorrect !";
        }
        return response()->json([
          "message"=>$message
        ]);
    }


    //modification pointage absent
    public function modifier_pointage_absent(Request $request){
        $message = "";
        try {

            //validation des donnes
            $validatedData = $request->validate([
                'id_pointage' => 'required',
                'id_personnel' => 'required',
                'date_absent' => 'required',
            ]);
            //Extraction des donne dans une variable
            $id_pointage = $validatedData['id_pointage'];
            $id_personnel = $validatedData['id_personnel'];
            $date_absent = $validatedData['date_absent'];
            $date_absent_carbon = Carbon::parse($date_absent);//transformer la date en type carbon
            $jour = $date_absent_carbon->locale('fr')->dayName;//capter le nom du jour de la date d'absent

            $planning = Planning::where('id_personnel', $id_personnel)->first();//chercher le planning du personnel en question
            if($planning){
               $colonne =$jour."_deb"; //formant le nom du jour
               $type_planning = $planning->$colonne; //selecter le date de debut du planning dans le jour en question
               if($type_planning != "off"){ // si different de off

                  $absent_en_question = Absent::find($id_pointage);//chercher l'absence en question
                  if($absent_en_question){//if absence existe
                     try {
                         $absent_en_question->update([
                            "date_absent"=>$date_absent,
                            "jour"=>$jour,
                         ]);
                         $message = "Modification avec succès !";
                      } catch (QueryException $e) {
                        $message = "Une erreur est survenue";
                      }
                  }else{
                    $message = "Absence n'existe pas !";
                  }


               }else{ // si le personnel est off sur le jour en question
                $message = "Ce personnel est OFF le ".$jour;
               }

            }else{ // si le planning n'existe pas
                $message ="planning n'existe pas";
            }


        } catch (\Exception $e) {
            $message = "Validation des champs incorrect !";
        }
        return response()->json([
            "message"=>$message
        ]);
    }

    public function suprimer_absent($id){

        $message = "";
        $existe_absent = Absent::find($id);
        if($existe_absent){
            $existe_absent->delete();

            //algorithme pour augmenter le nombre d'absence
            $personnel_en_question = Personnel::find($existe_absent->id_personnel);//chercher le personnel qui va etre pointer
            $nb_absent_a_jour = $personnel_en_question->nb_absent - 1;
            $personnel_en_question->nb_absent = $nb_absent_a_jour;
            $personnel_en_question->save();

            $message = "Supression avec succès";
        }else{
            $message = "Absent n'existe pas !";
        }
        return response()->json([
            "message"=>$message
        ]);
    }


    public function select_absent_simplement(){
        $donnes = Absent::with('personnel')->orderBy('id', 'desc')->get();
        return response()->json([
             "donnes"=>$donnes
         ]);

    }

}
