<?php
include_once './model/adminManager.php';
include_once './model/challengeManager.php';
include_once './model/placeManager.php';


class AdminContr {
    public static function admin(){
        
        //Delete user
        //prepare data
        $manager = new Admin();
        
        if (isset($_POST['delete'])) {
            // delete user
            $id = $_POST['id'];
            $manager->deleteUser($id);
        }elseif (isset($_POST['add'])) {
            // add user
            $manager->addUsers($_POST);
        }
        
        $users = $manager->listUsers();
        
        //Reset password
        if(isset($_POST['reset'])){
            $id = $_POST['id'];
            $reset_password = '0000';
            $manager->resetUserPassword($reset_password, $id);
        }

        // ********************************************* list challenges
        $c_manager = new ChallengeManager();
        $p_manager = new PlaceManager();
        $challenges = $c_manager->getChallDataForAdmin();
        
        if(isset($_POST['delete-chll']) && $_POST['delete-chll']!= '') {
            $c_manager->deleteChallenge($_POST['delete-chll']);
        } 

        // ********************************************* list locations
        $places = $c_manager->getPlacesForAdmin();
        // $challenges = $c_manager->getChallenges($places[$i]["id"]);
        if(isset($_POST['delete-Place']) && $_POST['delete-Place']!='') {
            $delete_msg = $c_manager->deletePlace($_POST['delete-Place']);
            if($delete_msg == 1) {
                header('Location:' . ADMIN_PATH);
            }
        }

        include './view/adminView.php';
    }

    public static function adminEdit(){
        //prepare data
        $admin_edit_manager = new Admin();

        $id = $_GET['id'];

        if (isset($_POST['edit'])) {
            //show the pre-filled answer
            $edited_username = $_POST['username'];
            $edited_email = $_POST['email']; 
            $edited_firstname = $_POST['first_name']; 
            $edited_lastname = $_POST['last_name']; 
            $edited_admin = $_POST['admin']; 
            $admin_edit_manager -> editEntry($id, $edited_username, $edited_email, $edited_firstname, $edited_lastname, $edited_admin);
        }

        $user = $admin_edit_manager->showEntry($id);

        include './view/adminEditView.php';
    }
}