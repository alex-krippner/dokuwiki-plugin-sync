<?php

namespace dokuwiki\plugin\sync;

class ProfileManager {

    protected $profiles = [];

    /**
     * Return a list of all available profiles
     *
     * @return array
     */
    public function getAllProfiles() {
        return $this->profiles;
    }

    /**
     * Get a Profile instance by it's index
     *
     * @param int $num
     * @return Profile
     */
    public function getProfile($num) {
        $config = $this->getProfileConfig($num);
        return new Profile($config);
    }

    /**
     * Load a profile config by it's index
     *
     * @param int $num
     * @return array
     * @throws SyncException
     */
    public function getProfileConfig($num) {
        if(isset($this->profiles[$num])) return $this->profiles[$num];
        throw new SyncException('No such profile');
    }

    /**
     * Set the given config for the given profile
     *
     * When $num is null, the data is added to a new profile
     *
     * @param int|null $num
     * @param $data
     */
    public function setProfileConfig($num, $data) {
        if($num !== null && isset($this->profiles[$num])) {
            $this->profiles[$num] = array_merge($this->profiles[$num], $data);
        } else {
            $this->profiles[$num] = $data;
        }

        $this->save();
    }

    /**
     * load profile configuration
     */
    protected function load() {
        global $conf;
        $profiles = $conf['metadir'] . '/sync.profiles';
        if(file_exists($profiles)) {
            $this->profiles = unserialize(io_readFile($profiles, false));
        }
    }

    /**
     * Save profiles to serialized storage
     */
    protected function save() {
        global $conf;
        $profiles = $conf['metadir'] . '/sync.profiles';
        io_saveFile($profiles, serialize($this->profiles));
    }
}
