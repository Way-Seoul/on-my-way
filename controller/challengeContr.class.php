<?php
include_once '_paths.php';
include_once './model/challengeManager.php';
include_once './model/placeManager.php';
include_once './model/commentManager.php';
require_once 'model/usersManager.php';

class ChallengeContr {
    public static function createChallenge(){
        if(!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) header('location: ' . ROOT);

        $challenges = new ChallengeManager();
        $places = new PlaceManager();
        $action = 'create-challenge';
        // $btnText = 'Add A New Challenge';
        $btnText = 'Save';
        $btnName = 'add-challenge';

        //IF POST ARR IS SET - THEN FORM HAS BEEN SUBMITTED
        $data = $_POST ?? null;
        if(isset($_POST['add-challenge']) && $data) {
            $cleanData = $challenges->validateData($data);
            if($cleanData) {
                $challenges->addChallenge($cleanData);
                $formMsg = 'New Challenge Added';
            } else {
                $formMsg = 'Form Validation Failed!';
            }
        }

        //Pull list of existing places from DB for user to select from
        $existingPlaces = $places->retrievePlaces();

        require_once 'view/challenge-form.php';
    }

    public static function listChallenges(){
        if(!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) header('location: ' . ROOT);

        $search_param = isset($_GET['search']) ? $_GET['search']:'';

        $c_manager = new ChallengeManager();
        $p_manager = new PlaceManager();
        $places = $c_manager->getPlaces();
        $places_byId = $c_manager->newPlaces();
        $json_places = json_encode($places_byId);

        // if(isset($_POST['delete']) && $_POST['delete']!= '') {
        //     $c_manager->deleteChallenge($_POST['delete']);
        // } 
        require_once 'view/listChallengesView.php';
    }

    public static function editChallenges(){
        if(!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) header('location: ' . ROOT);


        $c_manager = new ChallengeManager();
        $p_manager = new PlaceManager();

        // if(isset($_POST['edit'])) {
        // $challengeId = $_POST['edit'];
        $challengeId = $_GET['id'];

        //POPULATE EDIT FORM WITH EXISTING DATA FOR THAT CHALLENGE
        $challengeData = $c_manager->getChallengeData($challengeId);
        $existingPlaces = $p_manager->retrievePlaces();
        $action = 'edit-challenge&id='. $challengeId;
        $btnName = 'edit-challenge';
        // $btnText = 'Edit Challenge';
        $btnText = 'Done';
        $name = $challengeData['name'];
        $content = $challengeData['content'];
        $conditions = $challengeData['conditions'];
        $score = $challengeData['score'];
        $edit_place_id = $challengeData['place_id'];
        $backBtn = "<a href='" . ADMIN_PATH . "'>← Go Back</a>";
    
        //Update existing Challenges
        if(isset($_POST['edit-challenge'])) {
            $cleanData = $c_manager->validateData($_POST);
            if($cleanData) {
                $c_manager->updateChallenge($cleanData);
                $formMsg = 'Record Updated!';
            } else {
                $formMsg = 'Form Validation Failed!';
            }
        }

        //4 Populate the existing form with data for that place
        require_once 'view/challenge-form.php';
    }

    public static function showChallengeInfo(){
        if(!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) header('location: ' . ROOT);

        $userManager = new Users();
        $c_manager = new ChallengeManager();
        $comment_manager = new CommentManager();

        $userID = $_SESSION['user_id'];
        $challID = $_GET['id'] ?? '';

        //IF GET REQ WAS MADE FOR A SPECIFIC CHALL, THEN POPULATE PAGE WITH RELEVANT CHALLENGE INFO..
        if($challID) {
            $challenge = $c_manager->getChallengeData($challID);
            $place = $c_manager->getPlace($challenge['place_id']);
            $userCompleteChall = $c_manager->hasUserCompletedChall($userID, $challID);
        }

        if(isset($_POST['add_comment'])) {
            if(isset($_POST['comment_content'])) {
                $newComment = $comment_manager->addComment($challID, $userID, $_POST['comment_content']);
            }
        }

        if(isset($_POST['delete'])) {
            $commentId = $_POST['comment_id'];
            //IF SET CALL DELETE FUNCTION.......
            if(isset($commentId)) {
                $comment_manager->deleteComment($commentId);
            }
        }

        //REQ SENT TO THIS ROUTE FROM CLIENT WHEN USER CLICKS "ON THE SPOT BTN" & VALIDATION PASSED....
        if(isset($_POST['challengeAchieved'])) {
            //Update User Points Total, then increment users_accomplished for challenge, then add record to user_chal Table
            try {
                $c_manager->incrementUsersAccomplished($_POST['challID']);
                $userManager->addRecordToUserChallTable($_POST['userID'], $_POST['challID']);
                $userPointsTotal = Users::getUserTotalPoints($_SESSION['user_id']);

                $_SESSION['total_points'] = $userPointsTotal['points_total'];
                die(
                    json_encode(
                        [
                            'msg' => 'Well Done You Completed The Challenge! Your new points total is ' . $userPointsTotal['points_total'] . '. Now try another challenge!'
                            , 'totalPoints' => $_SESSION['total_points']
                        ]
                    )
                );
            }
            catch(Exception $e) {
                die(
                    json_encode(
                        [
                        'msg' => 'Sorry there was an issue updating the DB: ' .$e->getMessage()
                        ]
                    )
                );
            }
        }
        if($challID) {
            $comments = $comment_manager->getAllCommentsForChallenge($challID);
            require_once 'view/challenge-specific-view.php';
        }
    }
}