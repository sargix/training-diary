<div class="message">
    <?php if (!empty($param['error'])) {
        switch ($param['error']) {
            case 'wrongPass':
                echo 'Hasła się nie zgadzają';
                break;
            case 'nameError':
                echo 'Nazwa jest już używana';
                break;
        }
    } ?>
</div>
<header>
    <div class="wrap-header">
        <h1 class="title">Dziennik treningowy</h1>

        <div class="form">
            <h2 class="title2">Rejestracja</h2>
            <form action="/Dziennik%20treningowy/?task=registerUser" method="post">
                <label><input type="text" name="name_user" placeholder="Login"></label>
                <label><input type="password" name="pass" placeholder="Hasło"></label>
                <label><input type="password" name="pass2" placeholder="Powtórz hasło"></label>
                <input type="submit" value="Zarejestruj się">
            </form>
        </div>

        <div class="icon"><a href="/Dziennik%20treningowy/?task=start"><i class="fas fa-sign-in-alt"></i><span class="icon-span">Zaloguj się</span></a></div>
    </div>
</header>
<section>


</section>