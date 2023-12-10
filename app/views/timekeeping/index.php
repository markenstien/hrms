<?php build('content')?>

    <form action="/timekeeping/clockIn" method="post">
        <div class="form-group">
            <input type="submit" 
            value="Clock In">
        </div>
    </form>

    <form action="/timekeeping/clockOut" method="post">
        <div class="form-group">
            <input type="submit" 
            value="Clock Out">
        </div>
    </form>
<?php endbuild()?>

<?php loadTo('tmp/layout')?>