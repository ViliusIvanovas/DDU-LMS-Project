<style>
    .room-image {
        max-height: 100%;
        object-fit: contain;
    }

    .card-title {
        display: flex;
        align-items: center;
        justify-content: flex-start;
        font-size: 1.5em;
        padding-left: 10px;
    }

    .section-row {
        margin-top: 20px;
        width: 100%;
    }

    .section {
        display: flex;
        justify-content: space-between;
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
        height: 200px;
        object-fit: cover;
    }

    a {
        color: inherit;
        text-decoration: none;
    }

    a:hover {
        color: inherit;
        text-decoration: underline;
    }

    .image-container {
        height: 300px;
        width: 100%;
    }

    .download-icon {
        position: absolute;
        top: 10px;
        right: 10px;
    }
</style>

<?php
$section_id = $_GET['section_id'];
$posts = Posts::getAllPostsBySectionId($section_id);
?>

<div class="container">
    <h2>Posts</h2>

    <a href="setup-post.php?section_id=<?php echo $section_id; ?>" class="btn btn-primary">Add Post</a>

    <div class="row">
        <?php foreach ($posts as $post) : ?>
            <?php
            $type = Posts::getPostType($post->post_id);
            $specific_post = Posts::getPostByPostId($post->post_id)
            ?>

            <?php
            if ($type->name == 'Note') {
                require_once 'parsedown-1.7.4/Parsedown.php';
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
                if (Files::isFileImage($specific_post->file_id)) {
            ?>
                    <div class="col-md-4 section-row">
                        <div class="card bg-body-tertiary mb-3 section">
                            <div class="row no-gutters">
                                <div class="col-md-12">
                                    <div class="text-field">
                                        <!-- Add an icon to represent that this is an image -->
                                        <h5 class="card-title">
                                            <i class="bi bi-image-fill"></i> <!-- Bootstrap image icon -->
                                            <?php echo $specific_post->name; ?>
                                        </h5>
                                        <div class="image-container d-flex align-items-start justify-content-center text-center">
                                            <img src="image.php?id=<?php echo $specific_post->file_id; ?>" class="room-image">
                                        </div>
                                        <!-- Add a download icon in the top right -->
                                        <div class="download-icon">
                                            <a href="download.php?id=<?php echo $specific_post->file_id; ?>">
                                                <i class="bi bi-download"></i> <!-- Bootstrap download icon -->
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php
                }
                if (Files::fileHasPDF($specific_post->file_id)) {
                ?>
                    <div class="col-md-2 section-row">
                        <div class="card bg-body-tertiary mb-3 section">
                            <div class="row no-gutters">
                                <div class="col-md-12">
                                    <div class="text-field">
                                        <h5 class="card-title">
                                            <i class="bi bi-file-earmark-pdf-fill"></i> <!-- Bootstrap PDF icon -->
                                            <?php echo $specific_post->name; ?>
                                        </h5>
                                        <!-- Add a button to download the PDF and trigger the toggle action -->
                                        <div class="pdf-download-button">
                                            <a id="toggleButton">
                                                <i class="bi bi-arrow-right-circle"></i> <!-- Bootstrap arrow icon -->
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php
                }
                ?>
            <?php }
            ?>
        <?php endforeach; ?>
    </div>
</div>