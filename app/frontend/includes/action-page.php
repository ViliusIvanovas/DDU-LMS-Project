<style>
    .actionPage {
        width: 0;
        height: 100%;
        position: fixed;
        top: 0;
        right: 0;
        overflow: hidden;
        transition: width .5s;
        box-sizing: border-box;
        visibility: hidden;
        /* Hide the text when the action page is inactive */
    }

    .actionPage.active {
        z-index: 1;
        border-left-color: gray;
        width: 45%;
        visibility: visible;
        /* Show the text when the action page is active */
    }

    #main.shrink {
        margin-right: 45%;
    }
</style>

<div id="myActionPage" class="actionPage container">
    <?php
    $file_path = Files::getFilePathById(100);
    ?>
    <iframe style="height: 100%; width: 100%;" src="preview-pdf.php?file_id=<?php echo 100 ?>" frameborder="0"></iframe>
</div>

</div>
</div>

<script>
    var toggleButton = document.getElementById('toggleButton');
    var main = document.getElementById('main');
    var actionPage = document.getElementById('myActionPage');

    toggleButton.addEventListener('click', function() {
        actionPage.classList.toggle('active');
        main.classList.toggle('shrink');
    });
</script>