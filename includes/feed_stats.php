<?php
//feed like + view helpers. views count once per user (unique key handles it)

function recordFeedView($conn, $feed_id, $viewer_id, $owner_id)
{
    if ((int) $viewer_id == (int) $owner_id) {
        return; //looking at your own post doesnt count
    }
    $feed_id = (int) $feed_id;
    $viewer_id = (int) $viewer_id;
    mysqli_query($conn, "INSERT IGNORE INTO feed_views (feed_id,user_id) VALUES ('$feed_id','$viewer_id')");
}

function getFeedStats($conn, $feed_id, $viewer_id)
{
    $feed_id = (int) $feed_id;
    $viewer_id = (int) $viewer_id;
    $likes = 0;
    $views = 0;
    $liked = false;

    $q = mysqli_query($conn, "SELECT COUNT(id) FROM feed_likes WHERE feed_id='$feed_id'");
    if ($q && ($row = mysqli_fetch_row($q))) {
        $likes = (int) $row[0];
    }
    $q = mysqli_query($conn, "SELECT COUNT(id) FROM feed_views WHERE feed_id='$feed_id'");
    if ($q && ($row = mysqli_fetch_row($q))) {
        $views = (int) $row[0];
    }
    $q = mysqli_query($conn, "SELECT id FROM feed_likes WHERE feed_id='$feed_id' AND user_id='$viewer_id'");
    if ($q && mysqli_num_rows($q) > 0) {
        $liked = true;
    }
    return array("likes" => $likes, "views" => $views, "liked" => $liked);
}

//footer bar under a feed card. $show_like_button false = own post, counts only
function feedActionsHtml($stats, $feed_id, $show_like_button)
{
    $feed_id = (int) $feed_id;
    $html = "<div class='box-footer feed-actions'>";
    if ($show_like_button) {
        $liked_class = $stats['liked'] ? " is-liked" : "";
        $heart = $stats['liked'] ? "fa-heart" : "fa-heart-o";
        $html .= "<button type='button' class='feed-like-btn$liked_class' data-feed='$feed_id'><i class='fa $heart'></i> <span class='like-count'>" . $stats['likes'] . "</span></button>";
    } else {
        $html .= "<span class='feed-like-static'><i class='fa fa-heart'></i> " . $stats['likes'] . "</span>";
    }
    $html .= "<span class='feed-views'><i class='ti ti-eye'></i> " . $stats['views'] . " views</span>";
    $html .= "</div>";
    return $html;
}
