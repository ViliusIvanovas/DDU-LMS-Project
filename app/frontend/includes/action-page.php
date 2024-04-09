<style>
    .actionPage {
        width: 0;
        height: 100%;
        position: fixed;
        /* Make the action page scroll with the screen */
        top: 0;
        right: 0;
        overflow: hidden;
        transition: width .5s;
        padding-top: 60px;
        border-left: 2px solid transparent;
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
        margin-right: 50%;
    }
</style>

<div id="myActionPage" class="actionPage container">
    Action Page
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