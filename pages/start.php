<div class="message">
    <?php if (!empty($param['error'])) {
        switch ($param['error']) {
            case 'notFoundUser':
                echo 'Nieprawidłowy login lub hasło';
                break;
        }
    } ?>
</div>
<div class="message good">
    <?php if (!empty($param['message'])) {
        switch ($param['message']) {
            case 'createUser':
                echo 'Stworzono nowego użytkownika';
                break;
        }
    } ?>
</div>
<header>
    <div class="wrap-header">
        <h1 class="title">Dziennik treningowy</h1>

        <div class="form">
            <h2 class="title2">Logowanie</h2>
            <?php if (empty($_SESSION['user'])) : ?>
                <form action="/Dziennik%20treningowy/?task=login" method="post">
                    <label><input type="text" name="name_user" placeholder="Login"></label>
                    <label><input type="password" name="pass" placeholder="Hasło"></label>
                    <input type="submit" value="Zaloguj się">
                </form>
            <?php else : header('Location: /Dziennik%20treningowy/?task=reload'); ?>
            <?php endif; ?>
        </div>

        <div class="icon"><a href="/Dziennik%20treningowy/?task=registerPage"><i class="fas fa-user-alt"></i><span class="icon-span">Zarejestruj się</span></a></div>
    </div>
</header>