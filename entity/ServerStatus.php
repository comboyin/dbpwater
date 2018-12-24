<?php
class ServerStatus {
    private $id;
    private $is_busy;

    public function getServerStatusId() {
        return $this->id;
    }
    public function getBusyState() {
        return $this->is_busy;
    }
    public function setBusyState($state) {
        return $this->is_busy = $state;
    }
}
