<?php
require_once "includes/init.php";

date_default_timezone_set('Europe/Athens');

header('Content-Type: application/json; charset=utf-8');
header('Content-Disposition: attachment; filename="open_data.json"');

$stmt = $pdo->query("
    SELECT p.id AS playlist_id, p.title AS playlist_title, p.created_at AS playlist_created_at, 
           u.username AS owner,
           pi.video_title, pi.youtube_id, pi.created_at AS video_added
    FROM playlists p
    LEFT JOIN users u ON p.user_id = u.id
    LEFT JOIN playlist_items pi ON p.id = pi.playlist_id
    WHERE p.is_public = 1
    ORDER BY p.id, pi.created_at
");

$data = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $playlistId = $row['playlist_id'];

    if (!isset($data[$playlistId])) {
        $created = $row['playlist_created_at'];
        if ($created) {
            $dt = new DateTime($created);
            $dt->setTimezone(new DateTimeZone('Europe/Athens'));
            $created = $dt->format('Y-m-d H:i:s');
        }

        $data[$playlistId] = [
            'title' => $row['playlist_title'],
            'created_at' => $created,
            'owner' => $row['owner'],
            'videos' => []
        ];
    }

    if (!empty($row['youtube_id'])) {
        $videoCreated = $row['video_added'];
        if ($videoCreated) {
            $dt = new DateTime($videoCreated);
            $dt->setTimezone(new DateTimeZone('Europe/Athens'));
            $videoCreated = $dt->format('Y-m-d H:i:s');
        }

        $data[$playlistId]['videos'][] = [
            'title' => $row['video_title'],
            'youtube_id' => $row['youtube_id'],
            'added_at' => $videoCreated
        ];
    }
}

echo json_encode(array_values($data), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
exit;