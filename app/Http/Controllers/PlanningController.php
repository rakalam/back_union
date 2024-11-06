<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Planning;
use App\Models\Personnel;
use App\Models\Activite;

class PlanningController extends Controller
{
     //ajout planning
   public function ajout_plannign(Request $request){

        $message = "";$lundi_deb="";$lundi_fin="";$mardi_deb="";
        $mardi_fin="";$mercredi_deb=""; $mercredi_fin=""; $jeudi_deb=""; $jeudi_fin="";
        $vendredi_deb="";$vendredi_fin="";$samedi_deb="";  $samedi_fin="";$dimanche_deb=""; $dimanche_fin="";
      //validation des donnes
      try {
        $validedData = $request->validate([
            'id_personnel' => 'required|unique:plannings,id_personnel',
            'type_lundi'=> 'required|string',
            'type_mardi'=> 'required|string',
            'type_mercredi'=> 'required|string',
            'type_jeudi'=> 'required|string',
            'type_vendredi'=> 'required|string',
            'type_samedi'=> 'required|string',
            'type_dimanche'=> 'required|string',
        ]);
        //Extraction des donnes
        $id_personnel = $validedData['id_personnel'];
        $type_lundi = $validedData['type_lundi'];
        $type_mardi = $validedData['type_mardi'];
        $type_mercredi = $validedData['type_mercredi'];
        $type_jeudi = $validedData['type_jeudi'];
        $type_vendredi = $validedData['type_vendredi'];
        $type_samedi = $validedData['type_samedi'];
        $type_dimanche = $validedData['type_dimanche'];

        //teste pour shift ou off
        if( $type_lundi == "off" ){
            $lundi_deb = $lundi_fin = "off";
        }else{
            $lundi_deb = $request->lundi_deb;
            $lundi_fin = $request->lundi_fin;
        }
        if( $type_mardi == "off" ){
            $mardi_deb = $mardi_fin = "off";
        }else{
            $mardi_deb = $request->mardi_deb ;
            $mardi_fin = $request->mardi_fin ;
        }
        if( $type_mercredi == "off" ){
            $mercredi_deb = $mercredi_fin = "off";
        }else{
            $mercredi_deb = $request->mercredi_deb ;
            $mercredi_fin = $request->mercredi_fin ;
        }
        if( $type_jeudi == "off" ){
            $jeudi_deb = $jeudi_fin = "off";
        }else{
            $jeudi_deb = $request->jeudi_deb ;
            $jeudi_fin = $request->jeudi_fin ;
        }
        if( $type_vendredi == "off" ){
            $vendredi_deb = $vendedi_fin = "off";
        }else{
            $vendredi_deb = $request->vendredi_deb ;
            $vendedi_fin = $request->vendredi_fin ;
        }
        if( $type_samedi == "off" ){
            $samedi_deb = $samedi_fin = "off";
        }else{
            $samedi_deb = $request->samedi_deb ;
            $samedi_fin = $request->samedi_fin ;
        }
        if( $type_dimanche == "off" ){
            $dimanche_deb = $dimanche_fin = "off";
        }else{
            $dimanche_deb = $request->dimanche_deb;
            $dimanche_fin = $request->dimanche_fin ;
        }
        $existe_personnel = Personnel::find($id_personnel);
        if($existe_personnel){
            try {
                Planning::create([
                'id_personnel' => $id_personnel,
                'lundi_deb'=> $lundi_deb,
                'lundi_fin'=> $lundi_fin,
                'mardi_deb'=> $mardi_deb,
                'mardi_fin'=> $mardi_fin,
                'mercredi_deb'=> $mercredi_deb,
                'mercredi_fin'=> $mercredi_fin,
                'jeudi_deb'=> $jeudi_deb,
                'jeudi_fin'=> $jeudi_fin,
                'vendredi_deb'=> $vendredi_deb,
                'vendedi_fin'=> $vendedi_fin,
                'samedi_deb'=> $samedi_deb,
                'samedi_fin'=> $samedi_fin,
                'dimanche_deb'=> $dimanche_deb,
                'dimanche_fin'=> $dimanche_fin,
                ]);

                $titre = "Concernant le Planning";
                $description = "Nouveau planning ajouté pour ".$existe_personnel->identifiant;
                Activite::create([
                   "titre_activite"=>$titre,
                   "description"=>$description,
                   "status"=>"Planning",
                ]);
                $message = "Planning de ".$existe_personnel->avatar." ajouté avec succès";
            } catch (QueryException $e) {
                 $message = "Une erreur est survenue lors de l'ajout de le ";
            }
        }else{
            $message="On ne peut pas stocker ce planning car le personnel n'existe pas !";
        }
      } catch (\Exception $e) {
        $message = $e->getMessage();
      }

      return response()->json([
           "message"=>$message
      ]);
   }


   //modification planning
   public function modifie_planning(Request $request){
     $message ="";
     $message = "";$lundi_deb="";$lundi_fin="";$mardi_deb="";
     $mardi_fin="";$mercredi_deb=""; $mercredi_fin=""; $jeudi_deb=""; $jeudi_fin="";
     $vendredi_deb="";$vendredi_fin="";$samedi_deb="";  $samedi_fin="";$dimanche_deb=""; $dimanche_fin="";

    //validation des donnes
     try {

        $validedData = $request->validate([
            'id_planning' => 'required',
            'id_personnel' => 'required',
            'type_lundi'=> 'required|string',
            'type_mardi'=> 'required|string',
            'type_mercredi'=> 'required|string',
            'type_jeudi'=> 'required|string',
            'type_vendredi'=> 'required|string',
            'type_samedi'=> 'required|string',
            'type_dimanche'=> 'required|string',
        ]);
         //Extraction des donnes
         $id_planning = $validedData['id_planning'];
         $id_personnel = $validedData['id_personnel'];
         $type_lundi = $validedData['type_lundi'];
         $type_mardi = $validedData['type_mardi'];
         $type_mercredi = $validedData['type_mercredi'];
         $type_jeudi = $validedData['type_jeudi'];
         $type_vendredi = $validedData['type_vendredi'];
         $type_samedi = $validedData['type_samedi'];
         $type_dimanche = $validedData['type_dimanche'];

         if( $type_lundi == "off" ){
            $lundi_deb = $lundi_fin = "off";
        }else{
            $lundi_deb = $request->lundi_deb;
            $lundi_fin = $request->lundi_fin;
        }
        if( $type_mardi == "off" ){
            $mardi_deb = $mardi_fin = "off";
        }else{
            $mardi_deb = $request->mardi_deb ;
            $mardi_fin = $request->mardi_fin ;
        }
        if( $type_mercredi == "off" ){
            $mercredi_deb = $mercredi_fin = "off";
        }else{
            $mercredi_deb = $request->mercredi_deb ;
            $mercredi_fin = $request->mercredi_fin ;
        }
        if( $type_jeudi == "off" ){
            $jeudi_deb = $jeudi_fin = "off";
        }else{
            $jeudi_deb = $request->jeudi_deb ;
            $jeudi_fin = $request->jeudi_fin ;
        }
        if( $type_vendredi == "off" ){
            $vendredi_deb = $vendedi_fin = "off";
        }else{
            $vendredi_deb = $request->vendredi_deb ;
            $vendedi_fin = $request->vendredi_fin ;
        }
        if( $type_samedi == "off" ){
            $samedi_deb = $samedi_fin = "off";
        }else{
            $samedi_deb = $request->samedi_deb ;
            $samedi_fin = $request->samedi_fin ;
        }
        if( $type_dimanche == "off" ){
            $dimanche_deb = $dimanche_fin = "off";
        }else{
            $dimanche_deb = $request->dimanche_deb;
            $dimanche_fin = $request->dimanche_fin ;
        }

        $existe_personnel = Personnel::find($id_personnel);
        $existe_planning = Planning::find($id_planning);
        if($existe_personnel && $existe_planning){
            try {
                $existe_planning->update([
                'id_personnel' => $id_personnel,
                'lundi_deb'=> $lundi_deb,
                'lundi_fin'=> $lundi_fin,
                'mardi_deb'=> $mardi_deb,
                'mardi_fin'=> $mardi_fin,
                'mercredi_deb'=> $mercredi_deb,
                'mercredi_fin'=> $mercredi_fin,
                'jeudi_deb'=> $jeudi_deb,
                'jeudi_fin'=> $jeudi_fin,
                'vendredi_deb'=> $vendredi_deb,
                'vendedi_fin'=> $vendedi_fin,
                'samedi_deb'=> $samedi_deb,
                'samedi_fin'=> $samedi_fin,
                'dimanche_deb'=> $dimanche_deb,
                'dimanche_fin'=> $dimanche_fin,
                ]);
                $titre = "Concernant le Planning";
                $description = "Modification planning pour ".$existe_personnel->identifiant;
                Activite::create([
                   "titre_activite"=>$titre,
                   "description"=>$description,
                   "status"=>"Planning",
                ]);
                $message = "Planning du ".$existe_personnel->avatar." modifié avec succès";

            } catch (QueryException $e) {
                 $message = "Une erreur est survenue lors de l'ajout de le ";
            }
        }else{
            $message="On ne peut pas stocker ce planning car le personnel n'existe pas !";
        }

     }  catch (\Exception $e) {
        $message = "Il y a un ou plusieurs champ(s) qui est vide";
     }
     return response()->json([
        "message"=>$message
   ]);
   }

   //supression planning
   public function suprimer_planning($id){
     $message="";
     $existe_planning = Planning::find($id);
     if($existe_planning){
        $personnel = Personnel::find($existe_planning->id_personnel);
        $existe_planning->delete();
        $titre = "Concernant le Planning";
        $description = "Suppression du planning pour ".$personnel->identifiant;
        Activite::create([
            "titre_activite"=>$titre,
            "description"=>$description,
            "status"=>"Planning",
        ]);
        $message="Le planning de ".$personnel->avatar." a bien été supprimé";


     }else{
        $message="Planning n'existe pas";
     }

     return response()->json([
        "message"=>$message
     ]);
   }

   //selection des personnels
   public function select_planning_simplement(){
      $plannings = Planning::with('personnel')->orderBy('id_personnel', 'asc')->get();

      return response()->json([
        "donnes"=>$plannings
   ]);

   }


}
