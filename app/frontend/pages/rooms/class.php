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
$class_id = $_GET['class_id'];
$students = Classes::getAllStudents($class_id);
$teachers = Classes::getAllTeachers($class_id);
?>

<div class="container">
    <h2>Teachers</h2>
    <div class="row">
        <?php foreach ($teachers as $teacher) : ?>
            <div class="col-md-4 section-row">
                <div class="card bg-body-tertiary mb-3 section">
                    <div class="row no-gutters">
                        <div class="col-md-8">
                            <div class="text-field">
                                <h5 class="card-title"><?php echo $teacher->first_name . ' ' . $teacher->middle_name . ' ' . $teacher->last_name; ?></h5>
                            </div>
                        </div>
                        <?php if ($teacher->profile_picture) : ?>
                            <div class="col-md-4">
                                <img src="image.php?id=<?php echo $teacher->profile_picture; ?>" class="card-img room-image" alt="">
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <h2>Students</h2>
    <div class="row">
        <?php foreach ($students as $student) : ?>
            <div class="col-md-4 section-row">
                <div class="card bg-body-tertiary mb-3 section">
                    <div class="row no-gutters">
                        <div class="col-md-8">
                            <div class="text-field">
                                <h5 class="card-title"><?php echo $student->first_name . ' ' . $student->middle_name . ' ' . $student->last_name; ?></h5>
                            </div>
                        </div>
                        <?php if ($student->profile_picture) : ?>
                            <div class="col-md-4">
                                <img src="image.php?id=<?php echo $student->profile_picture; ?>" class="card-img room-image" alt="">
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>