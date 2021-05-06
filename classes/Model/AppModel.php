<?php

declare(strict_types=1);

namespace App\Model;

use Throwable;
use App\Exception\ConfigurationException;
use App\Exception\StorageException;
use PDO;
use PDOException;

class AppModel
{
    protected PDO $conn;

    public function __construct(array $config)
    {
        // try {
        $this->validateConfig($config);
        $this->createConnection($config);
        // } catch (PDOException $e) {
        //     throw new StorageException($e);
        // }
    }

    private function createConnection(array $config): void
    {
        $dsn = "mysql:dbname={$config['database']};{$config['host']}=127.0.0.1";
        $this->conn = new PDO($dsn, $config['user'], $config['password'], [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    }

    private function validateConfig(array $config): void
    {
        if (empty($config['database']) || empty($config['host']) || empty($config['user']) || empty($config['password'])) {
            throw new ConfigurationException('Storage configuration error');
        }
    }
    public function login(string $nameUsr, string $password): array
    {
        try {
            $query = "SELECT user, pass FROM users WHERE user = '$nameUsr' LIMIT 1";
            $result = $this->conn->query($query);
            $user = $result->fetchAll(PDO::FETCH_ASSOC);
            if (!$user) {
                return $user = null;
            } else {
                if (password_verify($password, $user[0]['pass'])) {
                    $table = $this->getTable($nameUsr);
                    $data = ['user' => $user[0], 'table' => $table];
                    return $data;
                } else {
                    return $user = null;
                }
            }
        } catch (Throwable $e) {
            throw new StorageException("Nie udało się zalogować, spróbuj później");
        }
    }

    public function registerUser(string $nameUsr, string $password): void
    {
        try {
            $hashPass = password_hash($password, PASSWORD_DEFAULT);

            $user = $this->conn->quote($nameUsr);
            $pass = $this->conn->quote($hashPass);
            $queryCreateTable = "CREATE TABLE exercises_$nameUsr (id INT AUTO_INCREMENT PRIMARY KEY, add_date DATE, pushups INT, pullups INT,dips INT, squats INT)";

            $queryAddInf = "INSERT INTO users(user, pass) VALUES ($user, $pass)";

            $this->conn->exec($queryCreateTable);
            $this->conn->exec($queryAddInf);
        } catch (Throwable $e) {
            throw new StorageException("Nie udało się utworzyć użytkownika, spróbuj później");
        }
    }

    public function checkLogin(string $name): ?array
    {
        try {
            $name = $this->conn->quote($name);
            $query = "SELECT user FROM users WHERE user = $name LIMIT 1";
            $result = $this->conn->query($query);
            $result = $result->fetchAll(PDO::FETCH_ASSOC);
            if (!$result) {
                return $result = null;
            } else {
                return $result;
            }
        } catch (Throwable $e) {
            throw new StorageException("Nie udało się sprawdzić czy nazwa jest już używana");
        }
    }

    public function checkUserPass(string $pass)
    {
        try {
            $user = $_SESSION['user'];
            $query = "SELECT user, pass FROM users WHERE user = '$user' LIMIT 1";
            $result = $this->conn->query($query);
            $user = $result->fetchAll(PDO::FETCH_ASSOC);
            if (empty($user[0]['pass'])) {
                return false;
            } else {
                if (password_verify($pass, $user[0]['pass'])) {
                    return true;
                }
            }
        } catch (Throwable $e) {
            throw new StorageException("Nie udało się sprawdzić czy hasło jest poprawne");
        }
    }

    public function changeUserPass(string $newPass): void
    {
        try {
            $hashPass = password_hash($newPass, PASSWORD_DEFAULT);
            $user = $_SESSION['user'];
            $query = "UPDATE users SET pass = '$hashPass' WHERE user = '$user'";
            $this->conn->exec($query);
        } catch (Throwable $e) {
            throw new StorageException("Nie udało się zmienic hasła");
        }
    }

    public function addEntry(string $nameUser, int $pushups, int $pullups, int $dips, int $squats, $date): void
    {
        try {
            $date = $this->conn->quote($date);

            $query = "INSERT INTO exercises_$nameUser(add_date, pushups, pullups, dips, squats) VALUES ($date, $pushups, $pullups, $dips, $squats)";

            $this->conn->exec($query);
        } catch (Throwable $e) {
            throw new StorageException("Nie udało się dodać wpisu");
        }
    }

    public function deleteEntry(int $id): void
    {
        try {
            $user = $_SESSION['user'];
            $query = "DELETE FROM exercises_$user WHERE id = $id";
            $this->conn->exec($query);
        } catch (Throwable $e) {
            throw new StorageException("Nie udało się usunąć wpisu");
        }
    }

    public function editEntry(int $id, int $pushups, int $pullups, int $dips, int $squats, $date): void
    {
        try {
            $user = $_SESSION['user'];
            $query = "UPDATE exercises_$user SET add_date = '$date', pushups = $pushups, pullups = $pullups, dips = $dips, squats = $squats WHERE id = $id";
            $this->conn->exec($query);
        } catch (Throwable $e) {
            throw new StorageException("Nie udało się zedytować wpisu");
        }
    }

    public function statsDay(): array
    {
        try {
            $user = $_SESSION['user'];
            $day = $this->conn->query("SELECT DAY(CURRENT_DATE) AS Day");
            $day = $day->fetchAll(PDO::FETCH_ASSOC);
            $day = $day[0]['Day'];
            $query = "SELECT SUM(pushups), SUM(pullups), SUM(dips), SUM(squats) FROM exercises_$user WHERE DAY(add_date) = $day";
            $exercises = $this->conn->query($query);
            $exercises = $exercises->fetchAll(PDO::FETCH_ASSOC);
            return $exercises;
        } catch (Throwable $e) {
            throw new StorageException("Nie udało się pobrać statystyk");
        }
    }

    public function statsWeek(): array
    {
        try {
            $user = $_SESSION['user'];
            $day = $this->conn->query("SELECT DAY(CURRENT_DATE) AS Day");
            $day = $day->fetchAll(PDO::FETCH_ASSOC);
            $day2 = $day[0]['Day'];
            $day = $day[0]['Day'] - 7;
            $query = "SELECT SUM(pushups), SUM(pullups), SUM(dips), SUM(squats) FROM exercises_$user WHERE DAY(add_date) >= $day AND DAY(add_date) <= $day2";
            $exercises = $this->conn->query($query);
            $exercises = $exercises->fetchAll(PDO::FETCH_ASSOC);
            return $exercises;
        } catch (Throwable $e) {
            throw new StorageException("Nie udało się pobrać statystyk");
        }
    }

    public function statsMonth(): array
    {
        try {
            $user = $_SESSION['user'];
            $month = $this->conn->query("SELECT MONTH(CURRENT_DATE) AS Month");
            $month = $month->fetchAll(PDO::FETCH_ASSOC);
            $month = $month[0]['Month'];
            $query = "SELECT SUM(pushups), SUM(pullups), SUM(dips), SUM(squats) FROM exercises_$user WHERE MONTH(add_date) = $month";
            $exercises = $this->conn->query($query);
            $exercises = $exercises->fetchAll(PDO::FETCH_ASSOC);
            return $exercises;
        } catch (Throwable $e) {
            throw new StorageException("Nie udało się pobrać statystyk");
        }
    }

    public function statsYear(): array
    {
        try {
            $user = $_SESSION['user'];
            $year = $this->conn->query("SELECT YEAR(CURRENT_DATE) AS Year");
            $year = $year->fetchAll(PDO::FETCH_ASSOC);
            $year = $year[0]['Year'];
            $query = "SELECT SUM(pushups), SUM(pullups), SUM(dips), SUM(squats) FROM exercises_$user WHERE YEAR(add_date) = $year";
            $exercises = $this->conn->query($query);
            $exercises = $exercises->fetchAll(PDO::FETCH_ASSOC);
            return $exercises;
        } catch (Throwable $e) {
            throw new StorageException("Nie udało się pobrać statystyk");
        }
    }

    public function reload(string $nameUser): array
    {
        try {
            $query = "SELECT user FROM users WHERE user = '$nameUser'";
            $result = $this->conn->query($query);
            $user = $result->fetchAll(PDO::FETCH_ASSOC);
            if (!$user) {
                return $user = null;
            } else {
                $table = $this->getTable($nameUser);
                $data = ['user' => $user[0], 'table' => $table];
                return $data;
            }
        } catch (Throwable $e) {
            throw new StorageException("Nie udało się wznowić sesji, spróbuj później");
        }
    }

    private function getTable(string $name): object
    {
        try {
            $query = "SELECT * FROM exercises_$name";
            $result = $this->conn->query($query);
            return $result;
        } catch (Throwable $e) {
            throw new StorageException("Nie udało się pobrać danych");
        }
    }
}
