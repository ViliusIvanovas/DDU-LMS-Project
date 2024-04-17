<style>
    .schedule-box {
        height: 60vh;
        box-sizing: border-box;
    }

    .time-table {
        width: 100%;
        box-sizing: border-box;
    }

    .timeline {
        background-image: linear-gradient(to bottom, #5FB6D9 1px, transparent 1px);
        background-size: 100% 8vh;
        background-repeat: repeat-y;
    }

    .timeline p {
        display: flex;
        align-items: center;
        height: 8vh;
        box-sizing: border-box;
        margin: 0;
    }

    .day {
        position: relative;
        box-sizing: border-box;
        background-image: linear-gradient(to bottom, #5FB6D9 1px, transparent 1px);
        background-size: 100% 8vh;
        background-repeat: repeat-y;
    }

    .subject {
        position: absolute;
        border: 1px solid #e9ecef;
        border-radius: 5px;
        padding: 2px;
        margin-bottom: 10px;
        width: 100%;
        font-size: 0.8em;
        top: 0.5em;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        box-sizing: border-box;
    }

    .subject p {
        margin-bottom: 0.1em;
        box-sizing: border-box;
    }

    .subject-note-available {
        background-color: #A1D6A1;
        color: #ffffff;
    }

    .subject h4 {
        font-size: 1.2em;
        box-sizing: border-box;
    }

    .scrollable-table {
        height: 59vh;
        overflow: auto;
        box-sizing: border-box;
    }

    a.no-decoration {
        text-decoration: none;
    }

    td.day {
        position: relative;
        /* Other styles... */
    }

    .dropdown {
        position: absolute;
        top: 0;
        right: 0;
        z-index: 2;
    }
</style>

<div class="container my-5">
    <?php

    if ($user->data()->access_level == 3) {
        ?>
        <div class="row">
            <div class="col-md-12">
                <a href="build-schedule.php" class="btn btn-primary">Rediger/opstil skema</a>
            </div>
        </div>
        <br>
        <?php
    }

    ?>
    <div class="row align-items-center rounded-3 border shadow-lg bg-body-tertiary schedule-box">
        <div class="col-sm-9 schedule">
            <div class="scrollable-table">
                <table class="time-table">
                    <?php
                    $weeknumber = $_SESSION['weeknumber'];

                    if (isset($_GET['weeknumber'])) {
                        $weeknumber = $_GET['weeknumber'];
                    } else {
                        $weeknumber = Calender::getTodaysWeekNumber();
                    }

                    $year = 2024;
                    $dates = Calender::getAllDatesOfTheWeek($weeknumber, $year);
                    ?>

                    <tbody>
                        <tr class="weekdays">
                            <th>Uge <?php echo $weeknumber ?> (<a
                                    href="schedule.php?weeknumber=<?php echo $weeknumber - 1; ?>">-</a>/<a
                                    href="schedule.php?weeknumber=<?php echo $weeknumber + 1; ?>">+</a>)

                            </th>
                            <th>Mandag (<?php echo date('d-m', strtotime($dates[0])); ?>)</th>
                            <th>Tirsdag (<?php echo date('d-m', strtotime($dates[1])); ?>)</th>
                            <th>Onsdag (<?php echo date('d-m', strtotime($dates[2])); ?>)</th>
                            <th>Torsdag (<?php echo date('d-m', strtotime($dates[3])); ?>)</th>
                            <th>Fredag (<?php echo date('d-m', strtotime($dates[4])); ?>)</th>
                        </tr>

                        <tr>
                            <td class="timeline">
                                <p>08:00</p>
                                <p>09:00</p>
                                <p>10:00</p>
                                <p>11:00</p>
                                <p>12:00</p>
                                <p>13:00</p>
                                <p>14:00</p>
                                <p>15:00</p>
                                <p>16:00</p>
                                <p>17:00</p>
                            </td>

                            <?php
                            for ($i = 0; $i < 5; $i++) {
                                ?>

                                <td class="day">
                                    <?php
                                    $subjects = Calender::getAllSubjectsForPersonForADay($dates[$i], $user->data()->user_id);

                                    if (is_object($subjects) && get_class($subjects) == 'Database' && $subjects->count()) {
                                        $subjects = $subjects->results();
                                    }

                                    // sort
                                    usort($subjects, function ($a, $b) {
                                        return strtotime($a->start_time) - strtotime($b->start_time);
                                    });

                                    foreach ($subjects as $subject) {
                                        $day = date('Y-m-d', strtotime($subject->start_time));
                                        $class_id = Classes::getClassById($class->class_id)->class_id;
                                        $noteAvailable = Calender::getTimeModuleNotes($subject->time_module_id);
                                        $noteText = '';
                                        if (is_array($noteAvailable) && isset($noteAvailable[0]->text)) {
                                            $noteText = $noteAvailable[0]->text;
                                        }

                                        // Add a dropdown menu for each subject
                                        echo "<div class='dropdown'>";
                                        echo "<select id='subject_" . $subject->time_module_id . "' class='subject_dropdown' name='subject_" . $subject->time_module_id . "' style='width: 40px;' onchange='handleSelectChange(this)' data-day='" . $day . "' data-class-id='" . $class_id . "'>";
                                        echo "<option value='' style='display: none;'>'''</option>";
                                        echo "<option value='start'>Start fraværsårsag herfra</option>";
                                        echo "<option value='statistics'>Fraværsstatistik</option>";

                                        // If the current user is a teacher, add a "Register Absence" option
                                        if ($user->data()->access_level == '2') {
                                            echo "<option value='absence'>Registrer fravær</option>";
                                        }

                                        echo "</select>";
                                        echo "</div>";
                                        ?>

                                        <div class="subject <?php echo $noteText ? 'subject-note-available' : 'bg-body'; ?>"
                                            title="<?php echo htmlspecialchars($noteText); ?>">
                                            <p><?php echo date('H:i', strtotime($subject->start_time)) . ' - ' . date('H:i', strtotime($subject->end_time)); ?>
                                            </p>
                                            <h4><?php echo $subject->name; ?></h4>

                                            <?php
                                            $teachers = Calender::getTeachersIdsByTimeModuleId($subject->time_module_id);
                                            $teacherNames = array();
                                            foreach ($teachers as $teacher) {
                                                $teacherNames[] = User::getFullName($teacher->teacher);
                                            }
                                            ?>

                                            <p><?php echo !empty($teacherNames) ? implode(', ', $teacherNames) : 'Ikke angivet'; ?>
                                            </p>

                                            <?php
                                            $locations = Calender::getTimeModuleLocation($subject->time_module_id);
                                            $locationNames = array();
                                            foreach ($locations as $location) {
                                                $locationNames[] = $location->name;
                                            }
                                            ?>

                                            <p><?php echo !empty($locationNames) ? implode(', ', $locationNames) : 'Ikke angivet'; ?>
                                            </p>

                                            <?php
                                            /*
                                            $classes = Calender::getParticipatingClasses($subject->time_module_id);
                                            $classNames = array();
                                            if ($classes !== null) {
                                                foreach ($classes as $class) {
                                                    $classNames[] = Classes::getClassById($class->class_id)->name;
                                                }
                                            }
                                            */
                                            ?>

                                            <p><?php echo !empty($classNames) ? implode(', ', $classNames) : 'Ikke angivet'; ?>
                                            </p>
                                        </div>

                                        <?php
                                        echo "</div>";
                                    }
                                    ?>
                                </td>

                                <?php
                            }
                            ?>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-sm-3 bg-body deadlines" style="height: 100%;">
            <h3>Afleveringer</h3>

            <?php

            $assignments = Classes::getAllAssignmentsByStudent($user->data()->user_id);

            foreach ($assignments as $assignment) {
                $post = Classes::getPostLinkedToAssignment($assignment->assignment_id);
                $class = Classes::getClassBySectionId($post->section_id);
                $section = Rooms::getSectionById($post->section_id);
                $room = Rooms::getRoomById($section->room_id);

                $deadline = date('d-m-Y', strtotime($assignment->due_date));
                $deadlinePassed = strtotime($assignment->due_date) < time();
                $secondsRemaining = strtotime($assignment->due_date) - time();

                if ($deadlinePassed) {
                    $secondsMissed = -$secondsRemaining;
                    if ($secondsMissed > 24 * 60 * 60) {
                        $timeRemaining = 'Missed by: ' . floor($secondsMissed / (24 * 60 * 60)) . ' days';
                    } else {
                        $timeRemaining = 'Missed by: ' . gmdate('H:i:s', $secondsMissed);
                    }
                } else if ($secondsRemaining > 24 * 60 * 60) {
                    $timeRemaining = 'Time remaining: ' . floor($secondsRemaining / (24 * 60 * 60)) . ' days';
                } else {
                    $timeRemaining = 'Time remaining: ' . gmdate('H:i:s', $secondsRemaining);
                }

                ?>
                <a href="assignment.php?assignment_id=<?php echo $assignment->assignment_id ?>" class="no-decoration">
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="card-title <?php echo $deadlinePassed ? 'text-danger' : ''; ?>">
                                <?php echo $assignment->name; ?>
                            </h5>
                            <p class="card-text">
                                <small class="<?php echo $deadlinePassed ? 'text-danger' : 'text-muted'; ?>">
                                    <?php echo $timeRemaining; ?>
                                </small>
                            </p>
                            <p class="card-text">
                                <small class="text-muted">
                                    <?php echo $class->name . ': ' . $room->name; ?>
                                </small>
                            </p>
                        </div>
                    </div>
                </a>

                <?php
            }
            ?>
        </div>
    </div>

    <br>
    <br>

    <script>
        window.onload = function () {
            var subjects = document.querySelectorAll('.subject');
            var timelineHeight = document.querySelector('.timeline').offsetHeight;
            var scheduleMinutes = 540; // Total minutes from 08:00 to 17:00
            var vhPerHour = 8; // 8vh is equivalent to 1 hour
            var vhPerMinute = vhPerHour / 60; // Calculate the vh equivalent of 1 minute

            subjects.forEach(function (subject) {
                var timeText = subject.querySelector('p').textContent;
                var times = timeText.split(' - ');
                var startTime = times[0];
                var endTime = times[1];

                var startDate = new Date("1970-01-01T" + startTime + ":00");
                var endDate = new Date("1970-01-01T" + endTime + ":00");

                var startMinutes = (startDate.getHours() * 60) + startDate.getMinutes() - 480; // Minutes from 08:00

                subject.style.height = vhPerHour + 'vh'; // Set height to 8vh as all subjects are 1 hour long
                subject.style.top = (startMinutes * vhPerMinute) + 'vh'; // Convert startMinutes to vh
            });
        };

        function handleSelectChange(dropdown) {
            // Get the selected option
            var selectedOption = dropdown.options[dropdown.selectedIndex].value;

            // Check if the selected option is "Registrer fravær"
            if (selectedOption === 'absence') {
                // Get the day and class_id
                var day = dropdown.getAttribute('data-day');
                var class_id = dropdown.getAttribute('data-class-id');

                // Send the data
                sendData(selectedOption, day, class_id);
            }
        }

        function sendData(option, day, class_id) {
            // Create a FormData object
            var formData = new FormData();
            formData.append('option', option);
            formData.append('day', day);
            formData.append('class_id', class_id);

            // Send the data to check_absence.php
            fetch('check_abscence.php', {
                method: 'POST',
                body: formData
            })
                .then(response => {
                    if (response.ok) {
                        // If the request was successful, redirect to check_absence.php
                        window.location.href = 'check_abscence.php';
                    } else {
                        console.error('Error:', response.statusText);
                    }
                })
                .catch(error => console.error('Error:', error));
        }
    </script>