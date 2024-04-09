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
                            test 1
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