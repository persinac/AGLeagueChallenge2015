<?php
/**
 * Created by PhpStorm.
 * User: APersinger
 * Date: 02/12/15
 * Time: 9:48 AM
 */

class SummonerNetwork {

    public $mys;
    var $summoner_id = 0;
    var $host = "";
    var $user = "";
    var $pass = "";
    var $db = "";

    var $player_nodes = '';
    var $player_links = '';
    var $temp_table = '';
    var $global_counter = 0;
    var $recursive_calls = 0;

    var $summonerLinks = array();

    var $node_array = array();
    var $link_array = array();

    function __construct($sid, $host, $user, $pass, $database) {
        $this->summoner_id = $sid;
        $this->NewConnection($host, $user, $pass, $database);
    }

    function NewConnection($host, $user, $pass, $database)
    {
        $this->mys = mysqli_connect($host, $user, $pass, $database);
        if (mysqli_connect_errno()) {
            printf("Connect failed: %s\n", mysqli_connect_error());
            exit();
        }
    }

    function CloseConnection()
    {
        try {
            mysqli_close($this->mys);
            return true;
        } catch (Exception $e) {
            printf("Close connection failed: %s\n", $this->mys->error);
        }
    }

    function FetchDataForSummonerNetwork_FD($sid = -1)
    {
        if($sid == -1) {
            $sid = $this->summoner_id;
        }

        $index = 1;
        $target_index = 0;
        $source_index = 0;
        $type = 1;
        $value = 1;
        $degree = 0;
        $distance = 25;
        $query = "CALL getNetworkOfSummoners($sid)";
        $s_name = $this->GetSummonerName($sid);

        $entity = "company";
        $slug = "";
        $this->AddToNodeArray($sid, $s_name, $type, $s_name, $slug, $entity);

        $this->mys->next_result();
        if ($result = $this->mys->query($query)) {

            while ($row = $result->fetch_assoc()) {
                $type = 2;
                $target_index = $index;
                $degree = 1;
                $value = 1;
                $target_summoner_id = $row['summonerId'];
                $target_sum_name = $row['summonerName'];
                $slug = "";
                $entity = "company";
                if ($index % 10 == 0) {
                    $distance = $distance + 10;
                }

                if(!$this->DoesPlayerExistInNodeArray($target_summoner_id)) {
                    $this->AddToNodeArray($target_summoner_id, $target_sum_name, $type, $target_sum_name, $slug, $entity);
                } else {
                    $this->UpdateType($target_summoner_id, $type);
                }

                if(!$this->DoesLinkExist($sid, $target_summoner_id)) {
                    $this->AddToLinkArray($sid, $target_summoner_id, $source_index, $target_index, $distance, $value, $degree);
                } else {
                    /*
                     * I'm about to leave work, and should clean up this logic,
                     * but if the link exists, and because it's in this main function,
                     * UpdateType again (just to make sure) then update the link with the
                     * following:
                     *  value = 1
                     *  degree = 1
                     */
                    $this->UpdateDegree($sid, $target_summoner_id, 1, 1);
                }
                $value = 10;

                $this->recursive_NoS($target_summoner_id, $degree,
                    $type, $value, $source_index + 1,
                    $distance, $index);
                $index = $index + 1;
            }
            $result->free();
        }
    }

    function recursive_NoS($id, $degree, $type, $value, $source, $distance, $index) {

        $source_id = $id;
        $t_degree = $degree + 1;
        $t_type = $type;
        $t_value = $value;

        $t_distance = ($distance * $t_degree);

        $target = 0;
        $t_source = $index;

        $query = "CALL getNetworkOfSummoners($source_id)";

        if($t_degree <= 3) {
            $this->mys->next_result();
            if ($result = $this->mys->query($query)) {
                while ($row = $result->fetch_assoc()) {
                    $target = $index + 1;
                    $t_type = 3;
                    $target_summoner_id = $row['summonerId'];
                    $sum_name = $row['summonerName'];
                    $slug = "";
                    $entity = "company";

                    if(!$this->DoesPlayerExistInNodeArray($target_summoner_id)) {
                        $this->AddToNodeArray($target_summoner_id, $sum_name, $t_type, $sum_name, $slug, $entity);
                    }

                    if(!$this->DoesLinkExist($source_id, $target_summoner_id)) {
                        $new_target = $this->GetSummonerIDInArray($target_summoner_id);
                        $this->AddToLinkArray($source_id, $target_summoner_id, $t_source, $new_target, $t_distance, $value, $t_degree);
                    }

                    $this->recursive_NoS($target_summoner_id, $degree + 1,
                        $type, $value, $source + 1,
                        $distance, $index);

                    $index = $index + 1;
                }
            }
        }
    }

    function AddToNodeArray($sid, $name, $type, $full_name,
                $slug, $entity) {
        $detail = new stdClass();
        $detail->summonerId = $sid;
        $detail->name = $name;
        $detail->type = $type;
        $detail->full_name = $full_name;
        $detail->slug = $slug;
        $detail->entity = $entity;
        $this->node_array[] = $detail;
    }

    function AddToLinkArray($source_id, $target_id, $source, $target, $distance,
                            $value, $degree) {
        $detail = new stdClass();
        $detail->source_summonerId = $source_id;
        $detail->target_summonerId = $target_id;
        $detail->source = $source;
        $detail->target = $target;
        $detail->distance = $distance;
        $detail->value = $value;
        $detail->degree = $degree;
        $this->link_array[] = $detail;
    }

    function UpdateType($sid, $type) {
        for($i = 0; $i < sizeof($this->node_array); $i++) {
            if($this->node_array[$i]->summonerId == $sid) {
                $this->node_array[$i]->type = $type;
                break;
            }
        }
    }

    function UpdateDegree($source_id, $target_id, $degree, $value) {
        for($i = 0; $i < sizeof($this->link_array); $i++) {
            if($this->link_array[$i]->source_summonerId == $source_id
                && $this->link_array[$i]->target_summonerId == $target_id) {
                //echo "*****FOUND: $source_id, $target_id, $degree";
                $this->link_array[$i]->degree = $degree;
                $this->link_array[$i]->value = $value;
                break;
            } /*else if($this->link_array[$i]->source_summonerId == $target_id
                && $this->link_array[$i]->target_summonerId == $source_id) {
                $isFound = TRUE;
                break;
            }*/
        }
    }
    /**
     * @param $sid - summonerID to search for
     * @return int - index of found ID, -1 if no ID is found
     */
    function GetSummonerIDInArray($sid) {
        $isFound = -1;
        for($i = 0; $i < sizeof($this->node_array); $i++) {
            if($this->node_array[$i]->summonerId == $sid) {
                $isFound = $i;
                break;
            }
        }
        return $isFound;
    }


    function DoesPlayerExistInNodeArray($sid) {
        $isFound = FALSE;
        for($i = 0; $i < sizeof($this->node_array); $i++) {
            if($this->node_array[$i]->summonerId == $sid) {
                $isFound = TRUE;
                break;
            }
        }
        return $isFound;
    }

    function DoesLinkExist($source_id, $target_id) {
        $isFound = FALSE;
        for($i = 0; $i < sizeof($this->link_array); $i++) {
            if($this->link_array[$i]->source_summonerId == $source_id
                && $this->link_array[$i]->target_summonerId == $target_id) {
                $isFound = TRUE;
                break;
            } else if($this->link_array[$i]->source_summonerId == $target_id
                && $this->link_array[$i]->target_summonerId == $source_id) {
                $isFound = TRUE;
                break;
            }
        }
        return $isFound;
    }

    function GetLinkArrayTable() {
        $html = '<div class="recent_games_size">';
        $html .= '<table id="win_perc_by_lane_table" class="table table-striped table-hover">';
        $html .= '<thead class="header_bg">
            <th>Source Summoner ID</th>
            <th>Target Summoner ID</th>
            <th>Source Index</th>
            <th>Target Index</th>
            <th>Distance</th>
            <th>Value</th>
            <th>Degree</th>
            </tr>';
        $html .= '</thead>';
        $html .= '<tbody>';
        for($i = 0; $i < sizeof($this->link_array); $i++) {
            $html .= '<tr>';
            $html .= '<td >'.$this->link_array[$i]->source_summonerId.'</td>';
            $html .= '<td >'.$this->link_array[$i]->target_summonerId.'</td>';
            $html .= '<td >'.$this->link_array[$i]->source.'</td>';
            $html .= '<td >'.$this->link_array[$i]->target.'</td>';
            $html .= '<td >'.$this->link_array[$i]->distance.'</td>';
            $html .= '<td >'.$this->link_array[$i]->value.'</td>';
            $html .= '<td >'.$this->link_array[$i]->degree.'</td>';
            $html .= '</tr>';
        }
        $html .= '</tbody></table>';
        $html .= '</div>';

        return $html;
    }

    function GetNodeArrayTable() {
        $html = '<div class="recent_games_size">';
        $html .= '<table id="win_perc_by_lane_table" class="table table-striped table-hover">';
        $html .= '<thead class="header_bg">
            <th>Summoner ID</th>
            <th>Name</th>
            <th>Type</th>
            <th>Full Name</th>
            <th>Slug</th>
            <th>Entity</th>
            </tr>';
        $html .= '</thead>';
        $html .= '<tbody>';
        for($i = 0; $i < sizeof($this->node_array); $i++) {
            $html .= '<tr>';
            $html .= '<td >'.$this->node_array[$i]->summonerId.'</td>';
            $html .= '<td >'.$this->node_array[$i]->name.'</td>';
            $html .= '<td >'.$this->node_array[$i]->type.'</td>';
            $html .= '<td >'.$this->node_array[$i]->full_name.'</td>';
            $html .= '<td >'.$this->node_array[$i]->slug.'</td>';
            $html .= '<td >'.$this->node_array[$i]->entity.'</td>';
            $html .= '</tr>';
        }
        $html .= '</tbody></table>';
        $html .= '</div>';

        return $html;
    }

    function GetSummonerLinkArrayTable() {
        $html = '<div class="recent_games_size">';
        $html .= '<table id="win_perc_by_lane_table" class="table table-striped table-hover">';
        $html .= '<thead class="header_bg">
            <th>Summoner ID</th>
            <th>Source</th>
            <th>Target</th>
            </tr>';
        $html .= '</thead>';
        $html .= '<tbody>';
        for($i = 0; $i < sizeof($this->summonerLinks); $i++) {
            $html .= '<tr>';
            $html .= '<td >'.$this->summonerLinks[$i]->summonerId.'</td>';
            $html .= '<td >'.$this->summonerLinks[$i]->source.'</td>';
            $html .= '<td >'.$this->summonerLinks[$i]->target.'</td>';
            $html .= '</tr>';
        }
        $html .= '</tbody></table>';
        $html .= '</div>';

        return $html;
    }

    function GetSummonerName($id)
    {
        $x = "";
        $query = 'select league_name from Players
                  WHERE playerid = ' . $id;
        $this->mys->next_result();
        if ($result = $this->mys->query($query)) {
            while ($row = $result->fetch_assoc()) {
                $x = $row['league_name'];
            }
            $result->free();
        }
        return $x;
    }

    function BuildNodeString() {
        $final_string = "";
        $nodes = '"nodes":[';

        for($i = 0; $i < sizeof($this->node_array); $i++) {
            $nodes .= '{"name":"'.$this->node_array[$i]->name.'"';
            $nodes .= ', "type":"'.$this->node_array[$i]->type.'", "full_name":"'.$this->node_array[$i]->full_name.'"';
            $nodes .= ', "slug":"'.$this->node_array[$i]->slug.'"';
            $nodes .= ', "entity":"'.$this->node_array[$i]->entity.'"';
            $nodes .= ', "img_hrefD":"", "img_hrefL":"", "summonerId":"'.$this->node_array[$i]->summonerId.'"}
            ,';
        }

        $final_string .= substr($nodes, 0, strlen($nodes) - 1);
        return $final_string . ']';
    }

    function BuildLinkString() {
        $final_string = "";
        $links = '"links":[';

        for($i = 0; $i < sizeof($this->link_array); $i++) {
            $links .= '{"source":'.$this->link_array[$i]->source.', "target":' . $this->link_array[$i]->target;
            $links .= ', "distance":' . $this->link_array[$i]->distance . ', "value":'.$this->link_array[$i]->value.'}
            ,';
        }

        $final_string .= substr($links, 0, strlen($links) - 1);
        return $final_string . ']';
    }

    function BuildCompleteNodeLinkString() {
        $nodes = $this->BuildNodeString();
        $links = $this->BuildLinkString();
        $main_string = "{" . $nodes . "," . $links . "}";
        return $main_string;
    }
}