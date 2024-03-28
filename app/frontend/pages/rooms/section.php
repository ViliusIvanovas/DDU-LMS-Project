<style>
    .room-image {
        width: 80px;
        height: 80px;
        object-fit: cover;
        float: right;
    }

    .card-title {
        display: flex;
        align-items: center;
        /* Vertically center the text */
        justify-content: flex-start;
        /* Align the text to the left */
        font-size: 1.5em;
        /* Increase the font size */
        padding-left: 10px;
        /* Add some space to the left of the text */
    }

    .section-row {
        margin-top: 20px;
        width: 100%;
    }

    .section {
        display: flex;
        justify-content: space-between;
        /* Align items on opposite ends */
    }

    .text-field {
        display: flex;
        align-items: center;
        justify-content: flex-start;
        height: 100%;
        width: 100%;
        padding-left: 15px;
    }

    .text-field {
        display: flex;
        align-items: flex-start;
        justify-content: flex-start;
        flex-direction: column;
        height: 100%;
        width: 100%;
        padding-left: 15px;
    }

    .banner-image {
        width: 100%;
        /* Make the image fill the whole width */
        height: 200px;
        /* Control the height of the image */
        object-fit: cover;
        /* Ensure the aspect ratio of the image is maintained */
    }

    a {
        color: inherit;
        /* Make the link color the same as the surrounding text */
        text-decoration: none;
        /* Remove the underline */
    }

    a:hover {
        color: inherit;
        /* Keep the link color the same when hovered */
        text-decoration: underline;
        /* Add an underline when hovered */
    }
</style>

<?php
$section_id = $_GET['section_id'];
$posts = Posts::getAllPostsBySectionId($section_id);
?>

<div class="container">
    <h2>Posts</h2>
    <div class="row">
        <?php foreach ($posts as $post): ?>
            <?php
            $type = Posts::getPostType($post->post_id);
            $specific_post = Posts::getPostByPostId($post->post_id)
                ?>

            <?php
            if ($type->name == 'Note') {
                require 'parsedown-1.7.4/Parsedown.php';
                ?>
                <div class="col-md-4 section-row">
                    <div class="card bg-body-tertiary mb-3 section">
                        <div class="row no-gutters">
                            <div class="col-md-8">
                                <div class="text-field">
                                    <h5 class="card-title">
                                        <?php echo $specific_post->title; ?>
                                    </h5>
                                    <div>
                                        <?php
                                        $Parsedown = new Parsedown();
                                        echo $Parsedown->text($specific_post->text);
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php }
            ?>

            <?php
            if ($type->name == 'Fil') {
                ?>
                <div class="col-md-4 section-row">
                    <div class="card bg-body-tertiary mb-3 section">
                        <div class="row no-gutters">
                            <div class="col-md-8">
                                <div class="text-field">
                                    <h5 class="card-title">
                                        <?php echo $specific_post->name; ?>
                                    </h5>
                                    <div>
                                        // set up code here for figuring out what file_type it is and how to display it
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php }
            ?>



        <?php endforeach; ?>
    </div>
</div>