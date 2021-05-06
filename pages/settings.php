<div class="message">
    <?php if (!empty($param['error'])) {
        switch ($param['error']) {
            case 'changePassError':
                echo 'Nie udało się zmienić hasła';
                break;
            case 'changeNickError':
                echo 'Nie udało się zmienić nazwy';
                break;
        }
    } ?>
</div>
<header>
    <div class="wrap-header">
        <h3 class="title">Ustawienia</h3>

        <div class="form">
            <form action="/Dziennik%20treningowy/?task=changePass" method="post">
                <label>Aktualne hasło: <input type="password" name="old"></label>
                <label>Nowe hasło: <input type="password" name="new"></label>
                <label>Powtórz hasło: <input type="password" name="new2"></label>
                <input type="submit" value="Zmień hasło">
            </form>
        </div>

        <a class="btn return" href="/Dziennik%20treningowy/?task=reload">Strona główna</a>

        <div class="icon"><a href="/Dziennik%20treningowy/?task=logout"><i class="fas fa-sign-out-alt"></i><span class="icon-span">Wyloguj się</span></a></div>
    </div>
</header>