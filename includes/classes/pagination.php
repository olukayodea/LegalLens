<?php
    class pagination {
        function draw($page, $data, $count, $url, $tab=false) {
            $lenght = ceil($count/page_list);
            if ($page <= 3) {
                if ($lenght < 5) {
                    $first = 0;
                    $last = $lenght-1;
                } else {
                    $first = 0;
                    $last = 4;
                }
            } else if ($page < ($lenght-2)) {
                $first = $page-2;
                $last = $page+2;
            } else if ($page <= $lenght) {
                $first = $page-5;
                $last = $lenght-1;
            }
            if ($page > 0) {
                $prev = $page-1;
                $next = $page+1;
            }

            if ($tab == false) {
                $tabLink = "";
            } else {
                $tabLink = "&tab=".$tab;
            }

            echo '<div class="rightHand">';
            echo '<span>Viewing page '.($page+1).' of '.$lenght.'</span><br>';
            echo '<div class="pagination">';
            if ($prev > 0) {
                echo '<a href="'.$url.'?page=0&query='.urlencode($data).$tabLink.'">&laquo;&laquo;</a>';
                echo '<a href="'.$url.'?page='.$prev.'&query='.urlencode($data).$tabLink.'">&laquo;</a>';
            }
            for ($i = $first; $i<=$last; $i++) {
                if ($i == $page) {
                    $active = ' class="active"';
                } else {
                    $active = '';
                }
                echo '<a'.$active.' href="'.$url.'?page='.$i.'&query='.urlencode($data).$tabLink.'">'.($i+1).'</a>';
            }
            if ($next <= $last) {
                echo '<a href="'.$url.'?page='.$next.'&query='.urlencode($data).$tabLink.'">&raquo;</a>';
                echo '<a href="'.$url.'?page='.($lenght-1).'&query='.urlencode($data).$tabLink.'">&raquo;&raquo;</a>';
            }
            echo '</div>';
            echo '</div>';
        }
    }
?>