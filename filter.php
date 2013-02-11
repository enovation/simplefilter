<?php

class filter_simplefilter extends moodle_text_filter {

    public function filter($text, array $options = array()) {

        global $DB;
        global $CFG;


        $matches = array();
        //find all occurrences of @username in text
        preg_match_all("/@[a-zA-z0-9]+/", $text, $matches);

        $clean_matches = array();
        
        //strip '@' characters
        foreach ($matches[0] as $match) {

            $match = str_replace('@', '', $match);
            $clean_matches[] =  $match;
        }

        //get rows from database that match usernames found in text
        $result = $DB->get_records_list('user', 'username', $clean_matches);

        foreach ($result as $record) {
            $search_string = '@' . $record->username;
            
            $url = $CFG->wwwroot . '/user/profile.php?id=' . $record->id;
            $open_tag = "<a href=$url>";
            $close_tag = "</a>";
            $replace_string = $open_tag . $search_string . $close_tag;
            //replace occurences of usernames with links to profiles
            $text = str_replace($search_string, $replace_string, $text);
        }
        return $text;
    }

}

?>
