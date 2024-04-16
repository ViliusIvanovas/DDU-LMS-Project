<?php
$rooms = Rooms::getAllRoomsByUserId($user->data()->user_id);
?>

<div class="container">
    <h1>Dine rum</h1>

    <div class="row">
        <?php foreach ($rooms as $room) : ?>
            <div class="col-md-4">
                <div class="card bg-body-tertiary mb-3" style="width: 18rem;">
                    <div class="card-body">
                        <h6>
                            <?php
                            // get the name of the class of this room
                            $class = Classes::getClassById($room->class_id);
                            echo $class->name;
                            ?>
                        </h6>
                        <h5 class="card-title"><?php echo $room->name; ?></h5>
                        <a href="room.php?room_id=<?php echo $room->room_id; ?>" class="stretched-link"></a>
                        <?php if ($room->banner) : ?>
                            <img src="image.php?id=<?php echo $room->banner; ?>" class="card-img-top room-image" alt="">
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>