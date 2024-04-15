<style>
    .subject {
        border: 1px solid #e9ecef;
        border-radius: 5px;
        padding: 10px;
        margin-bottom: 10px;
    }
</style>

<div class="container my-5">
    <div class="row align-items-center rounded-3 border shadow-lg bg-body-tertiary schedule-box">
        <div class="col-sm-9 p-4 schedule" style="height: 100%;">
            <table class="time-table">
                <?php
                $weeknumber = Calender::getTodaysWeekNumber();
                $year = 2024;
                ?>

                <tbody>
                    <tr class="weekdays">
                        <th>Uge</th>
                        <th>Mandag</th>
                        <th>Tirsdag</th>
                        <th>Onsdag</th>
                        <th>Torsdag</th>
                        <th>Fredag</th>
                    </tr>

                    <?php
                    $dates = Calender::getAllDatesOfTheWeek($weeknumber, $year);
                    ?>

                    <tr>
                        <td>
                            time
                        </td>

                        <?php
                        for ($i = 0; $i < 5; $i++) {
                            ?>

                            <td>
                                <?php
                                $subjects = Calender::getAllSubjectsForPersonForADay($dates[$i], $user->data()->user_id);

                                if (is_object($subjects) && get_class($subjects) == 'Database' && $subjects->count()) {
                                    $subjects = $subjects->results();
                                }

                                usort($subjects, function ($a, $b) {
                                    return strtotime($a->start_time) - strtotime($b->start_time);
                                });

                                foreach ($subjects as $subject) {
                                    ?>

                                    <div class="subject bg-body">
                                        <h3><?php echo $subject->name; ?></h3>
                                        <p><?php echo $subject->start_time; ?> - <?php echo $subject->end_time; ?></p>

                                        <?php
                                        $teachers = Calender::getTeachersIdsByTimeModuleId($subject->time_module_id);

                                        $teacherNames = array();

                                        foreach ($teachers as $teacher) {
                                            $teacherNames[] = User::getFullName($teacher->teacher);
                                        }
                                        ?>

                                        <p>Undervisere:
                                            <?php
                                            if (!empty($teacherNames)) {
                                                echo implode(', ', $teacherNames);
                                            } else {
                                                echo 'Ikke angivet';
                                            }
                                            ?>
                                        </p>

                                        <?php
                                        $locations = Calender::getTimeModuleLocation($subject->time_module_id);

                                        $locationNames = array();

                                        foreach ($locations as $location) {
                                            $locationNames[] = $location->name;
                                        }
                                        ?>

                                        <p>Lokation:
                                            <?php
                                            if (!empty($locationNames)) {
                                                echo implode(', ', $locationNames);
                                            } else {
                                                echo 'Ikke angivet';
                                            }
                                            ?>
                                        </p>

                                        <?php
                                        if ($notes = Calender::getTimeModuleNotes($subject->time_module_id)) {
                                            ?>
                                            <p>Der er en note</p>
                                            <?php
                                        }
                                        ?>
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
        <div class="col-sm-3 bg-body deadlines" style="height: 100%;">
            afleveringer
        </div>
    </div>
</div>