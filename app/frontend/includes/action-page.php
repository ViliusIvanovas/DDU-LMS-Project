<style>
    .actionPage {
        width: 0;
        height: 100%;
        position: absolute;
        z-index: 1;
        top: 0;
        right: 0;
        overflow-x: hidden;
        transition: width .5s;
        padding-top: 60px;
        border-left: 2px solid transparent;
        box-sizing: border-box;
    }

    .actionPage.active {
        border-left-color: gray;
        width: 50%;
    }

    #main.shrink {
        margin-right: 50%;
        /* Add this line */
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

    toggleButton.addEventListener('click', function () {
        actionPage.classList.toggle('active');
        main.classList.toggle('shrink');
    });
</script>