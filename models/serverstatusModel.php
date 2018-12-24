<?php
class serverstatusModel extends baseModel{
    /**
     *
     * @return object|result  */
    public function getServerStatus() {
        try {
            $pdo = $this->getPdo();
            $sql = 'SELECT * FROM server_status';
            $sth = $pdo->prepare($sql);
            $sth->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'ServerStatus');
            $sth->execute();
            $result = $sth->fetch();
            return $result;
        }
        catch (Exception $e) {
            return false;
        }
    }

    /**
     *
     * @param int $status
     * @return int|$row_count */
    public function setServerStatus($status) {
        try {
            $pdo = $this->getPdo();
            $sql = 'UPDATE server_status SET is_busy = '.$status;
            $sth = $pdo->prepare($sql);
            $sth->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'ServerStatus');
            $sth->execute();
            $row_count = $sth->rowCount();
            return $row_count;
        }
        catch (Exception $e) {
            return false;
        }

    }
}