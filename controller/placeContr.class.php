<?php

include_once '_paths.php';
require_once 'model/challengeManager.php';

class PlaceContr {
    public static function addPlace(){
        $c_manager = new ChallengeManager();
        $invalidInput = false; //TODO this should be edited to be delivered to other file

        if(isset($_POST['add-place']) && $_POST['add-place']) {
            $cleanData = $c_manager->validatePlace($_POST);
            if($cleanData) {
                $c_manager->addPlace($cleanData);
                header('Location: ' . LIST_CHALLENGES_PATH);
            } else {
                // echo "validation failed.";
                $prefill = array(
                    'name' => isset($_POST['name']) ? $_POST['name']:'',
                    'latitude' => isset($_POST['latitude']) ? $_POST['latitude']:'',
                    'longitude' => isset($_POST['longitude']) ? $_POST['longitude']:''
                );
                $invalidInput = true; //TODO here as well
            }
        }

        require_once 'view/add-place-view.php';
    }

    public static function listPlaces(){
        $c_manager = new ChallengeManager();

        $places = $c_manager->getPlaces();

        if(isset($_POST['delete-Place']) && $_POST['delete-Place']!='') {
            $delete_msg = $c_manager->deletePlace($_POST['delete-Place']);
            if($delete_msg == 1) {
                header('Location:' . ADMIN_PATH);
            }
        }

    }




}
