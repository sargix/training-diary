<div class="message good">
    <?php if (!empty($param['inf'])) {
        switch ($param['inf']) {
            case 'logged':
                echo "Zalogowano, witaj " . $param['user']['user'];
                break;
            case 'changePass':
                echo "Hasło zostało pomyślnie zmienione";
                break;
            case 'changeNick':
                echo "Nazwa została pomyślnie zmieniona";
                break;
        }
    } ?>
</div>
<header>
    <div class="wrap-header">
        <div class="btn-wrap">
            <a href="/Dziennik%20treningowy/?task=showStatsDay" class="btn">Podsumowanie dnia</a>
            <a href="/Dziennik%20treningowy/?task=showStatsWeek" class="btn">Podsumowanie tygodnia</a>
            <a href="/Dziennik%20treningowy/?task=showStatsMonth" class="btn">Podsumowanie miesiąca</a>
            <a href="/Dziennik%20treningowy/?task=showStatsYear" class="btn">Podsumowanie roku</a>
        </div>
        <div class="table-wrap">
            <div class="table-head-wrap">
                <h1 class="table-title">Tabela powtórzeń</h1>
                <table>
                    <thead>
                        <tr>
                            <td>Opcje</td>
                            <td>Data</td>
                            <td>Pompki</td>
                            <td>Podciągnięcia</td>
                            <td>Dipy</td>
                            <td>Przysiady</td>
                        </tr>
                    </thead>
                </table>
            </div>
            <div class="table-body-wrap">
                <table>
                    <tbody>
                        <?php foreach ($param['exercises'] as $exercise) : ?>
                            <tr>
                                <td class="options">
                                    <a href="/Dziennik%20treningowy/?task=editEntryPage&id=<?php echo $exercise['id']  ?>" class="btn edit">Edytuj</a>
                                    <a href="/Dziennik%20treningowy/?task=deleteEntry&id=<?php echo $exercise['id']  ?>" class="btn delete">Usuń</a>
                                </td>
                                <td><?php echo $exercise['add_date'] ?></td>
                                <td><?php echo $exercise['pushups'] ?></td>
                                <td><?php echo $exercise['pullups'] ?></td>
                                <td><?php echo $exercise['dips'] ?></td>
                                <td><?php echo $exercise['squats'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <a class="btn add" href="/Dziennik%20treningowy/?task=addEntryPage">Dodaj wpis</a>


        <div class="icon settings"><a href="/Dziennik%20treningowy/?task=settingsPage"><i class="fas fa-user-cog"></i><span class="icon-span">Ustawienia</span></a></div>

        <div class="icon"><a href="/Dziennik%20treningowy/?task=logout"><i class="fas fa-sign-out-alt"></i><span class="icon-span">Wyloguj się</span></a></div>
    </div>
</header>