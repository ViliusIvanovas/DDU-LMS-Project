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
        align-items: center;
        /* Vertically center the text */
        justify-content: flex-start;
        /* Align the text to the left */
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
$room_id = $_GET['room_id'];
$room = Rooms::getRoomById($room_id);
$class = Classes::getClassById($room->class_id);
$sections = Rooms::getAllSectionsByRoomId($room_id);
?>

<div class="container">
    <img src="image.php?id=<?php echo $room->banner; ?>" class="card-img-top banner-image" alt="">
    <br>
    <h1><?php echo $room->name ?></h1>
    <a href="class.php?class_id=<?php echo $class->class_id ?>">
        <h3><?php echo $class->name ?></h3>
    </a>

    <button> <a href="give_grade.php?room_id=<?php echo $room_id; ?>">Giv Karakter</a></button>

    <div class="row">
        <?php foreach ($sections as $section) : ?>
            <div class="col-md-4 section-row">
                <div class="card bg-body-tertiary mb-3 section">
                    <div class="row no-gutters">
                        <div class="col-md-8">
                            <div class="text-field">
                                <h5 class="card-title"><?php echo $section->name; ?></h5>
                                <a href="section.php?section_id=<?php echo $section->section_id; ?>" class="stretched-link"></a>
                            </div>
                        </div>
                        <?php if ($section->image_id) : ?>
                            <div class="col-md-4">
                                <img src="image.php?id=<?php echo $section->image_id; ?>" class="card-img room-image" alt="">
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>