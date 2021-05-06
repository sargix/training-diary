<header>
    <div class="wrap-header">
        <a class="btn return" href="/Dziennik%20treningowy/?task=reload">Strona główna</a>
        <div class="table-wrap">
            <div class="table-head-wrap">
                <h1 class="table-title">Podsumowanie <?php echo $param['title']; ?></h1>
                <table>
                    <thead>
                        <tr>
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
                        <tr>
                            <td><?php echo $param['pushups']; ?></td>
                            <td><?php echo $param['pullups']; ?></td>
                            <td><?php echo $param['dips']; ?></td>
                            <td><?php echo $param['squats']; ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="icon"><a href="/Dziennik%20treningowy/?task=logout"><i class="fas fa-sign-out-alt"></i><span class="icon-span">Wyloguj się</span></a></div>
    </div>
</header>