<style>
    .schedule-box {
        height: 500px;
        box-sizing: border-box;
        /* Added */
    }

    .time-table {
        width: 100%;
        table-layout: fixed;
        box-sizing: border-box;
        /* Added */
    }

    .timeline {
        width: 10%;
        box-sizing: border-box;
        /* Added */
    }

    .timeline p {
        writing-mode: vertical-rl;
        text-align: center;
        transform: rotate(180deg);
        border-bottom: 1px solid #e9ecef;
        height: 10%;
        box-sizing: border-box;
        /* Added */
    }

    .day {
        width: 22%;
        position: relative;
        box-sizing: border-box;
        /* Added */
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
        /* Added */
    }

    .subject p {
        margin-bottom: 0.1em;
        box-sizing: border-box;
        /* Added */
    }

    .subject-note-available {
        background-color: #A1D6A1;
        color: #ffffff;
    }

    .subject h4 {
        font-size: 1.2em;
        box-sizing: border-box;
        /* Added */
    }

    .scrollable-table {
        height: 500px;
        overflow: auto;
        box-sizing: border-box;
        /* Added */
    }

    a.no-decoration {
        text-decoration: none;
    }
</style>

<div class="container my-5">
    <div class="row align-items-center rounded-3 border shadow-lg bg-body-tertiary schedule-box">
        <div class="col-sm-9 schedule">
            <div class="scrollable-table">
                <table class="time-table">
                    <?php
                    $weeknumber = $_SESSION['weeknumber'] ?? Calender::getTodaysWeekNumber();
                    $year = 2024;
                    $dates = Calender::getAllDatesOfTheWeek($weeknumber, $year);
                    ?>

                    <tbody>
                        <tr class="weekdays">
                            <th>Uge <?php echo $weeknumber ?></th>
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
                                        $noteAvailable = Calender::getTimeModuleNotes($subject->time_module_id);
                                        $noteText = '';
                                        if (is_array($noteAvailable) && isset($noteAvailable[0]->text)) {
                                            $noteText = $noteAvailable[0]->text;
                                        }
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
                                            $classes = Calender::getParticipatingClasses($subject->time_module_id);
                                            $classNames = array();
                                            if ($classes !== null) {
                                                foreach ($classes as $class) {
                                                    $classNames[] = Classes::getClassById($class->class_id)->name;
                                                }
                                            }
                                            ?>

                                            <p><?php echo !empty($classNames) ? implode(', ', $classNames) : 'Ikke angivet'; ?>
                                            </p>
                                        </div>

                                        <?php
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

            subjects.forEach(function (subject) {
                var timeText = subject.querySelector('p').textContent;
                var times = timeText.split(' - ');
                var startTime = times[0];
                var endTime = times[1];

                var startDate = new Date("1970-01-01T" + startTime + ":00");
                var endDate = new Date("1970-01-01T" + endTime + ":00");

                var duration = (endDate - startDate) / 60000; // Duration in minutes
                var startMinutes = (startDate.getHours() * 60) + startDate.getMinutes() - 480; // Minutes from 08:00

                subject.style.height = (duration / scheduleMinutes * timelineHeight) + 'px';
                subject.style.top = (startMinutes / scheduleMinutes * timelineHeight) + 'px';
            });
        };
    </script>