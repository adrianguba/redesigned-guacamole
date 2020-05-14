<?php
namespace OsomRecrutation\Models\Submission;

class Submission
{
    public $save_method = 'db';
    public function __construct()
    {
        add_action("after_switch_theme", array($this,"createTable"));
    }

    public function createTable() {
        global $wpdb;

        $table_name = $wpdb->prefix . 'submissions';

        if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {

            $charset_collate = $wpdb->get_charset_collate();

            $sql = "CREATE TABLE $table_name (
						id mediumint(9) NOT NULL AUTO_INCREMENT,
                        first_name varchar(100) NOT NULL,
                        last_name varchar(100) NOT NULL,
                        login varchar(100) NOT NULL,
                        email varchar(100) NOT NULL,
                        city enum('katowice','lodz','warszawa'),
						PRIMARY KEY  (id)
					) $charset_collate;";

            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
            dbDelta( $sql );
        }
    }

    public function save($data) {
        if($this->save_method == 'db') {
            $this->saveToDatabase($data);
        } else {
            $this->saveToFile($data);
        }
    }

    private function saveToDatabase($data) {
        global $wpdb;

        $table_name = $wpdb->prefix . 'submissions';

        $wpdb->insert($table_name,$data,array(
            '%s','%s','%s','%s','%s'
        ));

        return true;
    }

    private function saveToFile($data) {
        $file_path = get_template_directory() .'/submissions.csv';

        $file = fopen($file_path,'a');

        fputcsv($file,$data);

        fclose($file);
    }

    public function getAll() {
        if($this->save_method == 'db') {
            return $this->getAllFromDatabase();
        } else {
            return $this->getAllFromFile();
        }
    }

    private function getAllFromDatabase() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'submissions';

        return $wpdb->get_results("SELECT * from $table_name");
    }

    private function getAllFromFile() {
        $file_path = get_template_directory() .'/submissions.csv';

        $file = fopen($file_path,'r');

        $results = array();
        $i=1;
        while (($line = fgetcsv($file)) !== FALSE) {
            $item = array(
                'id'        => (string)$i,
                'first_name' => $line[0],
                'last_name' => $line[1],
                'login' => $line[2],
                'email' => $line[3],
                'city' => $line[4]
            );
            $results[] = $item;
            $i++;
        }

        return $results;
    }
}