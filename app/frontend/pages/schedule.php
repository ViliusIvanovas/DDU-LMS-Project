<style>
    .schedule-box {
        height: 500px;
    }

    .time-table {
        width: 100%;
        table-layout: fixed;
    }

    .timeline {
        width: 10%;
    }

    .timeline p {
        writing-mode: vertical-rl;
        text-align: center;
        transform: rotate(180deg);
        border-bottom: 1px solid #e9ecef;
        height: 10%;
    }

    .day {
        width: 22%;
        position: relative;
    }

    .subject {
        position: absolute;
        border: 1px solid #e9ecef;
        border-radius: 5px;
        padding: 2px;
        /* Reduced padding */
        margin-bottom: 10px;
        width: 100%;
        font-size: 0.8em;
        top: 0.5em;
        overflow: hidden;
        /* Added */
        text-overflow: ellipsis;
        /* Added */
        white-space: nowrap;
        /* Added */
    }

    .subject p {
        margin-bottom: 0.1em;
        /* Reduced margin */
    }

    .subject-note-available {
        background-color: #A1D6A1;
        color: #ffffff;
    }

    .subject h4 {
        font-size: 1.2em;
    }

    .scrollable-table {
        height: 500px;
        overflow: auto;
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
                                    usort($subjects, function($a, $b) {
                                        return strtotime($a->start_time) - strtotime($b->start_time);
                                    });

                                    foreach ($subjects as $subject) {
                                        $noteAvailable = Calender::getTimeModuleNotes($subject->time_module_id);
                                    ?>

                                        <div class="subject <?php echo $noteAvailable ? 'subject-note-available' : 'bg-body'; ?>">
                                            <p><?php echo date('H:i', strtotime($subject->start_time)) . ' - ' . date('H:i', strtotime($subject->end_time)); ?></p>
                                            <h4><?php echo $subject->name; ?></h4>

                                            <?php
                                            $teachers = Calender::getTeachersIdsByTimeModuleId($subject->time_module_id);
                                            $teacherNames = array();
                                            foreach ($teachers as $teacher) {
                                                $teacherNames[] = User::getFullName($teacher->teacher);
                                            }
                                            ?>

                                            <p><?php echo !empty($teacherNames) ? implode(', ', $teacherNames) : 'Ikke angivet'; ?></p>

                                            <?php
                                            $locations = Calender::getTimeModuleLocation($subject->time_module_id);
                                            $locationNames = array();
                                            foreach ($locations as $location) {
                                                $locationNames[] = $location->name;
                                            }
                                            ?>

                                            <p><?php echo !empty($locationNames) ? implode(', ', $locationNames) : 'Ikke angivet'; ?></p>

                                            <?php
                                            $classes = Calender::getParticipatingClasses($subject->time_module_id);
                                            $classNames = array();
                                            if ($classes !== null) {
                                                foreach ($classes as $class) {
                                                    $classNames[] = Classes::getClassById($class->class_id)->name;
                                                }
                                            }
                                            ?>

                                            <p><?php echo !empty($classNames) ? implode(', ', $classNames) : 'Ikke angivet'; ?></p>
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
            <h3>afleveringer</h3>
            Testy test
        </div>
    </div>
</div>

<script>
    window.onload = function() {
        var subjects = document.querySelectorAll('.subject');
        subjects.forEach(function(subject) {
            var timeText = subject.querySelector('p').textContent;
            var times = timeText.split(' - ');
            var startTime = times[0];
            var endTime = times[1];

            var startDate = new Date("1970-01-01T" + startTime + ":00");
            var endDate = new Date("1970-01-01T" + endTime + ":00");

            var duration = (endDate - startDate) / 60000;
            var startMinutes = (startDate.getHours() * 60) + startDate.getMinutes() - 480;

            var containerHeight = document.querySelector('.timeline').offsetHeight;
            var scheduleMinutes = 540;
            subject.style.height = (duration / scheduleMinutes * containerHeight) + 'px';
            subject.style.top = (startMinutes / scheduleMinutes * containerHeight) + 'px';
        });
    };
</script>