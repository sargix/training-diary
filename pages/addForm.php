<div class="message good">
    <?php if (!empty($param['inf'])) {
        switch ($param['inf']) {
            case 'logged':
                echo "Zalogowano, witaj " . $param['user']['user'];
                break;
        }
    } ?>
</div>
<header>
    <div class="wrap-header">
        <h3 class="title">Dodawanie wpisu do dziennika</h3>
        <div class="form">
            <form action="/Dziennik%20treningowy/?task=addEntry" method="post">
                <label><input name="pushups" type="number" placeholder="Pompki"></label>
                <label><input name="pullups" type="number" placeholder="Podciągnięcia"></label>
                <label><input name="dips" type="number" placeholder="Dipy"></label>
                <label><input name="squats" type="number" placeholder="Przysiady"></label>
                <label><input name="date" type="date" value="<?php echo date('Y-m-d'); ?>"></label>
                <label><input type="submit" value="Dodaj"></label>
            </form>
        </div>

        <a class="btn return" href="/Dziennik%20treningowy/?task=reload">Strona główna</a>

        <div class="icon"><a href="/Dziennik%20treningowy/?task=logout"><i class="fas fa-sign-out-alt"></i><span class="icon-span">Wyloguj się</span></a></div>
    </div>
</header>