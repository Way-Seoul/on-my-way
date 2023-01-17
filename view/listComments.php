<section class="comments">
    <h3>Comments:</h3>
    <?php
    $path = $challenge_path . '&id=' . $id;
    ?>
    <form action="<?=$path?>" method="post">
        <textarea required name="comment_content" id="comment_content" cols="80" rows="5" placeholder="Add a comment....."></textarea>
        <br>
        <button name="add_comment">Add a comment</button>
    </form>
    <?php
        if(isset($comments) && count($comments) > 0) {
            for($i=0; $i<count($comments); $i++) {
    ?>
                <div class="comment" style="border: 1px solid red; margin: 5px;">
                    <h4>
                        <?=
                        $comments[$i]['first_name'] . ' ' . $comments[$i]['last_name'] 
                        .' said on: '
                        . $comments[$i]['date_added']
                        ?>
                    </h4>
                    <p class="comment-content">
                        <?=$comments[$i]['content']?>
                    </p>
                </div>
    <?php
            }
        } else {
            echo "No Comments available for this challenge yet";
        }
    ?>
</section>