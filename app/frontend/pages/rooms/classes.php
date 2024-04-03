<?php 
$classes = Classes::getAllClassesByUserId($user->data()->user_id);
?>

<div class="container">
    <h1>Your classes</h1>

    <div class="row">
        <?php foreach ($classes as $class) : ?>
            <div class="col-md-4">
                <div class="card bg-body-tertiary mb-3" style="width: 18rem;">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $class->name; ?></h5>
                        <a href="class.php?class_id=<?php echo $class->class_id; ?>" class="stretched-link"></a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>