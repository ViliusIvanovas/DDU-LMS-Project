<?php
$room_id = $_GET['room_id'];
$room = Rooms::getRoomById($room_id);
$class = Classes::getClassById($room->class_id);
$sections = Rooms::getAllSectionsByRoomId($room_id);
?>

<div class="container">
    <h1><?php echo $room->name ?></h1>
    <h3><?php echo $class->name ?></h3>

    <div class="row">
        <?php foreach ($sections as $section) : ?>
            <div class="col-md-4">
                <div class="card bg-body-tertiary mb-3" style="width: 18rem;">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $section->name; ?></h5>
                        <a href="section.php?section_id=<?php echo $section->section_id; ?>" class="stretched-link"></a>
                        <?php if ($section->image_id) : ?>
                            <img src="image.php?id=<?php echo $section->image_id; ?>" class="card-img-top room-image" alt="">
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
