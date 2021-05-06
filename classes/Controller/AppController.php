<?php

declare(strict_types=1);

namespace App\Controller;

class AppController extends AbstractController
{
    public function login(): void
    {
        $nameUser = htmlentities($this->request->paramPost('name_user'));
        $password = htmlentities($this->request->paramPost('pass'));

        $user = $this->appModel->login($nameUser, $password);

        if ($user['user']) {
            $_SESSION['user'] = $nameUser;
            $page = 'main';
            $param = ['inf' => 'logged', 'user' => $user['user'], 'exercises' => $user['table']];
        } else {
            $page = 'start';
            $param = ['error' => 'notFoundUser'];
        }
        $this->view->renderView($page, $param);
    }

    public function logout(): void
    {
        unset($_SESSION['user']);
        session_destroy();
        $this->view->renderView('start');
    }

    public function registerPage(): void
    {
        $this->view->renderView('register');
    }

    public function registerUser(): void
    {
        $name = htmlentities($this->request->paramPost('name_user'));
        $pass = htmlentities($this->request->paramPost('pass'));
        $pass2 = htmlentities($this->request->paramPost('pass2'));

        if ($pass === $pass2) {
            $page = 'start';
            $nameUse = $this->appModel->checkLogin($name);
            if (empty($nameUse)) {
                $this->appModel->registerUser($name, $pass);
                $params = ['message' => 'createUser'];
            } else {
                $page = 'register';
                $params = ['error' => 'nameError'];
            }
        } else {
            $page = 'register';
            $params = ['error' => 'wrongPass'];
        }
        $this->view->renderView($page, $params);
    }

    public function changePass(): void
    {
        $oldPass = htmlentities($this->request->paramPost('old'));
        $newPass = htmlentities($this->request->paramPost('new'));
        $newPass2 = htmlentities($this->request->paramPost('new2'));

        if ($newPass === $newPass2) {
            $result = $this->appModel->checkUserPass($oldPass);
            if ($result === true) {
                $this->appModel->changeUserPass($newPass);
                $params = 'changePass';
                $this->reload->reload($params);
            } else {
                $params = ['error' => 'changePassError'];
                $this->view->renderView('settings', $params);
            }
        } else {
            $params = ['error' => 'changePassError'];
            $this->view->renderView('settings', $params);
        }
    }

    public function changeNick(): void
    {
        $nick = htmlentities($this->request->paramPost('nick'));
        $pass = htmlentities($this->request->paramPost('pass'));
        $result = $this->appModel->checkUserPass($pass);

        if ($result === true) {
            // $this->appModel->changeUserNick($nick);
            $_SESSION['user'] = $nick;
            $params = 'changeNick';
            $this->reload->reload($params);
        } else {
            $params = ['error' => 'changeNickError'];
            $this->view->renderView('settings', $params);
        }
    }

    public function addEntryPage(): void
    {
        $this->view->renderView('addForm');
    }

    public function settingsPage(): void
    {
        $this->view->renderView('settings');
    }

    public function editEntryPage(): void
    {
        $_SESSION['recId'] = (int) $this->request->paramGet('id');
        $this->view->renderView('edit');
    }

    public function showStatsDay(): void
    {
        $params = $this->appModel->statsDay();

        $exercises = $this->getRepsToStats($params);

        $this->view->renderView('stats', ['title' => 'tygodnia', 'pushups' => $exercises[0], 'pullups' => $exercises[1], 'dips' => $exercises[2], 'squats' => $exercises[3]]);
    }

    public function showStatsWeek(): void
    {
        $params = $this->appModel->statsWeek();

        $exercises = $this->getRepsToStats($params);

        $this->view->renderView('stats', ['title' => 'tygodnia', 'pushups' => $exercises[0], 'pullups' => $exercises[1], 'dips' => $exercises[2], 'squats' => $exercises[3]]);
    }

    public function showStatsMonth(): void
    {
        $params = $this->appModel->statsMonth();

        $exercises = $this->getRepsToStats($params);

        $this->view->renderView('stats', ['title' => 'miesiąca', 'pushups' => $exercises[0], 'pullups' => $exercises[1], 'dips' => $exercises[2], 'squats' => $exercises[3]]);
    }

    public function showStatsYear(): void
    {
        $params = $this->appModel->statsYear();

        $exercises = $this->getRepsToStats($params);

        $this->view->renderView('stats', ['title' => 'roku', 'pushups' => $exercises[0], 'pullups' => $exercises[1], 'dips' => $exercises[2], 'squats' => $exercises[3]]);
    }

    public function addEntry(): void
    {
        $pushups = (int) $this->request->paramPost('pushups');
        $pullups = (int) $this->request->paramPost('pullups');
        $dips = (int) $this->request->paramPost('dips');
        $squats = (int) $this->request->paramPost('squats');
        $date =  htmlentities($this->request->paramPost('date'));

        $this->appModel->addEntry($_SESSION['user'], $pushups, $pullups, $dips, $squats, $date);

        $user = $this->appModel->reload($_SESSION['user']);
        if ($user['user']) {
            $user = ['inf' => '', 'user' => $user['user'], 'exercises' => $user['table']];
        }
        $this->view->renderView('result', $user);
    }

    public function deleteEntry(): void
    {
        $id = (int) $this->request->paramGet('id');
        $this->appModel->deleteEntry($id);
        $this->reload();
    }

    public function editEntry(): void
    {
        $id = $_SESSION['recId'];
        $pushups = (int) $this->request->paramPost('pushups');
        $pullups = (int) $this->request->paramPost('pullups');
        $dips = (int) $this->request->paramPost('dips');
        $squats = (int) $this->request->paramPost('squats');
        $date =  htmlentities($this->request->paramPost('date'));

        $this->appModel->editEntry($id, $pushups, $pullups, $dips, $squats, $date);
        $this->view->renderView('result');
    }

    public function reload($param = []): void
    {
        $user = $this->appModel->reload($_SESSION['user']);
        if ($user['user']) {
            $page = 'main';
            $user = ['inf' => $param, 'user' => $user['user'], 'exercises' => $user['table']];
        } else {
            $page = 'start';
            $user = ['error' => 'notFoundUser'];
        }
        $this->view->renderView($page, $user);
    }

    private function getRepsToStats($params): array
    {
        $pushupsNumber = $params[0]['SUM(pushups)'];
        $pullupsNumber = $params[0]['SUM(pullups)'];
        $dipsNumber = $params[0]['SUM(dips)'];
        $squatsNumber = $params[0]['SUM(squats)'];

        return $exercises = [$pushupsNumber, $pullupsNumber, $dipsNumber, $squatsNumber];
    }
}
