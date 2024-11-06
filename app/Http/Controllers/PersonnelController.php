<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Personnel;
use App\Models\Planning;
use App\Models\Activite;

class PersonnelController extends Controller
{


    //ajout personnel
   public function ajout_personnel(Request $request){

       $message = "";
    // validation des donne pour un personnel
    try {
        $validatedData = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'date_naissance' => 'required',
            'sexe' => 'required|string',
        ]);

         // extraction des donne apres validation
        $nom = $validatedData['nom'];
        $prenom = $validatedData['prenom'];
        $date_naissance = $validatedData['date_naissance'];
        $sexe = $validatedData['sexe'];

        // creation d'un avatar à partir de nom et prenom
        $avatar = strtoupper(substr($prenom, 0, 1) . substr($nom, 0, 1));

            try {
                //les nb_absent et nb_retard sont zero par defaut
                //l'identifiant aussi creer automatiquement par la fonction  parent::boot creating
                $personnel = Personnel::create([
                    "avatar"=>$avatar,
                    "nom"=>$nom,
                    "prenom"=>$prenom,
                    "date_naissance"=>$date_naissance,
                    "sexe"=>$sexe,
                ]);

                $titre = "Concernant Personnel";
                $description = "Nouveau personnel ajouté :".$avatar ;
                Activite::create([
                    "titre_activite"=>$titre,
                    "description"=>$description,
                    "status"=>"Personnel",
                ]);

                $message = "Le personnel a été enregistré avec succès";


            } catch (QueryException $e) {
                if($e->errorInfo[1] === 1062){  //probleme sur l'unicité
                $message = "Identifiant deja existe";
                }else{
                    $message = "Une erreur est survenue lors d'ajout de personnel";
                }
            }

    } catch (\Exception $e) {
        $message = "Validation des champs incorrect !";
    }


    return response()->json([
       "message"=>$message,
    ]);
   }

   //modification d'un personnel
   public function modifie_personnel(Request $request){
      $message = "";
      try {

        //Validation des donnes
        $validatedData = $request->validate([
            'id_personnel' => 'required',
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'date_naissance' => 'required',
            'sexe' => 'required|string',
        ]);

         // extraction des donne apres validation
         $id_personnel = $validatedData['id_personnel'];
         $nom = $validatedData['nom'];
         $prenom = $validatedData['prenom'];
         $date_naissance = $validatedData['date_naissance'];
         $sexe = $validatedData['sexe'];

         // creation d'un avatar à partir de nom et prenom
         $avatar = strtoupper(substr($prenom, 0, 1) . substr($nom, 0, 1));
         $existe_personnel = Personnel::find($id_personnel);
         if($existe_personnel){
            try {
              $existe_personnel->nom = $nom;
              $existe_personnel->prenom = $prenom;
              $existe_personnel->date_naissance = $date_naissance;
              $existe_personnel->sexe = $sexe;
              $existe_personnel->save();

              $titre = "Concernant Personnel";
                $description = "Informations modifiées sur:".$avatar ;
                Activite::create([
                    "titre_activite"=>$titre,
                    "description"=>$description,
                    "status"=>"Personnel",
                ]);


              $message = "Modification réussie avec succès!";
            } catch (QueryException $e) {
               $message = "une erreur se produit lors de la modification";
            }

         }else{
            $message = "personnel n'existe pas !";
         }

    } catch (\Exception $e) {
        $message = "Validation des champs incorrect !";
    }

    return response()->json([
        "message"=>$message
     ]);

   }


   //supression d'un personnel
   public function suprimer_personnel($id){
     $message = "";
     $existe_personnel = Personnel::find($id);
     if($existe_personnel){
        $existe_personnel->delete();
        $message = "Suppression du ".$existe_personnel->avatar." réussie";
        $message = "Personnel n'esixte pas !";
     }

     return response()->json([
        "message"=>$message
     ]);


   }


   public function select_personnel_simplement(){
     $donnes = Personnel::orderBy('id', 'asc')->get();
     return response()->json([
        "donnes"=>$donnes
     ]);
   }

   public function select_personnel_pas_planning(){

    $personnels = Personnel::whereNotIn('id', function ($query)  {
        $query->select('id_personnel')->from('plannings');
    })->orderBy('id', 'asc')->get();

    return response()->json([
        "donnes"=>$personnels
     ]);

   }

   public function select_sanction(){
    $donnes = Personnel::orderBy('nb_absent', 'desc')
                        ->orderBy('nb_retard', 'desc')
                        ->limit(5)
                         ->get();
    return response()->json([
       "donnes"=>$donnes
    ]);
  }

  public function statistique_personnel(){
    $masculin_total = Personnel::where('sexe', 'masculin')->count();
    $feminin_total = Personnel::where('sexe', 'feminin')->count();

    $pourcentage_masculin = ($masculin_total / ($masculin_total + $feminin_total)) *100 ;
    $pourcentage_feminin = ($feminin_total / ($masculin_total + $feminin_total)) *100 ;


    $avatar_masculin = Personnel::select('*')
                                ->where('sexe', 'masculin')
                                ->orderBy('id', 'asc')
                                ->limit(6)->get();
    $avatar_feminin= Personnel::select('*')
                                ->where('sexe', 'feminin')
                                ->orderBy('id', 'asc')
                                ->limit(4)->get();

    return response()->json([
       "masculin_total"=>$masculin_total,
       "feminin_total"=>$feminin_total,
       "avatar_masculin"=>$avatar_masculin,
       "avatar_feminin"=>$avatar_feminin,
       "pourcentage_masculin"=>$pourcentage_masculin,
       "pourcentage_feminin"=>$pourcentage_feminin,
    ]);
  }




}
