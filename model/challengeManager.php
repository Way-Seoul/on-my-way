<?php

require_once './models/db.php';

class ChallengeManger extends Db {
    // public function deletePlace($place_id)
    // {
    //     $this->delete('places', $place_id);
    // }
    public function addChallenge($data)
    {
        $db = $this->connectDB();
        $newChallenge = $db->prepare('INSERT INTO challenges (name, conditions, place_id, score, users_accomplished, created_date)
        VALUES (:name, :conditions, :place_id, :score, :users_accomplished, :created_date)');
        $newChallenge->execute([
            'name' => $data['name'],
            'conditions' => $data['conditions'],
            'place_id' => $data['place_id'],
            'score' => $data['score'],
            'users_accomplished' => 0,
            'created_date' => date("Y-m-d H:i:s")
        ]);
    }

    // public function editPlace($place_id, $formData)
    // {
    //     $db = Manager::connectDB();
    //     $edit_places = $db->prepare(
    //         "UPDATE places SET " . "
    //         name = ?,
    //         map_provider = ?,
    //         map_link = ?,
    //         memo = ?,
    //         rating = ?
    //         " . "WHERE id = ?"
    //     );
    //     $edit_places->execute([
    //         $formData['name'],
    //         $formData['map_provider'],
    //         $formData['map_link'],
    //         $formData['memo'],
    //         $formData['rating'],
    //         $place_id
    //     ]);
    // }
}

