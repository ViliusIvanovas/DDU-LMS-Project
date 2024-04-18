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
require_once 'parsedown-1.7.4/Parsedown.php';

$section_id = $_GET['section_id'];
$posts = Posts::getAllPostsBySectionId($section_id);

$is_teacher = User::isUserTeacherForClass($user->data()->user_id, $class->class_id);
?>

<div class="container">
    <h2>Posts</h2>

    <form method="post" action="save_post_order.php">
        <input type="hidden" name="section_id" value="<?php echo $section_id; ?>">
        <button type="submit" class="btn btn-primary">Save Changes</button>

        <a href="setup-post.php?section_id=<?php echo $section_id; ?>" class="btn btn-primary">Add Post</a>

        <div class="row">
            <?php foreach ($posts as $post) : ?>
                <?php
                $type = Posts::getPostType($post->post_id);
                $specific_post = Posts::getPostByPostId($post->post_id)
                ?>

                <div class="d-flex">
                    <?php if ($is_teacher) : ?>
                        <div class="form-group">
                            <label for="post_order[<?php echo $post->post_id; ?>]">Sort Order</label>
                            <select name="post_order[<?php echo $post->post_id; ?>]" class="form-control">
                                <?php for ($i = 1; $i <= count($posts); $i++) : ?>
                                    <option value="<?php echo $i; ?>" <?php if ($post->sort == $i) echo 'selected'; ?>>
                                        <?php echo $i; ?>
                                    </option>
                                <?php endfor; ?>
                                <option value="-" <?php if ($post->sort == '-') echo 'selected'; ?>>Hidden</option>
                            </select>
                        </div>
                    <?php endif; ?>

                    <?php
                    // If the post is hidden and the user is not a teacher, skip this iteration
                    if ($post->sort == '-' && !$is_teacher) continue;
                    ?>

                    <?php if ($type->name == 'Note') : ?>
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
                    <?php endif; ?>

                    <?php if ($type->name == 'Fil') : ?>
                        <?php if (Files::isFileImage($specific_post->file_id)) : ?>
                            <div class="col-md-4 section-row">
                                <div class="card bg-body-tertiary mb-3 section">
                                    <div class="row no-gutters">
                                        <div class="col-md-12">
                                            <div class="text-field">
                                                <h5 class="card-title">
                                                    <i class="bi bi-image-fill"></i>
                                                    <?php echo $specific_post->name; ?>
                                                </h5>
                                                <div class="image-container d-flex align-items-start justify-content-center text-center">
                                                    <img src="image.php?id=<?php echo $specific_post->file_id; ?>" class="room-image">
                                                </div>
                                                <div class="download-icon">
                                                    <a href="download.php?id=<?php echo $specific_post->file_id; ?>">
                                                        <i class="bi bi-download"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php elseif (Files::fileHasPDF($specific_post->file_id)) : ?>
                            <div class="col-md-2 section-row">
                                <div class="card bg-body-tertiary mb-3 section">
                                    <div class="row no-gutters">
                                        <div class="col-md-12">
                                            <div class="text-field">
                                                <h5 class="card-title">
                                                    <i class="bi bi-file-earmark-pdf-fill"></i>
                                                    <?php echo $specific_post->name; ?>
                                                </h5>
                                                <div class="pdf-download-button">
                                                    <a id="toggleButton">
                                                        <i class="bi bi-arrow-right-circle"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php else : ?>
                            <div class="col-md-2 section-row">
                                <div class="card bg-body-tertiary mb-3 section">
                                    <div class="row no-gutters">
                                        <div class="col-md-12">
                                            <div class="text-field">
                                                <h5 class="card-title">
                                                    <i class="bi bi-file-earmark-text-fill"></i>
                                                    <?php echo $specific_post->name; ?>
                                                </h5>
                                                <div class="text-download-button">
                                                    <a href="download.php?id=<?php echo $specific_post->file_id; ?>">
                                                        <i class="bi bi-arrow-down-circle"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>

                    <?php if ($type->name == 'Grupper') : ?>
                        <div class="col-md-2 section-row">
                            <div class="card bg-body-tertiary mb-3 section">
                                <div class="row no-gutters">
                                    <div class="col-md-12">
                                        <div class="text-field">
                                            <h5 class="card-title">
                                                <?php echo $specific_post->name; ?>
                                            </h5>
                                            <?php if (!$is_teacher && Groups::getCurrentGroup($user->data()->user_id, $specific_post->group_room_id)) :
                                                $group_id = Groups::getCurrentGroup($user->data()->user_id, $specific_post->group_room_id);
                                                $group = Groups::getGroupById($group_id->group_id);
                                                $participants = Groups::getGroupsParticipants($group->group_id);
                                            ?>
                                                <div class='bg-body-tertiary p-3 my-3'>
                                                    <h2><?php echo $group->name; ?></h2>
                                                    <?php foreach ($participants as $participant) : ?>
                                                        <div>
                                                            <h5><?php if (isset($participant->student)) echo User::getFullName($participant->student); ?></h5>
                                                        </div>
                                                    <?php endforeach; ?>
                                                </div>
                                            <?php endif; ?>
                                            <a href="groups.php?group_room_id=<?php echo $specific_post->group_room_id; ?>&section_id=<?php echo $section_id; ?>">Rediger grupper</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if ($type->name == 'Aflevering') :
                        $assignment = Classes::getAssignmentById($specific_post->assignment_id);
                    ?>
                        <div class="col-md-2 section-row">
                            <div class="card bg-body-tertiary mb-3 section">
                                <div class="row no-gutters">
                                    <div class="col-md-12">
                                        <div class="text-field">
                                            <h5 class="card-title">
                                                <?php echo $specific_post->name; ?>
                                            </h5>
                                            <div>
                                                <?php
                                                $due_date = new DateTime($assignment->due_date);
                                                $now = new DateTime();
                                                $interval = $now->diff($due_date);
                                                $days = $interval->format('%a');
                                                if ($days > 0) {
                                                    echo "Aflevering om $days dage";
                                                } else {
                                                    echo "Aflevering i dag";
                                                }
                                                ?> <br>
                                                <a href="assignment.php?assignment_id=<?php echo $specific_post->assignment_id; ?>">Se aflevering</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </form>
</div>