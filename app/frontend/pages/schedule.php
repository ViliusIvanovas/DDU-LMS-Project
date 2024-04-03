<style>
    .time-table {
        width: 100%;
        display: block;
        overflow-y: auto;
        max-height: 200px;
    }

    .time-table thead {
        display: table;
        width: 100%;
    }

    .time-table tbody {
        display: table;
        width: 100%;
    }

    .time-table tbody tr {
        border-bottom: 1px solid #ddd;
        height: 60px;
        position: relative;
    }

    .time-table tbody tr:last-child {
        border-bottom: none;
    }

    .weekdays {
        border-bottom: 1px solid;
    }

    body.light .weekdays {
        border-color: #000;
    }

    body.dark .weekdays {
        border-color: #fff;
    }

    .schedule {
        overflow-x: auto;
    }

    .schedule-box {
        height: 500px;
    }

    .deadlines {
        height: 100%;
        overflow-y: auto;
        border-top-right-radius: 0.5rem;
        border-bottom-right-radius: 0.5rem;
    }

    .subject {
        width: 100%;
        border-radius: 0.5rem;
    }
</style>

<div class="container my-5">
    <div class="row align-items-center rounded-3 border shadow-lg bg-body-tertiary schedule-box">
        <div class="col-sm-9 p-4 schedule" style="height: 100%;">
            <table class="time-table">
                <?php
                $weeknumber = 14;
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
                        <td>
                            <?php
                            $userId = isset($user) && method_exists($user, 'data') && isset($user->data()->user_id) ? $user->data()->user_id : null;
                            $classes = Classes::getAllClassesByUserId($userId);

                            // Check if the $dates array is not empty and the first element is a string
                            if (!empty($dates) && is_string($dates[0])) {
                                $subjects = Calender::getAllSubjectsForPersonForADay($dates[0], $userId);
                            } else {
                                // Handle the error
                                echo "Error: Invalid date";
                                $subjects = [];
                            }

                            var_dump($subjects);
                            var_dump($classes);
                            var_dump($dates);

                            if (isset($_SESSION['user']['user_id'])) {
                                $subjects = Calender::getAllSubjectsForPersonForADay($dates[0], $_SESSION['user']['user_id']);
                            } else {
                                echo "Error: User ID is not set in session";
                                $subjects = [];
                            }
                            ?>

                            <div class="container bg-body subject" style="top: 0px; height: <?php echo $height ?>px">
                                test 1
                            </div>
                        </td>
                        <td>
                            test 2
                        </td>
                        <td>
                            test 3
                        </td>
                        <td>
                            test 4
                        </td>
                        <td>
                            test 5
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="col-sm-3 bg-body deadlines" style="height: 100%;">
            afleveringer
        </div>
    </div>
</div>